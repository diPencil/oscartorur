<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\RatePlan;
use App\Models\ContractRoomType;
use App\Models\HotelBooking;
use App\Models\BookingRoom;
use App\Models\BookingGuest;
use App\Models\RoomInventory;
use App\Models\AdminNotification;
use App\Services\HotelSearchService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    protected $hotelSearchService;

    public function __construct(HotelSearchService $hotelSearchService)
    {
        $this->hotelSearchService = $hotelSearchService;
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'hotel_id'     => 'required|integer|exists:hotels,id',
            'rate_plan_id' => 'required|integer|exists:rate_plans,id',
            'check_in'     => 'required|date|after_or_equal:today',
            'check_out'    => 'required|date|after:check_in',
            'rooms'        => 'required|integer|min:1',
            'adults'       => 'required|integer|min:1',
        ]);

        $hotel = Hotel::findOrFail($request->hotel_id);
        $ratePlan = RatePlan::with(['contractRoomType.roomType'])->findOrFail($request->rate_plan_id);

        $params = $request->only(['check_in', 'check_out', 'rooms', 'adults']);
        $params['location_id'] = $hotel->location_id;

        // Verify availability and get price
        $allAvailable = $this->hotelSearchService->searchHotels($params);
        $isAvailable = false;
        $totalPrice = 0;

        foreach ($allAvailable as $ah) {
            if ($ah['hotel']->id == $hotel->id) {
                foreach ($ah['available_rooms'] as $roomData) {
                    if ($roomData['room_type']->id == $ratePlan->contractRoomType->roomType->id) {
                        foreach ($roomData['rate_plans'] as $rpData) {
                            if ($rpData['rate_plan']->id == $ratePlan->id) {
                                $isAvailable = true;
                                $totalPrice = $rpData['total_price'];
                                break 3;
                            }
                        }
                    }
                }
            }
        }

        if (!$isAvailable) {
            $notify[] = ['error', 'Sorry, the selected room and rate is no longer available.'];
            return to_route('hotel.details', [$hotel->id, slug($hotel->name)])->withNotify($notify);
        }
        
        // --- Booking Hold Logic ---
        $sessionId = session()->getId();
        
        // Check if already holding this exact booking
        $existingHold = \App\Models\BookingHold::where('session_id', $sessionId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
            
        if ($existingHold) {
            // Check if it's for the same rate plan and dates, else release old hold
            $oldHoldRoom = \App\Models\BookingHoldRoom::where('booking_hold_id', $existingHold->id)->first();
            if ($oldHoldRoom && ($oldHoldRoom->rate_plan_id != $ratePlan->id || $oldHoldRoom->check_in != $request->check_in || $oldHoldRoom->check_out != $request->check_out)) {
                // Release old hold
                $this->releaseHold($existingHold);
                $existingHold = null;
            }
        }

        if (!$existingHold) {
            try {
                DB::beginTransaction();
                
                // Freeze Inventory
                $currentDate = Carbon::parse($request->check_in);
                $checkOutDate = Carbon::parse($request->check_out);
    
                while ($currentDate->lt($checkOutDate)) {
                    $dateStr = $currentDate->format('Y-m-d');
                    $inventory = RoomInventory::where('contract_room_type_id', $ratePlan->contract_room_type_id)
                        ->where('date', $dateStr)
                        ->lockForUpdate()
                        ->first();
    
                    if (!$inventory) {
                        $inventory = new RoomInventory();
                        $inventory->contract_room_type_id = $ratePlan->contract_room_type_id;
                        $inventory->date = $dateStr;
                        $inventory->total_inventory = $ratePlan->contractRoomType->allotment;
                        $inventory->reserved_inventory = 0;
                        $inventory->held_inventory = 0;
                    }
                    
                    $available = $inventory->total_inventory - ($inventory->reserved_inventory + $inventory->held_inventory + $inventory->blocked_inventory);
                    
                    if ($available < $request->rooms || $inventory->stop_sale) {
                        throw new \Exception('Not enough inventory available to hold.');
                    }
    
                    $inventory->held_inventory += $request->rooms;
                    $inventory->save();
                    
                    // Log Movement
                    $movement = new \App\Models\InventoryMovement();
                    $movement->room_inventory_id = $inventory->id;
                    $movement->movement_type = 'hold';
                    $movement->quantity = $request->rooms;
                    $movement->before_quantity = $inventory->held_inventory - $request->rooms;
                    $movement->after_quantity = $inventory->held_inventory;
                    $movement->reason = 'Checkout page hold';
                    $movement->save();
                    
                    $currentDate->addDay();
                }
    
                // Create Hold Record
                $hold = new \App\Models\BookingHold();
                $hold->session_id = $sessionId;
                $hold->user_id = auth()->id();
                $hold->expires_at = now()->addMinutes(15);
                $hold->status = 'active';
                $hold->save();
    
                $holdRoom = new \App\Models\BookingHoldRoom();
                $holdRoom->booking_hold_id = $hold->id;
                $holdRoom->contract_room_type_id = $ratePlan->contract_room_type_id;
                $holdRoom->rate_plan_id = $ratePlan->id;
                $holdRoom->check_in = $request->check_in;
                $holdRoom->check_out = $request->check_out;
                $holdRoom->rooms_count = $request->rooms;
                $holdRoom->adults = $request->adults;
                $holdRoom->save();
                
                DB::commit();
                session()->put('booking_hold_id', $hold->id);
            } catch (\Exception $e) {
                DB::rollBack();
                $notify[] = ['error', 'Could not secure the room. It might have just sold out.'];
                return to_route('hotel.details', [$hotel->id, slug($hotel->name)])->withNotify($notify);
            }
        }
        // -------------------------

        $pageTitle = 'Checkout';
        $user = auth()->user();

        return view('Template::hotel.checkout', compact('pageTitle', 'hotel', 'ratePlan', 'params', 'totalPrice', 'user'));
    }

    protected function releaseHold($hold)
    {
        DB::beginTransaction();
        try {
            if ($hold->status != 'active') {
                DB::rollBack();
                return;
            }
            $holdRooms = \App\Models\BookingHoldRoom::where('booking_hold_id', $hold->id)->get();
            foreach ($holdRooms as $hr) {
                $currentDate = Carbon::parse($hr->check_in);
                $checkOutDate = Carbon::parse($hr->check_out);
                while ($currentDate->lt($checkOutDate)) {
                    $dateStr = $currentDate->format('Y-m-d');
                    $inventory = RoomInventory::where('contract_room_type_id', $hr->contract_room_type_id)
                        ->where('date', $dateStr)
                        ->lockForUpdate()
                        ->first();
                    if ($inventory && $inventory->held_inventory >= $hr->rooms_count) {
                        $inventory->held_inventory -= $hr->rooms_count;
                        $inventory->save();
                        
                        $movement = new \App\Models\InventoryMovement();
                        $movement->room_inventory_id = $inventory->id;
                        $movement->movement_type = 'release';
                        $movement->quantity = -$hr->rooms_count;
                        $movement->before_quantity = $inventory->held_inventory + $hr->rooms_count;
                        $movement->after_quantity = $inventory->held_inventory;
                        $movement->reason = 'Hold released';
                        $movement->save();
                    }
                    $currentDate->addDay();
                }
            }
            $hold->status = 'released';
            $hold->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'hotel_id'     => 'required|integer|exists:hotels,id',
            'rate_plan_id' => 'required|integer|exists:rate_plans,id',
            'check_in'     => 'required|date|after_or_equal:today',
            'check_out'    => 'required|date|after:check_in',
            'rooms'        => 'required|integer|min:1',
            'adults'       => 'required|integer|min:1',
            'first_name'   => 'required|array',
            'first_name.*' => 'required|string',
            'last_name'    => 'required|array',
            'last_name.*'  => 'required|string',
            'email'        => 'required|email',
            'phone'        => 'required|string',
        ]);

        $hotel = Hotel::findOrFail($request->hotel_id);
        $ratePlan = RatePlan::with(['contractRoomType'])->findOrFail($request->rate_plan_id);

        $params = $request->only(['check_in', 'check_out', 'rooms', 'adults']);
        $params['location_id'] = $hotel->location_id;

        // Verify active hold!
        $holdId = session()->get('booking_hold_id');
        if (!$holdId) {
            $notify[] = ['error', 'Your session expired. Please search again.'];
            return to_route('hotel.details', [$hotel->id, slug($hotel->name)])->withNotify($notify);
        }

        $hold = \App\Models\BookingHold::where('id', $holdId)
            ->where('session_id', session()->getId())
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if (!$hold) {
            $notify[] = ['error', 'Your booking hold has expired. Please try again.'];
            return to_route('hotel.details', [$hotel->id, slug($hotel->name)])->withNotify($notify);
        }

        // Verify availability again before booking to get prices (in case we didn't save them in hold)
        $allAvailable = $this->hotelSearchService->searchHotels($params);
        $isAvailable = false;
        $totalPrice = 0;

        foreach ($allAvailable as $ah) {
            if ($ah['hotel']->id == $hotel->id) {
                foreach ($ah['available_rooms'] as $roomData) {
                    if ($roomData['room_type']->id == $ratePlan->contractRoomType->room_type_id) {
                        foreach ($roomData['rate_plans'] as $rpData) {
                            if ($rpData['rate_plan']->id == $ratePlan->id) {
                                $isAvailable = true;
                                $totalPrice = $rpData['total_price'];
                                break 3;
                            }
                        }
                    }
                }
            }
        }

        if (!$isAvailable) {
            $this->releaseHold($hold);
            $notify[] = ['error', 'Sorry, the selected room and rate is no longer available.'];
            return to_route('hotel.details', [$hotel->id, slug($hotel->name)])->withNotify($notify);
        }

        DB::beginTransaction();
        try {
            // Transfer Inventory (Held -> Reserved)
            $currentDate = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            while ($currentDate->lt($checkOut)) {
                $dateStr = $currentDate->format('Y-m-d');
                $inventory = RoomInventory::where('contract_room_type_id', $ratePlan->contract_room_type_id)
                    ->where('date', $dateStr)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory || $inventory->held_inventory < $request->rooms) {
                    throw new \Exception('Inventory sync error during checkout.');
                }

                $inventory->held_inventory -= $request->rooms;
                $inventory->reserved_inventory += $request->rooms;
                $inventory->save();
                
                $movement = new \App\Models\InventoryMovement();
                $movement->room_inventory_id = $inventory->id;
                $movement->movement_type = 'booking';
                $movement->quantity = $request->rooms;
                $movement->before_quantity = $inventory->reserved_inventory - $request->rooms;
                $movement->after_quantity = $inventory->reserved_inventory;
                $movement->reason = 'Hold converted to reservation';
                $movement->save();

                $currentDate->addDay();
            }

            // Create Booking
            $booking = new HotelBooking();
            $booking->booking_number = 'HB-' . strtoupper(Str::random(8));
            $booking->user_id = auth()->id() ?? null;
            $booking->hotel_id = $hotel->id;
            $booking->check_in = $request->check_in;
            $booking->check_out = $request->check_out;
            $booking->rooms_count = $request->rooms;
            $booking->adults = $request->adults;
            $booking->total_price = $totalPrice;
            $booking->subtotal = $totalPrice;
            $booking->booking_status = 'pending';
            $booking->payment_status = 'unpaid';
            $booking->save();

            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $booking->user_id ?? 0;
            $adminNotification->title = 'New Hotel Booking: ' . $booking->booking_number;
            $adminNotification->click_url = urlPath('admin.hotel.booking.details', $booking->id);
            $adminNotification->save();

            // Mark hold as converted
            $hold->status = 'converted';
            $hold->converted_booking_id = $booking->id;
            $hold->save();

            // Create Booking Room
            $bookingRoom = new BookingRoom();
            $bookingRoom->hotel_booking_id = $booking->id;
            $bookingRoom->contract_room_type_id = $ratePlan->contract_room_type_id;
            $bookingRoom->rate_plan_id = $ratePlan->id;
            $bookingRoom->adults = $request->adults;
            $bookingRoom->price = $totalPrice;
            $bookingRoom->rate_plan_name_snapshot = $ratePlan->name;
            $bookingRoom->save();

            // Generate Booking Room Nights
            $nights = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));
            $avgNightPrice = $totalPrice / $nights / $request->rooms; // Simplified fallback
            
            $currentDate = Carbon::parse($request->check_in);
            while ($currentDate->lt($checkOut)) {
                $dateStr = $currentDate->format('Y-m-d');
                $rate = \App\Models\RoomRate::where('rate_plan_id', $ratePlan->id)->where('date', $dateStr)->first();
                
                $nightlySelling = $rate ? $rate->selling_price : $avgNightPrice; // Base price before markup
                
                $night = new \App\Models\BookingRoomNight();
                $night->booking_room_id = $bookingRoom->id;
                $night->stay_date = $dateStr;
                $night->cost_price = $rate ? $rate->selling_price : 0; // The contract selling price is our cost
                $night->selling_price = $avgNightPrice; // Distributed evenly for now
                $night->total_amount = $avgNightPrice * $request->rooms;
                $night->save();
                
                $currentDate->addDay();
            }

            // Create Guests
            for ($i = 0; $i < $request->rooms; $i++) {
                $guest = new BookingGuest();
                $guest->hotel_booking_id = $booking->id;
                $guest->booking_room_id = $bookingRoom->id;
                $guest->first_name = $request->first_name[$i];
                $guest->last_name = $request->last_name[$i];
                $guest->is_lead_guest = ($i == 0) ? true : false;
                $guest->save();
            }

            // B2B Agency Credit Deduction Flow
            $user = auth()->user();
            if ($user && $user->agency_id) {
                $agency = \App\Models\Agency::find($user->agency_id);
                if ($agency && $agency->credit_limit >= $totalPrice) {
                    // Deduct from credit limit
                    $agency->credit_limit -= $totalPrice;
                    $agency->save();

                    // Log in Agency Ledger
                    $ledger = new \App\Models\AgencyLedger();
                    $ledger->agency_id = $agency->id;
                    $ledger->transaction_type = 'booking';
                    $ledger->reference_id = $booking->id;
                    $ledger->amount = -$totalPrice;
                    $ledger->balance_after = $agency->credit_limit;
                    $ledger->description = 'Booking payment for ' . $booking->booking_number;
                    $ledger->save();

                    // Confirm Booking directly
                    $booking->booking_status = 'confirmed';
                    $booking->payment_status = 'paid';
                    $booking->save();

                    DB::commit();
                    session()->forget('booking_hold_id');
                    $notify[] = ['success', 'Booking confirmed and deducted from Agency Credit.'];
                    return redirect()->route('user.hotel-bookings.index')->withNotify($notify);
                }
            }

            // Create PlanLog for PaymentGateway (B2C Flow)
            $log = new \App\Models\PlanLog();
            $log->user_id = $user->id ?? 0;
            $log->plan_id = $booking->id;
            $log->seat = $request->rooms;
            $log->price = $totalPrice;
            $log->trx = $booking->booking_number;
            $log->type = 'hotel';
            $log->status = \App\Constants\Status::TOUR_PENDING;
            $log->save();

            DB::commit();

            session()->forget('booking_hold_id');
            session()->put('log_id', $log->id);
            
            $notify[] = ['success', 'Booking created successfully. Proceed to payment.'];
            return redirect()->route('user.deposit.index')->withNotify($notify);

        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', 'Failed to process booking: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
