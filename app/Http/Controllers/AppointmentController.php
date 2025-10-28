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

        if ($user->role === 'patient') {
            if ($user->patient) {
                $appointments = $user->patient->appointments()
                    ->with(['doctor.user', 'hospital', 'department'])
                    ->latest()
                    ->paginate(10);
            } else {
                $appointments = collect()->paginate(10);
            }
        } elseif ($user->role === 'doctor') {
            if ($user->doctor) {
                $appointments = $user->doctor->appointments()
                    ->with(['patient.user', 'hospital', 'department'])
                    ->latest()
                    ->paginate(10);
            } else {
                $appointments = collect()->paginate(10);
            }
        } else {
            $appointments = Appointment::with(['patient.user', 'doctor.user', 'hospital', 'department'])
                ->latest()
                ->paginate(10);
        }

        return view('appointments.index', compact('appointments', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Ensure patient record exists for patient users
        if ($user->role === 'patient' && !$user->patient) {
            // Create patient record if it doesn't exist
            $patient = Patient::create([
                'user_id' => $user->id,
                'patient_number' => 'P' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                'blood_type' => null,
                'height' => null,
                'weight' => null,
                'medical_history' => null,
                'allergies' => null,
            ]);
        }
        
        $hospitals = Hospital::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $doctors = Doctor::where('is_available', true)->with('user', 'department.hospital')->get();

        return view('appointments.create', compact('hospitals', 'departments', 'doctors'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date|after:now',
            'type' => 'required|in:consultation,follow_up,emergency,routine_checkup',
            'reason_for_visit' => 'required|string|max:500',
            'symptoms' => 'nullable|string|max:1000',
            'auto_assign_doctor' => 'nullable|boolean',
        ]);
        
        // Auto-set patient_id if user is a patient
        if (!isset($validated['patient_id'])) {
            if ($user->role === 'patient') {
                // Ensure patient record exists
                if (!$user->patient) {
                    $patient = Patient::create([
                        'user_id' => $user->id,
                        'patient_number' => 'P' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                        'blood_type' => null,
                        'height' => null,
                        'weight' => null,
                        'medical_history' => null,
                        'allergies' => null,
                    ]);
                    $validated['patient_id'] = $patient->id;
                } else {
                    $validated['patient_id'] = $user->patient->id;
                }
            } else {
                return back()->withErrors(['patient_id' => 'Patient ID is required for non-patient users.'])->withInput();
            }
        }

        $appointmentDate = Carbon::parse($validated['appointment_date']);
        
        // Auto-assign doctor if requested or no doctor selected
        if (!isset($validated['doctor_id']) || $request->input('auto_assign_doctor')) {
            $doctor = $this->findNearestAvailableDoctor(
                $validated['department_id'],
                $validated['hospital_id'],
                $appointmentDate
            );
            
            if (!$doctor) {
                return back()->withErrors([
                    'doctor_id' => 'No available doctors found for the selected department and date. Please try another date or select a doctor manually.'
                ])->withInput();
            }
            
            $validated['doctor_id'] = $doctor->id;
        } else {
            // Check doctor availability if manually selected
            $doctor = Doctor::find($validated['doctor_id']);
            
            if (!$doctor->is_available) {
                return back()->withErrors(['doctor_id' => 'Selected doctor is currently unavailable.'])->withInput();
            }
        }

        $existingAppointments = $doctor->appointments()
            ->whereDate('appointment_date', $appointmentDate->toDateString())
            ->count();

        if ($existingAppointments >= $doctor->max_patients_per_day) {
            return back()->withErrors(['appointment_date' => 'Doctor is fully booked for this date. Please select another date.'])->withInput();
        }

        $validated['consultation_fee'] = $doctor->consultation_fee;
        $validated['priority_score'] = $this->calculateBasePriority($validated['type']);

        $appointment = Appointment::create($validated);

        // Create notification for patient
        Notification::create([
            'user_id' => $appointment->patient->user_id,
            'title' => 'Appointment Scheduled',
            'message' => "Your appointment has been scheduled with Dr. {$doctor->user->name} for {$appointmentDate->format('M d, Y \\a\\t H:i')}",
            'type' => 'appointment',
            'data' => ['appointment_id' => $appointment->id],
            'priority' => 'medium'
        ]);

        // Create notification for doctor
        Notification::create([
            'user_id' => $doctor->user_id,
            'title' => 'New Appointment',
            'message' => "New appointment scheduled for {$appointmentDate->format('M d, Y \\a\\t H:i')}",
            'type' => 'appointment',
            'data' => ['appointment_id' => $appointment->id],
            'priority' => 'medium'
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment scheduled successfully with Dr. ' . $doctor->user->name . '!');
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

        // Track changes for notification
        $changes = [];
        $oldDoctor = $appointment->doctor;
        $oldDate = $appointment->appointment_date;
        $oldStatus = $appointment->status;
        
        if ($appointment->doctor_id != $validated['doctor_id']) {
            $newDoctor = Doctor::with('user')->find($validated['doctor_id']);
            $changes[] = "Doctor changed to Dr. {$newDoctor->user->name}";
        }
        
        if ($appointment->appointment_date != $validated['appointment_date']) {
            $newDate = Carbon::parse($validated['appointment_date']);
            $changes[] = "Date/Time changed to {$newDate->format('M d, Y \\a\\t H:i')}";
        }
        
        if ($appointment->status != $validated['status']) {
            $changes[] = "Status changed to " . ucfirst(str_replace('_', ' ', $validated['status']));
        }

        $appointment->update($validated);

        // Send notification to patient if there are changes
        if (!empty($changes)) {
            $changesList = implode(', ', $changes);
            
            Notification::create([
                'user_id' => $appointment->patient->user_id,
                'title' => 'Appointment Updated',
                'message' => "Your appointment has been updated. Changes: {$changesList}",
                'type' => 'appointment',
                'data' => ['appointment_id' => $appointment->id, 'changes' => $changes],
                'priority' => 'high'
            ]);
            
            // Notify old doctor if doctor changed
            if ($appointment->doctor_id != $oldDoctor->id) {
                Notification::create([
                    'user_id' => $oldDoctor->user_id,
                    'title' => 'Appointment Reassigned',
                    'message' => "An appointment scheduled for {$oldDate} has been reassigned to another doctor.",
                    'type' => 'appointment',
                    'data' => ['appointment_id' => $appointment->id],
                    'priority' => 'medium'
                ]);
                
                // Notify new doctor
                Notification::create([
                    'user_id' => $appointment->doctor->user_id,
                    'title' => 'New Appointment Assigned',
                    'message' => "A new appointment has been assigned to you for {$appointment->appointment_date}",
                    'type' => 'appointment',
                    'data' => ['appointment_id' => $appointment->id],
                    'priority' => 'medium'
                ]);
            }
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment)
    {
        $appointmentDate = Carbon::parse($appointment->appointment_date);
        $patientUserId = $appointment->patient->user_id;
        $doctorUserId = $appointment->doctor->user_id;
        
        // Send cancellation notification to patient
        Notification::create([
            'user_id' => $patientUserId,
            'title' => 'Appointment Cancelled',
            'message' => "Your appointment scheduled for {$appointmentDate->format('M d, Y \\a\\t H:i')} has been cancelled.",
            'type' => 'appointment',
            'data' => ['appointment_id' => $appointment->id, 'status' => 'cancelled'],
            'priority' => 'high'
        ]);
        
        // Send cancellation notification to doctor
        Notification::create([
            'user_id' => $doctorUserId,
            'title' => 'Appointment Cancelled',
            'message' => "An appointment scheduled for {$appointmentDate->format('M d, Y \\a\\t H:i')} has been cancelled.",
            'type' => 'appointment',
            'data' => ['appointment_id' => $appointment->id, 'status' => 'cancelled'],
            'priority' => 'medium'
        ]);
        
        $appointment->delete();
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully! Notifications sent to patient and doctor.');
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

    /**
     * Find the nearest available doctor based on department, hospital, and workload
     */
    private function findNearestAvailableDoctor($departmentId, $hospitalId, $appointmentDate)
    {
        $dateString = Carbon::parse($appointmentDate)->toDateString();
        
        // Find available doctors in the same hospital and department
        $doctor = Doctor::where('department_id', $departmentId)
            ->where('is_available', true)
            ->whereHas('department', function($q) use ($hospitalId) {
                $q->where('hospital_id', $hospitalId);
            })
            ->withCount(['appointments' => function($q) use ($dateString) {
                $q->whereDate('appointment_date', $dateString);
            }])
            ->get()
            ->filter(function($doctor) {
                return $doctor->appointments_count < $doctor->max_patients_per_day;
            })
            ->sortBy('appointments_count')
            ->first();
        
        // If no doctor found in same hospital, try other hospitals with same department
        if (!$doctor) {
            $doctor = Doctor::where('department_id', $departmentId)
                ->where('is_available', true)
                ->withCount(['appointments' => function($q) use ($dateString) {
                    $q->whereDate('appointment_date', $dateString);
                }])
                ->get()
                ->filter(function($doctor) {
                    return $doctor->appointments_count < $doctor->max_patients_per_day;
                })
                ->sortBy('appointments_count')
                ->first();
        }
        
        return $doctor;
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
