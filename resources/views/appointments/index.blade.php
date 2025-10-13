<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Appointments
                </h2>
                <p class="text-sm opacity-90 mt-1">Manage patient appointments and schedules</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('appointments.create') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Book Appointment
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-list mr-2 text-medical-primary"></i>
                    All Appointments
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-medical-primary rounded-lg flex items-center justify-center text-white">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $appointment->patient->patient_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Dr. {{ $appointment->doctor->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->doctor->specialization }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->appointment_date->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($appointment->status === 'completed') bg-green-100 text-green-800
                                        @elseif($appointment->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($appointment->status === 'confirmed') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900 mr-2">{{ $appointment->priority_score }}</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full 
                                                @if($appointment->priority_score >= 80) bg-red-600
                                                @elseif($appointment->priority_score >= 60) bg-orange-500
                                                @elseif($appointment->priority_score >= 40) bg-yellow-500
                                                @else bg-green-500 @endif" 
                                                style="width: {{ $appointment->priority_score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="text-medical-primary hover:text-medical-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($appointment->status === 'scheduled' && (Auth::user()->isStaff() || Auth::user()->isDoctor()))
                                            <form method="POST" action="{{ route('appointments.check-in', $appointment) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Check In">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No appointments found</p>
                                    <a href="{{ route('appointments.create') }}" class="text-medical-primary hover:text-medical-secondary mt-2 inline-block">
                                        Book your first appointment
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($appointments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
