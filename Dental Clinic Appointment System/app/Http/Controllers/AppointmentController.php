<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Add this line to import DB facade

class AppointmentController extends Controller
{
    public function user()
    {
        $appointments = Appointment::all();
        return view('user', compact('appointments'));
    }
    public function submit(Request $request)
    {
        $incoming_fields = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'service' => 'required',
            'subservice' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);
    
        // Check for conflicts (again on the server-side)
        $existingAppointment = Appointment::where('date', $incoming_fields['date'])
                                           ->where('time', $incoming_fields['time'])
                                           ->first();
    
        if ($existingAppointment) {
            return back()->with('error', 'This time slot is already booked. Please choose another.');
        }
    
        Appointment::create($incoming_fields);
        return redirect()->route('user')->with('success', 'Appointment booked successfully!');
    }
    

    public function index()
    {
        $appointments = Appointment::all();
        return view('appointment', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'service' => 'required|string',
            'subservice' => 'required|string',
            'amount' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
        ]);

        // Check for conflicts
        $existingAppointment = Appointment::where('date', $request->date)
                                           ->where('time', $request->time)
                                           ->first();

        if ($existingAppointment) {
            return response()->json(['success' => false, 'message' => 'This time slot is already booked.'], 409);
        }

        Appointment::create($request->all());
        return redirect()->route('appointment')->with('success', 'Appointment created successfully!');
    }

    public function updateStatus(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string'
        ]);
    
        try {
            // Find the appointment by ID
            $appointment = Appointment::findOrFail($request->id);
    
            // Update the status
            $appointment->status = $request->status;
            $appointment->save();
    
            // Return success response
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['success' => false, 'message' => 'Failed to update status']);
        }
    }

    public function reschedule(Request $request)
{
    $appointment = Appointment::find($request->appointmentId);
    if ($appointment) {
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->save();

        return response()->json(['success' => true, 'message' => 'Appointment rescheduled successfully.']);
    }

    return response()->json(['success' => false, 'message' => 'Appointment not found.']);
}

    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return redirect()->route('appointments.index')->with('error', 'Appointment not found.');
        }

        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }

    public function showAppointmentForm(Request $request)
    {
        // Get today's date if no date is provided
        $date = $request->input('date', date('Y-m-d'));
    
        // Get the times that are already taken for the selected date
        $takenTimes = DB::table('appointments')
                        ->whereDate('appointment_date', $date)
                        ->pluck('appointment_time')
                        ->toArray();
    
        return view('appointment.form', compact('takenTimes', 'date'));
    }

    // public function getBookedSlots(Request $request)
    // {
    //     $date = $request->query('date');
    //     $bookedSlots = Appointment::where('date', $date)
    //         ->pluck('time')
    //         ->map(function ($time) {
    //             return date('H:i', strtotime($time)); // Format time to match option values
    //         })
    //         ->toArray();
    //     return response()->json($bookedSlots);
    // }

    public function getBookedSlots(Request $request)
{
    $request->validate([
        'date' => 'required|date',
    ]);

    // Fetch all booked times for the selected date
    $bookedSlots = Appointment::where('date', $request->date)->pluck('time')->toArray();

    return response()->json($bookedSlots);
}

public function destroy($id)
        {
            $appointment = Appointment::find($id);

            if (!$appointment) {
                return redirect()->route('appointments.index')->with('error', 'Appointment not found.');
            }

            $appointment->delete();
            return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
        }
        public function submitAppointment(Request $request)
{
    // Validate form data
    $validated = $request->validate([
        'name' => 'required|string',
        'phone' => 'required|string',
        'address' => 'required|string',
        'service' => 'required|string',
        'subservice' => 'required|string',
        'amount' => 'required|numeric',
        'date' => 'required|date',
        'time' => 'required|string',
    ]);

    // Create a new appointment in the database
    $appointment = new Appointment();
    $appointment->name = $request->name;
    $appointment->phone = $request->phone;
    $appointment->address = $request->address;
    $appointment->service = $request->service;
    $appointment->subservice = $request->subservice; // Store selected subservice
    $appointment->amount = $request->amount;
    $appointment->date = $request->date;
    $appointment->time = $request->time;
    $appointment->status = 'Pending'; // Default status
    $appointment->save();

    // Redirect back with success message
    return redirect()->back()->with('success', 'Appointment booked successfully!');
}
    

}
    public function upload(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'proof-of-transaction' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Store the file in the 'proofs' directory
    $filePath = $request->file('proof-of-transaction')->store('proofs');

    // Optionally save the file path in the database
    // Example: Appointment::where('id', $appointmentId)->update(['proof_of_transaction' => $filePath]);

    return response()->json(['success' => true, 'filePath' => $filePath]);
}

}
