<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Location;
use App\Services\HotelSearchService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected $hotelSearchService;

    public function __construct(HotelSearchService $hotelSearchService)
    {
        $this->hotelSearchService = $hotelSearchService;
    }

    public function search(Request $request)
    {
        $pageTitle = 'Search Hotels';
        $locations = Location::active()->orderBy('name')->get();

        $request->validate([
            'location_id' => 'nullable|integer',
            'check_in'    => 'required|date|after_or_equal:today',
            'check_out'   => 'required|date|after:check_in',
            'rooms'       => 'required|integer|min:1',
            'adults'      => 'required|integer|min:1',
            'children'    => 'nullable|integer|min:0',
        ]);

        $params = $request->only(['location_id', 'check_in', 'check_out', 'rooms', 'adults', 'children']);
        
        $availableHotels = $this->hotelSearchService->searchHotels($params);

        return view('Template::hotel.search_results', compact('pageTitle', 'availableHotels', 'locations', 'params'));
    }

    public function details(Request $request, $id, $slug)
    {
        $hotel = Hotel::active()->where('id', $id)->with(['location', 'supplier', 'amenities', 'images'])->firstOrFail();
        $pageTitle = $hotel->name;

        // If dates are not provided, default to today and tomorrow
        if (!$request->has('check_in') || !$request->has('check_out')) {
            $request->merge([
                'check_in' => now()->format('Y-m-d'),
                'check_out' => now()->addDay()->format('Y-m-d'),
                'rooms' => 1,
                'adults' => 2,
            ]);
        }

        $availableRooms = [];
        $params = [];

        if ($request->has('check_in') && $request->has('check_out')) {
            $request->validate([
                'check_in'    => 'required|date|after_or_equal:today',
                'check_out'   => 'required|date|after:check_in',
                'rooms'       => 'required|integer|min:1',
                'adults'      => 'required|integer|min:1',
            ]);

            $params = $request->only(['check_in', 'check_out', 'rooms', 'adults', 'children']);
            $params['location_id'] = $hotel->location_id; 
            
            $allAvailable = $this->hotelSearchService->searchHotels($params);
            
            foreach ($allAvailable as $ah) {
                if ($ah['hotel']->id == $hotel->id) {
                    $availableRooms = $ah['available_rooms'];
                    break;
                }
            }
        }

        return view('Template::hotel.details', compact('pageTitle', 'hotel', 'availableRooms', 'params'));
    }
}
