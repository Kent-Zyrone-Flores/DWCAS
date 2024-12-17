<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\Appointment;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index() {
        $appointments = Appointment::all(); // Assuming 'Appointment' is your model for the appointments table
        $revenues = Revenue::all(); // Assuming 'Revenue' is your model for the revenues table
    
        return view('dashboard', compact('appointments', 'revenues'));
    }
    public function appointments()
    {
        // Fetch appointments from the database
        $appointments = Appointment::all();
        
        // Pass the appointments data to the view
        return view('appointments', ['appointments' => $appointments]);
    }

    public function reports()
    {
        // This function might need to fetch and process data for reports
        // For now, just return the view with a placeholder message
        return view('reports', ['message' => 'This is the reports section.']);
    }

    public function history()
    {
        // Fetch appointments data for the history view
        $appointments = Appointment::all();
        
        // Pass the appointments data to the view
        return view('history', ['appointments' => $appointments]);
    }
}

