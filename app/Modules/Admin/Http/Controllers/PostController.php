<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\OrganizationUser;
use App\Models\Post;
use App\Models\PostGallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Post::class);

        $search = $request->string('search')->toString();
        $authorId = $request->integer('author_id') ?: null;

        $posts = Post::query()
            ->with('organizationUser')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($authorId, fn ($query) => $query->where('organization_user_id', $authorId))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $authors = OrganizationUser::orderBy('name')->get();

        return view('admin::posts.index', [
            'posts' => $posts,
            'authors' => $authors,
            'filters' => [
                'search' => $search,
                'author_id' => $authorId,
            ],
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        Gate::authorize('create', Post::class);

        $authors = OrganizationUser::orderBy('name')->get();

        return view('admin::posts.create', [
            'post' => new Post(),
            'authors' => $authors,
        ]);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'organization_user_id' => ['required', 'exists:organization_users,id'],
                'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
                'gallery' => ['nullable', 'array', 'max:10'],
                'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            ]);

            // Sanitize HTML content using HTMLPurifier
            $validated['content'] = Purifier::clean($validated['content'], 'quill');

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('posts/thumbnails', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $post = Post::create($validated);

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                $galleryFiles = $request->file('gallery');
                foreach ($galleryFiles as $index => $file) {
                    $galleryPath = $file->store('posts/gallery', 'public');
                    PostGallery::create([
                        'post_id' => $post->id,
                        'image_path' => $galleryPath,
                        'order' => $index,
                    ]);
                }
            }

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create post. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        $authors = OrganizationUser::orderBy('name')->get();
        $post->load('gallery');

        return view('admin::posts.edit', [
            'post' => $post,
            'authors' => $authors,
        ]);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'organization_user_id' => ['required', 'exists:organization_users,id'],
                'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
                'gallery' => ['nullable', 'array', 'max:10'],
                'gallery.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
                'removed_gallery' => ['nullable', 'array'],
                'removed_gallery.*' => ['exists:post_galleries,id'],
            ]);

            // Sanitize HTML content using HTMLPurifier
            $validated['content'] = Purifier::clean($validated['content'], 'quill');

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if it exists
                if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                    Storage::disk('public')->delete($post->thumbnail);
                }

                $thumbnailPath = $request->file('thumbnail')->store('posts/thumbnails', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $post->update($validated);

            // Handle removed gallery images
            if ($request->has('removed_gallery')) {
                $removedIds = $request->input('removed_gallery');
                $removedImages = PostGallery::whereIn('id', $removedIds)
                    ->where('post_id', $post->id)
                    ->get();

                foreach ($removedImages as $image) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }

            // Handle new gallery uploads
            if ($request->hasFile('gallery')) {
                $existingCount = $post->gallery()->count();
                $galleryFiles = $request->file('gallery');
                
                // Check total count
                if ($existingCount + count($galleryFiles) > 10) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Maximum 10 gallery images allowed. Please remove some existing images first.');
                }

                foreach ($galleryFiles as $index => $file) {
                    $galleryPath = $file->store('posts/gallery', 'public');
                    PostGallery::create([
                        'post_id' => $post->id,
                        'image_path' => $galleryPath,
                        'order' => $existingCount + $index,
                    ]);
                }
            }

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update post. Please try again.');
        }
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        try {
            // Delete thumbnail if it exists
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            // Delete gallery images
            foreach ($post->gallery as $galleryImage) {
                if (Storage::disk('public')->exists($galleryImage->image_path)) {
                    Storage::disk('public')->delete($galleryImage->image_path);
                }
            }

            $post->delete();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete post. Please try again.');
        }
    }
}
