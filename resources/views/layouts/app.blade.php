<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Healthcare Management') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        <!-- Replaced Vite with CDNs -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #800020 0%, #a0002a 50%, #c0002f 100%);
            }
            .card-shadow {
                box-shadow: 0 4px 6px -1px rgba(128, 0, 32, 0.1), 0 2px 4px -1px rgba(128, 0, 32, 0.06);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="gradient-bg shadow-lg">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="text-white">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times cursor-pointer" onclick="this.parentElement.parentElement.style.display='none'"></i>
                        </span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times cursor-pointer" onclick="this.parentElement.parentElement.style.display='none'"></i>
                        </span>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="py-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-medical-primary text-white mt-12">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Healthcare Management System</h3>
                            <p class="text-maroon-200">Providing efficient healthcare services with intelligent triage and appointment scheduling.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                            <ul class="space-y-2 text-maroon-200">
                                <li><a href="{{ route('appointments.index') }}" class="hover:text-white transition-colors">Appointments</a></li>
                                <li><a href="{{ route('triage.index') }}" class="hover:text-white transition-colors">Triage</a></li>
                                <li><a href="{{ route('patients.index') }}" class="hover:text-white transition-colors">Patients</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Emergency Contact</h3>
                            <p class="text-maroon-200">
                                <i class="fas fa-phone mr-2"></i>Emergency: 911<br>
                                <i class="fas fa-envelope mr-2"></i>support@healthcare.com
                            </p>
                        </div>
                    </div>
                    <div class="border-t border-maroon-700 mt-8 pt-8 text-center text-maroon-200">
                        <p>&copy; {{ date('Y') }} Healthcare Management System. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            // Auto-hide flash messages after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.style.display = 'none', 500);
                });
            }, 5000);
        </script>
    </body>
</html>
