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
        // Clear existing data
        $this->command->info('Clearing existing appointments...');
        Appointment::truncate();
        
        $this->command->info('Clearing existing doctors...');
        Doctor::truncate();
        
        // Create Hospitals if they don't exist
        $hospital1 = Hospital::firstOrCreate(
            ['code' => 'CGH001'],
            [
            'name' => 'City General Hospital',
            'code' => 'CGH001',
            'address' => '123 Main Street, Downtown City',
            'phone' => '+1-555-0100',
            'email' => 'info@citygeneral.com',
            'type' => 'public',
            'bed_capacity' => 500,
            'is_active' => true,
        ]);

        $hospital2 = Hospital::firstOrCreate(
            ['code' => 'SMC002'],
            [
                'name' => 'St. Mary\'s Medical Center',
                'code' => 'SMC002',
                'address' => '456 Oak Avenue, Medical District',
                'phone' => '+1-555-0200',
                'email' => 'contact@stmarys.com',
                'type' => 'private',
                'bed_capacity' => 300,
                'is_active' => true,
            ]
        );

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
            Department::firstOrCreate(
                ['code' => $dept['code'], 'hospital_id' => $dept['hospital_id']],
                $dept
            );
        }

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@healthcare.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@healthcare.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1-555-0001',
                'is_active' => true,
                'hospital_id' => $hospital1->id,
            ]
        );

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
                'is_available' => true,
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1002',
                'specialization' => 'Cardiology',
                'department_id' => 2,
                'license_number' => 'MD123457',
                'is_available' => true,
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1003',
                'specialization' => 'Pediatrics',
                'department_id' => 3,
                'license_number' => 'MD123458',
                'is_available' => false,
            ],
            [
                'name' => 'Dr. James Wilson',
                'email' => 'james.wilson@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1004',
                'specialization' => 'Orthopedics',
                'department_id' => 4,
                'license_number' => 'MD123459',
                'is_available' => true,
            ],
            [
                'name' => 'Dr. Lisa Thompson',
                'email' => 'lisa.thompson@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1005',
                'specialization' => 'Internal Medicine',
                'department_id' => 5,
                'license_number' => 'MD123460',
                'is_available' => true,
            ],
            [
                'name' => 'Dr. Robert Garcia',
                'email' => 'robert.garcia@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1006',
                'specialization' => 'Surgery',
                'department_id' => 6,
                'license_number' => 'MD123461',
                'is_available' => false,
            ],
            [
                'name' => 'Dr. Jennifer Lee',
                'email' => 'jennifer.lee@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1007',
                'specialization' => 'Dermatology',
                'department_id' => 2,
                'license_number' => 'MD123462',
                'is_available' => true,
            ],
            [
                'name' => 'Dr. David Martinez',
                'email' => 'david.martinez@healthcare.com',
                'role' => 'doctor',
                'phone' => '+1-555-1008',
                'specialization' => 'Neurology',
                'department_id' => 1,
                'license_number' => 'MD123463',
                'is_available' => true,
            ],
        ];

        foreach ($doctorUsers as $doctorData) {
            // Determine hospital based on department_id
            $hospitalId = $doctorData['department_id'] <= 4 ? $hospital1->id : $hospital2->id;
            
            $user = User::firstOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'email' => $doctorData['email'],
                    'password' => Hash::make('password'),
                    'role' => $doctorData['role'],
                    'phone' => $doctorData['phone'],
                    'is_active' => true,
                    'hospital_id' => $hospitalId,
                ]
            );

            // Set different working hours and fees based on specialization
            $shiftStart = '08:00';
            $shiftEnd = '17:00';
            $maxPatients = 20;
            $consultationFee = 150.00;
            $availableDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            
            // Customize based on specialization
            switch ($doctorData['specialization']) {
                case 'Emergency Medicine':
                    $shiftStart = '07:00';
                    $shiftEnd = '19:00';
                    $maxPatients = 30;
                    $consultationFee = 200.00;
                    $availableDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    break;
                case 'Cardiology':
                    $shiftStart = '09:00';
                    $shiftEnd = '18:00';
                    $maxPatients = 15;
                    $consultationFee = 250.00;
                    break;
                case 'Pediatrics':
                    $shiftStart = '08:30';
                    $shiftEnd = '16:30';
                    $maxPatients = 25;
                    $consultationFee = 175.00;
                    break;
                case 'Surgery':
                    $shiftStart = '07:30';
                    $shiftEnd = '16:00';
                    $maxPatients = 10;
                    $consultationFee = 300.00;
                    $availableDays = ['monday', 'wednesday', 'friday'];
                    break;
                case 'Internal Medicine':
                    $shiftStart = '08:00';
                    $shiftEnd = '17:00';
                    $maxPatients = 18;
                    $consultationFee = 180.00;
                    $availableDays = ['monday', 'tuesday', 'thursday', 'friday'];
                    break;
                case 'Dermatology':
                    $shiftStart = '09:30';
                    $shiftEnd = '16:00';
                    $maxPatients = 12;
                    $consultationFee = 220.00;
                    $availableDays = ['monday', 'wednesday', 'friday'];
                    break;
                case 'Neurology':
                    $shiftStart = '08:30';
                    $shiftEnd = '17:30';
                    $maxPatients = 14;
                    $consultationFee = 275.00;
                    $availableDays = ['tuesday', 'wednesday', 'thursday', 'friday'];
                    break;
                case 'Orthopedics':
                    $shiftStart = '08:00';
                    $shiftEnd = '16:00';
                    $maxPatients = 16;
                    $consultationFee = 225.00;
                    break;
            }
            
            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'license_number' => $doctorData['license_number'],
                    'specialization' => $doctorData['specialization'],
                    'department_id' => $doctorData['department_id'],
                    'available_days' => $availableDays,
                    'shift_start' => $shiftStart,
                    'shift_end' => $shiftEnd,
                    'max_patients_per_day' => $maxPatients,
                    'consultation_fee' => $consultationFee,
                    'is_available' => $doctorData['is_available'] ?? true,
                ]
            );
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
            User::firstOrCreate(
                ['email' => $nurseData['email']],
                [
                    'name' => $nurseData['name'],
                    'email' => $nurseData['email'],
                    'password' => Hash::make('password'),
                    'role' => $nurseData['role'],
                    'phone' => $nurseData['phone'],
                    'is_active' => true,
                    'hospital_id' => $hospital1->id,
                ]
            );
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
            $user = User::firstOrCreate(
                ['email' => $patientData['email']],
                [
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
                ]
            );

            Patient::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'patient_number' => $patientData['patient_number'],
                    'blood_type' => $patientData['blood_type'],
                    'height' => rand(150, 190),
                    'weight' => rand(50, 100),
                    'medical_history' => 'No significant medical history',
                    'allergies' => 'None known',
                ]
            );
        }

        // Create sample appointments
        $patients = Patient::all();
        $doctors = Doctor::all();
        $appointmentTypes = ['consultation', 'follow_up', 'emergency', 'routine_checkup'];
        $reasons = [
            'Regular checkup',
            'Persistent headache',
            'Chest pain',
            'Follow-up after surgery',
            'Annual physical',
            'Skin rash',
            'Joint pain',
            'Respiratory issues',
            'Digestive problems',
            'Medication review'
        ];
        
        // Create multiple appointments for each patient
        foreach ($patients as $patient) {
            // Create 2-4 appointments per patient
            $appointmentCount = rand(2, 4);
            
            for ($i = 0; $i < $appointmentCount; $i++) {
                // Only use available doctors for new appointments
                $availableDoctors = $doctors->where('is_available', true);
                if ($availableDoctors->count() > 0) {
                    $doctor = $availableDoctors->random();
                } else {
                    $doctor = $doctors->random(); // Fallback if no available doctors
                }
                
                // Randomize appointment details
                $type = $appointmentTypes[array_rand($appointmentTypes)];
                $reason = $reasons[array_rand($reasons)];
                $daysToAdd = rand(1, 45); // Appointments up to 45 days in the future
                $status = $daysToAdd <= 7 ? 'confirmed' : 'scheduled';
                
                // Calculate priority based on type
                $basePriority = match($type) {
                    'emergency' => 80,
                    'consultation' => 50,
                    'follow_up' => 40,
                    'routine_checkup' => 30,
                    default => 30
                };
                
                // Add some randomness to priority
                $priority = $basePriority + rand(-10, 10);
                
                Appointment::create([
                    'appointment_number' => 'APT-' . strtoupper(uniqid()),
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'hospital_id' => $doctor->department->hospital_id,
                    'department_id' => $doctor->department_id,
                    'appointment_date' => now()->addDays($daysToAdd)->setTime(rand(8, 16), rand(0, 11) * 5, 0),
                    'status' => $status,
                    'type' => $type,
                    'reason_for_visit' => $reason,
                    'consultation_fee' => $doctor->consultation_fee,
                    'priority_score' => $priority,
                ]);
            }
        }

        $this->command->info('Healthcare system seeded successfully!');
    }
}
