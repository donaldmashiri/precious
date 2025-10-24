<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-user-md mr-2"></i>
                    Doctor Profile
                </h2>
                <p class="text-sm opacity-90 mt-1">View doctor details and information</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('doctors.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Doctors
                </a>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('doctors.availability') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-toggle-on mr-2"></i>Manage Availability
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row">
                        <!-- Doctor Info -->
                        <div class="md:w-1/3 p-4">
                            <div class="text-center mb-6">
                                <div class="h-32 w-32 rounded-full bg-medical-light mx-auto flex items-center justify-center">
                                    <i class="fas fa-user-md text-5xl text-medical-primary"></i>
                                </div>
                                <h3 class="text-xl font-semibold mt-4">Dr. {{ $doctor->user->name }}</h3>
                                <p class="text-gray-600">{{ $doctor->specialization }}</p>
                                <div class="mt-2">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $doctor->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $doctor->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="mb-3">
                                    <span class="text-gray-500 text-sm">License Number:</span>
                                    <p class="font-medium">{{ $doctor->license_number }}</p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-gray-500 text-sm">Years of Experience:</span>
                                    <p class="font-medium">{{ $doctor->years_of_experience }} years</p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-gray-500 text-sm">Department:</span>
                                    <p class="font-medium">{{ $doctor->department->name ?? 'Not Assigned' }}</p>
                                </div>
                                <div class="mb-3">
                                    <span class="text-gray-500 text-sm">Consultation Fee:</span>
                                    <p class="font-medium">${{ number_format($doctor->consultation_fee, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Schedule & Details -->
                        <div class="md:w-2/3 p-4 md:border-l">
                            <h4 class="text-lg font-medium mb-4">
                                <i class="fas fa-calendar-alt text-medical-primary mr-2"></i>
                                Schedule Information
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h5 class="font-medium mb-2">Available Days</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($doctor->available_days ?? [] as $day)
                                            <span class="px-2 py-1 bg-medical-light text-medical-primary text-xs rounded-full">
                                                {{ ucfirst($day) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h5 class="font-medium mb-2">Working Hours</h5>
                                    <p>
                                        <span class="text-gray-600">From:</span> 
                                        <span class="font-medium">{{ $doctor->shift_start ? $doctor->shift_start->format('h:i A') : 'Not set' }}</span>
                                    </p>
                                    <p>
                                        <span class="text-gray-600">To:</span> 
                                        <span class="font-medium">{{ $doctor->shift_end ? $doctor->shift_end->format('h:i A') : 'Not set' }}</span>
                                    </p>
                                </div>
                            </div>

                            <h4 class="text-lg font-medium mb-4">
                                <i class="fas fa-user-graduate text-medical-primary mr-2"></i>
                                Qualifications
                            </h4>
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <p>{{ $doctor->qualifications ?? 'No qualifications listed' }}</p>
                            </div>

                            <h4 class="text-lg font-medium mb-4">
                                <i class="fas fa-info-circle text-medical-primary mr-2"></i>
                                Additional Information
                            </h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="mb-2">
                                    <span class="text-gray-600">Maximum Patients Per Day:</span> 
                                    <span class="font-medium">{{ $doctor->max_patients_per_day }}</span>
                                </p>
                                <p>
                                    <span class="text-gray-600">Contact Email:</span> 
                                    <span class="font-medium">{{ $doctor->user->email }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
