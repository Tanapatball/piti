<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-900">
            <!-- Background Pattern -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-600 rounded-full opacity-20 blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
            </div>

            <!-- Logo -->
            <div class="relative z-10 mb-6">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-2xl">WMS</span>
                </a>
            </div>

            <!-- Card -->
            <div class="relative z-10 w-full sm:max-w-md px-8 py-8 bg-white shadow-2xl overflow-hidden rounded-2xl mx-4">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="relative z-10 mt-8 text-indigo-200 text-sm">
                &copy; {{ date('Y') }} ระบบจัดการสินค้าคงคลัง
            </p>
        </div>
    </body>
</html>
