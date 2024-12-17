<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();

        $totalRevenue = $appointments->sum(function ($appointment) {
            return (float) $appointment->amount; 
        });

        return view('dashboard', [
            'appointments' => $appointments,
            'totalRevenue' => $totalRevenue
        ]);
    }

    public function appointments()
    {
        $appointments = Appointment::all();
        
        return view('appointments', ['appointments' => $appointments]);
    }

    public function reports()
    {
        return view('reports', ['message' => 'This is the reports section.']);
    }

    public function history()
    {
        $appointments = Appointment::all();
        return view('history', ['appointments' => $appointments]);
    }
}
