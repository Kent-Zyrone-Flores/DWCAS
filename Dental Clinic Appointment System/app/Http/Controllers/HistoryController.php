<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class HistoryController extends Controller
{
    public function index() {
        // Fetch the appointments from the database
        $appointments = Appointment::all();
        
        // Calculate total revenue by ensuring 'amount' is treated as a numeric value
        $totalRevenue = $appointments->sum(function($appointment) {
            return (float) $appointment->amount; // Convert 'amount' to float before summing
        });
        
        // Pass the appointments data and total revenue to the view
        return view('history', ['appointments' => $appointments, 'totalRevenue' => $totalRevenue]);
    }
    
}


