<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TriageController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Patient routes
    Route::resource('patients', PatientController::class);
    Route::get('/patient/register', [PatientController::class, 'register'])->name('patient.register');
    Route::post('/patient/complete-registration', [PatientController::class, 'completeRegistration'])->name('patient.complete-registration');
    
    // Appointment routes
    Route::resource('appointments', AppointmentController::class);
    Route::post('/appointments/{appointment}/check-in', [AppointmentController::class, 'checkIn'])->name('appointments.check-in');
    Route::post('/appointments/{appointment}/start', [AppointmentController::class, 'start'])->name('appointments.start');
    Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::get('/api/available-slots', [AppointmentController::class, 'getAvailableSlots'])->name('appointments.available-slots');
    
    // Triage routes
    Route::resource('triage', TriageController::class);
    Route::get('/triage-queue', [TriageController::class, 'queue'])->name('triage.queue');
    
    // Doctor routes (for staff/admin)
    Route::resource('doctors', DoctorController::class)->middleware('can:manage-doctors');
    
    // Doctor availability management (for admin)
    Route::middleware(['auth', 'verified', 'web'])->group(function () {
        Route::get('/doctors-availability', [DoctorController::class, 'availability'])->name('doctors.availability');
        Route::post('/doctors/{doctor}/toggle-availability', [DoctorController::class, 'toggleAvailability'])->name('doctors.toggle-availability');
    });
    
    // Hospital routes (for admin)
    Route::resource('hospitals', HospitalController::class)->middleware('can:manage-hospitals');
    
    // API routes for dynamic content
    Route::get('/api/departments/{hospital}', function($hospitalId) {
        return \App\Models\Department::where('hospital_id', $hospitalId)
            ->where('is_active', true)
            ->get(['id', 'name']);
    })->name('api.departments');
    
    Route::get('/api/doctors/{department}', function($departmentId) {
        return \App\Models\Doctor::where('department_id', $departmentId)
            ->where('is_available', true)
            ->with('user:id,name')
            ->get(['id', 'user_id', 'specialization']);
    })->name('api.doctors');
});

require __DIR__.'/auth.php';
