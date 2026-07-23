<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage B2B Agencies';
        $agencies = Agency::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.agencies.index', compact('pageTitle', 'agencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => 'required|string|unique:agencies,code',
            'credit_limit' => 'numeric|min:0',
        ]);

        $agency = new Agency();
        $agency->name = $request->name;
        $agency->name_ar = $request->name_ar;
        $agency->code = $request->code;
        $agency->credit_limit = $request->credit_limit ?? 0;
        $agency->status = $request->status ? 1 : 0;
        $agency->save();

        $notify[] = ['success', 'Agency created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => 'required|string|unique:agencies,code,' . $id,
            'credit_limit' => 'numeric|min:0',
        ]);

        $agency = Agency::findOrFail($id);
        $agency->name = $request->name;
        $agency->name_ar = $request->name_ar;
        $agency->code = $request->code;
        $agency->credit_limit = $request->credit_limit ?? 0;
        $agency->status = $request->status ? 1 : 0;
        $agency->save();

        $notify[] = ['success', 'Agency updated successfully'];
        return back()->withNotify($notify);
    }
}
