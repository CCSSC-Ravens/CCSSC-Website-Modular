@props(['label', 'active' => false])

<button
    {{ $attributes->merge(['class' => 'w-full text-left px-6 py-3 bg-[#A42503] hover:bg-[#8a1f02] transition-colors rounded-full group shadow-md']) }}>
    <span class="text-[#FFB800] font-bold italic text-2xl font-['Instrument_Sans'] tracking-wide">
        {{ $label }}
    </span>
</button>
