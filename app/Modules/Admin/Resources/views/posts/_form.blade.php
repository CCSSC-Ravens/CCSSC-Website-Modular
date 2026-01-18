@php
    /** @var \App\Models\Post $post */
@endphp

<div class="space-y-6">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
            placeholder="Enter post title"
            class="block w-full rounded-lg border border-[#e3e3e0] px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FF4400] focus:border-transparent"
            required>
    </div>

    <div>
        <label for="organization_user_id" class="block text-sm font-medium text-gray-700 mb-1">Author</label>
        <select name="organization_user_id" id="organization_user_id"
            class="block w-full rounded-lg border border-[#e3e3e0] px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#FF4400] focus:border-transparent"
            required>
            <option value="">Select an author</option>
            @foreach ($authors as $author)
                <option value="{{ $author->id }}" @selected(old('organization_user_id', $post->organization_user_id) == $author->id)>
                    {{ $author->name }} ({{ $author->position }})
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">The organization member who authored this post.</p>
    </div>

    <div class="border-t border-[#e3e3e0] pt-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Photos</h3>
        <div class="space-y-6">
            @include('admin::components.thumbnail-upload', [
                'name' => 'thumbnail',
                'label' => 'Thumbnail Photo',
                'value' => old('thumbnail', $post->thumbnail ?? null),
                'required' => false
            ])
            
            @include('admin::components.gallery-upload', [
                'name' => 'gallery',
                'label' => 'Photo Gallery',
                'maxFiles' => 10,
                'existingImages' => old('gallery', $post->exists ? $post->gallery : [])
            ])
        </div>
    </div>

    <div>
        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
        <div class="quill-wrapper">
            <textarea name="content" id="content" rows="12"
                placeholder="Write your post content here..."
                class="block w-full rounded-lg border border-[#e3e3e0] px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#FF4400] focus:border-transparent resize-y"
                required>{{ old('content', $post->content ?? '') }}</textarea>
        </div>
        <p class="mt-1 text-xs text-gray-500">Use the rich text editor to format your content with headings, lists, links, and more.</p>
    </div>
</div>
