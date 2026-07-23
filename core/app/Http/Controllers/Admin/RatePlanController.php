<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RatePlan;
use Illuminate\Http\Request;

class RatePlanController extends Controller
{
    public function index($contract_room_type_id = null)
    {
        $pageTitle = 'Rate Plans';
        $ratePlans = RatePlan::with(['contractRoomType.roomType', 'cancellationPolicy'])->searchable(['name']);
        
        if ($contract_room_type_id) {
            $ratePlans = $ratePlans->where('contract_room_type_id', $contract_room_type_id);
        }
        
        $ratePlans = $ratePlans->orderByDesc('id')->paginate(getPaginate());
        
        $contractRoomTypes = \App\Models\ContractRoomType::with(['roomType', 'contract'])->get();
        $cancellationPolicies = \App\Models\CancellationPolicy::active()->get();
        
        return view('admin.rate_plan.index', compact('pageTitle', 'ratePlans', 'contractRoomTypes', 'cancellationPolicies', 'contract_room_type_id'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'contract_room_type_id'  => 'required|integer|exists:contract_room_types,id',
            'name'                   => 'required|string|max:255',
            'name_ar'                => 'nullable|string|max:255',
            'cancellation_policy_id' => 'nullable|integer|exists:cancellation_policies,id',
            'payment_type'           => 'nullable|string|in:prepaid,post_paid',
            'refundable'             => 'required|in:1,0',
        ]);
        
        if ($id) {
            $ratePlan       = RatePlan::findOrFail($id);
            $notification   = 'Rate Plan updated successfully';
        } else {
            $ratePlan       = new RatePlan();
            $notification   = 'Rate Plan added successfully';
        }
        
        $ratePlan->contract_room_type_id  = $request->contract_room_type_id;
        $ratePlan->name                   = $request->name;
        $ratePlan->name_ar                = $request->name_ar;
        $ratePlan->cancellation_policy_id = $request->cancellation_policy_id;
        $ratePlan->payment_type           = $request->payment_type;
        $ratePlan->refundable             = $request->refundable;
        $ratePlan->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return RatePlan::changeStatus($id);
    }
}
