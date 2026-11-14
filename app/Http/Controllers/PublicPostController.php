<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicPostController extends Controller
{
    public function home(): View
    {
        $latestPosts = Post::published()->latest('published_at')->limit(9)->get();
        return view('home', compact('latestPosts'));
    }

    public function index(Request $request): View
    {
        $posts = Post::published()
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->input('category')))
            ->latest('published_at')->paginate(9)->withQueryString();
        $categories = Post::published()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        return view('posts.index', compact('posts', 'categories'));
    }

    public function show(Request $request, string $slug): View
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();
        $visitorId = $request->session()->get('public_visitor_id');
        if (! $visitorId) {
            $visitorId = (string) Str::uuid();
            $request->session()->put('public_visitor_id', $visitorId);
        }
        $visitorToken = hash('sha256', $visitorId);

        // Count one view per visitor per post in each rolling 24-hour period.
        $recentView = $post->views()->where('visitor_token', $visitorToken)
            ->where('viewed_at', '>=', now()->subDay())->exists();
        if (! $recentView) {
            PostView::create([
                'post_id' => $post->id,
                'visitor_token' => $visitorToken,
                'ip_hash' => $request->ip() ? hash('sha256', $request->ip()) : null,
                'viewed_at' => now(),
            ]);
        }

        $approvedReviews = $post->approvedReviews()->latest()->limit(30)->get();
        $approvedComments = $post->approvedComments()->latest()->limit(50)->get();
        $likesCount = $post->likes()->count();
        $viewsCount = $post->views()->count();
        $averageRating = $post->reviews()->where('status', 'approved')->avg('rating');
        $likedByVisitor = $post->likes()->where('visitor_token', $visitorToken)->exists();

        return view('posts.show', compact('post', 'approvedReviews', 'approvedComments', 'likesCount', 'viewsCount', 'averageRating', 'likedByVisitor'));
    }

    public function markUpdatesRead(Request $request): RedirectResponse
    {
        $request->session()->put('public_updates_read_at', now()->toIso8601String());
        return back()->with('status', 'Latest updates marked as read.');
    }
}
