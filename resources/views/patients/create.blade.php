<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-user-plus mr-2"></i>
                    Register New Patient
                </h2>
                <p class="text-sm opacity-90 mt-1">Create a new patient profile in the system</p>
            </div>
            <a href="{{ route('patients.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Patients
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-clipboard-list mr-2 text-medical-primary"></i>
                    Patient Registration Form
                </h3>
                <p class="text-sm text-gray-600 mt-1">Please fill in all required information to register the patient</p>
            </div>

            <form method="POST" action="{{ route('patients.store') }}" class="p-6">
                @csrf

                <!-- Personal Information -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        <i class="fas fa-user mr-2 text-medical-primary"></i>Personal Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1"></i>Full Name *
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                   pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1"></i>Email Address *
                            </label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-1"></i>Phone Number *
                            </label>
                            <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}"
                                   pattern="[0-9+\-\s()]+" title="Phone number should only contain numbers, +, -, spaces, and parentheses"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="national_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-1"></i>National ID
                            </label>
                            <input type="text" name="national_id" id="national_id" value="{{ old('national_id') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('national_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-1"></i>Date of Birth *
                            </label>
                            <input type="date" name="date_of_birth" id="date_of_birth" required value="{{ old('date_of_birth') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-venus-mars mr-1"></i>Gender *
                            </label>
                            <select name="gender" id="gender" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>Address *
                        </label>
                        <textarea name="address" id="address" rows="3" required
                                  placeholder="Full address including street, city, state, and postal code"
                                  class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        <i class="fas fa-phone-alt mr-2 text-medical-primary"></i>Emergency Contact
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1"></i>Contact Name *
                            </label>
                            <input type="text" name="emergency_contact_name" id="emergency_contact_name" required value="{{ old('emergency_contact_name') }}"
                                   pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('emergency_contact_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-1"></i>Contact Phone *
                            </label>
                            <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" required value="{{ old('emergency_contact_phone') }}"
                                   pattern="[0-9+\-\s()]+" title="Phone number should only contain numbers, +, -, spaces, and parentheses"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('emergency_contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        <i class="fas fa-heartbeat mr-2 text-medical-primary"></i>Medical Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tint mr-1"></i>Blood Type
                            </label>
                            <select name="blood_type" id="blood_type" class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                                <option value="">Select Blood Type</option>
                                <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                            @error('blood_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-ruler-vertical mr-1"></i>Height (cm)
                            </label>
                            <input type="number" name="height" id="height" min="50" max="250" value="{{ old('height') }}"
                                   placeholder="170"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('height')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-weight mr-1"></i>Weight (kg)
                            </label>
                            <input type="number" name="weight" id="weight" min="10" max="300" value="{{ old('weight') }}"
                                   placeholder="70"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-history mr-1"></i>Medical History
                            </label>
                            <textarea name="medical_history" id="medical_history" rows="4"
                                      placeholder="Any significant medical history, chronic conditions, surgeries, etc."
                                      class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('medical_history') }}</textarea>
                            @error('medical_history')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Allergies
                            </label>
                            <textarea name="allergies" id="allergies" rows="4"
                                      placeholder="Known allergies to medications, foods, environmental factors, etc."
                                      class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('allergies') }}</textarea>
                            @error('allergies')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="current_medications" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-pills mr-1"></i>Current Medications
                        </label>
                        <textarea name="current_medications" id="current_medications" rows="3"
                                  placeholder="List all current medications, dosages, and frequency"
                                  class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('current_medications') }}</textarea>
                        @error('current_medications')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Insurance Information -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        <i class="fas fa-shield-alt mr-2 text-medical-primary"></i>Insurance Information (Optional)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="insurance_provider" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-building mr-1"></i>Insurance Provider
                            </label>
                            <input type="text" name="insurance_provider" id="insurance_provider" value="{{ old('insurance_provider') }}"
                                   placeholder="e.g., Blue Cross, Aetna, Medicare"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('insurance_provider')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="insurance_number" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-badge mr-1"></i>Policy Number
                            </label>
                            <input type="text" name="insurance_number" id="insurance_number" value="{{ old('insurance_number') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('insurance_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="insurance_expiry" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-times mr-1"></i>Expiry Date
                            </label>
                            <input type="date" name="insurance_expiry" id="insurance_expiry" value="{{ old('insurance_expiry') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            @error('insurance_expiry')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('patients.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-medical-primary text-white rounded-lg hover:bg-medical-secondary transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>
                        Register Patient
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Notice -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> A default password "password123" will be assigned to the patient. 
                        They can change this after their first login. The patient will receive their login credentials via email.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent numbers in name fields
            const nameFields = ['name', 'emergency_contact_name'];
            nameFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('keypress', function(e) {
                        const char = String.fromCharCode(e.which);
                        if (!/[A-Za-z\s]/.test(char)) {
                            e.preventDefault();
                        }
                    });
                }
            });
            
            // Prevent letters in phone fields
            const phoneFields = ['phone', 'emergency_contact_phone'];
            phoneFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('keypress', function(e) {
                        const char = String.fromCharCode(e.which);
                        if (!/[0-9+\-\s()]/.test(char)) {
                            e.preventDefault();
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
