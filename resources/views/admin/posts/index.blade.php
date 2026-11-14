@extends('layouts.admin')

@section('title', 'Manage posts | Haroon & Sons')
@section('page_heading', 'Manage posts')

@section('content')
<section class="admin-panel">
    <div class="panel-heading"><div><p class="admin-kicker">PUBLISHING</p><h2>All posts</h2></div><a class="admin-button primary" href="{{ route('admin.posts.create') }}">Create post</a></div>
    <form class="filters-form" method="GET" action="{{ route('admin.posts.index') }}">
        <label class="filter-search"><span>Search title</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Search posts"></label>
        <label><span>Status</span><select name="status"><option value="">All statuses</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option></select></label>
        <label><span>Category</span><select name="category"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>@endforeach</select></label>
        <label><span>From date</span><input type="date" name="from" value="{{ request('from') }}"></label>
        <label><span>To date</span><input type="date" name="to" value="{{ request('to') }}"></label>
        <button class="admin-button secondary" type="submit">Apply filters</button>
        @if(request()->hasAny(['q', 'status', 'category', 'from', 'to']))<a class="clear-filters" href="{{ route('admin.posts.index') }}">Clear</a>@endif
    </form>
    @if($posts->isEmpty())
        <div class="admin-empty"><h3>No posts found</h3><p>Try changing the filters or create a new post.</p></div>
    @else
        <div class="table-scroll"><table class="admin-table"><thead><tr><th>Post</th><th>Category</th><th>Status</th><th>Published</th><th>Updated</th><th>Actions</th></tr></thead><tbody>
            @foreach($posts as $post)
                <tr>
                    <td class="post-title-cell">@if($post->featured_image)<img src="{{ $post->featured_image_url }}" alt="">@endif<div><strong>{{ $post->title }}</strong><small>/posts/{{ $post->slug }}</small>@if($post->video_path)<small class="video-attached-label">Video attached</small>@endif</div></td>
                    <td>{{ $post->category ?: '—' }}</td>
                    <td><span class="status-pill {{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                    <td>{{ $post->published_at?->format('M j, Y') ?: '—' }}</td>
                    <td>{{ $post->updated_at->format('M j, Y') }}</td>
                    <td><div class="table-actions"><a class="table-link" href="{{ route('admin.posts.edit', $post) }}">Edit</a><a class="table-link" href="{{ route('admin.posts.preview', $post) }}" target="_blank" rel="noopener">Preview</a><form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post and its featured image? This cannot be undone.')">@csrf @method('DELETE')<button class="danger-link" type="submit">Delete</button></form></div></td>
                </tr>
            @endforeach
        </tbody></table></div>
        <div class="pagination-wrap">{{ $posts->links() }}</div>
    @endif
</section>
@endsection
