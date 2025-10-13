<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TriageAssessment;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TriageController extends Controller
{
    public function index()
    {
        $triageAssessments = TriageAssessment::with(['patient.user', 'appointment', 'assessor'])
            ->latest()
            ->paginate(15);

        return view('triage.index', compact('triageAssessments'));
    }

    public function create(Request $request)
    {
        $patientId = $request->get('patient_id');
        $appointmentId = $request->get('appointment_id');
        
        $patient = null;
        $appointment = null;
        
        if ($patientId) {
            $patient = Patient::with('user')->find($patientId);
        }
        
        if ($appointmentId) {
            $appointment = Appointment::with(['patient.user', 'doctor.user'])->find($appointmentId);
            $patient = $appointment->patient ?? $patient;
        }

        // Allow creating triage without pre-selecting a patient
        return view('triage.create', compact('patient', 'appointment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'urgency_level' => 'required|in:critical,urgent,semi_urgent,standard,non_urgent',
            'chief_complaint' => 'required|string|max:500',
            'symptoms_description' => 'required|string|max:1000',
            'pain_scale' => 'nullable|integer|min:0|max:10',
            'vital_signs' => 'nullable|array',
            'vital_signs.blood_pressure' => 'nullable|string',
            'vital_signs.heart_rate' => 'nullable|integer',
            'vital_signs.temperature' => 'nullable|numeric',
            'vital_signs.respiratory_rate' => 'nullable|integer',
            'vital_signs.oxygen_saturation' => 'nullable|integer|min:0|max:100',
            'medical_history_notes' => 'nullable|string|max:1000',
            'requires_immediate_attention' => 'boolean',
            'recommended_department' => 'nullable|string',
            'triage_notes' => 'nullable|string|max:1000',
        ]);

        // Calculate priority score
        $priorityScore = TriageAssessment::calculatePriorityScore(
            $validated['urgency_level'],
            $validated['vital_signs'] ?? [],
            $validated['pain_scale'] ?? 0,
            $validated['requires_immediate_attention'] ?? false
        );

        $validated['priority_score'] = $priorityScore;
        $validated['assessed_by'] = Auth::id();
        $validated['assessed_at'] = now();

        $triageAssessment = TriageAssessment::create($validated);

        // Update appointment priority if exists
        if ($triageAssessment->appointment) {
            $triageAssessment->appointment->update([
                'priority_score' => $priorityScore
            ]);
        }

        // Create notifications based on urgency
        $this->createUrgencyNotifications($triageAssessment);

        return redirect()->route('triage.show', $triageAssessment)
            ->with('success', 'Triage assessment completed successfully!');
    }

    public function show(TriageAssessment $triage)
    {
        $triage->load(['patient.user', 'appointment.doctor.user', 'assessor']);
        return view('triage.show', compact('triage'));
    }

    public function edit(TriageAssessment $triage)
    {
        $triage->load(['patient.user', 'appointment']);
        return view('triage.edit', compact('triage'));
    }

    public function update(Request $request, TriageAssessment $triage)
    {
        $validated = $request->validate([
            'urgency_level' => 'required|in:critical,urgent,semi_urgent,standard,non_urgent',
            'chief_complaint' => 'required|string|max:500',
            'symptoms_description' => 'required|string|max:1000',
            'pain_scale' => 'nullable|integer|min:0|max:10',
            'vital_signs' => 'nullable|array',
            'medical_history_notes' => 'nullable|string|max:1000',
            'requires_immediate_attention' => 'boolean',
            'recommended_department' => 'nullable|string',
            'triage_notes' => 'nullable|string|max:1000',
        ]);

        // Recalculate priority score
        $priorityScore = TriageAssessment::calculatePriorityScore(
            $validated['urgency_level'],
            $validated['vital_signs'] ?? [],
            $validated['pain_scale'] ?? 0,
            $validated['requires_immediate_attention'] ?? false
        );

        $validated['priority_score'] = $priorityScore;

        $triage->update($validated);

        // Update appointment priority if exists
        if ($triage->appointment) {
            $triage->appointment->update([
                'priority_score' => $priorityScore
            ]);
        }

        return redirect()->route('triage.show', $triage)
            ->with('success', 'Triage assessment updated successfully!');
    }

    public function destroy(TriageAssessment $triage)
    {
        $triage->delete();
        return redirect()->route('triage.index')
            ->with('success', 'Triage assessment deleted successfully!');
    }

    public function queue()
    {
        $criticalCases = TriageAssessment::where('urgency_level', 'critical')
            ->whereDate('created_at', today())
            ->with(['patient.user', 'appointment.doctor.user'])
            ->orderBy('priority_score', 'desc')
            ->get();

        $urgentCases = TriageAssessment::where('urgency_level', 'urgent')
            ->whereDate('created_at', today())
            ->with(['patient.user', 'appointment.doctor.user'])
            ->orderBy('priority_score', 'desc')
            ->get();

        $otherCases = TriageAssessment::whereNotIn('urgency_level', ['critical', 'urgent'])
            ->whereDate('created_at', today())
            ->with(['patient.user', 'appointment.doctor.user'])
            ->orderBy('priority_score', 'desc')
            ->get();

        return view('triage.queue', compact('criticalCases', 'urgentCases', 'otherCases'));
    }

    private function createUrgencyNotifications(TriageAssessment $triage)
    {
        // Notify patient
        Notification::create([
            'user_id' => $triage->patient->user_id,
            'title' => 'Triage Assessment Completed',
            'message' => "Your triage assessment has been completed. Priority level: {$triage->urgency_level}",
            'type' => 'triage',
            'data' => ['triage_id' => $triage->id],
            'priority' => $triage->urgency_level === 'critical' ? 'critical' : 'medium'
        ]);

        // Notify doctor if appointment exists
        if ($triage->appointment && $triage->appointment->doctor) {
            Notification::create([
                'user_id' => $triage->appointment->doctor->user_id,
                'title' => 'Patient Triage Update',
                'message' => "Triage completed for {$triage->patient->user->name}. Priority: {$triage->urgency_level}",
                'type' => 'triage',
                'data' => ['triage_id' => $triage->id, 'appointment_id' => $triage->appointment->id],
                'priority' => $triage->urgency_level === 'critical' ? 'critical' : 'medium'
            ]);
        }

        // Alert staff for critical cases
        if ($triage->urgency_level === 'critical') {
            // This would typically send to all staff/administrators
            // For now, we'll create a system notification
            Notification::create([
                'user_id' => Auth::id(), // Could be broadcast to all staff
                'title' => 'CRITICAL PATIENT ALERT',
                'message' => "Critical patient: {$triage->patient->user->name}. Immediate attention required.",
                'type' => 'emergency',
                'data' => ['triage_id' => $triage->id],
                'priority' => 'critical'
            ]);
        }
    }
}
