<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login.login');
    }

    public function register()
    {
        return view('auth.register.regsiter');
    }

    public function logoutView()
    {
        return view('auth.logout.logout');
    }
}
