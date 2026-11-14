<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $validated = $request->validate([
            'commenter_name' => ['required', 'string', 'max:100'],
            'commenter_email' => ['nullable', 'email', 'max:255'],
            'comment_text' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        $post->comments()->create([
            'commenter_name' => $validated['commenter_name'],
            'commenter_email' => $validated['commenter_email'] ?? null,
            'comment_text' => $validated['comment_text'],
            'status' => 'pending',
        ]);

        return back()->with(
            'status',
            __('site.comments_success')
        );
    }
}