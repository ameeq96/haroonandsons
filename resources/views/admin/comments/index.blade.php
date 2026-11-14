@extends('layouts.admin')

@section('title', 'Customer Comments')
@section('page_heading', 'Customer Comments')

@section('content')
<section class="admin-panel">
    <div class="panel-heading">
        <div>
            <p class="admin-kicker">CUSTOMER DISCUSSION</p>
            <h2>Manage Comments</h2>
        </div>
    </div>

    @forelse($comments as $comment)
        <article class="admin-panel" style="margin-bottom:15px">
            <h3>{{ $comment->commenter_name }}</h3>
            <p>Post: {{ $comment->post?->title ?? 'Deleted post' }}</p>
            <p>{{ $comment->comment_text }}</p>
            <p>Status: {{ ucfirst($comment->status) }}</p>

            <div class="form-actions">
                @if($comment->status !== 'approved')
                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                        @csrf
                        @method('PATCH')
                        <button class="admin-button" type="submit">Approve</button>
                    </form>
                @endif

                @if($comment->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.comments.reject', $comment) }}">
                        @csrf
                        @method('PATCH')
                        <button class="admin-button" type="submit">Reject</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                    onsubmit="return confirm('Delete this comment?')">
                    @csrf
                    @method('DELETE')
                    <button class="admin-button" type="submit">Delete</button>
                </form>
            </div>
        </article>
    @empty
        <p>No customer comments yet.</p>
    @endforelse

    {{ $comments->links() }}
</section>
@endsection