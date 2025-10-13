<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-user mr-2"></i>
                    Patient Profile
                </h2>
                <p class="text-sm opacity-90 mt-1">{{ $patient->user->name }} - {{ $patient->patient_number }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('patients.edit', $patient) }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
                <a href="{{ route('patients.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-id-card mr-2 text-medical-primary"></i>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $patient->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Patient Number</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $patient->patient_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $patient->user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $patient->user->phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $patient->user->date_of_birth ? $patient->user->date_of_birth->format('F j, Y') : 'Not specified' }}
                                    @if($patient->user->date_of_birth)
                                        <span class="text-gray-500">({{ $patient->user->date_of_birth->age }} years old)</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gender</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($patient->user->gender ?? 'Not specified') }}</p>
                            </div>
                        </div>
                        @if($patient->user->address)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $patient->user->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-heartbeat mr-2 text-medical-primary"></i>
                            Medical Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($patient->blood_type)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $patient->blood_type }}
                                        </span>
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">BMI</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($patient->bmi)
                                        {{ $patient->bmi }}
                                        @if($patient->height && $patient->weight)
                                            <span class="text-gray-500">({{ $patient->height }}cm, {{ $patient->weight }}kg)</span>
                                        @endif
                                    @else
                                        Not calculated
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($patient->medical_history)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Medical History</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $patient->medical_history }}</p>
                        </div>
                        @endif

                        @if($patient->allergies)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Allergies</label>
                            <p class="mt-1 text-sm text-gray-900 bg-red-50 p-3 rounded-lg border border-red-200">{{ $patient->allergies }}</p>
                        </div>
                        @endif

                        @if($patient->current_medications)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Current Medications</label>
                            <p class="mt-1 text-sm text-gray-900 bg-blue-50 p-3 rounded-lg border border-blue-200">{{ $patient->current_medications }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-calendar-alt mr-2 text-medical-primary"></i>
                                Recent Appointments
                            </h3>
                            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                                Book New Appointment
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @forelse($patient->appointments->take(5) as $appointment)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white
                                        @if($appointment->status === 'completed') bg-green-500
                                        @elseif($appointment->status === 'in_progress') bg-blue-500
                                        @elseif($appointment->status === 'confirmed') bg-yellow-500
                                        @else bg-gray-500 @endif">
                                        <i class="fas fa-calendar text-sm"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Dr. {{ $appointment->doctor->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $appointment->appointment_date->format('M d, Y g:i A') }}</p>
                                        <p class="text-xs text-gray-400">{{ $appointment->reason_for_visit }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($appointment->status === 'completed') bg-green-100 text-green-800
                                        @elseif($appointment->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($appointment->status === 'confirmed') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No appointments found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Emergency Contact -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-phone mr-2 text-medical-primary"></i>
                            Emergency Contact
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $patient->user->emergency_contact_name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $patient->user->emergency_contact_phone ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Insurance Information -->
                @if($patient->insurance_provider)
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-shield-alt mr-2 text-medical-primary"></i>
                            Insurance
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Provider</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $patient->insurance_provider }}</p>
                        </div>
                        @if($patient->insurance_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Policy Number</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $patient->insurance_number }}</p>
                        </div>
                        @endif
                        @if($patient->insurance_expiry)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $patient->insurance_expiry->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-bolt mr-2 text-medical-primary"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="w-full flex items-center justify-center px-4 py-2 bg-medical-primary text-white rounded-lg hover:bg-medical-secondary transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Book Appointment
                        </a>
                        <a href="{{ route('triage.create', ['patient_id' => $patient->id]) }}" class="w-full flex items-center justify-center px-4 py-2 border border-medical-primary text-medical-primary rounded-lg hover:bg-medical-primary hover:text-white transition-colors">
                            <i class="fas fa-stethoscope mr-2"></i>
                            Triage Assessment
                        </a>
                        <a href="{{ route('patients.edit', $patient) }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-chart-bar mr-2 text-medical-primary"></i>
                            Statistics
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Appointments</span>
                            <span class="text-sm font-medium text-gray-900">{{ $patient->appointments->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Completed</span>
                            <span class="text-sm font-medium text-gray-900">{{ $patient->appointments->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Upcoming</span>
                            <span class="text-sm font-medium text-gray-900">{{ $patient->appointments->where('status', 'scheduled')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Triage Assessments</span>
                            <span class="text-sm font-medium text-gray-900">{{ $patient->triageAssessments->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
