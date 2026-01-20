@props(['committees'])

<div class="fixed left-4 md:left-8 top-1/2 -translate-y-1/2 z-50 flex flex-col gap-3">
    @foreach ($committees as $index => $committee)
        <div class="w-12 h-12 md:w-14 md:h-14 flex items-center justify-center">
            <button onclick="scrollToSection({{ $index }})"
                class="nav-btn {{ $index === 0 ? 'active' : '' }} w-12 h-12 md:w-14 md:h-14 rounded-xl bg-white/10 backdrop-blur-sm border-2 border-white/30 hover:border-white/60 flex items-center justify-center"
                data-index="{{ $index }}" title="{{ $committee['shortName'] }}">
                @include('committee::components.committee-icon', ['id' => $committee['id'], 'size' => 'nav'])
            </button>
        </div>
    @endforeach
</div>
