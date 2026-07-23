<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Country;
use App\Models\Location;
use App\Models\Area;
use App\Models\HotelSupplier;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Hotels';
        $hotels = Hotel::orderByDesc('id')->with(['country', 'location'])->paginate(getPaginate());
        return view('admin.hotel.index', compact('pageTitle', 'hotels'));
    }

    public function create()
    {
        $pageTitle = 'Add New Hotel';
        $countries = Country::where('status', 1)->orderBy('name')->get();
        return view('admin.hotel.form', compact('pageTitle', 'countries'));
    }

    public function manage($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotelName = app()->getLocale() == 'ar' && $hotel->name_ar ? $hotel->name_ar : $hotel->name;
        $pageTitle = 'Manage Hotel: ' . $hotelName;
        
        $countries = Country::where('status', 1)->orderBy('name')->get();
        $locations = Location::where('status', 1)->orderBy('name')->get();
        $areas = Area::where('status', 1)->orderBy('name')->get();
        $suppliers = HotelSupplier::active()->orderBy('name')->get();
        
        $activationErrors = $hotel->checkActivationReadiness();
        
        return view('admin.hotel.manage', compact('pageTitle', 'hotel', 'countries', 'locations', 'areas', 'suppliers', 'activationErrors'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'property_type'  => 'required|string|max:100',
            'star_rating'    => 'required|integer|min:1|max:5',
            'country_id'     => 'required|integer|exists:countries,id',
            'location_id'    => 'required|integer|exists:locations,id',
            'area_id'        => 'nullable|integer|exists:areas,id',
            'address'        => 'required|string',
            'address_ar'     => 'nullable|string',
            'check_in_time'  => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'timezone'       => 'required|string',
            'hotel_email'    => 'nullable|email',
            'phone'          => 'nullable|string',
            'short_description' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);
        
        if ($id) {
            $hotel          = Hotel::findOrFail($id);
            $notification   = 'Hotel basic info updated successfully';
        } else {
            $hotel          = new Hotel();
            $notification   = 'Hotel created successfully as Draft';
        }
        
        $hotel->name           = $request->name;
        $hotel->name_ar        = $request->name_ar;
        if(!$id) {
            $hotel->slug       = slug($request->name) . '-' . time();
        }
        $hotel->property_type  = $request->property_type;
        $hotel->star_rating    = $request->star_rating;
        $hotel->country_id     = $request->country_id;
        $hotel->location_id    = $request->location_id;
        $hotel->area_id        = $request->area_id;
        $hotel->address        = $request->address;
        $hotel->address_ar     = $request->address_ar;
        $hotel->postal_code    = $request->postal_code;
        $hotel->latitude       = $request->latitude;
        $hotel->longitude      = $request->longitude;
        $hotel->check_in_time  = $request->check_in_time;
        $hotel->check_out_time = $request->check_out_time;
        $hotel->timezone       = $request->timezone;
        $hotel->hotel_email    = $request->hotel_email;
        $hotel->reservation_email = $request->reservation_email;
        $hotel->phone          = $request->phone;
        $hotel->whatsapp       = $request->whatsapp;
        $hotel->website        = $request->website;
        $hotel->contact_person = $request->contact_person;
        $hotel->short_description = $request->short_description;
        $hotel->short_description_ar = $request->short_description_ar;
        $hotel->description    = $request->description;
        $hotel->description_ar = $request->description_ar;
        $hotel->primary_supplier_id = $request->primary_supplier_id;
        $hotel->featured       = $request->featured ? 1 : 0;
        
        if (!$id) {
            $hotel->status = 'draft';
        }
        
        $hotel->save();
        
        $notify[] = ['success', $notification];
        
        if (!$id) {
            return to_route('admin.hotel.manage', $hotel->id)->withNotify($notify);
        }
        
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $hotel = Hotel::findOrFail($id);
        
        if ($hotel->status == 'active') {
            $hotel->status = 'inactive';
            $hotel->save();
            $notify[] = ['success', 'Hotel deactivated successfully'];
            return back()->withNotify($notify);
        }
        
        // Before activating, check readiness
        $errors = $hotel->checkActivationReadiness();
        if (count($errors) > 0) {
            $notify[] = ['error', 'Cannot activate hotel. Please complete all requirements.'];
            return back()->withNotify($notify);
        }
        
        $hotel->status = 'active';
        $hotel->save();
        
        $notify[] = ['success', 'Hotel activated successfully'];
        return back()->withNotify($notify);
    }
    
    public function delete($id)
    {
        $hotel = Hotel::findOrFail($id);
        
        if ($hotel->status == 'active') {
            $notify[] = ['error', 'Active hotels cannot be deleted. Deactivate it first.'];
            return back()->withNotify($notify);
        }
        
        // Delete related images from storage
        foreach ($hotel->images as $image) {
            fileManager()->removeFile(getFilePath('hotelImage') . '/' . $image->image);
            $image->delete();
        }
        
        // Delete the hotel
        $hotel->delete();
        
        $notify[] = ['success', 'Hotel permanently deleted successfully'];
        return back()->withNotify($notify);
    }
}
