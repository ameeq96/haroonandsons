@extends('layouts.admin')

@section('title', 'Analytics')
@section('page_heading', 'Website Analytics')

@section('content')
<section class="admin-panel">
    <p class="admin-kicker">WEBSITE PERFORMANCE</p>
    <h2>Overview</h2>

    <div class="stats-grid">
        @foreach([
            'Total Posts' => 'posts',
            'Total Reviews' => 'reviews',
            'Pending Reviews' => 'pending_reviews',
            'Total Comments' => 'comments',
            'Pending Comments' => 'pending_comments',
            'Total Likes' => 'likes',
            'Total Views' => 'views',
            'Total Videos' => 'videos'
        ] as $label => $key)
            <article class="stat-card">
                <span>{{ $label }}</span>
                <strong>{{ number_format($stats[$key] ?? 0) }}</strong>
            </article>
        @endforeach
    </div>
</section>

<section class="admin-panel">
    <h2>Recent Posts</h2>

    @forelse($recentPosts as $post)
        <div style="padding:12px 0;border-bottom:1px solid #ddd">
            <strong>{{ $post->title }}</strong>
            <span>{{ ucfirst($post->status) }}</span>
        </div>
    @empty
        <p>No posts available.</p>
    @endforelse
</section>
@endsection