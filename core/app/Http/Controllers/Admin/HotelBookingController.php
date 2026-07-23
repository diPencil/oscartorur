<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Hotel Bookings';
        $bookings = HotelBooking::with(['hotel', 'user'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.hotel_booking.index', compact('pageTitle', 'bookings'));
    }
    
    public function pending()
    {
        $pageTitle = 'Pending Hotel Bookings';
        $bookings = HotelBooking::where('booking_status', 'pending')->with(['hotel', 'user'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.hotel_booking.index', compact('pageTitle', 'bookings'));
    }
    
    public function confirmed()
    {
        $pageTitle = 'Confirmed Hotel Bookings';
        $bookings = HotelBooking::where('booking_status', 'confirmed')->with(['hotel', 'user'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.hotel_booking.index', compact('pageTitle', 'bookings'));
    }

    public function cancelled()
    {
        $pageTitle = 'Cancelled Hotel Bookings';
        $bookings = HotelBooking::where('booking_status', 'cancelled')->with(['hotel', 'user'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.hotel_booking.index', compact('pageTitle', 'bookings'));
    }

    public function details($id)
    {
        $booking = HotelBooking::with(['hotel', 'rooms.roomType', 'rooms.ratePlan', 'guests', 'user'])->findOrFail($id);
        $pageTitle = 'Booking Details - ' . $booking->booking_number;
        return view('admin.hotel_booking.details', compact('pageTitle', 'booking'));
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        $booking = HotelBooking::findOrFail($id);
        
        $oldStatus = $booking->booking_status;
        $booking->booking_status = $request->booking_status;
        $booking->payment_status = $request->payment_status;

        if ($request->booking_status == 'cancelled' && $oldStatus != 'cancelled') {
            $booking->cancelled_at = now();
            // Free up inventory
            $rooms = $booking->rooms;
            foreach($rooms as $room) {
                $inventoryRecords = \App\Models\RoomInventory::where('contract_room_type_id', $room->contract_room_type_id)
                    ->whereBetween('date', [$booking->check_in, \Carbon\Carbon::parse($booking->check_out)->subDay()->format('Y-m-d')])
                    ->get();
                    
                foreach($inventoryRecords as $inv) {
                    if($inv->reserved_inventory >= $booking->rooms_count) {
                        $inv->reserved_inventory -= $booking->rooms_count;
                        $inv->save();
                    }
                }
            }
        }
        
        $booking->save();

        $notify[] = ['success', 'Booking status updated successfully.'];
        return back()->withNotify($notify);
    }
}
