<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hotel;
use App\Models\Location;
use App\Models\RoomType;
use App\Models\HotelSupplier;
use App\Models\HotelContract;
use App\Models\ContractRoomType;
use App\Models\RatePlan;
use App\Models\RoomRate;
use App\Models\RoomInventory;
use App\Models\HotelImage;
use App\Models\RoomTypeImage;
use App\Models\Amenity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

Model::unguard();

// Clean up first
$existing = Hotel::where('slug', 'oscar-grand-palace')->first();
if($existing) {
    $existing->forceDelete();
}

// 1. Locations & Suppliers
$location = Location::firstOrCreate(['name' => 'Sharm El-Sheikh'], ['status' => 1, 'name_ar' => 'شرم الشيخ']);
$supplier = HotelSupplier::firstOrCreate(['name' => 'Oscar Direct'], ['status' => 1, 'email' => 'direct@oscar.com', 'phone' => '123456789']);

// 2. Create Amenities
$hotelAmenities = [
    ['name' => 'Free Wi-Fi', 'name_ar' => 'واي فاي مجاني', 'icon' => 'las la-wifi', 'type' => 'hotel'],
    ['name' => 'Swimming Pool', 'name_ar' => 'مسبح', 'icon' => 'las la-swimming-pool', 'type' => 'hotel'],
    ['name' => 'Restaurant', 'name_ar' => 'مطعم', 'icon' => 'las la-utensils', 'type' => 'hotel'],
    ['name' => 'Spa', 'name_ar' => 'سبا ومركز صحي', 'icon' => 'las la-spa', 'type' => 'hotel'],
    ['name' => 'Parking', 'name_ar' => 'موقف سيارات', 'icon' => 'las la-parking', 'type' => 'hotel'],
    ['name' => 'Gym', 'name_ar' => 'مركز لياقة بدنية', 'icon' => 'las la-dumbbell', 'type' => 'hotel'],
];

$hotelAmenityIds = [];
foreach($hotelAmenities as $am) {
    $amenity = Amenity::firstOrCreate(['name' => $am['name'], 'type' => 'hotel'], ['name_ar' => $am['name_ar'], 'icon' => $am['icon'], 'status' => 1]);
    $hotelAmenityIds[] = $amenity->id;
}

$roomAmenities = [
    ['name' => 'Sea View', 'name_ar' => 'إطلالة على البحر', 'icon' => 'las la-water', 'type' => 'room'],
    ['name' => 'Air Conditioning', 'name_ar' => 'تكييف هواء', 'icon' => 'las la-snowflake', 'type' => 'room'],
    ['name' => 'Balcony', 'name_ar' => 'شرفة', 'icon' => 'las la-border-all', 'type' => 'room'],
    ['name' => 'Flat-screen TV', 'name_ar' => 'شاشة مسطحة', 'icon' => 'las la-tv', 'type' => 'room'],
];

$roomAmenityIds = [];
foreach($roomAmenities as $am) {
    $amenity = Amenity::firstOrCreate(['name' => $am['name'], 'type' => 'room'], ['name_ar' => $am['name_ar'], 'icon' => $am['icon'], 'status' => 1]);
    $roomAmenityIds[] = $amenity->id;
}

// 3. Create Hotel
$hotel = Hotel::create([
    'name' => 'Sunrise Arabian Beach Resort',
    'name_ar' => 'صن رايز أرابيان بيتش ريزورت',
    'slug' => 'oscar-grand-palace', // keep the same slug so we don't break links
    'location_id' => $location->id,
    'primary_supplier_id' => $supplier->id,
    'star_rating' => 5,
    'status' => 'active',
    'address' => 'Sharks Bay, Sharm El Sheikh',
    'address_ar' => 'شاركس باي، شرم الشيخ',
    'phone' => '+20 69 360 2130',
    'hotel_email' => 'info@sunrise-resorts.com',
    'website' => 'www.sunrise-resorts.com',
    'check_in_time' => '14:00:00',
    'check_out_time' => '12:00:00',
    'description' => 'Experience world-class service at Sunrise Arabian Beach Resort. Located along its private beach in Sharks Bay, this Resort is surrounded by gardens and offers a variety of facilities including a spa. This property offers 6 a-la-carte restaurants.',
    'description_ar' => 'يقع هذا المنتجع على شاطئه الخاص في خليج القرش وتحيط به الحدائق ويوفر مجموعة متنوعة من المرافق تشمل سبا. يوفر مكان الإقامة هذا 6 مطاعم انتقائية. تتميز الغرف العصرية بتراس أو شرفة، وتطل بعضها على البحر الأحمر، وتضم جميعها تكييف هواء.',
]);

// Attach Hotel Amenities
$hotel->amenities()->sync($hotelAmenityIds);

