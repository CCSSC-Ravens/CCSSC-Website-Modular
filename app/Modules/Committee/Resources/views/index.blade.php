<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="committee-page">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Committees</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* 
         * Scoped styles for committee page only
         * All styles target .committee-page to avoid affecting other pages
         */
        
        /* Base styles - scoped to this page */
        html.committee-page,
        html.committee-page body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }

        html.committee-page body {
            position: fixed;
            top: 0;
            left: 0;
        }

        /* Hide scrollbar but allow scrolling */
        .snap-container::-webkit-scrollbar {
            display: none;
        }

        .snap-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
            scroll-snap-type: y mandatory;
            overflow-y: scroll;
            height: 100vh;
            width: 100vw;
            scroll-behavior: smooth;
            position: relative;
        }

        /* Fixed gradient background - Unified gradient spanning all sections */
        .snap-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: calc({{ $committeeCount }} * 100vh);
            background:
                linear-gradient(to bottom, 
                    rgba(194, 65, 12, 0.7) 0%, 
                    rgba(194, 65, 12, 0.5) 20%, 
                    rgba(194, 65, 12, 0.3) 50%,
                    rgba(194, 65, 12, 0.2) 80%,
                    rgba(194, 65, 12, 0.1) 100%),
                url('/images/homepage/background.png');
            background-size: cover, cover;
            background-position: center, center;
            background-attachment: scroll, fixed;
            z-index: -1;
            pointer-events: none;
        }

        /* Floating particles - Pure CSS animation */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatParticle var(--duration, 15s) infinite ease-in-out;
            animation-delay: var(--delay, 0s);
        }

        @keyframes floatParticle {
            0%, 100% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0.1;
            }
            25% {
                transform: translateY(-30vh) translateX(10vw) scale(1.1);
                opacity: 0.2;
            }
            50% {
                transform: translateY(-60vh) translateX(-5vw) scale(0.9);
                opacity: 0.15;
            }
            75% {
                transform: translateY(-90vh) translateX(15vw) scale(1.05);
                opacity: 0.1;
            }
        }

        /* Snap Section */
        .snap-section {
            scroll-snap-align: start;
            scroll-snap-stop: always;
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        /* Navigation Button Styles */
        .nav-btn {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-btn.active {
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        .nav-btn:not(.active) {
            opacity: 0.5;
        }

        .nav-btn:not(.active):hover {
            opacity: 1;
            transform: scale(1.05);
        }

        /* Nav button active state - shrink to dot */
        .nav-btn svg {
            transition: opacity 0.4s ease;
        }

        .nav-btn.active svg {
            opacity: 0;
        }

        .nav-btn.active {
            width: 12px !important;
            height: 12px !important;
            min-width: 12px;
            min-height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(255, 255, 255, 1);
            padding: 0;
        }

        /* Section Logo Animation - CSS only with animation-delay */
        .section-logo {
            opacity: 0;
            transform: scale(0.8);
            animation: revealLogo 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-play-state: paused;
        }

        .snap-section.in-view .section-logo {
            animation-play-state: running;
        }

        @keyframes revealLogo {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Content fade animations - CSS only */
        .fade-in-content {
            opacity: 0;
            transform: translateX(20px);
            animation: fadeInRight 0.6s ease forwards;
            animation-play-state: paused;
        }

        .snap-section.in-view .fade-in-content {
            animation-play-state: running;
        }

        @keyframes fadeInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="font-sans antialiased committee-body">
    {{-- Fixed Navigation Logos (Left Side) - Using Blade component --}}
    @include('committee::components.navigation', ['committees' => $committees])

    {{-- Floating Background Particles - Generated server-side by Laravel controller --}}
    <div class="bg-particles">
        @foreach ($particles as $particle)
            <div class="particle"
                style="width: {{ $particle['size'] }}px;
                       height: {{ $particle['size'] }}px;
                       left: {{ $particle['left'] }}%;
                       top: {{ $particle['top'] }}%;
                       --delay: {{ $particle['delay'] }}s;
                       --duration: {{ $particle['duration'] }}s;">
            </div>
        @endforeach
    </div>

    {{-- Main Snap Container --}}
    <div class="snap-container" id="main-container">
        @foreach ($committees as $index => $committee)
            <section class="snap-section {{ $index === 0 ? 'in-view' : '' }}"
                     data-index="{{ $index }}"
                     id="{{ $committee['id'] }}">

                {{-- Navbar only on first section --}}
                @if ($index === 0)
                    <div class="relative w-full px-6 md:px-10 pt-8 z-[2]">
                        <x-navbar />
                    </div>
                @endif

                <div class="content-wrapper flex-1 w-full px-6 md:px-16 flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16">
                    {{-- Committee Logo - Animation delay calculated by Blade --}}
                    <div class="section-logo" style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="w-48 h-48 md:w-72 md:h-72 lg:w-96 lg:h-96 rounded-3xl bg-gradient-to-br {{ $committee['gradient'] }} p-2 shadow-2xl flex items-center justify-center">
                            @include('committee::components.committee-icon', ['id' => $committee['id'], 'size' => 'section'])
                        </div>
                    </div>

                    {{-- Committee Info - Animation delays calculated by Blade --}}
                    <div class="flex-1 max-w-xl text-center md:text-left">
                        <h2 class="fade-in-content text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 md:mb-6 drop-shadow-lg"
                            style="animation-delay: {{ 0.1 + $index * 0.05 }}s">
                            {{ $committee['shortName'] }}
                        </h2>
                        <p class="fade-in-content text-white/90 text-sm md:text-base lg:text-lg leading-relaxed mb-6"
                           style="animation-delay: {{ 0.2 + $index * 0.05 }}s">
                            {{ $committee['description'] }}
                        </p>
                        <div class="fade-in-content flex flex-wrap gap-2 md:gap-3 justify-center md:justify-start mb-6"
                             style="animation-delay: {{ 0.3 + $index * 0.05 }}s">
                            @foreach ($committee['tags'] as $tag)
                                <span class="px-3 py-1.5 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs md:text-sm font-medium">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>

                        {{-- TODO: Add your View Members implementation here --}}
                    </div>
                </div>
            </section>
        @endforeach
    </div>

    {{-- JavaScript for scroll-snap initialization and navigation --}}
    <script>
        (function() {
            // Disable browser scroll restoration
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }

            const container = document.getElementById('main-container');
            const sections = container.querySelectorAll('.snap-section');
            const navBtns = document.querySelectorAll('.nav-btn');
            let currentSection = 0;
            let isInitialized = false;

            // Navigate to section using smooth scroll
            window.scrollToSection = function(index) {
                sections[index]?.scrollIntoView({ behavior: 'smooth' });
            };

            // Update active state based on scroll position
            function updateActiveSection() {
                const scrollTop = container.scrollTop;
                const viewportHeight = window.innerHeight;

                sections.forEach((section, index) => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const isInView = scrollTop >= sectionTop - viewportHeight / 2 &&
                                     scrollTop < sectionTop + sectionHeight - viewportHeight / 2;

                    if (isInView && currentSection !== index) {
                        currentSection = index;

                        // Update navigation active state
                        navBtns.forEach((btn, i) => btn.classList.toggle('active', i === index));

                        // Update section in-view state for CSS animations
                        sections.forEach((sec, i) => sec.classList.toggle('in-view', i === index));
                    }
                });
            }

            // Initialize the page
            function init() {
                if (isInitialized) return;
                isInitialized = true;

                // Force scroll to top immediately
                container.scrollTop = 0;
                
                // Small delay to ensure CSS is applied, then scroll to top again
                requestAnimationFrame(() => {
                    container.scrollTop = 0;
                    
                    // Ensure first section is active
                    currentSection = 0;
                    navBtns.forEach((btn, i) => btn.classList.toggle('active', i === 0));
                    sections.forEach((sec, i) => sec.classList.toggle('in-view', i === 0));
                });

                // Start listening for scroll events
                container.addEventListener('scroll', () => requestAnimationFrame(updateActiveSection), { passive: true });
            }

            // Multiple init triggers to ensure it works
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }

            window.addEventListener('load', () => {
                container.scrollTop = 0;
            });

            // Handle page show event (for back/forward navigation)
            window.addEventListener('pageshow', (event) => {
                container.scrollTop = 0;
                currentSection = 0;
                navBtns.forEach((btn, i) => btn.classList.toggle('active', i === 0));
                sections.forEach((sec, i) => sec.classList.toggle('in-view', i === 0));
            });

            // Cleanup when leaving the page
            window.addEventListener('beforeunload', () => {
                // Remove the committee-page class to prevent style leaking
                document.documentElement.classList.remove('committee-page');
            });
        })();
    </script>
</body>

</html>
