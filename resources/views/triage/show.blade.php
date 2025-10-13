<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Triage Assessment Details
                </h2>
                <p class="text-sm opacity-90 mt-1">Patient: {{ $triage->patient->user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('triage.edit', $triage) }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Assessment
                </a>
                <a href="{{ route('triage.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Main Assessment Details -->
            <div class="space-y-6">
                <!-- Priority & Urgency -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-exclamation-triangle mr-2 text-medical-primary"></i>
                            Priority Assessment
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Urgency Level</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($triage->urgency_level === 'critical') bg-red-100 text-red-800
                                        @elseif($triage->urgency_level === 'urgent') bg-orange-100 text-orange-800
                                        @elseif($triage->urgency_level === 'semi_urgent') bg-yellow-100 text-yellow-800
                                        @elseif($triage->urgency_level === 'standard') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $triage->urgency_level)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority Score</label>
                                <div class="mt-1 flex items-center">
                                    <span class="text-2xl font-bold text-gray-900 mr-3">{{ $triage->priority_score }}</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                                        <div class="h-3 rounded-full 
                                            @if($triage->priority_score >= 80) bg-red-600
                                            @elseif($triage->priority_score >= 60) bg-orange-500
                                            @elseif($triage->priority_score >= 40) bg-yellow-500
                                            @else bg-green-500 @endif" 
                                            style="width: {{ $triage->priority_score }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($triage->requires_immediate_attention)
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                <span class="text-sm font-medium text-red-800">Requires Immediate Medical Attention</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Clinical Information -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-clipboard-list mr-2 text-medical-primary"></i>
                            Clinical Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Chief Complaint</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $triage->chief_complaint }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Symptoms Description</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $triage->symptoms_description }}</p>
                        </div>

                        @if($triage->pain_scale)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pain Scale</label>
                            <div class="mt-1 flex items-center">
                                <span class="text-lg font-semibold text-gray-900 mr-2">{{ $triage->pain_scale }}/10</span>
                                <div class="flex space-x-1">
                                    @for($i = 1; $i <= 10; $i++)
                                        <div class="w-3 h-3 rounded-full 
                                            @if($i <= $triage->pain_scale) 
                                                @if($triage->pain_scale <= 3) bg-green-500
                                                @elseif($triage->pain_scale <= 6) bg-yellow-500
                                                @elseif($triage->pain_scale <= 9) bg-orange-500
                                                @else bg-red-500 @endif
                                            @else bg-gray-200 @endif"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($triage->medical_history_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Medical History Notes</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $triage->medical_history_notes }}</p>
                        </div>
                        @endif

                        @if($triage->triage_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Triage Notes</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $triage->triage_notes }}</p>
                        </div>
                        @endif

                        @if($triage->recommended_department)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Recommended Department</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $triage->recommended_department }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Vital Signs -->
                @if($triage->vital_signs)
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-heartbeat mr-2 text-medical-primary"></i>
                            Vital Signs
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @if(isset($triage->vital_signs['blood_pressure']))
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-tint text-2xl text-red-500 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Blood Pressure</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $triage->vital_signs['blood_pressure'] }}</p>
                            </div>
                            @endif

                            @if(isset($triage->vital_signs['heart_rate']))
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-heartbeat text-2xl text-red-500 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Heart Rate</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $triage->vital_signs['heart_rate'] }} BPM</p>
                            </div>
                            @endif

                            @if(isset($triage->vital_signs['temperature']))
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-thermometer-half text-2xl text-orange-500 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Temperature</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $triage->vital_signs['temperature'] }}°C</p>
                            </div>
                            @endif

                            @if(isset($triage->vital_signs['respiratory_rate']))
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-lungs text-2xl text-blue-500 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Respiratory Rate</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $triage->vital_signs['respiratory_rate'] }}/min</p>
                            </div>
                            @endif

                            @if(isset($triage->vital_signs['oxygen_saturation']))
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i class="fas fa-wind text-2xl text-green-500 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Oxygen Saturation</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $triage->vital_signs['oxygen_saturation'] }}%</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Patient & Assessment Overview -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-user-check mr-2 text-medical-primary"></i>
                            Patient & Assessment Overview
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Patient Info -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-user mr-2 text-medical-primary"></i>Patient Details
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $triage->patient->user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Patient Number</label>
                                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $triage->patient->patient_number }}</p>
                                    </div>
                                    @if($triage->patient->blood_type)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $triage->patient->blood_type }}
                                            </span>
                                        </p>
                                    </div>
                                    @endif
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Age</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $triage->patient->user->date_of_birth ? \Carbon\Carbon::parse($triage->patient->user->date_of_birth)->age : 'Not specified' }} years</p>
                                    </div>
                                    @if($triage->patient->user->phone)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $triage->patient->user->phone }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Assessment Info -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-clipboard-check mr-2 text-medical-primary"></i>Assessment Details
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Assessed By</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $triage->assessor->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Assessment Date</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $triage->assessed_at->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Assessment Time</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $triage->assessed_at->format('g:i A') }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Time Since Assessment</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $triage->assessed_at->diffForHumans() }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Priority Level</label>
                                        <p class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($triage->urgency_level === 'critical') bg-red-100 text-red-800
                                                @elseif($triage->urgency_level === 'urgent') bg-orange-100 text-orange-800
                                                @elseif($triage->urgency_level === 'semi_urgent') bg-yellow-100 text-yellow-800
                                                @elseif($triage->urgency_level === 'standard') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $triage->urgency_level)) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical History & Allergies -->
                @if($triage->patient->medical_history || $triage->patient->allergies || $triage->patient->current_medications)
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-history mr-2 text-medical-primary"></i>
                            Patient Medical Background
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($triage->patient->medical_history)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Medical History</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $triage->patient->medical_history }}</p>
                        </div>
                        @endif
                        
                        @if($triage->patient->allergies)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Known Allergies</label>
                            <p class="mt-1 text-sm text-gray-900 bg-red-50 p-3 rounded-lg border border-red-200">{{ $triage->patient->allergies }}</p>
                        </div>
                        @endif
                        
                        @if($triage->patient->current_medications)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Medications</label>
                            <p class="mt-1 text-sm text-gray-900 bg-blue-50 p-3 rounded-lg border border-blue-200">{{ $triage->patient->current_medications }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Related Appointment -->
                @if($triage->appointment)
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-calendar mr-2 text-medical-primary"></i>
                            Related Appointment
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Appointment Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $triage->appointment->appointment_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Doctor</label>
                            <p class="mt-1 text-sm text-gray-900">Dr. {{ $triage->appointment->doctor->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $triage->appointment->appointment_date->format('M d, Y g:i A') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('appointments.show', $triage->appointment) }}" class="inline-flex items-center text-medical-primary hover:text-medical-secondary">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                View Appointment
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-cogs mr-2 text-medical-primary"></i>
                            Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('triage.edit', $triage) }}" class="w-full flex items-center justify-center px-4 py-2 border border-medical-primary text-medical-primary rounded-lg hover:bg-medical-primary hover:text-white transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Assessment
                        </a>
                        @if(!$triage->appointment)
                        <a href="{{ route('appointments.create', ['patient_id' => $triage->patient_id]) }}" class="w-full flex items-center justify-center px-4 py-2 bg-medical-primary text-white rounded-lg hover:bg-medical-secondary transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Book Appointment
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
