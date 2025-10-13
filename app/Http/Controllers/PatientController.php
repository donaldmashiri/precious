<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['user', 'appointments'])
            ->latest()
            ->paginate(15);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $hospitals = Hospital::where('is_active', true)->get();
        return view('patients.create', compact('hospitals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'national_id' => 'nullable|string|unique:users,national_id',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height' => 'nullable|numeric|min:50|max:250',
            'weight' => 'nullable|numeric|min:10|max:300',
            'medical_history' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'current_medications' => 'nullable|string|max:1000',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_number' => 'nullable|string|max:100',
            'insurance_expiry' => 'nullable|date|after:today',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'), // Default password
            'role' => 'patient',
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'national_id' => $validated['national_id'] ?? null,
            'is_active' => true,
        ]);

        // Generate patient number
        $patientNumber = 'P' . str_pad(Patient::count() + 1, 6, '0', STR_PAD_LEFT);

        // Create patient profile
        $patient = Patient::create([
            'user_id' => $user->id,
            'patient_number' => $patientNumber,
            'blood_type' => $validated['blood_type'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'current_medications' => $validated['current_medications'] ?? null,
            'insurance_provider' => $validated['insurance_provider'] ?? null,
            'insurance_number' => $validated['insurance_number'] ?? null,
            'insurance_expiry' => $validated['insurance_expiry'] ?? null,
        ]);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient registered successfully! Default password is: password123');
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'appointments.doctor.user', 'triageAssessments']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $hospitals = Hospital::where('is_active', true)->get();
        return view('patients.edit', compact('patient', 'hospitals'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'national_id' => 'nullable|string|unique:users,national_id,' . $patient->user_id,
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height' => 'nullable|numeric|min:50|max:250',
            'weight' => 'nullable|numeric|min:10|max:300',
            'medical_history' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'current_medications' => 'nullable|string|max:1000',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_number' => 'nullable|string|max:100',
            'insurance_expiry' => 'nullable|date|after:today',
        ]);

        // Update user
        $patient->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'national_id' => $validated['national_id'] ?? null,
        ]);

        // Update patient
        $patient->update([
            'blood_type' => $validated['blood_type'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'current_medications' => $validated['current_medications'] ?? null,
            'insurance_provider' => $validated['insurance_provider'] ?? null,
            'insurance_number' => $validated['insurance_number'] ?? null,
            'insurance_expiry' => $validated['insurance_expiry'] ?? null,
        ]);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient information updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->user->delete(); // This will cascade delete the patient
        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully!');
    }

    public function register()
    {
        // For patients to self-register
        $hospitals = Hospital::where('is_active', true)->get();
        return view('patients.register', compact('hospitals'));
    }

    public function completeRegistration(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'national_id' => 'nullable|string|unique:users,national_id',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'medical_history' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'patient',
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'national_id' => $validated['national_id'] ?? null,
            'is_active' => true,
        ]);

        // Generate patient number
        $patientNumber = 'P' . str_pad(Patient::count() + 1, 6, '0', STR_PAD_LEFT);

        // Create patient profile
        $patient = Patient::create([
            'user_id' => $user->id,
            'patient_number' => $patientNumber,
            'blood_type' => $validated['blood_type'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
        ]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registration completed successfully! Welcome to our healthcare system.');
    }
}
