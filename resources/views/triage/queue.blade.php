<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-list-ol mr-2"></i>
                    Triage Queue
                </h2>
                <p class="text-sm opacity-90 mt-1">Patient priority queue - {{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('triage.create') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>New Assessment
                </a>
                <a href="{{ route('triage.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-list mr-2"></i>All Assessments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Critical Cases Alert -->
        @if($criticalCases->count() > 0)
        <div class="mb-8 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <strong>{{ $criticalCases->count() }} Critical Patient(s)</strong> require immediate attention!
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Critical Cases -->
        @if($criticalCases->count() > 0)
        <div class="mb-8 bg-white rounded-lg card-shadow border-l-4 border-red-500">
            <div class="p-6 border-b border-gray-200 bg-red-50">
                <h3 class="text-lg font-medium text-red-900">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    CRITICAL - Immediate Attention Required ({{ $criticalCases->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($criticalCases as $triage)
                    <div class="p-6 hover:bg-red-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-red-600 rounded-lg flex items-center justify-center text-white">
                                    <div class="text-center">
                                        <div class="text-xs font-bold">{{ $triage->priority_score }}</div>
                                        <i class="fas fa-exclamation text-lg"></i>
                                    </div>
                                </div>
                                <div class="ml-6">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $triage->patient->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $triage->patient->patient_number }}</p>
                                    <p class="text-sm font-medium text-red-700">{{ $triage->chief_complaint }}</p>
                                    <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                        <span><i class="fas fa-clock mr-1"></i>{{ $triage->assessed_at->diffForHumans() }}</span>
                                        @if($triage->pain_scale)
                                            <span><i class="fas fa-thermometer-half mr-1"></i>Pain: {{ $triage->pain_scale }}/10</span>
                                        @endif
                                        @if($triage->appointment)
                                            <span><i class="fas fa-user-md mr-1"></i>Dr. {{ $triage->appointment->doctor->user->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('triage.show', $triage) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-center">
                                    <i class="fas fa-eye mr-1"></i>View Details
                                </a>
                                @if($triage->appointment)
                                    <a href="{{ route('appointments.show', $triage->appointment) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center">
                                        <i class="fas fa-calendar mr-1"></i>Appointment
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Urgent Cases -->
        @if($urgentCases->count() > 0)
        <div class="mb-8 bg-white rounded-lg card-shadow border-l-4 border-orange-500">
            <div class="p-6 border-b border-gray-200 bg-orange-50">
                <h3 class="text-lg font-medium text-orange-900">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    URGENT - Priority Treatment ({{ $urgentCases->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($urgentCases as $triage)
                    <div class="p-6 hover:bg-orange-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-14 h-14 bg-orange-500 rounded-lg flex items-center justify-center text-white">
                                    <div class="text-center">
                                        <div class="text-xs font-bold">{{ $triage->priority_score }}</div>
                                        <i class="fas fa-exclamation text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-md font-medium text-gray-900">{{ $triage->patient->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $triage->patient->patient_number }}</p>
                                    <p class="text-sm text-orange-700">{{ Str::limit($triage->chief_complaint, 60) }}</p>
                                    <div class="flex items-center mt-1 space-x-3 text-xs text-gray-500">
                                        <span><i class="fas fa-clock mr-1"></i>{{ $triage->assessed_at->diffForHumans() }}</span>
                                        @if($triage->pain_scale)
                                            <span><i class="fas fa-thermometer-half mr-1"></i>Pain: {{ $triage->pain_scale }}/10</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('triage.show', $triage) }}" class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600 transition-colors">
                                    View
                                </a>
                                @if($triage->appointment)
                                    <a href="{{ route('appointments.show', $triage->appointment) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                                        Appointment
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Other Cases -->
        @if($otherCases->count() > 0)
        <div class="bg-white rounded-lg card-shadow border-l-4 border-blue-500">
            <div class="p-6 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-medium text-blue-900">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Standard & Non-Urgent Cases ({{ $otherCases->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($otherCases as $triage)
                    <div class="p-4 hover:bg-blue-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white
                                    @if($triage->urgency_level === 'semi_urgent') bg-yellow-500
                                    @elseif($triage->urgency_level === 'standard') bg-blue-500
                                    @else bg-green-500 @endif">
                                    <div class="text-center">
                                        <div class="text-xs font-bold">{{ $triage->priority_score }}</div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $triage->patient->user->name }}</h4>
                                    <p class="text-xs text-gray-600">{{ $triage->patient->patient_number }}</p>
                                    <p class="text-sm text-gray-700">{{ Str::limit($triage->chief_complaint, 50) }}</p>
                                    <div class="flex items-center mt-1 space-x-3 text-xs text-gray-500">
                                        <span>{{ $triage->assessed_at->diffForHumans() }}</span>
                                        <span class="capitalize">{{ str_replace('_', ' ', $triage->urgency_level) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('triage.show', $triage) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($triage->appointment)
                                    <a href="{{ route('appointments.show', $triage->appointment) }}" class="text-green-600 hover:text-green-800 text-sm">
                                        <i class="fas fa-calendar"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($criticalCases->count() == 0 && $urgentCases->count() == 0 && $otherCases->count() == 0)
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-12 text-center">
                <i class="fas fa-clipboard-check text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Patients in Triage Queue</h3>
                <p class="text-gray-500 mb-6">All patients have been assessed and processed for today.</p>
                <a href="{{ route('triage.create') }}" class="bg-medical-primary text-white px-6 py-2 rounded-lg hover:bg-medical-secondary transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    New Triage Assessment
                </a>
            </div>
        </div>
        @endif

        <!-- Legend -->
        <div class="mt-8 bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-info-circle mr-2 text-medical-primary"></i>
                    Priority Legend
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Critical (90-100)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Urgent (70-89)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Semi-urgent (50-69)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Standard (30-49)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Non-urgent (10-29)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh the page every 30 seconds to keep queue updated
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</x-app-layout>
