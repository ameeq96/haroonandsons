<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $stats = [
            'posts' => DB::table('posts')->count(),
            'reviews' => DB::table('post_reviews')->count(),
            'pending_reviews' => DB::table('post_reviews')
                ->where('status', 'pending')->count(),
            'comments' => DB::table('post_comments')->count(),
            'pending_comments' => DB::table('post_comments')
                ->where('status', 'pending')->count(),
            'likes' => DB::table('post_likes')->count(),
            'views' => DB::table('post_views')->count(),
            'videos' => DB::table('posts')->whereNotNull('video_path')->count(),
        ];

        $recentPosts = DB::table('posts')
            ->select('id', 'title', 'status', 'created_at')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.analytics', compact('stats', 'recentPosts'));
    }
}