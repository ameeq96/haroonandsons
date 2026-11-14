<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostLikeController extends Controller
{
    public function toggle(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Generate a persistent visitor ID in the session.
        $visitorId = $request->session()->get('public_visitor_id');

        if (!$visitorId) {
            $visitorId = (string) Str::uuid();

            $request->session()->put('public_visitor_id', $visitorId);
        }

        // Store only a hashed visitor token in the database.
        $visitorToken = hash('sha256', $visitorId);

        $like = $post->likes()
            ->where('visitor_token', $visitorToken)
            ->first();

        if ($like) {
            $like->delete();

            $liked = false;
            $message = __('site.unliked');
        } else {
            $post->likes()->create([
                'visitor_token' => $visitorToken,
                'ip_hash' => $request->ip()
                    ? hash('sha256', $request->ip())
                    : null,
            ]);

            $liked = true;
            $message = __('site.liked');
        }

        $likesCount = $post->likes()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message,
            ]);
        }

        return back()->with('status', $message);
    }
}