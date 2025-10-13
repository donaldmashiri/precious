<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hospital;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\TriageAssessment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Hospitals
        $hospital1 = Hospital::create([
            'name' => 'City General Hospital',
            'code' => 'CGH001',
            'address' => '123 Main Street, Downtown City',
            'phone' => '+1-555-0100',
            'email' => 'info@citygeneral.com',
            'type' => 'public',
            'bed_capacity' => 500,
            'is_active' => true,
        ]);

        $hospital2 = Hospital::create([
            'name' => 'St. Mary\'s Medical Center',
            'code' => 'SMC002',
            'address' => '456 Oak Avenue, Medical District',
            'phone' => '+1-555-0200',
            'email' => 'contact@stmarys.com',
            'type' => 'private',
            'bed_capacity' => 300,
            'is_active' => true,
        ]);

        // Create Departments
        $departments = [
            ['name' => 'Emergency Medicine', 'code' => 'EM', 'hospital_id' => $hospital1->id],
            ['name' => 'Cardiology', 'code' => 'CARD', 'hospital_id' => $hospital1->id],
            ['name' => 'Pediatrics', 'code' => 'PED', 'hospital_id' => $hospital1->id],
            ['name' => 'Orthopedics', 'code' => 'ORTHO', 'hospital_id' => $hospital1->id],
            ['name' => 'Internal Medicine', 'code' => 'IM', 'hospital_id' => $hospital2->id],
            ['name' => 'Surgery', 'code' => 'SURG', 'hospital_id' => $hospital2->id],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@healthcare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1-555-0001',
            'is_active' => true,
            'hospital_id' => $hospital1->id,
        ]);

        // Create Doctor Users and Doctor profiles
        $doctorUsers = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1001',
                'specialization' => 'Emergency Medicine',
                'department_id' => 1,
                'license_number' => 'MD123456',
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1002',
                'specialization' => 'Cardiology',
                'department_id' => 2,
                'license_number' => 'MD123457',
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1003',
                'specialization' => 'Pediatrics',
                'department_id' => 3,
                'license_number' => 'MD123458',
            ],
        ];

        foreach ($doctorUsers as $doctorData) {
            $user = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('password'),
                'role' => $doctorData['role'],
                'phone' => $doctorData['phone'],
                'is_active' => true,
                'hospital_id' => $hospital1->id,
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'license_number' => $doctorData['license_number'],
                'specialization' => $doctorData['specialization'],
                'department_id' => $doctorData['department_id'],
                'available_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'shift_start' => '08:00',
                'shift_end' => '17:00',
                'max_patients_per_day' => 20,
                'consultation_fee' => 150.00,
                'is_available' => true,
            ]);
        }

        // Create Nurse Users
        $nurseUsers = [
            [
                'name' => 'Nurse Jennifer Adams',
                'email' => 'jennifer.adams@healthcare.com',
                'role' => 'nurse',
                'phone' => '+1-555-2001',
            ],
            [
                'name' => 'Nurse Robert Wilson',
                'email' => 'robert.wilson@healthcare.com',
                'role' => 'nurse',
                'phone' => '+1-555-2002',
            ],
        ];

        foreach ($nurseUsers as $nurseData) {
            User::create([
                'name' => $nurseData['name'],
                'email' => $nurseData['email'],
                'password' => Hash::make('password'),
                'role' => $nurseData['role'],
                'phone' => $nurseData['phone'],
                'is_active' => true,
                'hospital_id' => $hospital1->id,
            ]);
        }

        // Create Patient Users and Patient profiles
        $patientUsers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+1-555-3001',
                'date_of_birth' => '1985-06-15',
                'gender' => 'male',
                'blood_type' => 'O+',
                'patient_number' => 'P001001',
            ],
            [
                'name' => 'Mary Johnson',
                'email' => 'mary.johnson@email.com',
                'phone' => '+1-555-3002',
                'date_of_birth' => '1990-03-22',
                'gender' => 'female',
                'blood_type' => 'A+',
                'patient_number' => 'P001002',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@email.com',
                'phone' => '+1-555-3003',
                'date_of_birth' => '1978-11-08',
                'gender' => 'male',
                'blood_type' => 'B-',
                'patient_number' => 'P001003',
            ],
        ];

        foreach ($patientUsers as $patientData) {
            $user = User::create([
                'name' => $patientData['name'],
                'email' => $patientData['email'],
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => $patientData['phone'],
                'date_of_birth' => $patientData['date_of_birth'],
                'gender' => $patientData['gender'],
                'address' => '123 Patient Street, City',
                'emergency_contact_name' => 'Emergency Contact',
                'emergency_contact_phone' => '+1-555-9999',
                'is_active' => true,
            ]);

            Patient::create([
                'user_id' => $user->id,
                'patient_number' => $patientData['patient_number'],
                'blood_type' => $patientData['blood_type'],
                'height' => rand(150, 190),
                'weight' => rand(50, 100),
                'medical_history' => 'No significant medical history',
                'allergies' => 'None known',
            ]);
        }

        // Create sample appointments
        $patients = Patient::all();
        $doctors = Doctor::all();

        foreach ($patients as $index => $patient) {
            $doctor = $doctors->random();
            
            Appointment::create([
                'appointment_number' => 'APT-' . strtoupper(uniqid()),
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'hospital_id' => $doctor->department->hospital_id,
                'department_id' => $doctor->department_id,
                'appointment_date' => now()->addDays(rand(1, 30)),
                'status' => 'scheduled',
                'type' => 'consultation',
                'reason_for_visit' => 'Regular checkup',
                'consultation_fee' => $doctor->consultation_fee,
                'priority_score' => rand(30, 70),
            ]);
        }

        $this->command->info('Healthcare system seeded successfully!');
    }
}
