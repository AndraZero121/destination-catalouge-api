<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class SavedController extends Controller
{
    public function index()
    {
        return view('dashboard.saved');
    }
}
