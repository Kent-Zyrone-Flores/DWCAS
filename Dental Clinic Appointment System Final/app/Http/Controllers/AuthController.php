<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout(Request $request)
{
    // Logout the user using the default web guard
    Auth::guard('web')->logout();
    Auth::logout();

    // Invalidate the session to prevent session fixation attacks
    $request->session()->invalidate();

    // Regenerate the session ID to prevent session fixation
    $request->session()->regenerateToken();

    // Redirect to login page
    return redirect()->route('login')->with('success', 'Logged out successfully!');
    return redirect()->route('landingpage')->with('success', 'Logged out successfully.');

    }

}