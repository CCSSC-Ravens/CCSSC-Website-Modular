@php
    $name = $name ?? 'gallery';
    $label = $label ?? 'Photo Gallery';
    $maxFiles = $maxFiles ?? 10;
    $existingImages = $existingImages ?? [];
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        <span class="text-gray-500 font-normal">(Max {{ $maxFiles }} photos)</span>
    </label>
    
    <div class="space-y-3">
        <input type="file" 
            name="{{ $name }}[]" 
            id="{{ $name }}" 
            accept="image/*"
            multiple
            class="block w-full rounded-lg border border-[#e3e3e0] px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FF4400] focus:border-transparent"
            onchange="handleGalleryUpload(this, {{ $maxFiles }})">
        
        @error($name)
            <p class="text-xs text-red-600">{{ $message }}</p>
        @enderror
        
        <div id="{{ $name }}_preview_container" class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
            <!-- Existing images will be rendered here -->
            @if(count($existingImages) > 0)
                @foreach($existingImages as $index => $image)
                    @php
                        $imageId = is_object($image) && isset($image->id) ? $image->id : $index;
                        $imagePath = is_object($image) && isset($image->image_path) ? $image->image_path : (is_string($image) ? $image : '');
                    @endphp
                    @if($imagePath)
                        <div class="relative group" data-image-id="{{ $imageId }}">
                            <img src="{{ Storage::url($imagePath) }}" 
                                alt="Gallery image {{ $index + 1 }}"
                                class="w-full h-32 rounded-lg object-cover border border-[#e3e3e0]">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-opacity flex items-center justify-center">
                                <button type="button" 
                                    onclick="removeExistingImage(this, '{{ $imageId }}')"
                                    class="opacity-0 group-hover:opacity-100 text-white bg-red-500 hover:bg-red-600 rounded px-2 py-1 text-xs transition-opacity">
                                    Remove
                                </button>
                            </div>
                            <input type="hidden" name="existing_gallery[]" value="{{ $imageId }}">
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        
        <p class="text-xs text-gray-500">Upload multiple images (JPEG, PNG, JPG, GIF, SVG). Max {{ $maxFiles }} files, 5MB each.</p>
        <p id="{{ $name }}_count" class="text-xs text-gray-600 hidden">
            <span id="{{ $name }}_selected_count">0</span> file(s) selected
        </p>
    </div>
</div>

<script>
let selectedFiles = [];
let existingImageIds = [];

function handleGalleryUpload(input, maxFiles) {
    const files = Array.from(input.files);
    const container = document.getElementById('{{ $name }}_preview_container');
    const countElement = document.getElementById('{{ $name }}_count');
    const countSpan = document.getElementById('{{ $name }}_selected_count');
    
    // Check total count (existing + new)
    const existingCount = container.querySelectorAll('[data-new-image]').length;
    const existingSavedCount = container.querySelectorAll('[data-image-id]:not([data-new-image])').length;
    
    if (files.length + existingCount + existingSavedCount > maxFiles) {
        alert(`You can only upload a maximum of ${maxFiles} photos. Please remove some images first.`);
        input.value = '';
        return;
    }
    
    // Clear previous new file previews
    container.querySelectorAll('[data-new-image]').forEach(el => el.remove());
    
    // Add new file previews
    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.setAttribute('data-new-image', 'true');
                div.innerHTML = `
                    <img src="${e.target.result}" 
                        alt="Preview ${index + 1}"
                        class="w-full h-32 rounded-lg object-cover border border-[#e3e3e0]">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-opacity flex items-center justify-center">
                        <button type="button" 
                            onclick="removeNewImage(this)"
                            class="opacity-0 group-hover:opacity-100 text-white bg-red-500 hover:bg-red-600 rounded px-2 py-1 text-xs transition-opacity">
                            Remove
                        </button>
                    </div>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Update count
    const totalNew = container.querySelectorAll('[data-new-image]').length;
    if (totalNew > 0) {
        countSpan.textContent = totalNew;
        countElement.classList.remove('hidden');
    } else {
        countElement.classList.add('hidden');
    }
}

function removeNewImage(button) {
    const container = document.getElementById('{{ $name }}_preview_container');
    const div = button.closest('[data-new-image]');
    if (div) {
        div.remove();
        
        // Update file input
        const input = document.getElementById('{{ $name }}');
        const dt = new DataTransfer();
        const files = Array.from(input.files);
        const index = Array.from(container.querySelectorAll('[data-new-image]')).indexOf(div);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        input.files = dt.files;
        
        // Update count
        const countElement = document.getElementById('{{ $name }}_count');
        const countSpan = document.getElementById('{{ $name }}_selected_count');
        const remaining = container.querySelectorAll('[data-new-image]').length;
        if (remaining > 0) {
            countSpan.textContent = remaining;
        } else {
            countElement.classList.add('hidden');
        }
    }
}

function removeExistingImage(button, imageId) {
    if (confirm('Are you sure you want to remove this image?')) {
        const div = button.closest('[data-image-id]');
        if (div) {
            // Add to removal list
            const hiddenInput = div.querySelector('input[name="existing_gallery[]"]');
            if (hiddenInput) {
                hiddenInput.name = 'removed_gallery[]';
            }
            div.remove();
        }
    }
}
</script>
