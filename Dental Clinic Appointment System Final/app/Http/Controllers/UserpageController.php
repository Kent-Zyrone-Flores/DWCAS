<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;


class UserpageController extends Controller
{
    public function user()
    {
        return view('user'); // Points to your login Blade
    }
}