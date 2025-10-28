<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-user-plus mr-2 text-medical-primary"></i>
            Create Your Account
        </h2>
        <p class="text-sm text-gray-600">Join our healthcare management system</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Role Selection -->
        <div class="mb-6">
            <x-input-label for="role" class="flex items-center">
                <i class="fas fa-users mr-2 text-medical-primary"></i>
                {{ __('I am registering as a') }} *
            </x-input-label>
            <div class="mt-3 grid grid-cols-2 gap-3">
                <label class="role-option relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-gray-400 transition-colors">
                    <input type="radio" name="role" value="patient" class="sr-only" {{ request('role') == 'patient' || old('role') == 'patient' || (!request('role') && !old('role')) ? 'checked' : '' }}>
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Patient
                            </span>
                            <span class="mt-1 flex items-center text-sm text-gray-500">Book appointments, view records</span>
                        </span>
                    </span>
                </label>

                <label class="role-option relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-gray-400 transition-colors">
                    <input type="radio" name="role" value="doctor" class="sr-only" {{ request('role') == 'doctor' || old('role') == 'doctor' ? 'checked' : '' }}>
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900">
                                <i class="fas fa-user-md mr-2 text-green-600"></i>Doctor
                            </span>
                            <span class="mt-1 flex items-center text-sm text-gray-500">Manage patients, consultations</span>
                        </span>
                    </span>
                </label>

                <label class="role-option relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-gray-400 transition-colors">
                    <input type="radio" name="role" value="nurse" class="sr-only" {{ request('role') == 'nurse' || old('role') == 'nurse' ? 'checked' : '' }}>
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900">
                                <i class="fas fa-user-nurse mr-2 text-purple-600"></i>Nurse/Staff
                            </span>
                            <span class="mt-1 flex items-center text-sm text-gray-500">Triage, patient management</span>
                        </span>
                    </span>
                </label>

                <label class="role-option relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-gray-400 transition-colors">
                    <input type="radio" name="role" value="admin" class="sr-only" {{ request('role') == 'admin' || old('role') == 'admin' ? 'checked' : '' }}>
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span class="block text-sm font-medium text-gray-900">
                                <i class="fas fa-user-shield mr-2" style="color: #800020;"></i>Administrator
                            </span>
                            <span class="mt-1 flex items-center text-sm text-gray-500">System management</span>
                        </span>
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" class="flex items-center">
                <i class="fas fa-user mr-2 text-medical-primary"></i>
                {{ __('Full Name') }} *
            </x-input-label>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" class="flex items-center">
                <i class="fas fa-envelope mr-2 text-medical-primary"></i>
                {{ __('Email Address') }} *
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" class="flex items-center">
                <i class="fas fa-phone mr-2 text-medical-primary"></i>
                {{ __('Phone Number') }} *
            </x-input-label>
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required placeholder="Enter your phone number" pattern="[0-9+\-\s()]+" title="Phone number should only contain numbers, +, -, spaces, and parentheses" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" class="flex items-center">
                <i class="fas fa-lock mr-2 text-medical-primary"></i>
                {{ __('Password') }} *
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" 
                            placeholder="Create a secure password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" class="flex items-center">
                <i class="fas fa-lock mr-2 text-medical-primary"></i>
                {{ __('Confirm Password') }} *
            </x-input-label>
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms and Conditions -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="terms" class="rounded border-gray-300 text-medical-primary shadow-sm focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50" required>
                <span class="ml-2 text-sm text-gray-600">
                    I agree to the <a href="#" class="text-medical-primary hover:text-medical-secondary underline">Terms of Service</a> and <a href="#" class="text-medical-primary hover:text-medical-secondary underline">Privacy Policy</a>
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-medical-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-primary transition-colors" href="{{ route('login') }}">
                <i class="fas fa-arrow-left mr-1"></i>
                {{ __('Already have an account?') }}
            </a>

            <x-primary-button class="bg-medical-primary hover:bg-medical-secondary focus:bg-medical-secondary">
                <i class="fas fa-user-plus mr-2"></i>
                {{ __('Create Account') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle role selection styling
            const roleOptions = document.querySelectorAll('.role-option');
            const roleInputs = document.querySelectorAll('input[name="role"]');
            
            // Function to update selection styling
            function updateSelection() {
                // Reset all options
                roleOptions.forEach(option => {
                    option.classList.remove('border-red-500', 'ring-2', 'ring-red-500', 'bg-red-50');
                    option.classList.add('border-gray-300');
                    option.style.borderColor = '';
                    option.style.backgroundColor = '';
                });
                
                // Highlight selected option
                roleInputs.forEach(input => {
                    if (input.checked) {
                        const label = input.closest('.role-option');
                        label.classList.remove('border-gray-300');
                        label.style.borderColor = '#800020';
                        label.style.backgroundColor = 'rgba(128, 0, 32, 0.05)';
                        label.style.boxShadow = '0 0 0 2px rgba(128, 0, 32, 0.2)';
                    }
                });
            }
            
            // Add click handlers to labels
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const input = this.querySelector('input[name="role"]');
                    input.checked = true;
                    updateSelection();
                });
            });
            
            // Add change handlers to inputs
            roleInputs.forEach(input => {
                input.addEventListener('change', updateSelection);
            });
            
            // Initial selection update
            updateSelection();
            
            // Prevent numbers in name field
            const nameInput = document.getElementById('name');
            nameInput.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[A-Za-z\s]/.test(char)) {
                    e.preventDefault();
                }
            });
            
            // Prevent letters in phone field
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[0-9+\-\s()]/.test(char)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-guest-layout>
