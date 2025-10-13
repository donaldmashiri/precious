<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Appointment Details
                </h2>
                <p class="text-sm opacity-90 mt-1">{{ $appointment->appointment_number }}</p>
            </div>
            <div class="flex space-x-3">
                @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                    <a href="{{ route('appointments.edit', $appointment) }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif
                <a href="{{ route('appointments.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Main Content -->
            <div class="space-y-6">
                <!-- Appointment Information -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-info-circle mr-2 text-medical-primary"></i>
                            Appointment Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Appointment Number</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $appointment->appointment_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        @if($appointment->status === 'completed') bg-green-100 text-green-800
                                        @elseif($appointment->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($appointment->status === 'confirmed') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointment->appointment_date->format('l, F j, Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority Score</label>
                                <div class="mt-1 flex items-center">
                                    <span class="text-lg font-semibold text-gray-900 mr-3">{{ $appointment->priority_score }}</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3 max-w-24">
                                        <div class="h-3 rounded-full 
                                            @if($appointment->priority_score >= 80) bg-red-600
                                            @elseif($appointment->priority_score >= 60) bg-orange-500
                                            @elseif($appointment->priority_score >= 40) bg-yellow-500
                                            @else bg-green-500 @endif" 
                                            style="width: {{ $appointment->priority_score }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Consultation Fee</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($appointment->consultation_fee, 2) }}</p>
                            </div>
                        </div>

                        @if($appointment->reason_for_visit)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Reason for Visit</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->reason_for_visit }}</p>
                        </div>
                        @endif

                        @if($appointment->symptoms)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Symptoms</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->symptoms }}</p>
                        </div>
                        @endif

                        @if($appointment->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Triage Assessment -->
                @if($appointment->triageAssessment)
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-stethoscope mr-2 text-medical-primary"></i>
                            Triage Assessment
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Urgency Level</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($appointment->triageAssessment->urgency_level === 'critical') bg-red-100 text-red-800
                                        @elseif($appointment->triageAssessment->urgency_level === 'urgent') bg-orange-100 text-orange-800
                                        @elseif($appointment->triageAssessment->urgency_level === 'semi_urgent') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->triageAssessment->urgency_level === 'standard') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->triageAssessment->urgency_level)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Assessed By</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointment->triageAssessment->assessor->name }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Chief Complaint</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->triageAssessment->chief_complaint }}</p>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('triage.show', $appointment->triageAssessment) }}" class="text-medical-primary hover:text-medical-secondary">
                                <i class="fas fa-external-link-alt mr-1"></i>View Full Assessment
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Timeline -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-history mr-2 text-medical-primary"></i>
                            Appointment Timeline
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-calendar-plus text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Appointment scheduled</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time>{{ $appointment->created_at->format('M d, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                @if($appointment->checked_in_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Patient checked in</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time>{{ $appointment->checked_in_at->format('M d, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                @if($appointment->started_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-play text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Consultation started</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time>{{ $appointment->started_at->format('M d, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                @if($appointment->completed_at)
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check-circle text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Appointment completed</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time>{{ $appointment->completed_at->format('M d, Y g:i A') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Patient & Doctor Information Combined -->
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-users mr-2 text-medical-primary"></i>
                            Patient & Doctor Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Patient Info -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-user mr-2 text-medical-primary"></i>Patient Details
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $appointment->patient->user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Patient Number</label>
                                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $appointment->patient->patient_number }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $appointment->patient->user->phone }}</p>
                                    </div>
                                    @if($appointment->patient->blood_type)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $appointment->patient->blood_type }}
                                            </span>
                                        </p>
                                    </div>
                                    @endif
                                    <div class="pt-2">
                                        <a href="{{ route('patients.show', $appointment->patient) }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                                            <i class="fas fa-external-link-alt mr-1"></i>View Full Patient Profile
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Doctor Info -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-user-md mr-2 text-medical-primary"></i>Doctor Details
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <p class="mt-1 text-sm text-gray-900">Dr. {{ $appointment->doctor->user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Specialization</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $appointment->doctor->specialization }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Department</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $appointment->department->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Hospital</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $appointment->hospital->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if(Auth::user()->isStaff() || Auth::user()->isDoctor())
                <div class="bg-white rounded-lg card-shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-cogs mr-2 text-medical-primary"></i>
                            Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($appointment->status === 'scheduled')
                            <form method="POST" action="{{ route('appointments.check-in', $appointment) }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Check In Patient
                                </button>
                            </form>
                        @endif

                        @if($appointment->status === 'confirmed')
                            <form method="POST" action="{{ route('appointments.start', $appointment) }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-play mr-2"></i>
                                    Start Consultation
                                </button>
                            </form>
                        @endif

                        @if($appointment->status === 'in_progress')
                            <form method="POST" action="{{ route('appointments.complete', $appointment) }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Complete Appointment
                                </button>
                            </form>
                        @endif

                        @if(!$appointment->triageAssessment && in_array($appointment->status, ['scheduled', 'confirmed']))
                            <a href="{{ route('triage.create', ['appointment_id' => $appointment->id]) }}" class="w-full flex items-center justify-center px-4 py-2 border border-medical-primary text-medical-primary rounded-lg hover:bg-medical-primary hover:text-white transition-colors">
                                <i class="fas fa-stethoscope mr-2"></i>
                                Triage Assessment
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
