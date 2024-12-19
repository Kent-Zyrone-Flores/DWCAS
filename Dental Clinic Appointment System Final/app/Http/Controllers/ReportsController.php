<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Reports;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    // Display reports and handle search if a query is provided
    public function index(Request $request)
    {
        $query = $request->input('search'); // Get the search query
        $reports = Reports::query();
    
        if ($query) {
            $reports->where('name', 'LIKE', "%$query%")
                    ->orWhere('service', 'LIKE', "%$query%")
                    ->orWhere('subservice', 'LIKE', "%$query%")
                    ->orWhere('status', 'LIKE', "%$query%")
                    ->orWhere('date', 'LIKE', "%$query%");
        }
    
        $reports = $reports->get();
        $noResults = $reports->isEmpty(); // Check if there are no results
    
        return view('reports', ['reports' => $reports, 'noResults' => $noResults, 'query' => $query]);
    }
    

    

public function store(Request $request)
{
    Log::info($request->all()); // Log all incoming data
    $validated = $request->validate([
        'name' => 'required',
        'service' => 'required',
        'subservice' => 'required',
        'amount' => 'required',
        'status' => 'required',
        'date' => 'required|date',
        'description' => 'required',
    ]);

    Reports::create($validated);

    return redirect()->route('reports.index')->with('success', 'Report created successfully!');
}

public function update(Request $request, $id)
{
    $report = Reports::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required',
        'category' => 'required',
        'subcategory' => 'required',
        'amount' => 'required',
        'status' => 'required',
        'date' => 'required|date',
        'description' => 'required',
    ]);

    $report->update($validated);

    return response()->json(['success' => true]);
}

public function destroy($id)
{
    $report = Reports::findOrFail($id);
    $report->delete();

    return response()->json(['success' => true]);
}


}
    






