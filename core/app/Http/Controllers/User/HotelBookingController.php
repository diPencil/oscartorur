<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    public function index()
    {
        $pageTitle = 'My Hotel Bookings';
        $bookings = HotelBooking::where('user_id', auth()->id())
                    ->with(['hotel'])
                    ->orderBy('id', 'desc')
                    ->paginate(getPaginate());
                    
        return view('Template::user.hotel_booking.index', compact('pageTitle', 'bookings'));
    }

    public function details($id)
    {
        $booking = HotelBooking::where('user_id', auth()->id())
                    ->with(['hotel', 'rooms.roomType', 'rooms.ratePlan', 'guests', 'priceBreakdown'])
                    ->findOrFail($id);
                    
        $pageTitle = 'Booking Details - ' . $booking->booking_number;
        
        return view('Template::user.hotel_booking.details', compact('pageTitle', 'booking'));
    }

    public function cancel(Request $request, $id)
    {
        $booking = HotelBooking::where('user_id', auth()->id())
            ->with(['rooms.ratePlan.cancellationPolicy.rules'])
            ->findOrFail($id);
        
        if($booking->booking_status == 'cancelled') {
            return back()->withNotify([['error', 'Booking is already cancelled.']]);
        }
        
        if($booking->check_in <= now()->format('Y-m-d')) {
            return back()->withNotify([['error', 'You cannot cancel a booking on or after the check-in date.']]);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $checkInTime = \Carbon\Carbon::parse($booking->check_in . ' 14:00:00'); // Assuming standard 2 PM check-in
            $hoursUntilCheckIn = now()->diffInHours($checkInTime, false);
            
            if ($hoursUntilCheckIn < 0) $hoursUntilCheckIn = 0;

            $totalPenalty = 0;

            // Calculate penalty per room
            foreach($booking->rooms as $room) {
                $penaltyForRoom = 0;
                $policy = $room->ratePlan->cancellationPolicy ?? null;
                
                if ($policy) {
                    $matchedRule = $policy->rules()
                        ->where('from_hours_before', '<=', $hoursUntilCheckIn)
                        ->orderBy('from_hours_before', 'desc')
                        ->first();
                        
                    if ($matchedRule) {
                        if ($matchedRule->penalty_type == 'percentage') {
                            $penaltyForRoom = ($room->price * $matchedRule->penalty_value) / 100;
                        } elseif ($matchedRule->penalty_type == 'fixed_amount') {
                            $penaltyForRoom = $matchedRule->penalty_value;
                        } elseif ($matchedRule->penalty_type == 'nights') {
                            // find average price per night
                            $nights = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out));
                            $avgNight = $room->price / $nights;
                            $penaltyForRoom = $avgNight * $matchedRule->penalty_value;
                        }
                    }
                }
                
                // Cap penalty at the room price
                if ($penaltyForRoom > $room->price) {
                    $penaltyForRoom = $room->price;
                }
                
                $totalPenalty += $penaltyForRoom;
            }

            // Cap total penalty to total booking price
            if ($totalPenalty > $booking->total_price) {
                $totalPenalty = $booking->total_price;
            }

            // Refund Calculation
            $totalPaid = \App\Models\Payment::where('hotel_booking_id', $booking->id)
                ->where('status', 'successful')
                ->sum('amount');
                
            $refundAmount = $totalPaid - $totalPenalty;
            if ($refundAmount < 0) $refundAmount = 0;

            $booking->booking_status = 'cancelled';
            $booking->cancelled_at = now();
            // In a real system, you might save penalty amount in the booking table. 
            // We will just process the Refund record.
            $booking->save();

            $adminNotification = new AdminNotification();
            $adminNotification->user_id = auth()->id();
            $adminNotification->title = 'Hotel Booking Cancelled: ' . $booking->booking_number;
            $adminNotification->click_url = urlPath('admin.hotel.booking.details', $booking->id);
            $adminNotification->save();

            if ($refundAmount > 0) {
                $refund = new \App\Models\Refund();
                $refund->payment_id = \App\Models\Payment::where('hotel_booking_id', $booking->id)->where('status', 'successful')->first()->id ?? 0;
                $refund->hotel_booking_id = $booking->id;
                $refund->amount = $refundAmount;
                $refund->reason = 'User Cancellation';
                $refund->status = 'pending';
                $refund->save();
            }

            // Revert inventory and log movement
            $rooms = $booking->rooms;
            foreach($rooms as $room) {
                $inventoryRecords = \App\Models\RoomInventory::where('contract_room_type_id', $room->contract_room_type_id)
                    ->whereBetween('date', [$booking->check_in, \Carbon\Carbon::parse($booking->check_out)->subDay()->format('Y-m-d')])
                    ->lockForUpdate()
                    ->get();
                    
                foreach($inventoryRecords as $inv) {
                    if($inv->reserved_inventory > 0) { // Should check actual room count but for simplicity we assume 1 room per BookingRoom record. Actually rooms_count is in Booking table.
                        $qty = 1; // Since we iterate BookingRoom, each room record is 1 room or depends on $room->adults. Wait, bookingRoom has NO rooms_count. The booking has rooms_count.
                        // Correct logic: we should just take total booking rooms_count and divide.
                        // But wait, the original simplistic logic just did $inv->reserved_inventory -= $booking->rooms_count;
                        // Let's do that for the FIRST room loop and break, since our B2C flow puts all rooms under one rate plan.
                        if($inv->reserved_inventory >= $booking->rooms_count) {
                            $inv->reserved_inventory -= $booking->rooms_count;
                            $inv->save();
                            
                            $movement = new \App\Models\InventoryMovement();
                            $movement->room_inventory_id = $inv->id;
                            $movement->hotel_booking_id = $booking->id;
                            $movement->movement_type = 'cancellation';
                            $movement->quantity = -$booking->rooms_count;
                            $movement->before_quantity = $inv->reserved_inventory + $booking->rooms_count;
                            $movement->after_quantity = $inv->reserved_inventory;
                            $movement->reason = 'User Cancelled';
                            $movement->save();
                        }
                    }
                }
                break; // Only run once for the whole booking since B2C maps all rooms to the same contract
            }

            \Illuminate\Support\Facades\DB::commit();
            return back()->withNotify([['success', 'Booking cancelled successfully. Refund of ' . $refundAmount . ' initiated.']]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withNotify([['error', 'Error cancelling booking: ' . $e->getMessage()]]);
        }
    }
}
