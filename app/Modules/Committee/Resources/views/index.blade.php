<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Department</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

</head>

<body class="min-h-screen bg-gradient-to-b from-orange-700 ">
    <!-- Navigation Bar -->
    <header class="text-white py-4 px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <!-- Logo/Brand -->
                <div class="flex items-center gap-2">
                    <span class="text-3xl font-bold">Phoenixes</span>
                </div>

                <!-- Navigation Links -->
                <nav class="flex items-center gap-8 rounded-full px-12 py-3 border-2 border-white">
                    <a href="{{ url('/') }}"
                        class="font-semibold hover:text-orange-100 transition duration-200">Home</a>
                    <a href="{{ url('/about') }}"
                        class="font-semibold hover:text-orange-100 transition duration-200">About Us</a>
                    <a href="{{ url('/committees') }}"
                        class="font-semibold hover:text-orange-100 transition duration-200 underline decoration-2">Department</a>
                    <a href="{{ url('/news') }}"
                        class="font-semibold hover:text-orange-100 transition duration-200">News</a>
                </nav>

                <!-- Search Bar -->
                <div class="relative w-64">
                    <input type="text" placeholder="Search Phoenixes"
                        class="w-full px-6 py-3 pr-12 rounded-full bg-white bg-opacity-80 text-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white">
                    <svg class="absolute right-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-8 py-12">
        <div class="grid grid-cols-2 gap-12 items-center min-h-[500px]">
            <!-- Left Side - Empty space for image -->
            <div class="flex items-center justify-center">
                <!-- Image would go here -->
            </div>

            <!-- Right Side - Content -->
            <div class="flex flex-col justify-center space-y-8">
                <h1 class="text-white text-6xl font-bold leading-tight">
                    Gordon College - CCSSC
                </h1>
                <p class="text-white text-base opacity-100 leading-relaxed">
                    The Gordon College College of Computer Studies - Student Council (GC CCS - SC) was founded in
                    2013. It functions as a local chapter of the Student Society on Information Technology Education
                    (SSITE), operating under the Philippine Society of Information Technology Educators-Region 3
                    (PSITE-RIII). The Council's primary purpose is to adhere to and support the organizational goals
                    of promoting Information Technology Education (ITE) throughout the region. Its members are
                    students currently enrolled in programs within the College of Computer Studies.
                </p>
            </div>
        </div>

        <!-- Bottom Navigation Circles -->
        <div class="flex items-center justify-center gap-8 mt-17 ">
            <a href="#"
                class="w-20 h-20 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 transition">
                <span class="text-orange-700 text-xs font-semibold text-center">LOGO</span>
            </a>
            <a href="#"
                class="w-20 h-20 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 transition">
                <span class="text-orange-700 text-xs font-semibold text-center">LOGO</span>
            </a>
            <a href="#"
                class="w-20 h-20 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 transition">
                <span class="text-orange-700 text-xs font-semibold text-center">LOGO</span>
            </a>
            <a href="#"
                class="w-20 h-20 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 transition">
                <span class="text-orange-700 text-xs font-semibold text-center">LOGO</span>
            </a>
            <a href="#"
                class="w-20 h-20 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 transition">
                <span class="text-orange-700 text-xs font-semibold text-center">LOGO</span>
            </a>
            <a href="#"
                class="w-20 h-20 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 transition">
                <span class="text-orange-700 text-xs font-semibold text-center">LOGO</span>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-white text-center py-8 mt-auto">
        <p class="text-sm opacity-80 mr-2">
            How are the Committees under Gordon College - CCSSC
        </p>
        <p class="text-xs mt-2 opacity-60">
            © Gordon College - College of Computer Science Student Council
        </p>
    </footer>
</body>

</html>
