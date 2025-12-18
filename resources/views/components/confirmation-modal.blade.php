@props([])

<div 
    x-data="{ 
        open: false, 
        title: '', 
        message: '', 
        confirmText: 'Confirm',
        actionUrl: '', 
        actionMethod: 'POST'
    }" 
    @open-confirmation-modal.window="
        open = true;
        title = $event.detail.title;
        message = $event.detail.message;
        confirmText = $event.detail.confirmText;
        actionUrl = $event.detail.actionUrl;
        actionMethod = $event.detail.actionMethod || 'POST';
    "
    x-show="open"
    style="display: none;"
    class="fixed inset-0 z-[60] flex items-center justify-center px-4"
>
    <!-- Overlay -->
    <div 
        x-show="open" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/80 backdrop-blur-sm" 
        @click="open = false"
    ></div>

    <!-- Modal Card -->
    <div 
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full max-w-[450px] bg-[#09090b] border border-[#27272a] rounded-xl shadow-2xl overflow-hidden"
    >
        <!-- Body -->
        <div class="p-6 flex gap-5">
            <!-- Icon -->
            <div class="shrink-0 w-12 h-12 rounded-full bg-[#1f1212]/50 border border-[#3f1d1d] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#ef4444]">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" x2="9" y1="12" y2="12"></line>
                </svg>
            </div>

            <!-- Text -->
            <div class="pt-1">
                <h3 class="text-xl font-bold text-white leading-tight mb-2" x-text="title"></h3>
                <p class="text-[#a1a1aa] text-[15px] leading-relaxed" x-text="message"></p>
            </div>
        </div>

        <!-- Footer / Actions -->
        <div class="bg-[#101012] px-6 py-4 flex items-center justify-end gap-3 border-t border-[#27272a]/50">
            <button 
                @click="open = false" 
                class="px-4 py-2.5 text-[15px] font-semibold text-white hover:text-gray-300 transition-colors"
            >
                Cancel
            </button>
            
            <form :action="actionUrl" :method="actionMethod === 'GET' ? 'GET' : 'POST'">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <template x-if="['PUT', 'PATCH', 'DELETE'].includes(actionMethod)">
                    <input type="hidden" name="_method" :value="actionMethod">
                </template>

                <button 
                    type="submit" 
                    class="px-6 py-2.5 text-[15px] font-bold text-white bg-[#dc2626] hover:bg-[#b91c1c] rounded-lg transition-all shadow-[0_4px_12px_rgba(220,38,38,0.2)]"
                    x-text="confirmText"
                >
                </button>
            </form>
        </div>
    </div>
</div>
