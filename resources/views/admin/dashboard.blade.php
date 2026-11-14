@extends('layouts.admin')

@section('title', 'Dashboard | Haroon & Sons')
@section('page_heading', 'Dashboard')

@section('content')
<section class="dashboard-welcome" aria-label="Your publishing workspace">
    <div class="welcome-copy">
        <p class="admin-kicker">YOUR PUBLISHING DESK</p>
        <h2>Good stories deserve<br><em>a place to be seen.</em></h2>
        <p>Share the news, moments, and milestones that move Haroon &amp; Sons forward.</p>
        <div class="welcome-actions"><a class="admin-button primary" href="{{ route('admin.posts.create') }}"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-plus"/></svg>Create a post</a><a class="admin-button welcome-secondary" href="{{ route('admin.posts.index') }}">Manage posts <svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-arrow"/></svg></a></div>
    </div>
    <div class="welcome-art" aria-hidden="true"><span class="welcome-line"></span><span class="welcome-line"></span><span class="welcome-line"></span><span class="welcome-monogram">H<span>&amp;</span>S</span><span class="welcome-art-caption">HAROON &amp; SONS<br>STORIES WORTH SHARING</span></div>
</section>
<section class="stats-grid" aria-label="Post summary">
    <article class="stat-card stat-total"><div class="stat-card-top"><span>Total posts</span><span class="stat-icon"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-posts"/></svg></span></div><strong>{{ number_format($stats['total']) }}</strong><small>Your complete content collection</small></article>
    <article class="stat-card stat-published"><div class="stat-card-top"><span>Published</span><span class="stat-icon"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-check"/></svg></span></div><strong>{{ number_format($stats['published']) }}</strong><small>Posts marked as published</small></article>
    <article class="stat-card stat-draft"><div class="stat-card-top"><span>Drafts</span><span class="stat-icon"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-draft"/></svg></span></div><strong>{{ number_format($stats['drafts']) }}</strong><small>Ideas waiting for their moment</small></article>
</section>
<div class="dashboard-columns">
    <section class="admin-panel">
        <div class="panel-heading"><div><p class="admin-kicker">YOUR CONTENT</p><h2>Recent posts</h2></div><a class="admin-button secondary" href="{{ route('admin.posts.create') }}"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-plus"/></svg>Create post</a></div>
        @if($recentPosts->isEmpty())
            <div class="admin-empty dashboard-empty"><span class="empty-icon"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-draft"/></svg></span><h3>Your first post starts here</h3><p>A company milestone. A new idea. A story worth sharing.<br>Bring your website to life with your first update.</p><a class="admin-button primary" href="{{ route('admin.posts.create') }}">Create a post <svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-arrow"/></svg></a></div>
        @else
            <div class="table-scroll"><table class="admin-table"><thead><tr><th>Title</th><th>Status</th><th>Last updated</th><th></th></tr></thead><tbody>
                @foreach($recentPosts as $post)
                    <tr><td><div class="recent-post-title">@if($post->featured_image_url)<img class="recent-post-thumbnail" src="{{ $post->featured_image_url }}" alt="">@else<span class="recent-post-placeholder"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-posts"/></svg></span>@endif<div><strong>{{ $post->title }}</strong><small>{{ $post->category ?: 'No category' }}</small></div></div></td><td><span class="status-pill {{ $post->status }}">{{ ucfirst($post->status) }}</span></td><td>{{ $post->updated_at->format('M j, Y') }}</td><td><a class="table-link" href="{{ route('admin.posts.edit', $post) }}">Edit <span aria-hidden="true">&#8599;</span></a></td></tr>
                @endforeach
            </tbody></table></div>
        @endif
    </section>
    <section class="admin-panel activity-panel">
        <div class="panel-heading"><div><p class="admin-kicker">THE LATEST</p><h2>Activity feed</h2></div><a class="table-link" href="{{ route('admin.notifications.index') }}">View all <span aria-hidden="true">&#8599;</span></a></div>
        @forelse($notifications as $notification)
            <article class="activity-item {{ $notification->read_at ? '' : 'unread' }}"><span class="activity-dot"></span><div><strong>{{ $notification->title }}</strong><p>{{ $notification->message }}</p><time>{{ $notification->created_at->diffForHumans() }}</time></div></article>
        @empty
            <div class="admin-empty compact activity-empty"><span class="empty-icon"><svg class="admin-icon" aria-hidden="true"><use href="#admin-icon-bell"/></svg></span><h3>A fresh start</h3><p>Your publishing activity will appear here as you create and update posts.</p></div>
        @endforelse
    </section>
</div>
@endsection
