<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class DestinationController extends Controller
{
    public function index()
    {
        return view('dashboard.destinations.destinations');
    }

    public function show($id)
    {
        return view('dashboard.destinations.show', compact('id'));
    }
}
