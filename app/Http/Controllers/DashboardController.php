<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\TriageAssessment;
use App\Models\Hospital;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isPatient()) {
            return $this->patientDashboard($user);
        } elseif ($user->isDoctor()) {
            return $this->doctorDashboard($user);
        } elseif ($user->isNurse() || $user->isStaff()) {
            return $this->staffDashboard($user);
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        return view('dashboard', compact('data'));
    }

    private function patientDashboard($user)
    {
        $patient = $user->patient;
        $data = [
            'upcoming_appointments' => $patient ? $patient->appointments()->upcoming()->with(['doctor.user', 'hospital', 'department'])->limit(5)->get() : collect(),
            'recent_appointments' => $patient ? $patient->appointments()->with(['doctor.user', 'hospital'])->latest()->limit(3)->get() : collect(),
            'triage_assessments' => $patient ? $patient->triageAssessments()->with('assessor')->latest()->limit(3)->get() : collect(),
            'notifications' => $user->notifications()->unread()->latest()->limit(5)->get(),
        ];

        return view('dashboard.patient', compact('data', 'user', 'patient'));
    }

    private function doctorDashboard($user)
    {
        $doctor = $user->doctor;
        $data = [
            'todays_appointments' => $doctor ? $doctor->appointments()->today()->with(['patient.user', 'hospital'])->orderBy('appointment_date')->get() : collect(),
            'upcoming_appointments' => $doctor ? $doctor->appointments()->upcoming()->with(['patient.user'])->limit(10)->get() : collect(),
            'pending_triage' => TriageAssessment::whereHas('appointment', function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id ?? 0)->where('status', 'scheduled');
            })->with(['patient.user', 'appointment'])->get(),
            'statistics' => [
                'total_patients_today' => $doctor ? $doctor->appointments()->today()->count() : 0,
                'completed_today' => $doctor ? $doctor->appointments()->today()->where('status', 'completed')->count() : 0,
                'pending_today' => $doctor ? $doctor->appointments()->today()->whereIn('status', ['scheduled', 'confirmed'])->count() : 0,
            ]
        ];

        return view('dashboard.doctor', compact('data', 'user', 'doctor'));
    }

    private function staffDashboard($user)
    {
        $data = [
            'pending_triage' => TriageAssessment::whereNull('assessed_at')->with(['patient.user'])->latest()->limit(10)->get(),
            'todays_appointments' => Appointment::today()->with(['patient.user', 'doctor.user'])->orderBy('priority_score', 'desc')->get(),
            'critical_patients' => TriageAssessment::where('urgency_level', 'critical')->whereDate('created_at', today())->with(['patient.user'])->get(),
            'statistics' => [
                'total_appointments_today' => Appointment::today()->count(),
                'completed_today' => Appointment::today()->where('status', 'completed')->count(),
                'pending_triage' => TriageAssessment::whereNull('assessed_at')->count(),
                'critical_cases' => TriageAssessment::where('urgency_level', 'critical')->whereDate('created_at', today())->count(),
            ]
        ];

        return view('dashboard.staff', compact('data', 'user'));
    }

    private function adminDashboard($user)
    {
        $data = [
            'hospitals' => Hospital::with('departments')->get(),
            'statistics' => [
                'total_patients' => Patient::count(),
                'total_doctors' => Doctor::count(),
                'total_appointments_today' => Appointment::today()->count(),
                'total_hospitals' => Hospital::count(),
                'pending_appointments' => Appointment::whereIn('status', ['scheduled', 'confirmed'])->count(),
                'completed_today' => Appointment::today()->where('status', 'completed')->count(),
            ],
            'recent_registrations' => Patient::with('user')->latest()->limit(5)->get(),
            'system_alerts' => TriageAssessment::where('urgency_level', 'critical')->whereDate('created_at', today())->with(['patient.user'])->get(),
        ];

        return view('dashboard.admin', compact('data', 'user'));
    }
}
