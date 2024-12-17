<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    // Display reports and handle search if a query is provided
    public function index(Request $request)
    {
        $query = $request->input('search');
        $reports = Reports::where('title', 'LIKE', "%$query%")
                          ->orWhere('status', 'LIKE', "%$query%")
                          ->orWhere('date', 'LIKE', "%$query%")
                          ->get();
    
        return view('reports', ['appointments' => $reports]); // Pass 'appointments' to the view
    }
    

    // Store new report
    public function store(Request $request)
    {
        $incoming_fields = $request->validate([
            'title' => 'required',
            'status' => 'required',
            'date' => 'required',
        ]);

        Reports::create($incoming_fields);

        return redirect()->route('reports.index'); // Assuming you have a route named reports.index
    }
}






