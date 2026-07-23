<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelSupplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Suppliers';
        $suppliers = HotelSupplier::searchable(['name', 'email', 'phone'])->orderByDesc('id')->paginate(getPaginate());
        return view('admin.supplier.index', compact('pageTitle', 'suppliers'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:40',
        ]);
        
        if ($id) {
            $supplier           = HotelSupplier::findOrFail($id);
            $notification       = 'Supplier updated successfully';
        } else {
            $supplier           = new HotelSupplier();
            $notification       = 'Supplier added successfully';
        }
        
        $supplier->name = $request->name;
        $supplier->name_ar = $request->name_ar;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return HotelSupplier::changeStatus($id);
    }
}
