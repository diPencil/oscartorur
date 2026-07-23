<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelContract;
use App\Models\HotelSupplier;
use Illuminate\Http\Request;

class HotelContractController extends Controller
{
    public function index($hotel_id = null)
    {
        $pageTitle = 'Hotel Contracts';
        
        $contracts = HotelContract::with(['hotel', 'supplier'])->searchable(['contract_name']);
        if ($hotel_id) {
            $contracts = $contracts->where('hotel_id', $hotel_id);
        }
        $contracts = $contracts->orderByDesc('id')->paginate(getPaginate());
        
        return view('admin.hotel.contract.index', compact('pageTitle', 'contracts'));
    }

    public function create($hotel_id = null)
    {
        $pageTitle = 'Add New Contract';
        $hotels = Hotel::active()->orderBy('name')->get();
        $suppliers = HotelSupplier::active()->orderBy('name')->get();
        return view('admin.hotel.contract.form', compact('pageTitle', 'hotels', 'suppliers', 'hotel_id'));
    }

    public function edit($id)
    {
        $contract = HotelContract::findOrFail($id);
        $pageTitle = 'Edit Contract: ' . $contract->contract_name;
        $hotels = Hotel::active()->orderBy('name')->get();
        $suppliers = HotelSupplier::active()->orderBy('name')->get();
        return view('admin.hotel.contract.form', compact('pageTitle', 'contract', 'hotels', 'suppliers'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'hotel_id'                => 'required|integer|exists:hotels,id',
            'supplier_id'             => 'nullable|integer|exists:hotel_suppliers,id',
            'contract_name'           => 'required|string|max:255',
            'contract_name_ar'        => 'nullable|string|max:255',
            'start_date'              => 'required|date',
            'end_date'                => 'required|date|after_or_equal:start_date',
            'market'                  => 'nullable|string',
            'nationality_restriction' => 'nullable|string',
            'release_days'            => 'required|integer|min:0',
            'payment_terms'           => 'nullable|string',
            'confirmation_mode'       => 'required|in:instant,on_request',
        ]);
        
        if ($id) {
            $contract       = HotelContract::findOrFail($id);
            $notification   = 'Contract updated successfully';
        } else {
            $contract       = new HotelContract();
            $notification   = 'Contract added successfully';
        }
        
        $contract->hotel_id                = $request->hotel_id;
        $contract->supplier_id             = $request->supplier_id;
        $contract->contract_name           = $request->contract_name;
        $contract->contract_name_ar        = $request->contract_name_ar;
        $contract->start_date              = $request->start_date;
        $contract->end_date                = $request->end_date;
        $contract->market                  = $request->market;
        $contract->nationality_restriction = $request->nationality_restriction;
        $contract->release_days            = $request->release_days;
        $contract->payment_terms           = $request->payment_terms;
        $contract->confirmation_mode       = $request->confirmation_mode;
        $contract->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $contract = HotelContract::findOrFail($id);
        $contract->status = $contract->status == 1 ? 0 : 1;
        $contract->save();
        
        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }
}
