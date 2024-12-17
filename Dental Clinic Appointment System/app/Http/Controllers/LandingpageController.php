<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Landingpage;


class LandingpageController extends Controller
{
    public function landingpage()
    {
        // Logic for the landing page
        return view('landingpage');
    }
}
