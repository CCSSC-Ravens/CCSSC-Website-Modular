<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
        </style>
    @endif
</head>

<body class="min-h-screen flex flex-col font-sans antialiased overflow-x-hidden">
    <div class="relative w-full h-screen overflow-hidden"
        style="background: url('{{ asset('images/about-us/background.png') }}') center / cover no-repeat;">
        <!-- Gradient overlay - fades from orange at top to transparent -->
        <div class="absolute inset-0 z-[1]"
            style="background: linear-gradient(180deg, #B13407 0%, rgba(235, 149, 118, 0) 20%, rgba(177, 52, 7, 0.5) 40%, rgba(177, 52, 7, 0.2) 60%, transparent 80%);">
        </div>
        <section class="relative flex flex-col w-full px-10 pt-10 z-[2]">
            <x-navbar class="mb-10" />
        </section>
        <section
            class="relative w-full h-[calc(100vh-120px)] px-4 md:px-10 pb-10 mx-auto flex flex-col md:flex-row justify-center items-center gap-8 z-[2]">

            <!-- Logo on the left -->
            <div class="flex-shrink-0">
                <img src="{{ asset('images/ccs_logo.jpg') }}"
                    class="h-[300px] md:h-[400px] lg:h-[500px] opacity-90 select-none pointer-events-none rounded-full shadow-2xl"
                    alt="CCS Logo" />
            </div>

            <!-- Details on the right -->
            <div class="max-w-xl z-10 text-center md:text-left">
                <h1 class="font-bold text-5xl md:text-6xl lg:text-7xl tracking-tighter leading-[0.9] mb-6 bg-gradient-to-b from-yellow-300 via-yellow-500 to-orange-600 bg-clip-text text-transparent drop-shadow-lg"
                    style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                    About Us
                </h1>
                <div class="text-white/90 text-sm md:text-base font-normal font-sans leading-relaxed mb-8">
                    The Gordon College College of Computer Studies - Student Council (GC CCS - SC) was founded in 2013.
                    It
                    functions as a local chapter of the Student Society on Information Technology Education (SSITE),
                    operating
                    under the Philippine Society of Information Technology Educators-Region 3 (PSITE-RIII). The
                    Council's
                    primary purpose is to adhere to and support the organizational goals of promoting Information
                    Technology
                    Education (ITE) throughout the region. Its members are students currently enrolled in programs
                    within the
                    College of Computer Studies.
                </div>
                <a href="#"
                    class="inline-block px-8 py-3 bg-white/90 hover:bg-white text-gray-800 font-medium rounded-full shadow-lg transition-all duration-300">
                    Learn More
                </a>
            </div>
        </section>
    </div>
    {{-- <section class="bg-white w-full px-10 pb-6 mt-auto">
        <div class="h-[1080px] relative bg-white"></div>

    </section> --}}
    <section class="w-full">

        <x-footer />
    </section>
    <x-feedback-button />
</body>

</html>
