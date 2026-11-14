<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->input('category')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to')))
            ->latest('updated_at')->paginate(15)->withQueryString();

        $categories = Post::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        return view('admin.posts.create', ['post' => new Post()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug((string) ($data['slug'] ?? $data['title']));
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $post = $this->savePost($request, $data);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post created successfully.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validatedData($request, $post);
        $data['slug'] = $this->uniqueSlug((string) ($data['slug'] ?? $data['title']), $post->id);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $post->published_at ?? now()) : null;
        $this->savePost($request, $data, $post);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $files = array_filter(array_merge($post->images, [$post->video_path]));
        DB::transaction(fn () => $post->delete());
        if ($files) Storage::disk('public')->delete($files);

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted successfully.');
    }

    public function preview(Post $post): View
    {
        return view('posts.show', [
            'post' => $post,
            'isPreview' => true,
            'approvedReviews' => collect(),
            'approvedComments' => collect(),
            'likesCount' => $post->likes()->count(),
            'viewsCount' => $post->views()->count(),
            'averageRating' => $post->average_rating,
            'likedByVisitor' => false,
        ]);
    }

    private function validatedData(Request $request, ?Post $post = null): array
    {
        $images = $post?->images ?? [];

        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'title_zh' => ['nullable', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'excerpt_zh' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'max:100000'],
            'content_zh' => ['nullable', 'string', 'max:100000'],
            'category' => ['nullable', 'string', 'max:100'],
            'category_zh' => ['nullable', 'string', 'max:100'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_images' => ['sometimes', 'array'],
            'gallery_images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_order' => ['sometimes', 'array'],
            'image_order.*' => ['required', 'string', 'distinct', Rule::in($images)],
            'remove_images' => ['sometimes', 'array'],
            'remove_images.*' => ['required', 'string', 'distinct', Rule::in($images)],
            'cover_image' => ['nullable', 'string', Rule::in($images)],
            // PHP/server limits also apply. Default application limit: 1 GB per video.
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime,video/x-m4v,video/x-msvideo'],
            'remove_video' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_title_zh' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'meta_description_zh' => ['nullable', 'string', 'max:320'],
        ]);
    }

    private function savePost(Request $request, array $data, ?Post $post = null): Post
    {
        $existingImages = $post?->images ?? [];
        $images = array_values(array_diff(array_unique(array_merge($data['image_order'] ?? [], $existingImages)), $data['remove_images'] ?? []));
        $cover = $data['cover_image'] ?? $post?->featured_image;
        $newFiles = [];
        $oldVideo = $post?->video_path;
        $removeVideo = $request->boolean('remove_video');
        $newVideo = null;

        $uploads = [];
        if ($request->hasFile('featured_image')) $uploads['featured_image'] = $request->file('featured_image');
        foreach ($request->file('gallery_images', []) as $index => $image) $uploads['gallery_images.'.$index] = $image;

        unset($data['featured_image'], $data['gallery_images'], $data['image_order'], $data['remove_images'], $data['cover_image'], $data['video'], $data['remove_video']);

        try {
            foreach ($uploads as $field => $image) {
                $path = $image->store('posts', 'public');
                if (! $path) throw ValidationException::withMessages([$field => 'The image could not be uploaded.']);
                $newFiles[] = $path;
                $images[] = $path;
                if ($field === 'featured_image') {
                    $images = array_values(array_diff($images, array_filter([$post?->featured_image])));
                    $cover = $path;
                }
            }

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $newVideo = $video->store('posts/videos', 'public');
                if (! $newVideo) throw ValidationException::withMessages(['video' => 'The video could not be uploaded. Check your PHP upload limits and free disk space.']);
                $newFiles[] = $newVideo;
                $data['video_path'] = $newVideo;
                $data['video_mime_type'] = $video->getMimeType();
                $data['video_size'] = $video->getSize();
            } elseif ($removeVideo) {
                $data['video_path'] = null;
                $data['video_mime_type'] = null;
                $data['video_size'] = null;
            }

            $data['featured_image'] = in_array($cover, $images, true) ? $cover : ($images[0] ?? null);
            $data['gallery_images'] = array_values(array_diff($images, array_filter([$data['featured_image']])));

            $saved = DB::transaction(function () use ($post, $data): Post {
                if ($post) { $post->update($data); return $post; }
                return Post::create($data);
            });
        } catch (\Throwable $exception) {
            if ($newFiles) Storage::disk('public')->delete($newFiles);
            throw $exception;
        }

        $removedImages = array_values(array_diff($existingImages, $saved->images));
        if ($removedImages) Storage::disk('public')->delete($removedImages);
        if (($removeVideo || $newVideo) && $oldVideo && $oldVideo !== $saved->video_path) Storage::disk('public')->delete($oldVideo);

        return $saved;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'post';
        $slug = $base;
        $suffix = 2;
        while (Post::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) $slug = $base.'-'.$suffix++;
        return $slug;
    }
}
