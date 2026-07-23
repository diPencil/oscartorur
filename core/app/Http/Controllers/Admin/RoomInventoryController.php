<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractRoomType;
use App\Models\RoomInventory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomInventoryController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Room Inventory';
        
        $contractRoomTypes = ContractRoomType::with(['roomType', 'contract'])->get();
        
        $inventories = [];
        $contract_room_type_id = $request->contract_room_type_id;
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d') : Carbon::now()->addDays(30)->format('Y-m-d');

        if ($contract_room_type_id) {
            $inventories = RoomInventory::where('contract_room_type_id', $contract_room_type_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->orderBy('date', 'asc')
                ->get();
        }

        return view('admin.room_inventory.index', compact('pageTitle', 'contractRoomTypes', 'inventories', 'contract_room_type_id', 'start_date', 'end_date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contract_room_type_id' => 'required|integer|exists:contract_room_types,id',
            'start_date'            => 'required|date|date_format:Y-m-d',
            'end_date'              => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'total_inventory'       => 'nullable|integer|min:0',
            'blocked_inventory'     => 'nullable|integer|min:0',
            'stop_sale'             => 'required|in:1,0',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);

        // Fetch contract room type default allotment
        $crt = ContractRoomType::findOrFail($request->contract_room_type_id);

        while ($startDate->lte($endDate)) {
            $date = $startDate->format('Y-m-d');
            
            $inventory = RoomInventory::firstOrNew([
                'contract_room_type_id' => $request->contract_room_type_id,
                'date'                  => $date
            ]);

            if ($request->filled('total_inventory')) {
                $inventory->total_inventory = $request->total_inventory;
            } elseif (!$inventory->exists) {
                // Default to contract allotment if creating new
                $inventory->total_inventory = $crt->allotment;
            }

            if ($request->filled('blocked_inventory')) {
                $inventory->blocked_inventory = $request->blocked_inventory;
            }

            $inventory->stop_sale = $request->stop_sale;
            $inventory->save();

            $startDate->addDay();
        }

        $notify[] = ['success', 'Inventory updated successfully for the selected date range.'];
        return back()->withNotify($notify);
    }
}
