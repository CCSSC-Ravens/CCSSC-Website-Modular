@php
    $name = $name ?? 'thumbnail';
    $label = $label ?? 'Thumbnail Photo';
    $value = $value ?? null;
    $required = $required ?? false;
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <div class="space-y-3">
        <input type="file" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            accept="image/*"
            class="block w-full rounded-lg border border-[#e3e3e0] px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FF4400] focus:border-transparent"
            onchange="previewThumbnail(this)">
        
        @error($name)
            <p class="text-xs text-red-600">{{ $message }}</p>
        @enderror
        
        <div id="{{ $name }}_preview" class="hidden">
            <p class="text-xs text-gray-500 mb-1">Preview:</p>
            <img id="{{ $name }}_preview_img" 
                src="" 
                alt="Thumbnail preview"
                class="w-32 h-32 rounded-lg object-cover border border-[#e3e3e0]">
        </div>
        
        @if($value)
            <div class="mt-2">
                <p class="text-xs text-gray-500 mb-1">Current thumbnail:</p>
                <img src="{{ Storage::url($value) }}" 
                    alt="Current thumbnail"
                    class="w-32 h-32 rounded-lg object-cover border border-[#e3e3e0]"
                    id="{{ $name }}_current">
            </div>
        @endif
        
        <p class="text-xs text-gray-500">Upload an image (JPEG, PNG, JPG, GIF, SVG). Max size: 5MB.</p>
    </div>
</div>

<script>
function previewThumbnail(input) {
    const previewDiv = document.getElementById('{{ $name }}_preview');
    const previewImg = document.getElementById('{{ $name }}_preview_img');
    const currentImg = document.getElementById('{{ $name }}_current');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.classList.remove('hidden');
            if (currentImg) {
                currentImg.style.display = 'none';
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewDiv.classList.add('hidden');
        if (currentImg) {
            currentImg.style.display = 'block';
        }
    }
}
</script>
