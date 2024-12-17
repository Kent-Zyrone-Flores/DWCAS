<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();

        $totalRevenue = $appointments->sum(function ($appointment) {
            return (float) $appointment->amount; 
        });

        return view('history', [
            'appointments' => $appointments,
            'totalRevenue' => $totalRevenue
        ]);
    }
}
