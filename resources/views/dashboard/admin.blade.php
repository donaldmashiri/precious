<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-cog mr-2"></i>
                    Administrator Dashboard
                </h2>
                <p class="text-sm opacity-90 mt-1">System Overview and Management</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('hospitals.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-hospital mr-2"></i>Manage Hospitals
                </a>
                <a href="{{ route('doctors.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-user-md mr-2"></i>Manage Doctors
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- System Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Patients</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['total_patients'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-user-md text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Doctors</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['total_doctors'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-hospital text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hospitals</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['total_hospitals'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
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
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Appointments</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $data['statistics']['pending_appointments'] }}</p>
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
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- System Alerts -->
            @if($data['system_alerts']->count() > 0)
            <div class="bg-red-50 border border-red-200 rounded-lg card-shadow">
                <div class="p-6 border-b border-red-200">
                    <h3 class="text-lg font-medium text-red-900">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Critical System Alerts
                    </h3>
                </div>
                <div class="p-6">
                    @foreach($data['system_alerts'] as $alert)
                        <div class="flex items-center justify-between py-3 border-b border-red-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-red-900">Critical Patient: {{ $alert->patient->user->name }}</p>
                                    <p class="text-sm text-red-700">{{ $alert->chief_complaint }}</p>
                                    <p class="text-xs text-red-600">Priority Score: {{ $alert->priority_score }} - {{ $alert->assessed_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('triage.show', $alert) }}" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Hospitals Overview -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-hospital mr-2 text-medical-primary"></i>
                            Hospitals Overview
                        </h3>
                        <a href="{{ route('hospitals.index') }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                            Manage All
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($data['hospitals'] as $hospital)
                        <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-medical-primary rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-hospital"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $hospital->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $hospital->departments->count() }} Departments</p>
                                    <p class="text-xs text-gray-400">Capacity: {{ $hospital->bed_capacity }} beds</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($hospital->is_active) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $hospital->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-hospital text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No hospitals registered</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Patient Registrations -->
            <div class="bg-white rounded-lg card-shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-user-plus mr-2 text-medical-primary"></i>
                            Recent Patient Registrations
                        </h3>
                        <a href="{{ route('patients.index') }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                            View All
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($data['recent_registrations'] as $patient)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $patient->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $patient->patient_number }}</p>
                                    <p class="text-xs text-gray-400">Registered {{ $patient->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('patients.show', $patient) }}" class="text-medical-primary hover:text-medical-secondary text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-user-plus text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No recent registrations</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Management Actions -->
        <div class="mt-8 bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-tools mr-2 text-medical-primary"></i>
                    System Management
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('hospitals.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-plus-circle text-2xl text-medical-primary mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Add Hospital</span>
                    </a>
                    <a href="{{ route('doctors.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user-md text-2xl text-medical-primary mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Add Doctor</span>
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
