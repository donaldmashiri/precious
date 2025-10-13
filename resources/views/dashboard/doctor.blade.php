<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-user-md mr-2"></i>
                    Doctor Dashboard
                </h2>
                <p class="text-sm opacity-90 mt-1">Welcome back, {{ $user->name }}!</p>
                @if($doctor)
                    <p class="text-xs opacity-75">{{ $doctor->specialization }} - {{ $doctor->department->name }}</p>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('appointments.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-calendar mr-2"></i>My Schedule
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Patients Today</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['total_patients_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['completed_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['pending_today'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Today's Schedule -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-calendar-day mr-2 text-medical-primary"></i>
                        Today's Schedule
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @forelse($data['todays_appointments'] as $appointment)
                        <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white
                                    @if($appointment->status === 'completed') bg-green-500
                                    @elseif($appointment->status === 'in_progress') bg-blue-500
                                    @elseif($appointment->status === 'confirmed') bg-yellow-500
                                    @else bg-gray-500 @endif">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->reason_for_visit }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $appointment->appointment_date->format('g:i A') }}
                                        @if($appointment->priority_score > 70)
                                            <span class="ml-2 text-red-600 font-medium">High Priority</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($appointment->status === 'scheduled')
                                    <form method="POST" action="{{ route('appointments.start', $appointment) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                            Start
                                        </button>
                                    </form>
                                @elseif($appointment->status === 'in_progress')
                                    <form method="POST" action="{{ route('appointments.complete', $appointment) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                            Complete
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($appointment->status === 'completed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                @endif
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

            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-calendar-alt mr-2 text-medical-primary"></i>
                            Upcoming Appointments
                        </h3>
                        <a href="{{ route('appointments.index') }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                            View All
                        </a>
                    </div>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @forelse($data['upcoming_appointments'] as $appointment)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-medical-primary rounded-lg flex items-center justify-center text-white text-sm">
                                    {{ $appointment->appointment_date->format('j') }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->reason_for_visit }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $appointment->appointment_date->format('M j, Y - g:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('appointments.show', $appointment) }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-plus text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Triage Updates -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-stethoscope mr-2 text-medical-primary"></i>
                        Recent Triage Updates
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($data['pending_triage'] as $triage)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm
                                    @if($triage->urgency_level === 'critical') bg-red-500
                                    @elseif($triage->urgency_level === 'urgent') bg-orange-500
                                    @elseif($triage->urgency_level === 'semi_urgent') bg-yellow-500
                                    @else bg-blue-500 @endif">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $triage->patient->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $triage->urgency_level)) }} - Score: {{ $triage->priority_score }}</p>
                                    <p class="text-xs text-gray-400">{{ $triage->assessed_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('triage.show', $triage) }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No recent triage updates</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Doctor Information -->
            @if($doctor)
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-id-badge mr-2 text-medical-primary"></i>
                        Doctor Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Specialization</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->specialization }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->department->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">License Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->license_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Working Hours</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $doctor->shift_start ? \Carbon\Carbon::parse($doctor->shift_start)->format('g:i A') : 'Not set' }} - 
                                {{ $doctor->shift_end ? \Carbon\Carbon::parse($doctor->shift_end)->format('g:i A') : 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max Patients/Day</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->max_patients_per_day }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
