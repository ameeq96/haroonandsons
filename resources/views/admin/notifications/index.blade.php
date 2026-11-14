@extends('layouts.admin')

@section('title', 'Notifications | Haroon & Sons')
@section('page_heading', 'Notifications')

@section('content')
<section class="admin-panel">
    <div class="panel-heading"><div><p class="admin-kicker">ACTIVITY</p><h2>Post notifications</h2></div>
        @if($notifications->whereNull('read_at')->isNotEmpty())
            <form method="POST" action="{{ route('admin.notifications.read') }}">@csrf<button class="admin-button secondary" type="submit">Mark all as read</button></form>
        @endif
    </div>
    <div class="notification-list">
        @forelse($notifications as $notification)
            <article class="notification-row {{ $notification->read_at ? '' : 'unread' }}">
                <span class="notification-event">{{ ucfirst($notification->event) }}</span>
                <div><h3>{{ $notification->title }}</h3><p>{{ $notification->message }}</p><time>{{ $notification->created_at->format('M j, Y \a\t g:i A') }}</time></div>
                @if($notification->post_id)<a class="table-link" href="{{ route('admin.posts.edit', $notification->post_id) }}">Open post</a>@endif
            </article>
        @empty
            <div class="admin-empty"><h3>No activity yet</h3><p>Notifications appear here when posts are created, changed, published, or deleted.</p></div>
        @endforelse
    </div>
    <div class="pagination-wrap">{{ $notifications->links() }}</div>
</section>
@endsection
