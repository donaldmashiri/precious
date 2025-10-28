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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/3.1.2/flowbite.min.css" rel="stylesheet" />
        
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            maroon: {
                                50: '#fdf2f2',
                                100: '#fce7e7',
                                200: '#f9d2d2',
                                300: '#f4b1b1',
                                400: '#ec8484',
                                500: '#e05c5c',
                                600: '#cc3f3f',
                                700: '#a83232',
                                800: '#8b2e2e',
                                900: '#722c2c',
                                950: '#3e1414',
                            },
                            medical: {
                                primary: '#722c2c',
                                secondary: '#8b2e2e',
                                accent: '#a83232',
                                light: '#f9d2d2',
                                dark: '#3e1414',
                            }
                        }
                    }
                }
            }
        </script>
        
        <style>
            /* Form input borders - make all inputs clearly visible */
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"],
            input[type="number"],
            input[type="date"],
            input[type="time"],
            input[type="datetime-local"],
            input[type="url"],
            input[type="search"],
            select,
            textarea {
                border: 2px solid #d1d5db !important;
                border-radius: 0.5rem;
                padding: 0.625rem 0.875rem;
                transition: all 0.2s;
            }
            
            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="password"]:focus,
            input[type="tel"]:focus,
            input[type="number"]:focus,
            input[type="date"]:focus,
            input[type="time"]:focus,
            input[type="datetime-local"]:focus,
            input[type="url"]:focus,
            input[type="search"]:focus,
            select:focus,
            textarea:focus {
                border-color: #722c2c !important;
                box-shadow: 0 0 0 3px rgba(114, 44, 44, 0.1) !important;
                outline: none;
            }
            
            input[type="text"]:hover,
            input[type="email"]:hover,
            input[type="password"]:hover,
            input[type="tel"]:hover,
            input[type="number"]:hover,
            input[type="date"]:hover,
            input[type="time"]:hover,
            input[type="datetime-local"]:hover,
            input[type="url"]:hover,
            input[type="search"]:hover,
            select:hover,
            textarea:hover {
                border-color: #9ca3af !important;
            }
            
            /* Disabled inputs */
            input:disabled,
            select:disabled,
            textarea:disabled {
                background-color: #f3f4f6 !important;
                border-color: #e5e7eb !important;
                cursor: not-allowed;
            }
            
            /* Checkbox and radio buttons */
            input[type="checkbox"],
            input[type="radio"] {
                border: 2px solid #d1d5db !important;
                width: 1.125rem;
                height: 1.125rem;
            }
            
            input[type="checkbox"]:checked,
            input[type="radio"]:checked {
                background-color: #722c2c !important;
                border-color: #722c2c !important;
            }
        </style>
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
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors text-gray-700 hover:text-medical-primary">
                            <i class="fas fa-home mr-2"></i>Home
                        </a>
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-colors border border-medical-primary text-gray-700 hover:text-medical-primary hover:border-medical-accent">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-medical-primary hover:bg-medical-secondary text-white px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
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
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/3.1.2/flowbite.min.js"></script>
    </body>
</html>
