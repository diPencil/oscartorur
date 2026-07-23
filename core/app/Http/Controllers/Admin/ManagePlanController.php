<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\Plan;
use App\Models\PlanLog;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManagePlanController extends Controller {
    public function index() {
        $plans     = Plan::searchable(['name', 'price', 'category:name', 'location:name'])->with('category', 'location')->latest()->paginate(getPaginate());
        $pageTitle = 'Tour Plans';
        return view('admin.plan.index', compact('pageTitle', 'plans'));
    }

    public function add() {
        $pageTitle  = 'Add Plan';
        $categories = Category::active()->orderBy('name')->get();
        $locations  = Location::active()->orderBy('name')->get();
        return view('admin.plan.form', compact('pageTitle', 'categories', 'locations'));
    }

    public function store(Request $request, $id = 0) {
        $isRequired = $id ? 'nullable' : 'required';

        $request->validate([
            'name'            => 'required|string',
            'category_id'     => ['required', 'integer', 'gt:0', Rule::exists('categories', 'id')->where(function ($query) {
                $query->where('status', Status::YES);
            })],
            'location_id'     => ['required', 'integer', 'gt:0', Rule::exists('locations', 'id')->where(function ($query) {
                $query->where('status', Status::YES);
            })],
            'images'          => "$isRequired|array",
            'images.*'        => ["required", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'map_latitude'    => 'required|string',
            'map_longitude'   => 'required|string',
            'duration'        => 'required',
            'departure_time'  => 'required|date_format:"Y-m-d H:i"|after_or_equal:today',
            'return_time'     => 'required|date_format:"Y-m-d H:i"|after_or_equal:departure_time',
            'capacity'        => 'required|integer|gt:0',
            'price'           => 'required|numeric|gt:0',
            'details'         => 'required|string',
            'included'        => "required|array",
            'included.*'      => 'required|string',
            'excluded'        => 'nullable|array',
            'excluded.*'      => 'required|string',
            'tour'            => 'nullable|array',
            'tour.*.title'    => 'required|string',
            'tour.*.subtitle' => 'required|string',
            'tour.*.content'  => 'required|string',
            'image_urls'      => 'nullable|array',
            'image_urls.*'    => 'url',

        ]);

        if ($id) {
            $plan         = Plan::findOrFail($id);
            $notification = 'Tour plan updated Successfully!';
        } else {
            $plan         = new Plan();
            $notification = 'Tour plan Added Successfully!';
        }

        $plan->name           = $request->name;
        $plan->name_ar        = $request->name_ar;
        $plan->category_id    = $request->category_id;
        $plan->location_id    = $request->location_id;
        $plan->map_latitude   = $request->map_latitude;
        $plan->map_longitude  = $request->map_longitude;
        $plan->duration       = $request->duration;
        $plan->departure_time = Carbon::parse($request->departure_time);
        $plan->return_time    = Carbon::parse($request->return_time);
        $plan->capacity       = $request->capacity;
        $plan->details        = $request->details;
        $plan->details_ar     = $request->details_ar;
        $plan->included       = $request->included;
        $plan->included_ar    = $request->included_ar;
        $plan->excluded       = $request->excluded;
        $plan->excluded_ar    = $request->excluded_ar;
        $plan->price          = $request->price;
        $plan->tour_plan      = @$request->tour;
        $plan->tour_plan_ar   = @$request->tour_ar;

        // Upload image
        $images = $this->insertImages($request, $plan, $id);
        if ($id) {
            $images = array_merge($plan->images ?? [], $images);
        }
        if ($request->image_urls) {
            $images = array_merge($images, $request->image_urls);
        }
        $plan->images = $images ?? [];

        $plan->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    protected function insertImages($request, $plan, $id = 0) {
        $path = getFilePath('plan');
        if ($id) {
            $this->removeImages($request, $plan, $path);
        }
        $hasImages = $request->file('images');

        if ($hasImages) {
            $size   = getFileSize('plan');
            $images = [];
            foreach ($hasImages as $file) {
                try {
                    $name     = fileUploader($file, $path, $size, null);
                    $images[] = $name;
                } catch (\Exception $exp) {
                    return false;
                }
            }
            $images;
        }
        return $images ?? [];
    }

    protected function removeImages($request, $plan, $path) {
        $previousImages = $plan->images;
        $imagesToRemove = array_values(array_diff($previousImages, $request->old ?? []));
        foreach ($imagesToRemove as $item) {
            fileManager()->removeFile($path . '/' . $item);
        }
        $images = array_filter($previousImages, function ($image) use ($imagesToRemove) {
            return !in_array($image, $imagesToRemove);
        });
        $plan->images = $images;
        $plan->save();
    }

    public function edit($id) {
        $pageTitle            = "Edit Tour Plan";
        $plan                 = Plan::findOrFail($id);
        $plan->departure_time = Carbon::parse($plan->departure_time)->format('Y-m-d H:i');
        $plan->return_time    = Carbon::parse($plan->return_time)->format('Y-m-d H:i');
        $categories           = Category::active()->orderBy('name')->get();
        $locations            = Location::active()->orderBy('name')->get();

        $images = [];
        foreach ($plan->images as $key => $image) {
            $img['id']  = $image;
            $img['src'] = getImage(getFilePath('plan') . '/' . $image);
            $images[]   = $img;
        }
        return view('admin.plan.form', compact('pageTitle', 'categories', 'locations', 'plan', 'images'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'           => 'required|string',
            'category_id'    => 'required|integer',
            'location'       => 'required|string',
            'map_latitude'   => 'required|string',
            'map_longitude'  => 'required|string',
            'duration'       => 'required|string',
            'departure_time' => 'required|date_format:"Y-m-d h:i a"|after_or_equal:today',
            'return_time'    => 'required|date_format:"Y-m-d h:i a"|after_or_equal:departure_time',
            'capacity'       => 'required|integer|gte:0',
            'price'          => 'required|numeric|gte:0',
            'details'        => 'required|string',
            'included.*'     => 'required|string',
            'excluded.*'     => 'required|string',
            'title.*'        => 'required|string',
            'subtitle.*'     => 'required|string',
            'content.*'      => 'required|string',
            'images.*'       => ['required', 'max:10000', new FileTypeValidate(['jpeg', 'jpg', 'png', 'gif'])],
            'image_urls'     => 'nullable|array',
            'image_urls.*'   => 'url',
        ]);

        $plan                 = Plan::findOrFail($id);
        $plan->name           = $request->name;
        $plan->name_ar        = $request->name_ar;
        $plan->category_id    = $request->category_id;
        $plan->location       = $request->location;
        $plan->map_latitude   = $request->map_latitude;
        $plan->map_longitude  = $request->map_longitude;
        $plan->duration       = $request->duration;
        $plan->departure_time = Carbon::create($request->departure_time);
        $plan->return_time    = Carbon::create($request->return_time);
        $plan->capacity       = $request->capacity;
        $plan->price          = $request->price;
        $plan->details        = $request->details;
        $plan->details_ar     = $request->details_ar;
        $plan->included       = $request->included ?? [];
        $plan->included_ar    = $request->included_ar ?? [];
        $plan->excluded       = $request->excluded ?? [];
        $plan->excluded_ar    = $request->excluded_ar ?? [];

        if ($request->title) {
            foreach ($request->title as $key => $item) {
                $tour_plans[$item] = [
                    $request->title[$key],
                    $request->subtitle[$key],
                    $request->content[$key],
                ];
            }
        }
        if ($request->title_ar) {
            foreach ($request->title_ar as $key => $item) {
                $tour_plans_ar[$item] = [
                    $request->title_ar[$key],
                    $request->subtitle_ar[$key],
                    $request->content_ar[$key],
                ];
            }
        }
        $plan->tour_plan = @$tour_plans ?? [];
        $plan->tour_plan_ar = @$tour_plans_ar ?? [];

        // Upload and Update image
        if ($request->images) {
            foreach ($request->images as $image) {
                $path = imagePath()['plans']['path'];
                $size = imagePath()['plans']['size'];

                $images[] = uploadImage($image, $path, $size);
            }
            $plan->images = array_merge((array) $plan->images, $images);
        }

        if ($request->image_urls) {
            $plan->images = array_merge((array) $plan->images, $request->image_urls);
        }

        $plan->save();

        $notify[] = ['success', 'Plan Updated Successfully!'];
        return back()->withNotify($notify);
    }

    public function status($id) {
        return Plan::changeStatus($id);
    }

    public function frontendSeo($id) {

        $key       = 'plan';
        $data      = Plan::findOrFail($id);
        $pageTitle = 'SEO Configuration';
        return view('admin.plan.frontend_seo', compact('pageTitle', 'key', 'data'));
    }

    public function frontendSeoUpdate(Request $request, $id) {

        $request->validate([
            'image' => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
        ]);

        $data  = Plan::findOrFail($id);
        $image = @$data->seo_content->image;
        if ($request->hasFile('image')) {
            try {
                $path  = 'assets/images/frontend/plan' . '/seo';
                $image = fileUploader($request->image, $path, getFileSize('seo'), @$data->seo_content->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the image'];
                return back()->withNotify($notify);
            }
        }
        $data->seo_content = [
            'image'              => $image,
            'description'        => $request->description,
            'social_title'       => $request->social_title,
            'social_description' => $request->social_description,
            'keywords'           => $request->keywords,
        ];
        $data->save();

        $notify[] = ['success', 'SEO content updated successfully'];
        return back()->withNotify($notify);
    }

    //Booking Log
    public function bookingLog() {
        $planLogs  = PlanLog::where('status', Status::TOUR_COMPLETED)->searchable(['trx', 'seat', 'price', 'plan:name', 'user:username'])->where('type', 'tour')->with(['plan', 'user'])->latest()->paginate(getPaginate());
        $pageTitle = 'Tour Booking Log';
        return view('admin.plan.booking_log', compact('pageTitle', 'planLogs'));
    }
}
