<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Book New Appointment
                </h2>
                <p class="text-sm opacity-90 mt-1">Schedule your medical consultation</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Appointments
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg card-shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user-plus mr-2 text-medical-primary"></i>
                    Appointment Information
                </h3>
                <p class="text-sm text-gray-600 mt-1">Please fill in all required information to schedule your appointment</p>
            </div>

            <form method="POST" action="{{ route('appointments.store') }}" class="p-6">
                @csrf

                <!-- Patient Selection (if staff/admin) -->
                @if(Auth::user()->isStaff())
                <div class="mb-6">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-injured mr-1"></i>Patient *
                    </label>
                    <select name="patient_id" id="patient_id" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        <option value="">Select Patient</option>
                        @foreach(\App\Models\Patient::with('user')->get() as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->user->name }} - {{ $patient->patient_number }}</option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @else
                <input type="hidden" name="patient_id" value="{{ Auth::user()->patient->id ?? '' }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Hospital Selection -->
                    <div>
                        <label for="hospital_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hospital mr-1"></i>Hospital *
                        </label>
                        <select name="hospital_id" id="hospital_id" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">Select Hospital</option>
                            @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                        @error('hospital_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department Selection -->
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-1"></i>Department *
                        </label>
                        <select name="department_id" id="department_id" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">Select Department</option>
                        </select>
                        @error('department_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Doctor Selection -->
                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-md mr-1"></i>Doctor *
                        </label>
                        <select name="doctor_id" id="doctor_id" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">Select Doctor</option>
                        </select>
                        @error('doctor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Appointment Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clipboard-list mr-1"></i>Appointment Type *
                        </label>
                        <select name="type" id="type" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">Select Type</option>
                            <option value="consultation">Consultation</option>
                            <option value="follow_up">Follow-up</option>
                            <option value="emergency">Emergency</option>
                            <option value="routine_checkup">Routine Checkup</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Appointment Date -->
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1"></i>Date *
                        </label>
                        <input type="date" name="appointment_date" id="appointment_date" required 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                        @error('appointment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Slot -->
                    <div>
                        <label for="time_slot" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-1"></i>Time Slot *
                        </label>
                        <select name="time_slot" id="time_slot" required class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">
                            <option value="">Select Time</option>
                        </select>
                        @error('time_slot')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reason for Visit -->
                <div class="mt-6">
                    <label for="reason_for_visit" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-medical mr-1"></i>Reason for Visit *
                    </label>
                    <textarea name="reason_for_visit" id="reason_for_visit" rows="3" required
                              placeholder="Please describe the reason for your visit..."
                              class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('reason_for_visit') }}</textarea>
                    @error('reason_for_visit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Symptoms -->
                <div class="mt-6">
                    <label for="symptoms" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-thermometer-half mr-1"></i>Current Symptoms (Optional)
                    </label>
                    <textarea name="symptoms" id="symptoms" rows="3"
                              placeholder="Please describe any current symptoms..."
                              class="w-full rounded-lg border-gray-300 focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50">{{ old('symptoms') }}</textarea>
                    @error('symptoms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('appointments.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-medical-primary text-white rounded-lg hover:bg-medical-secondary transition-colors">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hospitalSelect = document.getElementById('hospital_id');
            const departmentSelect = document.getElementById('department_id');
            const doctorSelect = document.getElementById('doctor_id');
            const dateInput = document.getElementById('appointment_date');
            const timeSlotSelect = document.getElementById('time_slot');

            // Load departments when hospital is selected
            hospitalSelect.addEventListener('change', function() {
                const hospitalId = this.value;
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
                timeSlotSelect.innerHTML = '<option value="">Select Time</option>';

                if (hospitalId) {
                    fetch(`/api/departments/${hospitalId}`)
                        .then(response => response.json())
                        .then(departments => {
                            departments.forEach(dept => {
                                departmentSelect.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
                            });
                        });
                }
            });

            // Load doctors when department is selected
            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
                doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
                timeSlotSelect.innerHTML = '<option value="">Select Time</option>';

                if (departmentId) {
                    fetch(`/api/doctors/${departmentId}`)
                        .then(response => response.json())
                        .then(doctors => {
                            doctors.forEach(doctor => {
                                doctorSelect.innerHTML += `<option value="${doctor.id}">Dr. ${doctor.user.name} - ${doctor.specialization}</option>`;
                            });
                        });
                }
            });

            // Load available time slots when doctor and date are selected
            function loadTimeSlots() {
                const doctorId = doctorSelect.value;
                const date = dateInput.value;
                
                timeSlotSelect.innerHTML = '<option value="">Select Time</option>';

                if (doctorId && date) {
                    fetch(`/api/available-slots?doctor_id=${doctorId}&date=${date}`)
                        .then(response => response.json())
                        .then(data => {
                            data.slots.forEach(slot => {
                                timeSlotSelect.innerHTML += `<option value="${slot.time}">${slot.display}</option>`;
                            });
                        });
                }
            }

            doctorSelect.addEventListener('change', loadTimeSlots);
            dateInput.addEventListener('change', loadTimeSlots);

            // Update appointment_date field when time slot is selected
            timeSlotSelect.addEventListener('change', function() {
                const date = dateInput.value;
                const time = this.value;
                
                if (date && time) {
                    const appointmentDateTime = `${date} ${time}`;
                    // Create a hidden input for the full datetime
                    let hiddenInput = document.querySelector('input[name="appointment_date_time"]');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'appointment_date';
                        this.parentNode.appendChild(hiddenInput);
                    }
                    hiddenInput.value = appointmentDateTime;
                }
            });
        });
    </script>
</x-app-layout>
