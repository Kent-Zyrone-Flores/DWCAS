<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
<<<<<<< HEAD
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
=======
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(){
        return view('history');
    }

    public function filter(Request $request)
    {
        $status = $request->query('status');

        // Fetch data based on status, or all if 'all' is selected
        $appointments = ($status && $status !== 'all')
            ? Appointment::where('status', $status)->get()
            : Appointment::all();

        return response()->json($appointments);
>>>>>>> 4ad902e4ad6a94992c2e27838c83111cd63080df
    }
}
