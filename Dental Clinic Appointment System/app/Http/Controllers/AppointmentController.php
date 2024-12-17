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

    public function updateStatus($id, Request $request)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found']);
        }

        $appointment->status = $request->input('status');
        $appointment->save();

        return response()->json(['success' => true]);
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

    /**
     * Handle the delete request for the appointment.
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id); // Find the appointment by ID

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found.'], 404); // Return 404 if not found
        }

        $appointment->delete(); // Delete the appointment

        return response()->json(['message' => 'Appointment deleted successfully.'], 200); // Return success response
    }
    public function delete($id)
{
    try {
        // Find the appointment by ID
        $appointment = Appointment::findOrFail($id);
        
        // Delete the appointment
        $appointment->delete();
        
        // Return a success response
        return response()->json(['success' => 'Appointment deleted successfully']);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json(['error' => 'Failed to delete the appointment'], 500);
    }
}

}
