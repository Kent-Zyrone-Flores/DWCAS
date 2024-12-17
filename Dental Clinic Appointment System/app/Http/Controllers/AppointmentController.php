<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

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
            'amount' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);

        // Check for conflicts
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
        $request->validate([
            'appointmentId' => 'required|exists:appointments,id',
            'date' => 'required|date',
            'time' => 'required|string',
        ]);

        $appointment = Appointment::find($request->appointmentId);

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found']);
        }

        // Check if the new slot is available
        $isSlotTaken = Appointment::where('date', $request->date)
                                   ->where('time', $request->time)
                                   ->exists();

        if ($isSlotTaken) {
            return response()->json(['success' => false, 'message' => 'The selected time slot is already booked.']);
        }

        // Update appointment details
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->status = 'Rescheduled';
        $appointment->save();

        return response()->json(['success' => true, 'message' => 'Appointment rescheduled successfully!']);
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
}