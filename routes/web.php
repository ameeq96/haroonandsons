<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\AdminPasswordController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PostReviewController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\PublicPostController;
use Illuminate\Support\Facades\Route;

// ─── Language Switcher ────────────────────────────────────────────────────────
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->where('locale', 'en|zh')
    ->name('language.switch');

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', [PublicPostController::class, 'home'])->name('home');
Route::get('/posts', [PublicPostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PublicPostController::class, 'show'])->name('posts.show');
Route::post('/updates/read', [PublicPostController::class, 'markUpdatesRead'])->name('updates.read');

// ─── Post Engagement (Public) ─────────────────────────────────────────────────
Route::post('/posts/{slug}/reviews', [PostReviewController::class, 'store'])
    ->name('posts.reviews.store')
    ->middleware('throttle:10,60');

Route::post('/posts/{slug}/comments', [PostCommentController::class, 'store'])
    ->name('posts.comments.store')
    ->middleware('throttle:10,60');

Route::post('/posts/{slug}/like', [PostLikeController::class, 'toggle'])
    ->name('posts.like.toggle')
    ->middleware('throttle:30,1');

// ─── Public Video Gallery ─────────────────────────────────────────────────────

// ─── Admin Auth ───────────────────────────────────────────────────────────────
Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');

// ─── Admin Panel (Protected) ──────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function (): void {
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    // Posts (existing)
    Route::resource('posts', AdminPostController::class)->except(['show']);
    Route::get('/posts/{post}/preview', [AdminPostController::class, 'preview'])->name('posts.preview');

    // Notifications (existing)
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read');

   Route::get('/analytics', [AnalyticsController::class, 'index'])
    ->name('analytics');

    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Comments
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::patch('/comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::patch('/comments/{comment}/reject', [AdminCommentController::class, 'reject'])->name('comments.reject');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

    // Videos

    // Password
    Route::get('/password', [AdminPasswordController::class, 'edit'])->name('password.edit');
    Route::patch('/password', [AdminPasswordController::class, 'update'])->name('password.update');
});
