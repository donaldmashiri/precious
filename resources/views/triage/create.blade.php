<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-stethoscope mr-2"></i>
                    New Triage Assessment
                </h2>
                <p class="text-sm opacity-90 mt-1">Assess patient priority and urgency</p>
            </div>
            <a href="{{ route('triage.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Triage
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user-injured mr-2 text-medical-primary"></i>
                    Patient Triage Assessment
                </h3>
                @if($patient)
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Patient: {{ $patient->user->name }}</p>
                                <p class="text-sm text-blue-700">Patient Number: {{ $patient->patient_number }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('triage.store') }}" class="p-6">
                @csrf

                @if($patient)
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                @endif
                @if($appointment)
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                @endif

                <!-- Patient Selection (if no patient specified) -->
                @if(!$patient)
                <div class="mb-6">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-injured mr-1"></i>Patient *
                    </label>
                    <select name="patient_id" id="patient_id" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        <option value="">Select Patient</option>
                        @foreach(\App\Models\Patient::with('user')->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->user->name }} - {{ $p->patient_number }}</option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Urgency Level -->
                    <div>
                        <label for="urgency_level" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Urgency Level *
                        </label>
                        <select name="urgency_level" id="urgency_level" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">Select Urgency Level</option>
                            <option value="critical" class="text-red-600">Critical - Life threatening</option>
                            <option value="urgent" class="text-orange-600">Urgent - Serious condition</option>
                            <option value="semi_urgent" class="text-yellow-600">Semi-urgent - Moderate condition</option>
                            <option value="standard" class="text-blue-600">Standard - Routine care</option>
                            <option value="non_urgent" class="text-green-600">Non-urgent - Minor condition</option>
                        </select>
                        @error('urgency_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pain Scale -->
                    <div>
                        <label for="pain_scale" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-thermometer-half mr-1"></i>Pain Scale (0-10)
                        </label>
                        <select name="pain_scale" id="pain_scale" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">No pain reported</option>
                            @for($i = 0; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} - @if($i == 0) No pain @elseif($i <= 3) Mild @elseif($i <= 6) Moderate @elseif($i <= 9) Severe @else Worst possible @endif</option>
                            @endfor
                        </select>
                        @error('pain_scale')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Chief Complaint -->
                <div class="mt-6">
                    <label for="chief_complaint" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-medical mr-1"></i>Chief Complaint *
                    </label>
                    <textarea name="chief_complaint" id="chief_complaint" rows="3" required
                              placeholder="Primary reason for the patient's visit..."
                              class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('chief_complaint') }}</textarea>
                    @error('chief_complaint')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Symptoms Description -->
                <div class="mt-6">
                    <label for="symptoms_description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-list-ul mr-1"></i>Symptoms Description *
                    </label>
                    <textarea name="symptoms_description" id="symptoms_description" rows="4" required
                              placeholder="Detailed description of symptoms..."
                              class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('symptoms_description') }}</textarea>
                    @error('symptoms_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vital Signs -->
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        <i class="fas fa-heartbeat mr-2 text-medical-primary"></i>Vital Signs
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="blood_pressure" class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure</label>
                            <input type="text" name="vital_signs[blood_pressure]" id="blood_pressure" 
                                   placeholder="120/80" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="heart_rate" class="block text-sm font-medium text-gray-700 mb-1">Heart Rate (BPM)</label>
                            <input type="number" name="vital_signs[heart_rate]" id="heart_rate" 
                                   placeholder="72" min="30" max="200" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="temperature" class="block text-sm font-medium text-gray-700 mb-1">Temperature (°C)</label>
                            <input type="number" name="vital_signs[temperature]" id="temperature" 
                                   placeholder="36.5" step="0.1" min="30" max="45" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="respiratory_rate" class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate</label>
                            <input type="number" name="vital_signs[respiratory_rate]" id="respiratory_rate" 
                                   placeholder="16" min="8" max="40" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="oxygen_saturation" class="block text-sm font-medium text-gray-700 mb-1">Oxygen Saturation (%)</label>
                            <input type="number" name="vital_signs[oxygen_saturation]" id="oxygen_saturation" 
                                   placeholder="98" min="70" max="100" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="medical_history_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-history mr-1"></i>Relevant Medical History
                        </label>
                        <textarea name="medical_history_notes" id="medical_history_notes" rows="3"
                                  placeholder="Any relevant medical history..."
                                  class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('medical_history_notes') }}</textarea>
                    </div>

                    <div>
                        <label for="recommended_department" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-1"></i>Recommended Department
                        </label>
                        <select name="recommended_department" id="recommended_department" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">Select Department</option>
                            <option value="Emergency Medicine">Emergency Medicine</option>
                            <option value="Cardiology">Cardiology</option>
                            <option value="Pediatrics">Pediatrics</option>
                            <option value="Orthopedics">Orthopedics</option>
                            <option value="Internal Medicine">Internal Medicine</option>
                            <option value="Surgery">Surgery</option>
                        </select>
                    </div>
                </div>

                <!-- Immediate Attention -->
                <div class="mt-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="requires_immediate_attention" id="requires_immediate_attention" value="1"
                               class="rounded border-gray-300 text-medical-primary focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        <label for="requires_immediate_attention" class="ml-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-exclamation-circle mr-1 text-red-500"></i>
                            Requires immediate medical attention
                        </label>
                    </div>
                </div>

                <!-- Triage Notes -->
                <div class="mt-6">
                    <label for="triage_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i>Triage Notes
                    </label>
                    <textarea name="triage_notes" id="triage_notes" rows="3"
                              placeholder="Additional notes and observations..."
                              class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('triage_notes') }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('triage.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-medical-primary text-white rounded-lg hover:bg-medical-secondary transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Complete Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
