<footer class="w-full bg-gradient-to-b from-white/90 to-[#B13407]/20 backdrop-blur-md pt-10 pb-5 text-black">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-8 border-b border-[#B13407]/20 pb-8">
            <!-- Brand & Contact -->
            <div class="w-full md:w-2/5 space-y-4">
                <div class="flex items-center gap-3">
                    <!-- Placeholder Logo -->
                    <img src="{{ asset('images/ccs_logo_transparent.png') }}" alt="GCCCS Logo"
                        class="h-15 w-15 object-contain" />
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-[#B13407] to-orange-800"
                        style="font-family: 'Instrument Sans', sans-serif;">GCCCS Student Council</span>
                </div>

                <p class="text-sm leading-relaxed text-black max-w-sm">
                    The official website of Gordon College - <br>
                    College of Computer Studies Student Council.
                </p>

                <div class="text-sm text-black space-y-1">
                    <p>Donor Street, East Tapinac 2200 Olongapo City Zambales</p>
                    <p>Email us at: <a href="mailto:ccssc@gordoncollege.edu.ph"
                            class="text-black hover:underline hover:text-orange-900 transition-colors">ccssc@gordoncollege.edu.ph</a>
                    </p>
                </div>

                <!-- Social Icons -->
                <div class="flex items-center gap-3 pt-2">
                    <a href="https://facebook.com/gc.ccs.sc" target="_blank"
                        class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-black hover:bg-[#B13407] hover:text-white hover:border-[#B13407] transition-all duration-300 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M22.675 0h-21.35C.595 0 0 .592 0 1.326v21.348C0 23.408.595 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.797.143v3.24l-1.918.001c-1.504 0-1.797.715-1.797 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.408 24 22.674V1.326C24 .592 23.406 0 22.675 0z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div class="w-full md:w-3/5 flex flex-col md:flex-row gap-8 ">
                <div class="min-w-[150px]">
                    <h3 class="font-bold text-black mb-4 text-base">Discover</h3>
                    <ul class="space-y-2 text-sm text-black">
                        <li><a href="#" class="hover:text-orange-900 transition-colors">Home</a></li>
                        <li><a href="#" class="hover:text-orange-900 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-orange-900 transition-colors">Committees</a></li>
                        <li><a href="#" class="hover:text-orange-900 transition-colors">News</a></li>
                        {{-- <li><a href="#" class="hover:text-orange-900 transition-colors">Contact</a></li> --}}
                    </ul>
                </div>
                {{--
                <div class="flex-1 min-w-[150px]">
                    <h3 class="font-bold text-black mb-4 text-base">Explore</h3>
                    <ul class="space-y-2 text-sm text-black">
                        <li><a href="#" class="hover:text-orange-900 transition-colors">Programs</a></li>
                        <li><a href="#" class="hover:text-orange-900 transition-colors">Events</a></li>
                        <li><a href="#" class="hover:text-orange-900 transition-colors">Projects</a></li>
                    </ul>
                </div>
                --}}
            </div>
        </div>

        <div class="pt-6 text-center md:text-left text-xs text-black">
            <p>&copy; {{ date('Y') }} GC-CCS Student Council. All rights reserved.</p>
        </div>
    </div>
</footer>
