<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::searchable(['name'])->orderBy('name')->paginate(getPaginate());
        $pageTitle = 'All Locations';
        return view('admin.location.index', compact('pageTitle','locations'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'        => 'required|unique:locations,name,' . $id,
        ]);

        if ($id) {
            $location           = Location::findOrFail($id);
            $notification       = 'Location updated successfully';
        } else {
            $location           = new Location();
            $notification       = 'Location added successfully';
        }
        $location->name = $request->name;
        $location->name_ar = $request->name_ar;
        $location->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Location::changeStatus($id);
    }
}
