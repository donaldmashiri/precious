<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Triage Management
                </h2>
                <p class="text-sm opacity-90 mt-1">Patient triage assessments and priority management</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('triage.create') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>New Assessment
                </a>
                <a href="{{ route('triage.queue') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                    <i class="fas fa-list mr-2"></i>Triage Queue
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-clipboard-list mr-2 text-medical-primary"></i>
                    All Triage Assessments
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Complaint</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessed By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($triageAssessments as $triage)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-medical-primary rounded-lg flex items-center justify-center text-white">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $triage->patient->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $triage->patient->patient_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($triage->urgency_level === 'critical') bg-red-100 text-red-800
                                        @elseif($triage->urgency_level === 'urgent') bg-orange-100 text-orange-800
                                        @elseif($triage->urgency_level === 'semi_urgent') bg-yellow-100 text-yellow-800
                                        @elseif($triage->urgency_level === 'standard') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $triage->urgency_level)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $triage->priority_score }}</div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="h-2 rounded-full 
                                            @if($triage->priority_score >= 80) bg-red-600
                                            @elseif($triage->priority_score >= 60) bg-orange-500
                                            @elseif($triage->priority_score >= 40) bg-yellow-500
                                            @else bg-green-500 @endif" 
                                            style="width: {{ $triage->priority_score }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($triage->chief_complaint, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $triage->assessor->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $triage->assessed_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $triage->assessed_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('triage.show', $triage) }}" class="text-medical-primary hover:text-medical-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('triage.edit', $triage) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No triage assessments found</p>
                                    <a href="{{ route('triage.create') }}" class="text-medical-primary hover:text-medical-secondary mt-2 inline-block">
                                        Create your first assessment
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($triageAssessments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $triageAssessments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
