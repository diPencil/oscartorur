<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RatePlan;
use App\Models\RoomRate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomRateController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Room Rates';
        
        $ratePlans = RatePlan::with(['contractRoomType.roomType', 'contractRoomType.contract'])->get();
        
        $rates = [];
        $rate_plan_id = $request->rate_plan_id;
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d') : Carbon::now()->addDays(30)->format('Y-m-d');

        if ($rate_plan_id) {
            $rates = RoomRate::where('rate_plan_id', $rate_plan_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->orderBy('date', 'asc')
                ->get();
        }

        return view('admin.room_rate.index', compact('pageTitle', 'ratePlans', 'rates', 'rate_plan_id', 'start_date', 'end_date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate_plan_id'      => 'required|integer|exists:rate_plans,id',
            'start_date'        => 'required|date|date_format:Y-m-d',
            'end_date'          => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'cost_price'        => 'nullable|numeric|min:0',
            'selling_price'     => 'nullable|numeric|min:0',
            'single_supplement' => 'nullable|numeric|min:0',
            'extra_adult_price' => 'nullable|numeric|min:0',
            'minimum_stay'      => 'nullable|integer|min:1',
            'maximum_stay'      => 'nullable|integer|min:1',
            'closed_to_arrival' => 'nullable|in:1,0',
            'closed_to_departure'=> 'nullable|in:1,0',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);

        while ($startDate->lte($endDate)) {
            $date = $startDate->format('Y-m-d');
            
            $rate = RoomRate::firstOrNew([
                'rate_plan_id' => $request->rate_plan_id,
                'date'         => $date
            ]);

            if ($request->filled('cost_price')) {
                $rate->cost_price = $request->cost_price;
            } elseif (!$rate->exists) {
                $rate->cost_price = 0;
            }

            if ($request->filled('selling_price')) {
                $rate->selling_price = $request->selling_price;
            } elseif (!$rate->exists) {
                $rate->selling_price = 0;
            }

            if ($request->filled('single_supplement')) {
                $rate->single_supplement = $request->single_supplement;
            } elseif (!$rate->exists) {
                $rate->single_supplement = 0;
            }
            
            if ($request->filled('extra_adult_price')) {
                $rate->extra_adult_price = $request->extra_adult_price;
            } elseif (!$rate->exists) {
                $rate->extra_adult_price = 0;
            }

            if ($request->filled('minimum_stay')) {
                $rate->minimum_stay = $request->minimum_stay;
            } elseif (!$rate->exists) {
                $rate->minimum_stay = 1;
            }

            if ($request->filled('maximum_stay')) {
                $rate->maximum_stay = $request->maximum_stay;
            }

            if ($request->filled('closed_to_arrival')) {
                $rate->closed_to_arrival = $request->closed_to_arrival;
            } elseif (!$rate->exists) {
                $rate->closed_to_arrival = 0;
            }

            if ($request->filled('closed_to_departure')) {
                $rate->closed_to_departure = $request->closed_to_departure;
            } elseif (!$rate->exists) {
                $rate->closed_to_departure = 0;
            }

            $rate->save();
            $startDate->addDay();
        }

        $notify[] = ['success', 'Room rates updated successfully for the selected date range.'];
        return back()->withNotify($notify);
    }
}
