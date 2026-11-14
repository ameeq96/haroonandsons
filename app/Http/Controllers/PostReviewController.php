<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostReviewController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $validated = $request->validate([
            'reviewer_name' => ['required', 'string', 'max:100'],
            'reviewer_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review_text' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $post->reviews()->create([
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'] ?? null,
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'status' => 'pending',
        ]);

        return back()->with(
            'status',
            __('site.reviews_success')
        );
    }
}