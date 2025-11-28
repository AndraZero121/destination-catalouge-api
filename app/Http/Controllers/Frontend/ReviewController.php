<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        return view('dashboard.reviews.reviews');
    }
}
