<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Rules\FileTypeValidate;

class HotelImageController extends Controller
{
    public function storeCover(Request $request, $hotel_id)
    {
        $request->validate([
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'image_url' => ['nullable', 'url']
        ]);

        if (!$request->hasFile('image') && !$request->image_url) {
            $notify[] = ['error', 'Please upload an image or provide an image URL.'];
            return back()->withNotify($notify);
        }

        $hotel = Hotel::findOrFail($hotel_id);

        $path = getFilePath('hotelImage');
        $size = getFileSize('hotelImage');
        
        $filename = '';
        if ($request->hasFile('image')) {
            try {
                $filename = fileUploader($request->image, $path, $size);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        } else {
            $filename = trim($request->image_url);
        }

        // Delete existing cover if exists and not an external URL
        $existingCover = HotelImage::where('hotel_id', $hotel_id)->where('is_cover', 1)->first();
        if ($existingCover) {
            $existingImagePath = html_entity_decode((string) $existingCover->image, ENT_QUOTES, 'UTF-8');
            if (!filter_var($existingImagePath, FILTER_VALIDATE_URL) && !preg_match('/https?:\/\//', $existingImagePath)) {
                fileManager()->removeFile($path . '/' . $existingCover->image);
            }
            $existingCover->delete();
        }

        $hotelImage = new HotelImage();
        $hotelImage->hotel_id = $hotel_id;
        $hotelImage->image = $filename;
        $hotelImage->is_cover = 1;
        $hotelImage->save();

        $notify[] = ['success', 'Cover image saved successfully'];
        return back()->withNotify($notify);
    }

    public function storeGallery(Request $request, $hotel_id)
    {
        $request->validate([
            'images' => 'nullable|array',
            'images.*' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'image_url' => 'nullable|url',
            'category' => 'required|string',
            'title' => 'nullable|string',
        ]);

        if (!$request->hasFile('images') && !$request->image_url) {
            $notify[] = ['error', 'Please upload images or provide an image URL.'];
            return back()->withNotify($notify);
        }

        $hotel = Hotel::findOrFail($hotel_id);
        $path = getFilePath('hotelImage');
        $size = getFileSize('hotelImage');

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $filename = fileUploader($image, $path, $size);
                    
                    $hotelImage = new HotelImage();
                    $hotelImage->hotel_id = $hotel_id;
                    $hotelImage->image = $filename;
                    $hotelImage->category = $request->category;
                    $hotelImage->title = $request->title;
                    $hotelImage->is_cover = 0;
                    $hotelImage->save();
                    
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'One or more images could not be uploaded.'];
                    return back()->withNotify($notify);
                }
            }
        }

        if ($request->image_url) {
            $hotelImage = new HotelImage();
            $hotelImage->hotel_id = $hotel_id;
            $hotelImage->image = trim($request->image_url);
            $hotelImage->category = $request->category;
            $hotelImage->title = $request->title;
            $hotelImage->is_cover = 0;
            $hotelImage->save();
        }

        $notify[] = ['success', 'Gallery images saved successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $hotelImage = HotelImage::findOrFail($id);
        $imagePath = html_entity_decode((string) $hotelImage->image, ENT_QUOTES, 'UTF-8');
        if (!filter_var($imagePath, FILTER_VALIDATE_URL) && !preg_match('/https?:\/\//', $imagePath)) {
            $path = getFilePath('hotelImage');
            fileManager()->removeFile($path . '/' . $hotelImage->image);
        }
        $hotelImage->delete();

        $notify[] = ['success', 'Image deleted successfully'];
        return back()->withNotify($notify);
    }
}
