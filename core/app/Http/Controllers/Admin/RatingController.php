<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;

class RatingController extends Controller {
    public function index() {
        $ratings   = Rating::latest()->paginate(getPaginate());
        $pageTitle = 'All Ratings';
        return view('admin.rating', compact('pageTitle', 'ratings'));
    }

    public function delete($id) {
        $rating = Rating::findOrFail($id);
        $rating->delete();
        $notify[] = ['success', 'Rating deleted successfully!'];
        return back()->withNotify($notify);
    }
}
