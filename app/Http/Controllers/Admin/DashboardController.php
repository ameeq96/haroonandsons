<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'drafts' => Post::where('status', 'draft')->count(),
        ];
        $recentPosts = Post::latest('updated_at')->limit(6)->get();
        $notifications = AdminNotification::latest()->limit(6)->get();
        $unreadNotifications = AdminNotification::whereNull('read_at')->count();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'notifications', 'unreadNotifications'));
    }
}
