<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\AdminNotification;
use App\Observers\PostObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);

        View::composer('layouts.public', function ($view): void {
            $updates = Post::published()
                ->latest('published_at')
                ->limit(5)
                ->get(['id', 'title', 'slug', 'published_at']);

            $readAt = session('public_updates_read_at');
            $unreadUpdates = $readAt
                ? $updates->filter(fn (Post $post): bool => $post->published_at->gt($readAt))->count()
                : $updates->count();

            $view->with(compact('updates', 'unreadUpdates'));
        });

        View::composer('layouts.admin', function ($view): void {
            $view->with('unreadNotifications', AdminNotification::whereNull('read_at')->count());
        });
    }
}
