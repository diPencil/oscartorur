<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HotelBooking;
use App\Models\Hotel;
use App\Models\User;
use App\Models\BookingRoom;
use Illuminate\Support\Str;

$booking = new HotelBooking();
$booking->booking_number = 'HB-'.strtoupper(Str::random(8));
$booking->hotel_id = Hotel::first()->id ?? 1;
$booking->user_id = User::first()->id ?? 1;
$booking->check_in = now()->addDays(2)->format('Y-m-d');
$booking->check_out = now()->addDays(5)->format('Y-m-d');
$booking->rooms_count = 1;
$booking->adults = 2;
$booking->total_price = 450.00;
$booking->subtotal = 450.00;
$booking->booking_status = 'pending';
$booking->payment_status = 'unpaid';
$booking->save();

$room = new BookingRoom();
$room->hotel_booking_id = $booking->id;
$room->contract_room_type_id = 1;
$room->rate_plan_id = 1;
$room->adults = 2;
$room->price = 450.00;
$room->rate_plan_name_snapshot = 'Standard Rate';
$room->save();

echo "Booking Created: " . $booking->booking_number;
