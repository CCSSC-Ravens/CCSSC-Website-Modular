<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    @else
        <style>
        </style>
    @endif

    <style>
        /* Hide scrollbar but allow scrolling */
        .snap-container::-webkit-scrollbar {
            display: none;
        }

        .snap-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Scroll Snap Container */
        html,
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
        }

        .snap-container {
            scroll-snap-type: y mandatory;
            overflow-y: scroll;
            height: 100vh;
            scroll-behavior: smooth;
            position: relative;
        }

        /* Fixed gradient background that spans all sections - shades of orange (light to dark) */
        .snap-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 700vh;
            /* 6 departments + footer */
            background:
                linear-gradient(180deg,
                    rgba(251, 146, 60, 0.7) 0%,
                    rgba(249, 115, 22, 0.7) 16.6%,
                    rgba(234, 88, 12, 0.7) 33.3%,
                    rgba(194, 65, 12, 0.7) 50%,
                    rgba(177, 52, 7, 0.7) 66.6%,
                    rgba(154, 52, 18, 0.7) 83.3%,
                    rgba(124, 45, 18, 0.7) 100%),
                url('/images/homepage/background.png');
            background-size: cover, cover;
            background-position: center, center;
            background-attachment: scroll, fixed;
            z-index: -1;
            pointer-events: none;
        }

        /* Floating particles/shapes for background animation */
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
            animation: floatParticle 15s infinite ease-in-out;
        }

        @keyframes floatParticle {

            0%,
            100% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0.1;
            }

            25% {
                transform: translateY(-30vh) translateX(10vw) scale(1.2);
                opacity: 0.15;
            }

            50% {
                transform: translateY(-60vh) translateX(-5vw) scale(0.8);
                opacity: 0.1;
            }

            75% {
                transform: translateY(-90vh) translateX(15vw) scale(1.1);
                opacity: 0.05;
            }
        }

        .snap-section {
            scroll-snap-align: start;
            scroll-snap-stop: always;
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            background: transparent;
        }

        /* Stronger snap transition effect */
        .snap-section .content-wrapper {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .snap-section.in-view .content-wrapper {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(60px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-60px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(60px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .visible {
            opacity: 1;
            transform: translateY(0) translateX(0) scale(1);
        }

        /* Navigation buttons */
        .nav-btn {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-btn.active {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.15);
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

        /* Floating animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }

        /* Flying logo animation */
        .section-logo {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .section-logo.visible {
            opacity: 1;
            transform: scale(1);
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
    </style>
</head>

<body class="font-sans antialiased">

    @php
        $departments = [
            [
                'id' => 'executive',
                'name' => 'Executive',
                'gradient' => 'from-orange-400 to-red-600',
                'description' =>
                    'The Executive Department serves as the governing body of the CCSSC, overseeing all operations and ensuring the council\'s mission is fulfilled. Led by the President and Vice President, this department coordinates with all other committees to maintain unity and efficiency within the organization.',
                'tags' => ['Leadership', 'Coordination', 'Decision Making'],
            ],
            [
                'id' => 'academics',
                'name' => 'Academics',
                'gradient' => 'from-blue-400 to-indigo-600',
                'description' =>
                    'The Academics Committee focuses on enhancing the academic experience of CCS students. They organize tutorial sessions, academic workshops, and seminars that complement the curriculum. This committee bridges the gap between students and faculty for academic concerns.',
                'tags' => ['Tutorials', 'Workshops', 'Seminars'],
            ],
            [
                'id' => 'finance',
                'name' => 'Finance',
                'gradient' => 'from-green-400 to-emerald-600',
                'description' =>
                    'The Finance Committee manages all financial matters of the student council. They handle budgeting, fund allocation, and financial reporting. This committee ensures transparency and accountability in all monetary transactions of the organization.',
                'tags' => ['Budgeting', 'Auditing', 'Transparency'],
            ],
            [
                'id' => 'externals',
                'name' => 'Externals',
                'gradient' => 'from-purple-400 to-violet-600',
                'description' =>
                    'The Externals Committee handles relationships with organizations outside the college. They establish partnerships, sponsorships, and collaborations with industry partners and other educational institutions to provide more opportunities for CCS students.',
                'tags' => ['Partnerships', 'Sponsorships', 'Networking'],
            ],
            [
                'id' => 'internals',
                'name' => 'Internals',
                'gradient' => 'from-yellow-400 to-amber-600',
                'description' =>
                    'The Internals Committee focuses on fostering community within the CCS department. They organize team-building activities, internal events, and maintain communication channels among students. This committee ensures a cohesive and supportive environment.',
                'tags' => ['Team Building', 'Events', 'Community'],
            ],
            [
                'id' => 'creatives',
                'name' => 'Creatives',
                'gradient' => 'from-pink-400 to-rose-600',
                'description' =>
                    'The Creatives Committee handles all visual and creative aspects of the council. They design marketing materials, manage social media presence, and create multimedia content. This committee brings the council\'s vision to life through creative expression.',
                'tags' => ['Design', 'Social Media', 'Multimedia'],
            ],
        ];
    @endphp

    <!-- Fixed Navigation Logos (Left Side) -->
    <div class="fixed left-4 md:left-8 top-1/2 -translate-y-1/2 z-50 flex flex-col gap-3">
        @foreach ($departments as $index => $dept)
            <div class="w-12 h-12 md:w-14 md:h-14 flex items-center justify-center">
                <button onclick="scrollToSection({{ $index }})"
                    class="nav-btn {{ $index === 0 ? 'active' : '' }} w-12 h-12 md:w-14 md:h-14 rounded-xl bg-white/10 backdrop-blur-sm border-2 border-white/30 hover:border-white/60 flex items-center justify-center"
                    data-index="{{ $index }}" title="{{ $dept['name'] }}">
                    @if ($dept['id'] === 'executive')
                        <!-- Crown icon for Executive -->
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3l3.5 7L12 6l3.5 4L19 3M5 21h14M5 21V8m14 13V8" />
                        </svg>
                    @elseif($dept['id'] === 'academics')
                        <!-- Book icon for Academics -->
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    @elseif($dept['id'] === 'finance')
                        <!-- Currency icon for Finance -->
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($dept['id'] === 'externals')
                        <!-- Globe icon for Externals -->
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    @elseif($dept['id'] === 'internals')
                        <!-- Users icon for Internals -->
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    @elseif($dept['id'] === 'creatives')
                        <!-- Palette icon for Creatives -->
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    @endif
                </button>
            </div>
        @endforeach
    </div>

    <!-- Floating Background Particles -->
    <div class="bg-particles" id="particles-container"></div>

    <!-- Main Snap Container -->
    <div class="snap-container" id="main-container">
        <!-- Department Sections -->
        @foreach ($departments as $index => $dept)
            <section class="snap-section {{ $index === 0 ? 'in-view' : '' }}" data-index="{{ $index }}"
                id="{{ $dept['id'] }}">

                @if ($index === 0)
                    <!-- Navbar -->
                    <div class="relative w-full px-6 md:px-10 pt-8 z-[2]">
                        <x-navbar />
                    </div>
                @endif
                <div
                    class="content-wrapper flex-1 w-full px-6 md:px-16 flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16">

                    <!-- Department Logo (SVG icon that grows from navigation) -->
                    <div class="section-logo" data-section-index="{{ $index }}">
                        <div
                            class="w-48 h-48 md:w-72 md:h-72 lg:w-96 lg:h-96 rounded-3xl bg-gradient-to-br {{ $dept['gradient'] }} p-2 shadow-2xl flex items-center justify-center">
                            @if ($dept['id'] === 'executive')
                                <!-- Crown icon for Executive -->
                                <svg class="w-32 h-32 md:w-48 md:h-48 lg:w-64 lg:h-64 text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M5 3l3.5 7L12 6l3.5 4L19 3M5 21h14M5 21V8m14 13V8" />
                                </svg>
                            @elseif($dept['id'] === 'academics')
                                <!-- Book icon for Academics -->
                                <svg class="w-32 h-32 md:w-48 md:h-48 lg:w-64 lg:h-64 text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @elseif($dept['id'] === 'finance')
                                <!-- Currency icon for Finance -->
                                <svg class="w-32 h-32 md:w-48 md:h-48 lg:w-64 lg:h-64 text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($dept['id'] === 'externals')
                                <!-- Globe icon for Externals -->
                                <svg class="w-32 h-32 md:w-48 md:h-48 lg:w-64 lg:h-64 text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            @elseif($dept['id'] === 'internals')
                                <!-- Users icon for Internals -->
                                <svg class="w-32 h-32 md:w-48 md:h-48 lg:w-64 lg:h-64 text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            @elseif($dept['id'] === 'creatives')
                                <!-- Palette icon for Creatives -->
                                <svg class="w-32 h-32 md:w-48 md:h-48 lg:w-64 lg:h-64 text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Department Info -->
                    <div class="flex-1 max-w-xl text-center md:text-left fade-in-right">
                        <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 md:mb-6 drop-shadow-lg">
                            {{ $dept['name'] }} <br class="hidden md:block">Committee
                        </h2>
                        <p class="text-white/90 text-sm md:text-base lg:text-lg leading-relaxed mb-6">
                            {{ $dept['description'] }}
                        </p>
                        <div class="flex flex-wrap gap-2 md:gap-3 justify-center md:justify-start">
                            @foreach ($dept['tags'] as $tag)
                                <span
                                    class="px-3 py-1.5 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs md:text-sm font-medium">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endforeach


    </div>

    <script>
        const container = document.getElementById('main-container');
        const sections = container.querySelectorAll('.snap-section');
        const navBtns = document.querySelectorAll('.nav-btn');
        const sectionLogos = document.querySelectorAll('.section-logo');
        let currentSection = 0;

        // Scroll to specific section
        function scrollToSection(index) {
            if (sections[index]) {
                sections[index].scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        // Update active button and trigger animations
        function updateActiveSection() {
            const scrollTop = container.scrollTop;
            const viewportHeight = window.innerHeight;

            sections.forEach((section, index) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;

                // Check if section is in view
                if (scrollTop >= sectionTop - viewportHeight / 2 && scrollTop < sectionTop + sectionHeight -
                    viewportHeight / 2) {

                    if (currentSection !== index) {
                        currentSection = index;

                        // Update nav buttons - active one becomes circle with faded icon
                        navBtns.forEach((btn, btnIndex) => {
                            btn.classList.toggle('active', btnIndex === index);
                        });

                        // Update in-view class for sections (stronger snap effect)
                        sections.forEach((sec, secIndex) => {
                            sec.classList.toggle('in-view', secIndex === index);
                        });

                        // Show the section logo with scale animation
                        sectionLogos.forEach((logo, logoIndex) => {
                            if (logoIndex === index) {
                                setTimeout(() => {
                                    logo.classList.add('visible');
                                }, 100);
                            } else {
                                logo.classList.remove('visible');
                            }
                        });
                    }

                    // Trigger animations for other elements in this section
                    const animatedElements = section.querySelectorAll(
                        '.fade-in-up, .fade-in-left, .fade-in-right');
                    animatedElements.forEach((el, i) => {
                        setTimeout(() => {
                            el.classList.add('visible');
                        }, i * 150);
                    });
                }
            });
        }

        // Create floating particles for background animation
        function createParticles() {
            const container = document.getElementById('particles-container');
            const particleCount = 15;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';

                // Random sizes
                const size = Math.random() * 100 + 50;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';

                // Random starting positions
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = (Math.random() * 100 + 100) + '%';

                // Random animation delay and duration
                particle.style.animationDelay = Math.random() * 10 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';

                container.appendChild(particle);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Create background particles
            createParticles();

            // Show first section's logo
            if (sectionLogos[0]) {
                sectionLogos[0].classList.add('visible');
            }

            // Trigger initial animations for first section
            setTimeout(() => {
                const firstSection = sections[0];
                const animatedElements = firstSection.querySelectorAll(
                    '.fade-in-up, .fade-in-left, .fade-in-right');
                animatedElements.forEach((el, i) => {
                    setTimeout(() => {
                        el.classList.add('visible');
                    }, i * 200);
                });
            }, 300);

            // Listen for scroll
            container.addEventListener('scroll', () => {
                requestAnimationFrame(updateActiveSection);
            });
        });
    </script>
</body>

</html>
