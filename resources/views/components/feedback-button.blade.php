{{-- Floating Feedback Button - Bottom Right --}}
{{-- Include Alpine.js if not already loaded --}}
@once
    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @endpush
    @if(!isset($__alpinejs_loaded))
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
        @php $__alpinejs_loaded = true; @endphp
    @endif
@endonce

<div x-data="{
    open: false,
    step: 1,
    totalSteps: 3,
    name: '',
    course: '',
    category: '',
    othersText: '',
    message: '',
    submitted: false,
    categories: ['Website', 'Event', 'Academics', 'Others'],
    
    nextStep() {
        if (this.step < this.totalSteps) this.step++;
    },
    prevStep() {
        if (this.step > 1) this.step--;
    },
    canProceed() {
        if (this.step === 1) return true; // Optional fields
        if (this.step === 2) {
            if (this.category === 'Others') {
                return this.othersText.trim() !== '';
            }
            return this.category !== '';
        }
        if (this.step === 3) return this.message.trim() !== '';
        return true;
    },
    getCategory() {
        return this.category === 'Others' && this.othersText.trim() ? 'Others: ' + this.othersText : this.category;
    },
    reset() {
        this.step = 1;
        this.name = '';
        this.course = '';
        this.category = '';
        this.othersText = '';
        this.message = '';
        this.submitted = false;
    },
    submitFeedback() {
        if (this.message.trim()) {
            const finalCategory = this.getCategory();
            const subject = 'Website Feedback' + (finalCategory ? ' - ' + finalCategory : '') + (this.name ? ' from ' + this.name : '');
            const body = 'Name: ' + (this.name || 'Anonymous') + 
                        '%0D%0ACourse/Block/Section: ' + (this.course || 'Not provided') + 
                        '%0D%0ACategory: ' + finalCategory +
                        '%0D%0A%0D%0AFeedback:%0D%0A' + this.message;
            window.location.href = 'mailto:ccssc@gordoncollege.edu.ph?subject=' + encodeURIComponent(subject) + '&body=' + body;
            this.submitted = true;
            setTimeout(() => {
                this.reset();
                this.open = false;
            }, 3000);
        }
    },
    close() {
        this.open = false;
        setTimeout(() => this.reset(), 300);
    }
}" class="fixed bottom-6 right-6 z-50">
    {{-- Feedback Button --}}
    <button 
        x-show="!open"
        @click="open = true"
        class="group flex items-center gap-2 bg-[#B13407] hover:bg-[#8B0000] text-white px-4 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
        aria-label="Send Feedback"
    >
        {{-- Feedback Icon --}}
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span class="font-medium text-sm">Feedback</span>
    </button>

    {{-- Backdrop Overlay --}}
    <div 
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="fixed inset-0 bg-black/50 z-40"
    ></div>

    {{-- Feedback Form Modal - Centered --}}
    <div 
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] max-w-[95vw] bg-white rounded-2xl shadow-2xl overflow-hidden z-50"
    >
        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#E85A24] to-[#D14B18] px-6 py-5 relative">
            <h3 class="text-white font-bold text-xl">Share Your Feedback</h3>
            <p class="text-white/80 text-sm mt-1">Basic Information</p>
            {{-- Close Button --}}
            <button 
                @click="close()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Step Indicator --}}
        <div class="px-6 py-6">
            <div class="flex items-center justify-center gap-2">
                <template x-for="i in totalSteps" :key="i">
                    <div class="flex items-center">
                        {{-- Step Circle --}}
                        <div 
                            class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 border-2"
                            :class="{
                                'bg-[#E85A24] border-[#E85A24] text-white': step === i,
                                'bg-white border-[#E85A24] text-[#E85A24]': step > i,
                                'bg-white border-gray-300 text-gray-400': step < i
                            }"
                        >
                            <template x-if="step > i">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </template>
                            <template x-if="step <= i">
                                <span x-text="i"></span>
                            </template>
                        </div>
                        {{-- Connector Line --}}
                        <div 
                            x-show="i < totalSteps"
                            class="w-8 h-0.5 mx-1 transition-colors duration-300"
                            :class="step > i ? 'bg-[#E85A24]' : 'bg-gray-300'"
                        ></div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Form Content --}}
        <div class="px-6 pb-4 min-h-[200px]">
            {{-- Success Message --}}
            <div x-show="submitted" x-cloak class="text-center py-8">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2">Thank You!</h4>
                <p class="text-gray-600 text-sm">Your feedback has been submitted successfully.</p>
            </div>

            {{-- Step 1: Basic Information --}}
            <div x-show="step === 1 && !submitted" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-gray-400">(optional)</span></label>
                        <input 
                            type="text" 
                            x-model="name"
                            placeholder="Enter your name"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#E85A24]/20 focus:border-[#E85A24] outline-none transition-all text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Course, Block, Section <span class="text-gray-400">(optional)</span></label>
                        <input 
                            type="text" 
                            x-model="course"
                            placeholder="Enter your Course, Block, Section"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#E85A24]/20 focus:border-[#E85A24] outline-none transition-all text-sm"
                        >
                    </div>
                </div>
            </div>

            {{-- Step 2: Category --}}
            <div x-show="step === 2 && !submitted" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <p class="text-gray-700 font-medium mb-4 text-center">What is your feedback about?</p>
                <div class="grid grid-cols-2 gap-3">
                    <template x-for="cat in categories" :key="cat">
                        <button 
                            type="button"
                            @click="category = cat; if(cat !== 'Others') othersText = '';"
                            class="px-4 py-3 border-2 rounded-lg text-sm font-medium transition-all duration-200"
                            :class="category === cat 
                                ? 'border-[#E85A24] bg-[#E85A24]/5 text-[#E85A24]' 
                                : 'border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50'"
                            x-text="cat"
                        ></button>
                    </template>
                </div>
                {{-- Others Text Field --}}
                <div 
                    x-show="category === 'Others'" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-4"
                >
                    <label class="block text-sm font-medium text-gray-700 mb-2">Please specify</label>
                    <input 
                        type="text" 
                        x-model="othersText"
                        placeholder="Enter your feedback category..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#E85A24]/20 focus:border-[#E85A24] outline-none transition-all text-sm"
                    >
                </div>
            </div>

            {{-- Step 3: Message --}}
            <div x-show="step === 3 && !submitted" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your feedback</label>
                    <textarea 
                        x-model="message"
                        rows="5"
                        placeholder="Tell us about your experience..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#E85A24]/20 focus:border-[#E85A24] outline-none transition-all text-sm resize-none"
                    ></textarea>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div x-show="!submitted" class="px-6 pb-6 flex items-center justify-between">
            <button 
                type="button"
                @click="prevStep()"
                class="flex items-center gap-1 text-gray-400 hover:text-gray-600 text-sm font-medium transition-colors disabled:opacity-50"
                :class="{ 'invisible': step === 1 }"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>
            
            <button 
                type="button"
                @click="step === totalSteps ? submitFeedback() : nextStep()"
                :disabled="!canProceed()"
                class="flex items-center gap-1 bg-[#E85A24] hover:bg-[#D14B18] disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors"
            >
                <span x-text="step === totalSteps ? 'Submit' : 'Next'"></span>
                <svg x-show="step !== totalSteps" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- Alpine.js x-cloak style --}}
<style>
    [x-cloak] { display: none !important; }
</style>
