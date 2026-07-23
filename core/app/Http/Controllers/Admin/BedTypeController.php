<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BedType;
use Illuminate\Http\Request;

class BedTypeController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Bed Types';
        $bedTypes = BedType::searchable(['name'])->orderByDesc('id')->paginate(getPaginate());
        return view('admin.bed_type.index', compact('pageTitle', 'bedTypes'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:bed_types,name,' . $id,
            'name_ar' => 'nullable|string|max:255',
        ]);
        
        if ($id) {
            $bedType            = BedType::findOrFail($id);
            $notification       = 'Bed Type updated successfully';
        } else {
            $bedType            = new BedType();
            $notification       = 'Bed Type added successfully';
        }
        
        $bedType->name = $request->name;
        $bedType->name_ar = $request->name_ar;
        $bedType->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return BedType::changeStatus($id);
    }
}
