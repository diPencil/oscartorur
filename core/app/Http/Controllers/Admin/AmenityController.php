<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Amenities';
        $amenities = Amenity::searchable(['name'])->orderByDesc('id')->paginate(getPaginate());
        return view('admin.amenity.index', compact('pageTitle', 'amenities'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'icon'    => 'nullable|string|max:255',
            'type'    => 'required|in:hotel,room',
        ]);
        
        if ($id) {
            $amenity            = Amenity::findOrFail($id);
            $notification       = 'Amenity updated successfully';
        } else {
            $amenity            = new Amenity();
            $notification       = 'Amenity added successfully';
        }
        
        $amenity->name = $request->name;
        $amenity->name_ar = $request->name_ar;
        $amenity->icon = $request->icon;
        $amenity->type = $request->type;
        $amenity->save();
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Amenity::changeStatus($id);
    }
}
