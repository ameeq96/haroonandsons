<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function index(): View
    {
        $notifications = AdminNotification::latest()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllRead(): RedirectResponse
    {
        AdminNotification::whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('status', 'Notifications marked as read.');
    }
}
