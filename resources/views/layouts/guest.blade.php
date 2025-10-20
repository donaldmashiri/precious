<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ITAS - Intelligent Triage & Appointment Scheduling System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        <!-- Replaced Vite with CDNs -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-red-50 to-white">
        <!-- Header with Logo -->
        <header class="bg-white shadow-lg border-b" style="border-color: #800020;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <a href="/" class="flex items-center">
                        <div class="relative">
                            <img src="{{ asset('logo/main.png') }}" alt="ITAS Logo" class="h-12 w-12 rounded-xl shadow-lg">
                        </div>
                        <div class="ml-4">
                            <h1 class="text-xl font-bold text-gray-900 leading-tight">ITAS</h1>
                            <p class="text-xs text-gray-600 leading-tight">Intelligent Triage & Appointment</p>
                            <p class="text-xs text-gray-600 leading-tight">Scheduling System</p>
                        </div>
                    </a>
                    
                    <nav class="flex items-center space-x-6">
                        <a href="/" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors text-gray-700"
                           style="hover:color: #800020;">
                            <i class="fas fa-home mr-2"></i>Home
                        </a>
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 rounded-md text-sm font-medium transition-colors border text-gray-700"
                           style="border-color: #800020; hover:color: #800020; hover:border-color: #a0002a;">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="text-white px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                           style="background: linear-gradient(135deg, #800020 0%, #a0002a 100%);">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-lg mt-6 px-6 py-8 bg-white shadow-xl overflow-hidden sm:rounded-xl border border-red-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
