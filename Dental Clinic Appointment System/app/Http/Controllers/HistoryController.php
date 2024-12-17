<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index()
    {
        // Fetch all appointments
        $appointments = Appointment::all();

        // Calculate total revenue safely
        $totalRevenue = $appointments->sum(function ($appointment) {
            return (float) $appointment->amount; // Ensure 'amount' is numeric
        });

        // Pass the appointments and total revenue to the view
        return view('history', [
            'appointments' => $appointments,
            'totalRevenue' => $totalRevenue
        ]);
    }
}
