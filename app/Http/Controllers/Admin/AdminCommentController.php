<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCommentController extends Controller
{
    public function index(): View
    {
        $comments = PostComment::with('post')
            ->latest()
            ->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    public function approve(PostComment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);

        return back()->with('status', 'Comment approved successfully.');
    }

    public function reject(PostComment $comment): RedirectResponse
    {
        $comment->update(['status' => 'rejected']);

        return back()->with('status', 'Comment rejected successfully.');
    }

    public function destroy(PostComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Comment deleted successfully.');
    }
}