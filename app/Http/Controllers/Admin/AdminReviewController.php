<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    public function index(): View
    {
        $reviews = PostReview::with('post')
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(PostReview $review): RedirectResponse
    {
        $review->update(['status' => 'approved']);

        return back()->with('status', 'Review approved successfully.');
    }

    public function reject(PostReview $review): RedirectResponse
    {
        $review->update(['status' => 'rejected']);

        return back()->with('status', 'Review rejected successfully.');
    }

    public function destroy(PostReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Review deleted successfully.');
    }
}