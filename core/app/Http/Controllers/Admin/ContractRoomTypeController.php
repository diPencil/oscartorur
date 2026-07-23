<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractRoomType;
use App\Models\HotelContract;
use App\Models\RoomType;
use Illuminate\Http\Request;

class ContractRoomTypeController extends Controller
{
    public function index($contract_id = null)
    {
        $pageTitle = 'Contract Room Types';
        
        $contractRoomTypes = ContractRoomType::with(['contract', 'roomType']);
        
        if ($contract_id) {
            $contractRoomTypes = $contractRoomTypes->where('contract_id', $contract_id);
        }
        
        $contractRoomTypes = $contractRoomTypes->orderByDesc('id')->paginate(getPaginate());
        
        return view('admin.contract_room_type.index', compact('pageTitle', 'contractRoomTypes', 'contract_id'));
    }

    public function create($contract_id = null)
    {
        $pageTitle = 'Add Room Type to Contract';
        $contracts = HotelContract::active()->get();
        $roomTypes = RoomType::active()->get();
        return view('admin.contract_room_type.form', compact('pageTitle', 'contracts', 'roomTypes', 'contract_id'));
    }

    public function edit($id)
    {
        $contractRoomType = ContractRoomType::findOrFail($id);
        $pageTitle = 'Edit Contract Room Type';
        $contracts = HotelContract::active()->get();
        $roomTypes = RoomType::active()->get();
        return view('admin.contract_room_type.form', compact('pageTitle', 'contractRoomType', 'contracts', 'roomTypes'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'contract_id'        => 'required|integer|exists:hotel_contracts,id',
            'room_type_id'       => 'required|integer|exists:room_types,id',
            'allotment'          => 'required|integer|min:0',
            'max_extra_beds'     => 'required|integer|min:0',
        ]);
        
        if ($id) {
            $contractRoomType = ContractRoomType::findOrFail($id);
            $notification = 'Contract Room Type updated successfully';
        } else {
            // Check if already exists
            $exists = ContractRoomType::where('contract_id', $request->contract_id)
                                      ->where('room_type_id', $request->room_type_id)
                                      ->exists();
            if ($exists) {
                return back()->withNotify([['error', 'This Room Type is already added to the selected Contract.']]);
            }
            $contractRoomType = new ContractRoomType();
            $notification = 'Contract Room Type added successfully';
        }
        
        $contractRoomType->contract_id    = $request->contract_id;
        $contractRoomType->room_type_id   = $request->room_type_id;
        $contractRoomType->allotment      = $request->allotment;
        $contractRoomType->max_extra_beds = $request->max_extra_beds;
        $contractRoomType->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
