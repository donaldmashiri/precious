<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Hospital;
use App\Models\Department;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = collect();

        if ($user->isPatient()) {
            $appointments = $user->patient->appointments()
                ->with(['doctor.user', 'hospital', 'department'])
                ->latest()
                ->paginate(10);
        } elseif ($user->isDoctor()) {
            $appointments = $user->doctor->appointments()
                ->with(['patient.user', 'hospital', 'department'])
                ->latest()
                ->paginate(10);
        } else {
            $appointments = Appointment::with(['patient.user', 'doctor.user', 'hospital', 'department'])
                ->latest()
                ->paginate(10);
        }

        return view('appointments.index', compact('appointments', 'user'));
    }

    public function create()
    {
        $hospitals = Hospital::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $doctors = Doctor::where('is_available', true)->with('user')->get();
        
        return view('appointments.create', compact('hospitals', 'departments', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date|after:now',
            'type' => 'required|in:consultation,follow_up,emergency,routine_checkup',
            'reason_for_visit' => 'required|string|max:500',
            'symptoms' => 'nullable|string|max:1000',
        ]);

        // Check doctor availability
        $doctor = Doctor::find($validated['doctor_id']);
        $appointmentDate = Carbon::parse($validated['appointment_date']);
        
        $existingAppointments = $doctor->appointments()
            ->whereDate('appointment_date', $appointmentDate->toDateString())
            ->count();

        if ($existingAppointments >= $doctor->max_patients_per_day) {
            return back()->withErrors(['appointment_date' => 'Doctor is fully booked for this date.']);
        }

        $validated['consultation_fee'] = $doctor->consultation_fee;
        $validated['priority_score'] = $this->calculateBasePriority($validated['type']);

        $appointment = Appointment::create($validated);

        // Create notification for patient
        Notification::create([
            'user_id' => $appointment->patient->user_id,
            'title' => 'Appointment Scheduled',
            'message' => "Your appointment has been scheduled for {$appointmentDate->format('M d, Y \\a\\t H:i')}",
            'type' => 'appointment',
            'data' => ['appointment_id' => $appointment->id],
            'priority' => 'medium'
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment scheduled successfully!');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user', 'hospital', 'department', 'triageAssessment']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $hospitals = Hospital::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $doctors = Doctor::where('is_available', true)->with('user')->get();
        
        return view('appointments.edit', compact('appointment', 'hospitals', 'departments', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'type' => 'required|in:consultation,follow_up,emergency,routine_checkup',
            'reason_for_visit' => 'required|string|max:500',
            'symptoms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully!');
    }

    public function checkIn(Appointment $appointment)
    {
        $appointment->update([
            'status' => 'confirmed',
            'checked_in_at' => now()
        ]);

        return back()->with('success', 'Patient checked in successfully!');
    }

    public function start(Appointment $appointment)
    {
        $appointment->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return back()->with('success', 'Appointment started!');
    }

    public function complete(Appointment $appointment)
    {
        $appointment->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return back()->with('success', 'Appointment completed!');
    }

    private function calculateBasePriority($type)
    {
        return match($type) {
            'emergency' => 80,
            'consultation' => 50,
            'follow_up' => 40,
            'routine_checkup' => 30,
            default => 30
        };
    }

    public function getAvailableSlots(Request $request)
    {
        $doctorId = $request->doctor_id;
        $date = $request->date;
        
        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return response()->json(['slots' => []]);
        }

        $existingAppointments = $doctor->appointments()
            ->whereDate('appointment_date', $date)
            ->pluck('appointment_date')
            ->map(function($datetime) {
                return Carbon::parse($datetime)->format('H:i');
            });

        $slots = [];
        $start = Carbon::parse($doctor->shift_start);
        $end = Carbon::parse($doctor->shift_end);
        
        while ($start < $end) {
            $timeSlot = $start->format('H:i');
            if (!$existingAppointments->contains($timeSlot)) {
                $slots[] = [
                    'time' => $timeSlot,
                    'display' => $start->format('g:i A')
                ];
            }
            $start->addMinutes(30); // 30-minute slots
        }

        return response()->json(['slots' => $slots]);
    }
}
