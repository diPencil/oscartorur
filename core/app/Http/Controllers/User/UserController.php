<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Plan;
use App\Models\PlanLog;
use App\Models\Rating;
use App\Models\AdminNotification;
use App\Models\Seminar;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller {
    public function home() {
        $pageTitle = 'Dashboard';

        $user                     = auth()->user();
        $widget['total_tours']    = PlanLog::where('status', Status::TOUR_COMPLETED)->where('type', 'tour')->where('user_id', $user->id)->count();
        $widget['upcoming_tours'] = PlanLog::where('status', Status::TOUR_COMPLETED)->where('type', 'tour')->where('user_id', $user->id)->whereHas('plan', function ($q) {
            $q->where('departure_time', '>', now());
        })->count();
        $widget['total_seminars']    = PlanLog::where('status', Status::TOUR_COMPLETED)->where('type', 'seminar')->where('user_id', $user->id)->count();
        $widget['upcoming_seminars'] = PlanLog::where('status', Status::TOUR_COMPLETED)->where('type', 'seminar')->where('user_id', $user->id)->whereHas('seminar', function ($q) {
            $q->where('start_time', '>', now());
        })->count();
        
        $widget['total_hotel_bookings'] = \App\Models\HotelBooking::where('user_id', $user->id)->count();
        $widget['upcoming_hotel_bookings'] = \App\Models\HotelBooking::where('user_id', $user->id)->where('booking_status', 'confirmed')->where('check_in', '>', now())->count();

        $deposits = auth()->user()->deposits()->with(['gateway'])->orderBy('id', 'desc')->take(10)->get();
        
        $hotelBookings = \App\Models\HotelBooking::where('user_id', $user->id)->with('hotel')->orderBy('id', 'desc')->take(10)->get();

        return view('Template::user.dashboard', compact('pageTitle', 'user', 'deposits', 'widget', 'hotelBookings'));
    }

    public function depositHistory(Request $request) {
        $pageTitle = 'Payment History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->with(['gateway', 'plan', 'seminar'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function userData() {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request) {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;

        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->state        = $request->state;
        $user->zip          = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code    = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }

    public function addDeviceToken(Request $request) {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash) {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function rating(Request $request, $id) {
        $request->validate([
            'type'   => 'required|string|in:tour,seminar,hotel',
            'rating' => 'required|integer|min:1|in:1,2,3,4,5',
            'review' => 'nullable|string',
        ]);

        if ($request->type == 'tour') {
            $tour = Plan::active()->where('id', $id)->first();
            if (!$tour) {
                $notify[] = ['error', 'Invalid tour plan'];
                return back()->withNotify($notify);
            }
        } elseif ($request->type == 'seminar') {
            $seminar = Seminar::active()->where('id', $id)->first();
            if (!$seminar) {
                $notify[] = ['error', 'Invalid seminar'];
                return back()->withNotify($notify);
            }
        } elseif ($request->type == 'hotel') {
            $hotel = Hotel::active()->where('id', $id)->first();
            if (!$hotel) {
                $notify[] = ['error', 'Invalid hotel'];
                return back()->withNotify($notify);
            }
        }

        $exist = Rating::where('user_id', auth()->id())->where('type', $request->type)->where('plan_id', $id)->exists();
        if ($exist) {
            $notify[] = ['error', 'Already exist your rating!'];
            return back()->withNotify($notify);
        }

        $rating          = new Rating();
        $rating->user_id = auth()->id();
        $rating->plan_id = $id;
        $rating->type    = $request->type;
        $rating->rating  = $request->rating;
        $rating->review  = $request->review;
        $rating->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->id();
        $adminNotification->title = 'New Rating Submitted for ' . ucfirst($request->type);
        $adminNotification->click_url = urlPath('admin.dashboard'); // Update this if there is a specific ratings page
        $adminNotification->save();

        $notify[] = ['success', 'Thanks for your rating!'];
        return back()->withNotify($notify);
    }

    //Tour log
    public function tourLog() {
        $pageTitle = 'Tour Booking Log';
        $logs      = PlanLog::searchable(['trx', 'price', 'seat', 'plan:name'])->where('status', Status::TOUR_COMPLETED)->where('type', 'tour')->where('user_id', auth()->id())->with('plan')->latest()->paginate(getPaginate());
        return view('Template::user.plan_log', compact('pageTitle', 'logs'));
    }

    //Seminar log
    public function seminarLog() {
        $logs      = PlanLog::searchable(['trx', 'price', 'seat', 'seminar:name'])->where('status', Status::TOUR_COMPLETED)->where('type', 'seminar')->where('user_id', auth()->id())->with('seminar')->latest()->paginate(getPaginate());
        $pageTitle = 'Seminar Booking Log';
        return view('Template::user.plan_log', compact('pageTitle', 'logs'));
    }

    public function booking(Request $request) {
        $request->validate([
            'type'    => 'required|string|in:tour,seminar',
            'seat'    => 'required|integer|min:1',
            'plan_id' => 'required|integer|gt:0',
        ]);

        $log = new PlanLog();
        if ($request->type == 'tour') {
            $package = Plan::find($request->plan_id);
        } else {
            $package = Seminar::find($request->plan_id);
        }

        if (!$package) {
            $notify[] = ['error', 'Plan doesn\'t exist'];
            return back()->withNotify($notify);
        }

        $available_seat = ($package->capacity - $package->sold);
        if ($available_seat < $request->seat) {
            $notify[] = ['error', "Only $available_seat seats available!"];
            return back()->withNotify($notify);
        }

        $log->user_id = auth()->user()->id;
        $log->plan_id = $package->id;
        $log->seat    = $request->seat;
        $log->price   = getAmount($package->price * $request->seat);
        $log->trx     = getTrx();
        $log->status  = Status::TOUR_PENDING;
        $log->type    = $request->type;
        $log->save();
        session()->put('log_id', $log->id);
        return to_route('user.deposit.index');
    }
}
