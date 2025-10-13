<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-user-nurse mr-2"></i>
                    Staff Dashboard
                </h2>
                <p class="text-sm opacity-90 mt-1">Welcome back, {{ $user->name }}!</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('triage.create') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>New Triage
                </a>
                <a href="{{ route('triage.queue') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-list mr-2"></i>Triage Queue
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-day text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Appointments</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['total_appointments_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed Today</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['completed_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-hourglass-half text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Triage</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['pending_triage'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Critical Cases</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['critical_cases'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Critical Patients Alert -->
            @if($data['critical_patients']->count() > 0)
            <div class="bg-red-50 border border-red-200 rounded-lg card-shadow">
                <div class="p-6 border-b border-red-200">
                    <h3 class="text-lg font-medium text-red-900">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Critical Patients - Immediate Attention Required
                    </h3>
                </div>
                <div class="p-6">
                    @foreach($data['critical_patients'] as $triage)
                        <div class="flex items-center justify-between py-3 border-b border-red-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-red-900">{{ $triage->patient->user->name }}</p>
                                    <p class="text-sm text-red-700">{{ $triage->chief_complaint }}</p>
                                    <p class="text-xs text-red-600">Priority Score: {{ $triage->priority_score }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('triage.show', $triage) }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Today's Appointments -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-calendar-alt mr-2 text-medical-primary"></i>
                            Today's Appointments
                        </h3>
                        <a href="{{ route('appointments.index') }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                            View All
                        </a>
                    </div>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @forelse($data['todays_appointments'] as $appointment)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-medical-primary rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->user->name }}</p>
                                    <p class="text-sm text-gray-500">Dr. {{ $appointment->doctor->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $appointment->appointment_date->format('g:i A') }} - Priority: {{ $appointment->priority_score }}</p>
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
                            <p class="text-gray-500">No appointments scheduled for today</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pending Triage -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-stethoscope mr-2 text-medical-primary"></i>
                            Pending Triage Assessments
                        </h3>
                        <a href="{{ route('triage.index') }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                            View All
                        </a>
                    </div>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @forelse($data['pending_triage'] as $triage)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $triage->patient->user->name }}</p>
                                    <p class="text-sm text-gray-500">Waiting for assessment</p>
                                    <p class="text-xs text-gray-400">Arrived: {{ $triage->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('triage.create', ['patient_id' => $triage->patient_id]) }}" 
                                   class="bg-medical-primary text-white px-3 py-1 rounded text-sm hover:bg-medical-secondary">
                                    Start Triage
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No pending triage assessments</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-bolt mr-2 text-medical-primary"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('triage.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-plus-circle text-2xl text-medical-primary mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">New Triage</span>
                    </a>
                    <a href="{{ route('appointments.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-calendar-plus text-2xl text-medical-primary mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Book Appointment</span>
                    </a>
                    <a href="{{ route('patients.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user-plus text-2xl text-medical-primary mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Register Patient</span>
                    </a>
                    <a href="{{ route('triage.queue') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-list-ol text-2xl text-medical-primary mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Triage Queue</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
