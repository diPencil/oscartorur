<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\BedType;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;

class RoomTypeController extends Controller
{
    public function index($hotel_id = null)
    {
        $pageTitle = 'Room Types';
        
        $roomTypes = RoomType::with(['hotel'])->searchable(['name']);
        if ($hotel_id) {
            $roomTypes = $roomTypes->where('hotel_id', $hotel_id);
        }
        $roomTypes = $roomTypes->orderByDesc('id')->paginate(getPaginate());
        
        return view('admin.room_type.index', compact('pageTitle', 'roomTypes', 'hotel_id'));
    }

    public function create($hotel_id = null)
    {
        $pageTitle = 'Add New Room Type';
        $hotels    = Hotel::orderBy('name')->get();
        $bedTypes  = BedType::active()->orderBy('name')->get();
        $amenities = Amenity::active()->where('type', 'room')->orderBy('name')->get();
        return view('admin.room_type.form', compact('pageTitle', 'hotels', 'bedTypes', 'amenities', 'hotel_id'));
    }

    public function edit($id)
    {
        $roomType  = RoomType::with(['beds', 'amenities', 'images'])->findOrFail($id);
        $pageTitle = 'Edit Room Type: ' . $roomType->name;
        $hotels    = Hotel::orderBy('name')->get();
        $bedTypes  = BedType::active()->orderBy('name')->get();
        $amenities = Amenity::active()->where('type', 'room')->orderBy('name')->get();
        return view('admin.room_type.form', compact('pageTitle', 'roomType', 'hotels', 'bedTypes', 'amenities'));
    }

    public function store(Request $request, $id = 0)
    {
        $beds = [];
        $bedCounts = [];
        if ($request->has('beds')) {
            foreach ($request->beds as $index => $bedId) {
                if (!empty($bedId)) {
                    $beds[] = $bedId;
                    $bedCounts[] = $request->bed_counts[$index] ?? 1;
                }
            }
        }
        $request->merge([
            'beds' => $beds,
            'bed_counts' => $bedCounts,
        ]);

        $request->validate([
            'hotel_id'       => 'required|integer|exists:hotels,id',
            'name'           => 'required|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'max_adults'     => 'required|integer|min:1',
            'max_children'   => 'required|integer|min:0',
            'max_occupancy'  => 'required|integer|min:1',
            'beds'           => 'nullable|array',
            'beds.*'         => 'integer|exists:bed_types,id',
            'bed_counts'     => 'nullable|array',
            'bed_counts.*'   => 'integer|min:1',
            'amenities'      => 'nullable|array',
            'amenities.*'    => 'integer|exists:amenities,id',
            'images'         => 'nullable|array',
            'images.*'       => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'image_url'      => 'nullable|url',
        ]);
        
        if ($id) {
            $roomType       = RoomType::findOrFail($id);
            $notification   = 'Room Type updated successfully';
        } else {
            $roomType       = new RoomType();
            $notification   = 'Room Type added successfully';
        }
        
        $roomType->hotel_id      = $request->hotel_id;
        $roomType->name          = $request->name;
        $roomType->name_ar       = $request->name_ar;
        $roomType->description   = $request->description;
        $roomType->description_ar = $request->description_ar;
        $roomType->max_adults    = $request->max_adults;
        $roomType->max_children  = $request->max_children;
        $roomType->max_occupancy = $request->max_occupancy;
        $roomType->save();

        // Sync amenities
        if ($request->has('amenities')) {
            $roomType->amenities()->sync($request->amenities);
        } else {
            $roomType->amenities()->sync([]);
        }

        // Sync beds
        if ($request->has('beds') && $request->has('bed_counts')) {
            $bedsData = [];
            foreach ($request->beds as $key => $bedId) {
                $count = $request->bed_counts[$key] ?? 1;
                $bedsData[$bedId] = ['quantity' => $count];
            }
            $roomType->beds()->sync($bedsData);
        } else {
            $roomType->beds()->sync([]);
        }
        
        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $path = getFilePath('roomTypeImage');
                    $size = getFileSize('roomTypeImage');
                    $filename = fileUploader($image, $path, $size);
                    
                    $roomImage = new RoomTypeImage();
                    $roomImage->room_type_id = $roomType->id;
                    $roomImage->image = $filename;
                    $roomImage->save();
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload some images.'];
                }
            }
        }
        
        if ($request->image_url) {
            $roomImage = new RoomTypeImage();
            $roomImage->room_type_id = $roomType->id;
            $roomImage->image = $request->image_url;
            $roomImage->save();
        }
        
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return RoomType::changeStatus($id);
    }
}