// 4. Hotel Images
$hotelImages = [
    ['url' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1470&auto=format&fit=crop', 'is_cover' => 1],
    ['url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1470&auto=format&fit=crop', 'is_cover' => 0],
    ['url' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1425&auto=format&fit=crop', 'is_cover' => 0],
    ['url' => 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?q=80&w=1470&auto=format&fit=crop', 'is_cover' => 0],
    ['url' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1480&auto=format&fit=crop', 'is_cover' => 0],
    ['url' => 'https://images.unsplash.com/photo-1551882547-ff40eb0d4791?q=80&w=1474&auto=format&fit=crop', 'is_cover' => 0],
];

foreach ($hotelImages as $img) {
    HotelImage::create([
        'hotel_id' => $hotel->id,
        'image' => $img['url'],
        'is_cover' => $img['is_cover'],
    ]);
}

// 5. Room Types
$deluxeRoom = RoomType::create([
    'hotel_id' => $hotel->id,
    'name' => 'Deluxe Room with Sea View',
    'name_ar' => 'غرفة ديلوكس مطلة على البحر',
    'description' => 'A beautiful room with a sea view.',
    'max_adults' => 2,
    'max_children' => 1,
    'status' => 1
]);
$deluxeRoom->amenities()->sync($roomAmenityIds);

$premiumSuite = RoomType::create([
    'hotel_id' => $hotel->id,
    'name' => 'Premium Suite with Private Pool',
    'name_ar' => 'جناح بريميوم مع مسبح خاص',
    'description' => 'A spacious suite with a private pool.',
    'max_adults' => 4,
    'max_children' => 2,
    'status' => 1
]);
$premiumSuite->amenities()->sync($roomAmenityIds);

// Room Images
RoomTypeImage::create(['room_type_id' => $deluxeRoom->id, 'image' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=1470&auto=format&fit=crop', 'is_cover' => 1]);
RoomTypeImage::create(['room_type_id' => $deluxeRoom->id, 'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=1470&auto=format&fit=crop', 'is_cover' => 0]);

RoomTypeImage::create(['room_type_id' => $premiumSuite->id, 'image' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1470&auto=format&fit=crop', 'is_cover' => 1]);
RoomTypeImage::create(['room_type_id' => $premiumSuite->id, 'image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?q=80&w=1470&auto=format&fit=crop', 'is_cover' => 0]);

// 6. Contract, Contract Room Types, and Rate Plans
$contract = HotelContract::create([
    'hotel_id' => $hotel->id,
    'contract_name' => 'Summer 2026 Direct Contract',
    'start_date' => Carbon::now()->format('Y-m-d'),
    'end_date' => Carbon::now()->addYear()->format('Y-m-d'),
    'status' => 1
]);

$crtDeluxe = ContractRoomType::create([
    'contract_id' => $contract->id,
    'room_type_id' => $deluxeRoom->id,
    'allotment' => 10
]);

$crtSuite = ContractRoomType::create([
    'contract_id' => $contract->id,
    'room_type_id' => $premiumSuite->id,
    'allotment' => 5
]);

$rpDeluxeRO = RatePlan::create([
    'contract_room_type_id' => $crtDeluxe->id,
    'name' => 'Room Only (Non-Refundable)',
    'name_ar' => 'إقامة فقط (غير قابل للاسترداد)',
    'meal_plan_id' => 1,
    'cancellation_policy_id' => 1,
    'payment_type' => 'pay_now',
    'refundable' => 0,
    'status' => 1
]);

$rpDeluxeBB = RatePlan::create([
    'contract_room_type_id' => $crtDeluxe->id,
    'name' => 'Bed & Breakfast (Free Cancellation)',
    'name_ar' => 'شامل الإفطار (إلغاء مجاني)',
    'meal_plan_id' => 2,
    'cancellation_policy_id' => 2,
    'payment_type' => 'pay_at_hotel',
    'refundable' => 1,
    'status' => 1
]);

$rpSuiteBB = RatePlan::create([
    'contract_room_type_id' => $crtSuite->id,
    'name' => 'Bed & Breakfast (Free Cancellation)',
    'name_ar' => 'شامل الإفطار (إلغاء مجاني)',
    'meal_plan_id' => 2,
    'cancellation_policy_id' => 2,
    'payment_type' => 'pay_at_hotel',
    'refundable' => 1,
    'status' => 1
]);

// 7. Inventory and Rates for next 30 days
$today = Carbon::now();
for ($i = 0; $i < 30; $i++) {
    $dateStr = $today->copy()->addDays($i)->format('Y-m-d');
    
    // Deluxe
    RoomInventory::create(['contract_room_type_id' => $crtDeluxe->id, 'date' => $dateStr, 'total_inventory' => 10, 'reserved_inventory' => 0]);
    RoomRate::create(['rate_plan_id' => $rpDeluxeRO->id, 'date' => $dateStr, 'cost_price' => 100, 'selling_price' => 150, 'extra_adult_price' => 50, 'minimum_stay' => 1]);
    RoomRate::create(['rate_plan_id' => $rpDeluxeBB->id, 'date' => $dateStr, 'cost_price' => 120, 'selling_price' => 180, 'extra_adult_price' => 50, 'minimum_stay' => 1]);
    
    // Suite
    RoomInventory::create(['contract_room_type_id' => $crtSuite->id, 'date' => $dateStr, 'total_inventory' => 5, 'reserved_inventory' => 0]);
    RoomRate::create(['rate_plan_id' => $rpSuiteBB->id, 'date' => $dateStr, 'cost_price' => 300, 'selling_price' => 450, 'extra_adult_price' => 100, 'minimum_stay' => 1]);
}

echo "Hotel '{$hotel->name}' seeded successfully with all comprehensive details!\n";
