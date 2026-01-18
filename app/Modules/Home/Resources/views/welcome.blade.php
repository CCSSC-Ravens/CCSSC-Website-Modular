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

<body class="font-sans antialiased overflow-x-hidden">
    {{-- Hero Section - Exact screen width and height --}}
    <section class="h-screen w-screen bg-cover bg-center bg-no-repeat flex flex-col px-10 pt-10 relative"
        style="background-image: linear-gradient(to bottom, rgba(194, 65, 12, 0.7) 0%, rgba(194, 65, 12, 0.4) 30%, rgba(250, 250, 250, 0) 60%), url('{{ asset('images/homepage/background.png') }}');">
        <x-navbar class="shrink-0" />
        <div class="flex-1 flex flex-col justify-between py-8">

            {{-- Hero Text Section --}}
            <div class="pt-10 lg:pt-15 p-4 lg:pl-10 max-w-4xl z-10 pointer-events-none">

                <h1
                    class="font-extrabold text-4xl md:text-6xl lg:text-[5rem] text-red-950 tracking-tighter leading-[0.9] mb-8 pointer-events-auto">
                    Soaring beyond<br>limits.
                </h1>
                <div
                    class="w-[613px] text-orange-700 text-lg md:text-xl font-bold font-roboto leading-relaxed mb-10 pointer-events-auto">
                    The official website of Gordon College - College of Computer Studies Student Council.
                </div>
                <button
                    class="pointer-events-auto bg-[#800000] text-white font-bold px-10 py-4 rounded-full text-xl hover:bg-[#600000] transition-colors shadow-lg hover:scale-105 active:scale-95 duration-300">
                    Visit Us
                </button>
            </div>

            <img src="{{ asset('images/homepage/phoenix.png') }}" alt="Phoenix Illustration"
                class="absolute bottom-0 right-0 -z-1 h-full select-none pointer-events-none" />

            {{-- Cards Grid Section --}}
            <div class="absolute bottom-0 mr-10">
                {{-- Offset the grid to start from the middle/right --}}
                <div class="grid grid-cols-1 gap-6 w-[65%] items-end ml-auto">
                    {{-- Actual Cards --}}
                    <div class="hidden lg:grid grid-cols-3 gap-6 items-end">
                        <x-breaking-card title="Sa Pagitan microfilm got 1st in RAITE" link="#"
                            class="bg-[#8F2203]" />
                        <x-breaking-card title="CCS bags medal in SkyDev’s hackathon" link="#"
                            class="bg-[#8F2203]" />
                        <x-breaking-card title="GGs CCS team in SkyDev’s MLBB compe" link="#"
                            class="bg-[#8F2203]" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Know More Section --}}
    <section class="w-full min-h-screen bg-white py-24 px-0">
        <div class="w-full h-full grid grid-cols-1 lg:grid-cols-12 gap-12">
            {{-- Left Content: Title + Image --}}
            <div class="col-span-1 lg:col-span-8 space-y-12 pl-4 lg:pl-10">
                {{-- Title --}}
                <div>
                    <h2
                        class="text-6xl md:text-7xl font-bold font-['Instrument_Sans'] tracking-tight leading-none text-[#2A2A2A]">
                        Know more <br>
                        <span class="text-[#B13407]">about us.</span>
                    </h2>
                    <p class="mt-6 text-[#B13407] text-lg font-medium tracking-wide">
                        Highlighting innovation and excellence, every step of the way.
                    </p>
                </div>

                {{-- Content Area: Image + Text --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    {{-- Image Placeholder --}}
                    <div class="relative aspect-square bg-[#B13407] rounded-3xl overflow-hidden shadow-2xl p-1">
                        <div class="w-full h-full bg-cover bg-center rounded-2xl"
                            style="background-image: url('https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg'); background-size: contain; background-repeat: no-repeat; background-position: center; background-color: #2a2a2a;">
                            {{-- Placeholder visual until user provides real asset --}}
                        </div>
                    </div>

                    {{-- Text Content --}}
                    <div class="space-y-6 text-[#2A2A2A] text-xl leading-relaxed font-['roboto'] mt-4">
                        <p class="text-justify">The College of Computer Studies proudly congratulates its student
                            filmmakers for securing
                            First Runner-Up in the Micro Short Film Contest at the Regional Assembly on Information
                            Technology Education in Cabanatuan City, Nueva Ecija.</p>
                        <p class="text-justify">The entry "Sa Pagitan (Sumpa Kita)", directed by Eizen Rodriguez, also
                            received the People's
                            Choice Award and earned recognition for Best Actress, awarded to Ms. Erica Mae Camintoy
                            (BSCS 2).</p>
                        <p class="text-justify">These achievements highlight the talent and dedication of the cast and
                            crew, bringing pride
                            to the CCS community and the institution.</p>
                    </div>
                </div>
            </div>

            {{-- Right Side: Navigation Buttons --}}
            <div
                class="col-span-1 lg:col-span-4 lg:pl-12 flex flex-col items-end gap-4 mt-8 lg:mt-0 w-full max-w-[999px] ml-auto">
                <x-committee-button label="Executives" />
                <x-committee-button label="Canaries" />
                <x-committee-button label="Falcons" />
                <x-committee-button label="Herons" />
                <x-committee-button label="Nightingales" />
                <x-committee-button label="Ravens" />
            </div>
        </div>
    </section>
    <x-footer />
</body>

</html>
