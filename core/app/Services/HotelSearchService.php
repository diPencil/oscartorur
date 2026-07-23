<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\RoomInventory;
use App\Models\RoomRate;
use App\Models\MarkupRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HotelSearchService
{
    /**
     * Search for available hotels.
     * 
     * @param array $params ['location_id', 'check_in', 'check_out', 'rooms', 'adults', 'children']
     * @return array
     */
    public function searchHotels(array $params)
    {
        $checkIn = Carbon::parse($params['check_in']);
        $checkOut = Carbon::parse($params['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        
        $requestedRooms = $params['rooms'] ?? 1;
        $requestedAdults = $params['adults'] ?? 2;
        $requestedChildren = $params['children'] ?? 0;
        
        $locationId = $params['location_id'] ?? null;

        // Query active hotels
        $hotelsQuery = Hotel::active()->with([
            'location', 'supplier', 'amenities', 'images',
            'roomTypes' => function ($q) {
                $q->active()->with(['beds', 'amenities', 'images', 'contractRoomTypes.ratePlans.roomRates']);
            }
        ]);

        if ($locationId) {
            $hotelsQuery->where('location_id', $locationId);
        }

        $hotels = $hotelsQuery->get();

        $availableHotels = [];

        foreach ($hotels as $hotel) {
            $hotelAvailableRoomTypes = [];

            foreach ($hotel->roomTypes as $roomType) {
                // Check capacity
                if ($roomType->max_adults < ceil($requestedAdults / $requestedRooms)) {
                    continue; // Skip if room can't accommodate adults per room
                }

                $availableRatePlans = [];

                foreach ($roomType->contractRoomTypes as $contractRoomType) {
                    // Check Inventory across all dates
                    $isAvailable = true;
                    
                    $currentDate = $checkIn->copy();
                    while ($currentDate->lt($checkOut)) {
                        $dateStr = $currentDate->format('Y-m-d');
                        
                        $inventory = RoomInventory::where('contract_room_type_id', $contractRoomType->id)
                            ->where('date', $dateStr)
                            ->first();

                        $total = $inventory ? $inventory->total_inventory : $contractRoomType->allotment;
                        $reserved = $inventory ? $inventory->reserved_inventory : 0;
                        $held = $inventory ? $inventory->held_inventory : 0;
                        $blocked = $inventory ? $inventory->blocked_inventory : 0;
                        $stopSale = $inventory ? $inventory->stop_sale : false;

                        $availableRooms = $total - ($reserved + $held + $blocked);

                        if ($stopSale || $availableRooms < $requestedRooms) {
                            $isAvailable = false;
                            break;
                        }

                        $currentDate->addDay();
                    }

                    if (!$isAvailable) {
                        continue; // This contract room type is not available
                    }

                    // Now check Rate Plans under this ContractRoomType
                    foreach ($contractRoomType->ratePlans as $ratePlan) {
                        if ($ratePlan->status == 0) continue;

                        $ratesValid = true;
                        $totalPrice = 0;

                        $currentDate = $checkIn->copy();
                        while ($currentDate->lt($checkOut)) {
                            $dateStr = $currentDate->format('Y-m-d');
                            
                            $rate = RoomRate::where('rate_plan_id', $ratePlan->id)
                                ->where('date', $dateStr)
                                ->first();

                            if (!$rate || $rate->selling_price <= 0) {
                                $ratesValid = false;
                                break;
                            }

                            // Check CTA/CTD
                            if ($currentDate->isSameDay($checkIn) && $rate->closed_to_arrival) {
                                $ratesValid = false;
                                break;
                            }
                            if ($currentDate->isSameDay($checkOut->copy()->subDay()) && $rate->closed_to_departure) {
                                $ratesValid = false;
                                break;
                            }
                            
                            // Check Min Stay
                            if ($rate->minimum_stay > $nights) {
                                $ratesValid = false;
                                break;
                            }

                            // Calculate daily price
                            $dailyPrice = $rate->selling_price;
                            
                            // Simple logic for extra adults (if total adults > base capacity * rooms)
                            $baseCapacityTotal = $roomType->base_capacity * $requestedRooms;
                            if ($requestedAdults > $baseCapacityTotal) {
                                $extraAdults = $requestedAdults - $baseCapacityTotal;
                                $dailyPrice += ($extraAdults * $rate->extra_adult_price);
                            }

                            $totalPrice += ($dailyPrice * $requestedRooms);
                            
                            $currentDate->addDay();
                        }

                        if ($ratesValid) {
                            // Apply Markups
                            $finalPrice = $this->applyMarkup($hotel, $totalPrice, $nights, $requestedRooms);

                            $availableRatePlans[] = [
                                'rate_plan' => $ratePlan,
                                'total_price' => $finalPrice,
                                'original_price' => $totalPrice,
                                'avg_nightly' => $finalPrice / $nights / $requestedRooms
                            ];
                        }
                    }
                }

                if (!empty($availableRatePlans)) {
                    $hotelAvailableRoomTypes[] = [
                        'room_type' => $roomType,
                        'rate_plans' => $availableRatePlans
                    ];
                }
            }

            if (!empty($hotelAvailableRoomTypes)) {
                $availableHotels[] = [
                    'hotel' => $hotel,
                    'available_rooms' => $hotelAvailableRoomTypes,
                    // Find cheapest rate for display
                    'starting_price' => collect($hotelAvailableRoomTypes)->pluck('rate_plans')->flatten(1)->min('avg_nightly')
                ];
            }
        }

        // Sort by starting price
        usort($availableHotels, function($a, $b) {
            return $a['starting_price'] <=> $b['starting_price'];
        });

        return $availableHotels;
    }

    private function applyMarkup($hotel, $price, $nights, $rooms)
    {
        $now = Carbon::now()->format('Y-m-d');
        
        $rules = MarkupRule::active()
            ->where(function($q) use ($hotel) {
                $q->whereNull('hotel_id')->orWhere('hotel_id', $hotel->id);
            })
            ->where(function($q) use ($hotel) {
                $q->whereNull('supplier_id')->orWhere('supplier_id', $hotel->supplier_id);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')
                  ->orWhere(function($q2) use ($now) {
                      $q2->where('start_date', '<=', $now)
                         ->where('end_date', '>=', $now);
                  });
            })
            ->orderBy('priority', 'desc')
            ->get();

        $finalPrice = $price;

        // Apply highest priority matching rule (simple implementation)
        $rule = $rules->first(); 
        
        if ($rule) {
            if ($rule->markup_type == 'percentage') {
                $finalPrice += ($finalPrice * ($rule->markup_value / 100));
            } elseif ($rule->markup_type == 'fixed_amount') {
                $finalPrice += $rule->markup_value;
            } elseif ($rule->markup_type == 'per_night') {
                $finalPrice += ($rule->markup_value * $nights * $rooms);
            } elseif ($rule->markup_type == 'per_booking') {
                $finalPrice += $rule->markup_value;
            }
        }

        return $finalPrice;
    }
}
