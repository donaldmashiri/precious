<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with(['user', 'department'])->get();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::with(['user', 'department', 'appointments'])->findOrFail($id);
        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * Display a list of doctors with their availability status for admin management.
     */
    public function availability()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }
        
        $doctors = Doctor::with(['user', 'department.hospital'])
            ->orderBy('is_available', 'desc')
            ->get();
            
        return view('doctors.availability', compact('doctors'));
    }
    
    /**
     * Toggle the availability status of a doctor.
     */
    public function toggleAvailability(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        try {
            $doctor = Doctor::with('user')->findOrFail($id);
            $doctor->is_available = !$doctor->is_available;
            $doctor->save();
            
            $status = $doctor->is_available ? 'available' : 'unavailable';
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'is_available' => $doctor->is_available,
                    'message' => "Dr. {$doctor->user->name} is now {$status}"
                ]);
            }
            
            return redirect()->route('doctors.availability')
                ->with('success', "Dr. {$doctor->user->name} is now {$status}");
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error updating doctor availability: {$e->getMessage()}"
                ], 500);
            }
            
            return redirect()->route('doctors.availability')
                ->with('error', "Error updating doctor availability: {$e->getMessage()}");
        }
    }
}
