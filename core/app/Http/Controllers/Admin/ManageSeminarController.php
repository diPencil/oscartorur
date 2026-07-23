<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\PlanLog;
use App\Models\Seminar;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManageSeminarController extends Controller
{
    public function index()
    {
        $pageTitle = 'Seminars';
        $seminars = Seminar::searchable(['name', 'price', 'category:name', 'location:name'])->with('category', 'location')->latest()->paginate(getPaginate());
        return view('admin.seminar.index', compact('pageTitle', 'seminars'));
    }

    public function add()
    {
        $pageTitle = 'Add Plan';
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();
        return view('admin.seminar.form', compact('pageTitle', 'categories', 'locations'));
    }

    public function store(Request $request, $id = 0)
    {
        $isRequired = $id ? 'nullable' : 'required';

        $request->validate([
            'name'        => 'required|string',
            'name_ar'     => 'nullable|string',
            'category_id' => ['required', 'integer', 'gt:0', Rule::exists('categories', 'id')->where(function ($query) {
                $query->where('status', Status::YES);
            }),],
            'location_id' => ['required', 'integer', 'gt:0', Rule::exists('locations', 'id')->where(function ($query) {
                $query->where('status', Status::YES);
            }),],
            'images'             => "$isRequired|array",
            'images.*'           => ["required", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'map_latitude'       => 'required|string',
            'map_longitude'      => 'required|string',
            'duration'           => 'required',
            'start_time'         => 'required|date_format:"Y-m-d H:i"|after_or_equal:today',
            'end_time'           => 'required|date_format:"Y-m-d H:i"|after_or_equal:start_time',
            'capacity'           => 'required|integer|gt:0',
            'price'              => 'required|numeric|gt:0',
            'details'            => 'required|string',
            'details_ar'         => 'nullable|string',
            'included'           => "required|array",
            'included.*'         => 'required|string',
            'included_ar'        => 'nullable|array',
            'included_ar.*'      => 'nullable|string',
            'excluded'           => 'nullable|array',
            'excluded.*'         => 'required|string',
            'excluded_ar'        => 'nullable|array',
            'excluded_ar.*'      => 'nullable|string',
            'seminar'            => 'nullable|array',
            'seminar.*.title'    => 'required|string',
            'seminar.*.subtitle' => 'required|string',
            'seminar.*.content'  => 'required|string',
            'seminar_ar'            => 'nullable|array',
            'seminar_ar.*.title'    => 'nullable|string',
            'seminar_ar.*.subtitle' => 'nullable|string',
            'seminar_ar.*.content'  => 'nullable|string',
            'image_urls'            => 'nullable|array',
            'image_urls.*'          => 'url',
        ]);

        if ($id) {
            $seminar      = Seminar::findOrFail($id);
            $notification = 'Seminar plan updated Successfully!';
        } else {
            $seminar      = new Seminar();
            $notification = 'Seminar plan Added Successfully!';
        }

        $seminar->name          = $request->name;
        $seminar->name_ar       = $request->name_ar;
        $seminar->category_id   = $request->category_id;
        $seminar->location_id   = $request->location_id;
        $seminar->map_latitude  = $request->map_latitude;
        $seminar->map_longitude = $request->map_longitude;
        $seminar->duration      = $request->duration;
        $seminar->start_time    = Carbon::parse($request->start_time);
        $seminar->end_time      = Carbon::parse($request->end_time);
        $seminar->capacity      = $request->capacity;
        $seminar->details       = $request->details;
        $seminar->details_ar    = $request->details_ar;
        $seminar->included      = $request->included;
        $seminar->included_ar   = $request->included_ar;
        $seminar->excluded      = $request->excluded;
        $seminar->excluded_ar   = $request->excluded_ar;
        $seminar->price         = $request->price;
        $seminar->seminar_plan  = @$request->seminar;
        $seminar->seminar_plan_ar = @$request->seminar_ar;

       // Upload image
       $images = $this->insertImages($request, $seminar, $id);
       if ($id) {
           $images = array_merge($seminar->images ?? [], $images);
       }
       if ($request->image_urls) {
           $images = array_merge($images, $request->image_urls);
       }
       $seminar->images = $images ?? [];

      

        $seminar->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    protected function insertImages($request, $seminar, $id = 0)
    {
        $path = getFilePath('seminar');
        if ($id) {
            $this->removeImages($request, $seminar, $path);
        }
        $hasImages = $request->file('images');

        if ($hasImages) {
            $size      = getFileSize('seminar');
            $images    = [];
            foreach ($hasImages as $file) {
                try {
                    $name              = fileUploader($file, $path, $size, null);
                    $images[]          = $name;
                } catch (\Exception $exp) {
                    return false;
                }
            }
            $images;
        }
        return $images ?? [];
    }

    protected function removeImages($request, $seminar, $path)
    {
        $previousImages = $seminar->images;
        $imagesToRemove  = array_values(array_diff($previousImages, $request->old ?? []));
        foreach ($imagesToRemove as $item) {
            fileManager()->removeFile($path . '/' . $item);
        }      
        $images = array_filter($previousImages, function($image) use($imagesToRemove) {
            return !in_array($image, $imagesToRemove);
        });
        $seminar->images = $images;
        $seminar->save();
    }


    public function edit($id)
    {
        $plan = Seminar::findOrFail($id);
        $plan->start_time = Carbon::parse($plan->start_time)->format('Y-m-d H:i');
        $plan->end_time = Carbon::parse($plan->end_time)->format('Y-m-d H:i');
        $pageTitle = 'Edit Seminar';
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();
        $images = [];
        foreach ($plan->images as $key => $image) {
            $img['id']  = $image;
            $img['src'] = getImage(getFilePath('seminar') . '/' . $image);
            $images[]   = $img;
        }
        return view('admin.seminar.form', compact('pageTitle', 'categories', 'locations', 'plan', 'images'));
    }

  
    public function status($id)
    {
        return Seminar::changeStatus($id);
    }


    public function frontendSeo($id)
    {
        $key = 'seminar';
        $data = Seminar::findOrFail($id);
        $pageTitle = 'SEO Configuration';
        return view('admin.seminar.frontend_seo', compact('pageTitle', 'key', 'data'));
    }

    public function frontendSeoUpdate(Request $request, $id)
    {

        $request->validate([
            'image' => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $data = Seminar::findOrFail($id);
        $image = @$data->seo_content->image;
        if ($request->hasFile('image')) {
            try {
                $path = 'assets/images/frontend/seminar' . '/seo';
                $image = fileUploader($request->image, $path, getFileSize('seo'), @$data->seo_content->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the image'];
                return back()->withNotify($notify);
            }
        }
        $data->seo_content = [
            'image' => $image,
            'description' => $request->description,
            'social_title' => $request->social_title,
            'social_description' => $request->social_description,
            'keywords' => $request->keywords,
        ];
        $data->save();

        $notify[] = ['success', 'SEO content updated successfully'];
        return back()->withNotify($notify);
    }

    //Booking Log
    public function bookingLog()
    {
        $planLogs = PlanLog::where('status', Status::TOUR_COMPLETED)->searchable(['trx', 'seat', 'price', 'plan:name', 'user:username'])->where('type', 'seminar')->with(['plan', 'user'])->latest()->paginate(getPaginate());
        $pageTitle = 'Tour Booking Log';
        return view('admin.seminar.booking_log', compact('pageTitle', 'planLogs'));
    }
}
