<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-users mr-2"></i>
                    Patients
                </h2>
                <p class="text-sm opacity-90 mt-1">Manage patient records and information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('patients.create') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>Register Patient
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-list mr-2 text-medical-primary"></i>
                    All Patients
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-medical-primary rounded-lg flex items-center justify-center text-white">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $patient->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ ucfirst($patient->user->gender ?? 'Not specified') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono text-gray-900">{{ $patient->patient_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $patient->user->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $patient->user->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($patient->blood_type)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $patient->blood_type }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">Not specified</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $patient->user->date_of_birth ? \Carbon\Carbon::parse($patient->user->date_of_birth)->age : 'Not specified' }} years
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $patient->appointments->count() }} total</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $patient->appointments->where('status', 'scheduled')->count() }} upcoming
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('patients.show', $patient) }}" class="text-medical-primary hover:text-medical-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="text-green-600 hover:text-green-900" title="Book Appointment">
                                            <i class="fas fa-calendar-plus"></i>
                                        </a>
                                        <a href="{{ route('triage.create', ['patient_id' => $patient->id]) }}" class="text-purple-600 hover:text-purple-900" title="Triage Assessment">
                                            <i class="fas fa-stethoscope"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-user-plus text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No patients registered</p>
                                    <a href="{{ route('patients.create') }}" class="text-medical-primary hover:text-medical-secondary mt-2 inline-block">
                                        Register your first patient
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($patients->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
