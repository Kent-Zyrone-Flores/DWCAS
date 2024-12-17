<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the login view.
     */
    public function login()
    {
        return view('login'); // Render the login view
    }

    /**
     * Handle login form submission using Auth guard.
     */
    public function submit(Request $request)
    {
        // Validate incoming request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in the user using the default 'web' guard
        if (Auth::guard('web')->attempt($credentials)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            $user = Auth::guard('web')->user(); // Retrieve authenticated user

            // Redirect based on user role
            if ($user->is_admin == 1) {
                return redirect()->route('dashboard')->with('success', 'Welcome, Admin!');
            } else {
                return redirect()->route('user')->with('success', 'Welcome back!');
            }
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email'); // Retain the email input
    }

    /**
     * Handle login form submission using Hash check.
     */
    public function submitUsingHash(Request $request)
    {
        $incoming_fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the email exists in the database
        $user = User::where('email', $incoming_fields['email'])->first();

        if (!$user || !Hash::check($incoming_fields['password'], $user->password)) {
            return back()->with('error', 'Invalid email or password.');
        }

        // Authentication successful
        session(['user' => $user->id]); // Store user id in session
        return redirect()->route('user')->with('success', 'Logged in successfully.');
    }
}
