<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Country;
use App\Models\Location;
use App\Models\Area;
use App\Models\HotelSupplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        ['countries' => $countries, 'locations' => $locations, 'areas' => $areas, 'locationOptions' => $locationOptions, 'areaOptions' => $areaOptions] = $this->hotelLocationFormData();
        $legacyLocationId = null;
        $legacyCountryId  = null;
        $suppliers = HotelSupplier::where('status', 1)->orderBy('name')->get();
        return view('admin.hotel.form', compact('pageTitle', 'countries', 'locations', 'areas', 'locationOptions', 'areaOptions', 'legacyLocationId', 'legacyCountryId', 'suppliers'));
    }

    public function manage($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotelName = app()->getLocale() == 'ar' && $hotel->name_ar ? $hotel->name_ar : $hotel->name;
        $pageTitle = 'Manage Hotel: ' . $hotelName;
        
        ['countries' => $countries, 'locations' => $locations, 'areas' => $areas, 'locationOptions' => $locationOptions, 'areaOptions' => $areaOptions] = $this->hotelLocationFormData($hotel);
        $suppliers = HotelSupplier::active()->orderBy('name')->get();
        $legacyLocationId = $this->legacyLocationIdForHotel($hotel);
        $legacyCountryId  = $legacyLocationId ? $hotel->country_id : null;
        
        $activationErrors = $hotel->checkActivationReadiness();
        
        return view('admin.hotel.manage', compact('pageTitle', 'hotel', 'countries', 'locations', 'areas', 'locationOptions', 'areaOptions', 'legacyLocationId', 'legacyCountryId', 'suppliers', 'activationErrors'));
    }

    public function store(Request $request, $id = 0)
    {
        $hotel = $id ? Hotel::findOrFail($id) : null;
        $legacyLocationId = $hotel ? $this->legacyLocationIdForHotel($hotel) : null;

        $request->validate([
            'name'           => 'required|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'property_type'  => 'required|string|max:100',
            'star_rating'    => 'required|integer|min:1|max:5',
            'country_id'     => ['required', 'integer', Rule::exists('countries', 'id')->where(fn ($query) => $query->where('status', 1))],
            'location_id'    => [
                'required',
                'integer',
                Rule::exists('locations', 'id')->where(function ($query) use ($request, $hotel, $legacyLocationId) {
                    $query->where('status', 1)->where(function ($query) use ($request, $hotel, $legacyLocationId) {
                        $query->where('country_id', $request->country_id);

                        if ($hotel && $legacyLocationId && (int) $request->country_id === (int) $hotel->country_id) {
                            $query->orWhere(function ($query) use ($legacyLocationId) {
                                $query->where('id', $legacyLocationId)->whereNull('country_id');
                            });
                        }
                    });
                }),
            ],
            'area_id'        => ['nullable', 'integer', Rule::exists('areas', 'id')->where(fn ($query) => $query->where('status', 1)->where('location_id', $request->location_id))],
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

    private function hotelLocationFormData(?Hotel $hotel = null): array
    {
        $countries = Country::where('status', 1)->orderBy('name')->get();
        $this->applyDuplicateCountryLabels($countries);

        $locations = Location::active()
            ->where(function ($query) use ($hotel) {
                $query->whereNotNull('country_id');

                if ($hotel && $this->legacyLocationIdForHotel($hotel)) {
                    $query->orWhere('id', $hotel->location_id);
                }
            })
            ->orderBy('name')
            ->get();
        $areas     = Area::active()->orderBy('name')->get();

        $locationOptions = $locations->map(fn ($location) => [
            'id'         => (int) $location->id,
            'country_id' => $location->country_id ? (int) $location->country_id : null,
            'text'       => $location->display_name,
        ])->values();

        $areaOptions = $areas->map(fn ($area) => [
            'id'          => (int) $area->id,
            'location_id' => (int) $area->location_id,
            'text'        => $area->display_name,
        ])->values();

        return compact('countries', 'locations', 'areas', 'locationOptions', 'areaOptions');
    }

    private function legacyLocationIdForHotel(Hotel $hotel): ?int
    {
        if (!$hotel->location_id) {
            return null;
        }

        $location = Location::find($hotel->location_id);

        if (!$location || $location->country_id) {
            return null;
        }

        return (int) $location->id;
    }

    private function applyDuplicateCountryLabels($countries): void
    {
        $duplicateNames = $countries
            ->groupBy(fn ($country) => strtolower(trim((string) $country->name)))
            ->filter(fn ($items) => $items->count() > 1)
            ->keys();

        foreach ($countries as $country) {
            $label = $country->display_name;

            if ($duplicateNames->contains(strtolower(trim((string) $country->name)))) {
                $label .= ' (ID ' . $country->id . ')';
            }

            $country->setAttribute('admin_dropdown_name', $label);
        }
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
            $imagePath = html_entity_decode((string) $image->image, ENT_QUOTES, 'UTF-8');
            if (!filter_var($imagePath, FILTER_VALIDATE_URL) && !preg_match('/https?:\/\//', $imagePath)) {
                fileManager()->removeFile(getFilePath('hotelImage') . '/' . $image->image);
            }
            $image->delete();
        }
        
        // Delete the hotel
        $hotel->delete();
        
        $notify[] = ['success', 'Hotel permanently deleted successfully'];
        return back()->withNotify($notify);
    }

    public function syncAmenities(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $request->validate([
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ]);
        
        if ($request->has('amenities')) {
            $hotel->amenities()->sync($request->amenities);
        } else {
            $hotel->amenities()->sync([]);
        }

        $notify[] = ['success', 'Amenities updated successfully'];
        return back()->withNotify($notify);
    }
}
