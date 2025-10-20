<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Healthcare Management System - Welcome</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Styles / Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            .medical-primary { background-color: #800020; }
            .text-medical-primary { color: #800020; }
            .bg-medical-primary { background-color: #800020; }
            .hover\:bg-medical-secondary:hover { background-color: #a0002a; }
            .border-medical-primary { border-color: #800020; }
            .from-maroon-50 { --tw-gradient-from: #fdf2f8; }
            .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-red-50 to-white">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg border-b" style="border-color: #800020;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <!-- Logo Design -->
                                <div class="relative">
                                    <img src="{{ asset('logo/main.png') }}" alt="ITAS Logo" class="h-16 w-16 rounded-xl shadow-lg">
                                </div>
                                <div class="ml-4">
                                    <h1 class="text-xl font-bold text-gray-900 leading-tight">ITAS</h1>
                                    <p class="text-xs text-gray-600 leading-tight">Intelligent Triage & Appointment</p>
                                    <p class="text-xs text-gray-600 leading-tight">Scheduling System</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-6">
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                   class="text-white px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                   style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%); hover:background: linear-gradient(135deg, #a0002a 0%, #800020 100%);">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                            @else
                                <a href="#features" 
                                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors text-gray-700"
                                   style="hover:color: #800020;">
                                    Features
                                </a>
                                <a href="{{ route('login') }}" 
                                   class="px-4 py-2 rounded-md text-sm font-medium transition-colors border text-gray-700"
                                   style="border-color: #800020; hover:color: #800020; hover:border-color: #a0002a;">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" 
                                       class="text-white px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                       style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                        <i class="fas fa-user-plus mr-2"></i>Get Started
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-center lg:text-left">
                        <div class="bg-white rounded-2xl shadow-xl p-8 border" style="border-color: #800020;">
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6" style="background-color: rgba(128, 0, 32, 0.1); color: #800020;">
                                <i class="fas fa-sparkles mr-2"></i>
                                AI-Powered Healthcare Management
                            </div>
                            
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">Intelligent Triage &</span>
                                <span class="block" style="color: #800020;">Appointment Scheduling</span>
                            </h1>
                            
                            <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                                Revolutionary healthcare management with AI-powered triage assessment, smart appointment scheduling, 
                                and real-time patient monitoring. Streamline your healthcare operations with ITAS.
                            </p>
                            
                            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" 
                                       class="flex items-center justify-center px-8 py-4 text-base font-medium rounded-lg text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
                                       style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                        <i class="fas fa-tachometer-alt mr-2"></i>
                                        Go to Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" 
                                       class="flex items-center justify-center px-8 py-4 text-base font-medium rounded-lg text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
                                       style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                        <i class="fas fa-rocket mr-2"></i>
                                        Get Started
                                    </a>
                                @endauth
                                
                                <a href="#features" 
                                   class="flex items-center justify-center px-8 py-4 text-base font-medium rounded-lg border-2 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200"
                                   style="color: #800020; border-color: #800020; background-color: rgba(128, 0, 32, 0.05);">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Feature Cards -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl shadow-lg p-6 border hover:shadow-xl transition-shadow duration-200" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-user-injured text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Triage</h3>
                            <p class="text-sm text-gray-600">AI-powered patient prioritization and assessment</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 border hover:shadow-xl transition-shadow duration-200" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-calendar-check text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Appointments</h3>
                            <p class="text-sm text-gray-600">Intelligent scheduling with availability optimization</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 border hover:shadow-xl transition-shadow duration-200" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-stethoscope text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Patient Care</h3>
                            <p class="text-sm text-gray-600">Comprehensive medical record management</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 border hover:shadow-xl transition-shadow duration-200" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-bell text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Real-time Alerts</h3>
                            <p class="text-sm text-gray-600">Instant notifications for critical updates</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-red-800 font-semibold tracking-wide uppercase">ITAS Features</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Intelligent Healthcare Management
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        Advanced triage algorithms and smart scheduling powered by artificial intelligence for optimal patient care.
                    </p>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border hover:shadow-xl transition-all duration-200 hover:-translate-y-1" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-user-plus text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Patient Registration</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Comprehensive patient registration system with medical history, allergies, and insurance information management.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border hover:shadow-xl transition-all duration-200 hover:-translate-y-1" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-clipboard-list text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Intelligent Triage</h3>
                            <p class="text-gray-600 leading-relaxed">
                                AI-powered priority-based triage system with automated scoring and real-time queue management.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border hover:shadow-xl transition-all duration-200 hover:-translate-y-1" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-calendar-alt text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Smart Scheduling</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Intelligent appointment scheduling with doctor availability optimization and automated reminders.
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border hover:shadow-xl transition-all duration-200 hover:-translate-y-1" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-bell text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Real-time Alerts</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Priority-based notification system for critical alerts, appointment updates, and patient status changes.
                            </p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border hover:shadow-xl transition-all duration-200 hover:-translate-y-1" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-users text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Multi-Role Access</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Specialized dashboards and features for patients, doctors, nurses, staff, and administrators.
                            </p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border hover:shadow-xl transition-all duration-200 hover:-translate-y-1" style="border-color: rgba(128, 0, 32, 0.2);">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                                <i class="fas fa-mobile-alt text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Mobile Responsive</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Beautiful, responsive design optimized for desktop, tablet, and mobile devices with touch-friendly interface.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Types Section -->
        <div class="py-16" style="background-color: rgba(128, 0, 32, 0.05);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base font-semibold tracking-wide uppercase" style="color: #800020;">User Types</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Register as the right user type
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        Choose your role to access the appropriate features and dashboard designed for your needs.
                    </p>
                </div>

                <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Patient -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-200 hover:-translate-y-1 border" style="border-color: rgba(128, 0, 32, 0.2);">
                        <div class="mx-auto h-20 w-20 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Patient</h3>
                        <p class="text-sm text-gray-600 mb-6 leading-relaxed">Book appointments, view medical records, receive notifications and manage your healthcare journey</p>
                        <a href="{{ route('register') }}?role=patient" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5"
                           style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">
                            Register as Patient
                        </a>
                    </div>

                    <!-- Doctor -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-200 hover:-translate-y-1 border" style="border-color: rgba(128, 0, 32, 0.2);">
                        <div class="mx-auto h-20 w-20 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                            <i class="fas fa-user-md text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Doctor</h3>
                        <p class="text-sm text-gray-600 mb-6 leading-relaxed">Manage appointments, review patient records, conduct consultations and provide medical care</p>
                        <a href="{{ route('register') }}?role=doctor" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5"
                           style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                            Register as Doctor
                        </a>
                    </div>

                    <!-- Nurse/Staff -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-200 hover:-translate-y-1 border" style="border-color: rgba(128, 0, 32, 0.2);">
                        <div class="mx-auto h-20 w-20 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
                            <i class="fas fa-user-nurse text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Nurse/Staff</h3>
                        <p class="text-sm text-gray-600 mb-6 leading-relaxed">Conduct triage assessments, manage patient queue, register patients and assist with care</p>
                        <a href="{{ route('register') }}?role=nurse" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5"
                           style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
                            Register as Nurse
                        </a>
                    </div>

                    <!-- Admin -->
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-200 hover:-translate-y-1 border" style="border-color: rgba(128, 0, 32, 0.2);">
                        <div class="mx-auto h-20 w-20 rounded-xl flex items-center justify-center mb-6" style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                            <i class="fas fa-user-shield text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Administrator</h3>
                        <p class="text-sm text-gray-600 mb-6 leading-relaxed">System management, user oversight, analytics, reporting and overall system administration</p>
                        <a href="{{ route('register') }}?role=admin" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5"
                           style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                            Register as Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center space-x-6 md:order-2">
                    <div class="flex items-center">
                        <img src="{{ asset('logo/main.png') }}" alt="ITAS Logo" class="h-10 w-10 rounded-lg mr-3 shadow-md">
                        <div>
                            <p class="text-sm font-medium text-gray-900">ITAS</p>
                            <p class="text-xs text-gray-500">Intelligent Triage & Appointment Scheduling System</p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-gray-400">
                        &copy; {{ date('Y') }} ITAS - Intelligent Triage & Appointment Scheduling System. Built with Laravel.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
