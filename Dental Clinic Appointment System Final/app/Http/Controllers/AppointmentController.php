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
        // Validate incoming fields
        $incoming_fields = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'service' => 'required',
            'subservice' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'time' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Check for conflicts (to avoid booking the same time slot)
        $existingAppointment = Appointment::where('date', $incoming_fields['date'])
            ->where('time', $incoming_fields['time'])
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'This time slot is already booked. Please choose another.');
        }

        // Check if a file has been uploaded and store it
        if ($request->hasFile('file')) {
            // Store the file in the 'appointments' folder in the 'public' disk
            $file = $request->file('file');
            $filePath = $file->store('appointments', 'public'); // Corrected storage path

            // Add the file path to the incoming fields
            $incoming_fields['file'] = $filePath;
        }

        // Create the appointment record in the database with the incoming fields
        Appointment::create([
            'name' => $incoming_fields['name'],
            'phone' => $incoming_fields['phone'],
            'address' => $incoming_fields['address'],
            'service' => $incoming_fields['service'],
            'subservice' => $incoming_fields['subservice'],
            'amount' => $incoming_fields['amount'],
            'date' => $incoming_fields['date'],
            'time' => $incoming_fields['time'],
            'file' => $incoming_fields['file'],
            'status' => 'Pending',
        ]);

        // Redirect to the user page with success message
        return redirect()->route('user')->with('success', 'Appointment booked successfully!');
    }




    public function index()
    {
        $appointments = Appointment::all();
        return view('appointment', compact('appointments'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
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
        // Create the appointment and save it to the database
        Appointment::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'service' => $request->service,
            'subservice' => $request->subservice,
            'amount' => $request->amount,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'Pending', // Set the default status to 'Pending'
        ]);

        // Redirect to the appointments index page with a success message
        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');
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

    // public function destroy($id)
    // {
    //     $appointment = Appointment::find($id);

    //     if (!$appointment) {
    //         return redirect()->route('appointments.index')->with('error', 'Appointment not found.');
    //     }

    //     $appointment->delete();
    //     return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    // }

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
    // public function submitAppointment(Request $request)
    // {
    //     // Validate form data
    //     $validated = $request->validate([
    //         'name' => 'required|string',
    //         'phone' => 'required|string',
    //         'address' => 'required|string',
    //         'service' => 'required|string',
    //         'subservice' => 'required|string',
    //         'amount' => 'required|numeric',
    //         'date' => 'required|date',
    //         'time' => 'required|string',
    //     ]);

    //     // Create a new appointment in the database
    //     $appointment = new Appointment();
    //     $appointment->name = $request->name;
    //     $appointment->phone = $request->phone;
    //     $appointment->address = $request->address;
    //     $appointment->service = $request->service;
    //     $appointment->subservice = $request->subservice; // Store selected subservice
    //     $appointment->amount = $request->amount;
    //     $appointment->date = $request->date;
    //     $appointment->time = $request->time;
    //     $appointment->status = 'Pending'; // Default status
    //     $appointment->save();

    //     // Redirect back with success message
    //     return redirect()->back()->with('success', 'Appointment booked successfully!');
    // }



    public function upload(Request $request)
    {

        dd($request->all());

        // Validate the uploaded file
        $request->validate([
            'proof-of-transaction' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validate file type and size
        ]);

        // Check if file is uploaded successfully
        if ($request->hasFile('proof-of-transaction') && $request->file('proof-of-transaction')->isValid()) {

            // Store the file in the 'proofs' directory within the 'public' disk
            $filePath = $request->file('proof-of-transaction')->store('proofs', 'public'); // Use 'public' disk for accessibility

            // Assuming you want to associate this uploaded file with an appointment:
            // Save the file path to the Appointment model (replace `$appointmentId` with the actual appointment ID if needed)
            $appointmentId = $request->input('appointment_id'); // Assuming you're passing the appointment ID via the request
            $appointment = Appointment::find($appointmentId);

            if ($appointment) {
                $appointment->proof_of_transaction = $filePath; // Store the file path in the database
                $appointment->save();
            }

            // Return success response with file path
            return response()->json([
                'success' => true,
                'file' => $filePath,
                'message' => 'File uploaded successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid file or file not uploaded.'], 400);
    }
}
