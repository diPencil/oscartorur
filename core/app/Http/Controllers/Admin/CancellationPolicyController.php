<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancellationPolicy;
use Illuminate\Http\Request;

class CancellationPolicyController extends Controller
{
    public function index()
    {
        $pageTitle = 'Cancellation Policies';
        $policies = CancellationPolicy::searchable(['name'])->orderByDesc('id')->paginate(getPaginate());
        return view('admin.cancellation_policy.index', compact('pageTitle', 'policies'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'name_ar'              => 'nullable|string|max:255',
            'description'          => 'nullable|string',
            'description_ar'       => 'nullable|string',
            'days_before_checkin'  => 'required|integer|min:0',
            'penalty_type'         => 'required|in:percentage,fixed,nights',
            'penalty_value'        => 'required|numeric|min:0',
        ]);
        
        if ($id) {
            $policy         = CancellationPolicy::findOrFail($id);
            $notification   = 'Policy updated successfully';
        } else {
            $policy         = new CancellationPolicy();
            $notification   = 'Policy added successfully';
        }
        
        $policy->name                 = $request->name;
        $policy->name_ar              = $request->name_ar;
        $policy->description          = $request->description;
        $policy->description_ar       = $request->description_ar;
        $policy->days_before_checkin  = $request->days_before_checkin;
        $policy->penalty_type         = $request->penalty_type;
        $policy->penalty_value        = $request->penalty_value;
        $policy->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return CancellationPolicy::changeStatus($id);
    }
}
