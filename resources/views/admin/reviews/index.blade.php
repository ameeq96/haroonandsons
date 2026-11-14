@extends('layouts.admin')

@section('title', 'Customer Reviews')
@section('page_heading', 'Customer Reviews')

@section('content')
<section class="admin-panel">
    <div class="panel-heading">
        <div>
            <p class="admin-kicker">CUSTOMER FEEDBACK</p>
            <h2>Manage Reviews</h2>
        </div>
    </div>

    @forelse($reviews as $review)
        <article class="admin-panel" style="margin-bottom:15px">
            <h3>{{ $review->reviewer_name }}</h3>
            <p>
                Post: {{ $review->post?->title ?? 'Deleted post' }}
            </p>
            <p>Rating: {{ $review->rating }}/5</p>
            <p>{{ $review->review_text }}</p>
            <p>Status: {{ ucfirst($review->status) }}</p>

            <div class="form-actions">
                @if($review->status !== 'approved')
                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                        @csrf
                        @method('PATCH')
                        <button class="admin-button" type="submit">Approve</button>
                    </form>
                @endif

                @if($review->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                        @csrf
                        @method('PATCH')
                        <button class="admin-button" type="submit">Reject</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                    onsubmit="return confirm('Delete this review?')">
                    @csrf
                    @method('DELETE')
                    <button class="admin-button" type="submit">Delete</button>
                </form>
            </div>
        </article>
    @empty
        <p>No customer reviews yet.</p>
    @endforelse

    {{ $reviews->links() }}
</section>
@endsection