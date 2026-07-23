<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\Amenity;
use App\Models\HotelSupplier;
use App\Models\Hotel;
use App\Models\HotelContract;
use App\Models\RoomType;
use App\Models\BedType;
use App\Models\ContractRoomType;
use App\Models\RatePlan;
use App\Models\RoomInventory;
use App\Models\RoomRate;
use App\Models\CancellationPolicy;
use App\Models\CancellationPolicyRule;
use App\Models\Agency;
use Carbon\Carbon;

class MockHotelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Location
        $location = Location::firstOrCreate(
            ['name' => 'Cairo'],
            ['status' => 1]
        );

        // 2. Amenity
        $amenity = Amenity::firstOrCreate(
            ['name' => 'Free Wi-Fi'],
            ['icon' => 'las la-wifi', 'status' => 1]
        );

        // 3. Hotel Supplier
        $supplier = HotelSupplier::firstOrCreate(
            ['email' => 'supplier@demo.com'],
            ['name' => 'Demo Supplier', 'phone' => '123456789', 'status' => 1]
        );

        // 4. Hotel
        $hotel = Hotel::firstOrCreate(
            ['slug' => Str::slug('The Grand Pyramids Hotel')],
            [
                'name' => 'The Grand Pyramids Hotel',
                'location_id' => $location->id,
                'supplier_id' => $supplier->id,
                'star_rating' => 5,
                'address' => 'Giza, Egypt',
                'description' => '<p>Experience luxury with a stunning view of the Great Pyramids.</p>',
                'status' => 1
            ]
        );

        // Attach Amenity
        if (!$hotel->amenities()->where('amenity_id', $amenity->id)->exists()) {
            $hotel->amenities()->attach($amenity->id);
        }

        // 5. Hotel Contract
        $contract = HotelContract::firstOrCreate(
            ['contract_name' => 'Demo Summer Contract 2026'],
            [
                'hotel_id' => $hotel->id,
                'supplier_id' => $supplier->id,
                'start_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'end_date' => Carbon::now()->addYear()->format('Y-m-d'),
                'status' => 1
            ]
        );

        // 6. Room Type & Bed Type
        $roomType = RoomType::firstOrCreate(
            ['hotel_id' => $hotel->id, 'name' => 'Deluxe Pyramid View'],
            ['description' => 'A beautiful room with a direct view of the Pyramids.', 'status' => 1]
        );

        $bedType = BedType::firstOrCreate(
            ['name' => 'King Size'],
            ['status' => 1]
        );

        // 7. Contract Room Type (Allotment)
        $contractRoomType = ContractRoomType::firstOrCreate(
            ['contract_id' => $contract->id, 'room_type_id' => $roomType->id],
            [
                'allotment' => 10
            ]
        );

        // Attach Bed Type to RoomType
        if (!$roomType->beds()->where('bed_type_id', $bedType->id)->exists()) {
            $roomType->beds()->attach($bedType->id, ['quantity' => 1]);
        }

        // 8. Cancellation Policy
        $policy = CancellationPolicy::firstOrCreate(
            ['name' => 'Standard Non-Refundable within 24h'],
            ['status' => 1]
        );
        CancellationPolicyRule::firstOrCreate(
            ['cancellation_policy_id' => $policy->id, 'from_hours_before' => 24],
            ['to_hours_before' => 0, 'penalty_type' => 'percentage', 'penalty_value' => 100]
        );

        // 9. Rate Plan
        $ratePlan = RatePlan::firstOrCreate(
            ['contract_room_type_id' => $contractRoomType->id, 'name' => 'Room Only - Best Available Rate'],
            [
                'cancellation_policy_id' => $policy->id,
                'refundable' => 0,
                'status' => 1
            ]
        );

        // 10. Room Inventory and Room Rate (next 30 days)
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            
            RoomInventory::firstOrCreate(
                ['contract_room_type_id' => $contractRoomType->id, 'date' => $date],
                ['total_inventory' => 10, 'reserved_inventory' => 0, 'held_inventory' => 0, 'stop_sale' => 0]
            );

            RoomRate::firstOrCreate(
                ['rate_plan_id' => $ratePlan->id, 'date' => $date],
                [
                    'cost_price' => 100.00,
                    'selling_price' => 150.00,
                    'extra_adult_price' => 50.00,
                    'single_supplement' => 30.00
                ]
            );
        }

        // 11. Agency
        Agency::firstOrCreate(
            ['code' => 'DEMO-B2B'],
            ['name' => 'Global Travel Agency', 'credit_limit' => 50000, 'status' => 1]
        );
    }
}
