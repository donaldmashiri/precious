<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-user-injured mr-2"></i>
                    Patient Dashboard
                </h2>
                <p class="text-sm opacity-90 mt-1">Welcome back, {{ $user->name }}!</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('appointments.create') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Book Appointment
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
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Upcoming</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['upcoming_appointments']->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-history text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Recent Visits</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['recent_appointments']->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-stethoscope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Triage Records</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['triage_assessments']->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-bell text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Notifications</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['notifications']->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                <div class="p-6">
                    @forelse($data['upcoming_appointments'] as $appointment)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-medical-primary rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Dr. {{ $appointment->doctor->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->department->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $appointment->appointment_date->format('M d, Y - g:i A') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($appointment->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No upcoming appointments</p>
                            <a href="{{ route('appointments.create') }}" class="text-medical-primary hover:text-medical-secondary mt-2 inline-block">
                                Book your first appointment
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-bell mr-2 text-medical-primary"></i>
                        Recent Notifications
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($data['notifications'] as $notification)
                        <div class="flex items-start py-3 border-b border-gray-100 last:border-b-0">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm
                                @if($notification->priority === 'critical') bg-red-500
                                @elseif($notification->priority === 'high') bg-orange-500
                                @elseif($notification->priority === 'medium') bg-blue-500
                                @else bg-green-500 @endif">
                                <i class="fas fa-info"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No new notifications</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Patient Information Card -->
        @if($patient)
        <div class="mt-8 bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-id-card mr-2 text-medical-primary"></i>
                    Patient Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $patient->patient_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $patient->blood_type ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->emergency_contact_name ?? 'Not specified' }}</p>
                        @if($user->emergency_contact_phone)
                            <p class="text-xs text-gray-500">{{ $user->emergency_contact_phone }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('profile.edit') }}" class="bg-medical-primary text-white px-4 py-2 rounded-lg hover:bg-medical-secondary transition-colors">
                        <i class="fas fa-edit mr-2"></i>Update Information
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
