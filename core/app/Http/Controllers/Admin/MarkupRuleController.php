<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarkupRule;
use App\Models\Hotel;
use App\Models\HotelSupplier;
use Illuminate\Http\Request;

class MarkupRuleController extends Controller
{
    public function index()
    {
        $pageTitle = 'Markup Rules';
        $rules = MarkupRule::orderByDesc('priority')->orderByDesc('id')->paginate(getPaginate());
        
        $hotels = Hotel::active()->get();
        $suppliers = HotelSupplier::active()->get();
        
        return view('admin.markup_rule.index', compact('pageTitle', 'rules', 'hotels', 'suppliers'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'hotel_id'      => 'nullable|integer|exists:hotels,id',
            'supplier_id'   => 'nullable|integer|exists:hotel_suppliers,id',
            'customer_type' => 'nullable|string|in:b2c,b2b,corporate',
            'market'        => 'nullable|string',
            'markup_type'   => 'required|in:percentage,fixed_amount,per_night,per_booking',
            'markup_value'  => 'required|numeric|min:0',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'priority'      => 'required|integer|min:0',
        ]);
        
        if ($id) {
            $rule           = MarkupRule::findOrFail($id);
            $notification   = 'Markup rule updated successfully';
        } else {
            $rule           = new MarkupRule();
            $notification   = 'Markup rule added successfully';
        }
        
        $rule->hotel_id      = $request->hotel_id;
        $rule->supplier_id   = $request->supplier_id;
        $rule->customer_type = $request->customer_type;
        $rule->market        = $request->market;
        $rule->markup_type   = $request->markup_type;
        $rule->markup_value  = $request->markup_value;
        $rule->start_date    = $request->start_date;
        $rule->end_date      = $request->end_date;
        $rule->priority      = $request->priority;
        $rule->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return MarkupRule::changeStatus($id);
    }
}
