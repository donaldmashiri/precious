<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            <i class="fas fa-sign-in-alt mr-2 text-medical-primary"></i>
            Welcome Back
        </h2>
        <p class="text-sm text-gray-600">Sign in to your healthcare account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Demo Credentials -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="text-sm font-medium text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-1"></i>
            Demo Credentials
        </h3>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="bg-white p-2 rounded border">
                <p class="font-medium text-blue-900">Admin</p>
                <p class="text-blue-700">admin@healthcare.com</p>
                <p class="text-blue-700">password</p>
            </div>
            <div class="bg-white p-2 rounded border">
                <p class="font-medium text-green-900">Doctor</p>
                <p class="text-green-700">sarah.johnson@healthcare.com</p>
                <p class="text-green-700">password</p>
            </div>
            <div class="bg-white p-2 rounded border">
                <p class="font-medium text-purple-900">Nurse</p>
                <p class="text-purple-700">jennifer.adams@healthcare.com</p>
                <p class="text-purple-700">password</p>
            </div>
            <div class="bg-white p-2 rounded border">
                <p class="font-medium text-blue-900">Patient</p>
                <p class="text-blue-700">john.smith@email.com</p>
                <p class="text-blue-700">password</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" class="flex items-center">
                <i class="fas fa-envelope mr-2 text-medical-primary"></i>
                {{ __('Email Address') }}
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" class="flex items-center">
                <i class="fas fa-lock mr-2 text-medical-primary"></i>
                {{ __('Password') }}
            </x-input-label>
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="current-password" 
                                placeholder="Enter your password" />
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-medical-primary shadow-sm focus:border-medical-primary focus:ring focus:ring-medical-primary focus:ring-opacity-50" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-medical-primary hover:text-medical-secondary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-primary transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center bg-medical-primary hover:bg-medical-secondary focus:bg-medical-secondary">
                <i class="fas fa-sign-in-alt mr-2"></i>
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-medical-primary hover:text-medical-secondary transition-colors">
                    Create one here
                </a>
            </p>
        </div>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Quick login function for demo credentials
        function quickLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        // Add click handlers to demo credential boxes
        document.addEventListener('DOMContentLoaded', function() {
            const demoBoxes = document.querySelectorAll('.bg-white.p-2.rounded.border');
            demoBoxes.forEach(box => {
                box.style.cursor = 'pointer';
                box.addEventListener('click', function() {
                    const emails = {
                        'Admin': 'admin@healthcare.com',
                        'Doctor': 'sarah.johnson@healthcare.com',
                        'Nurse': 'jennifer.adams@healthcare.com',
                        'Patient': 'john.smith@email.com'
                    };
                    
                    const role = this.querySelector('p').textContent;
                    const email = emails[role];
                    
                    if (email) {
                        quickLogin(email, 'password');
                    }
                });
            });
        });
    </script>
</x-guest-layout>
