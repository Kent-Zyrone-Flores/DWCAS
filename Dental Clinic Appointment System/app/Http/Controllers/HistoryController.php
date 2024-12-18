<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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
    }
}