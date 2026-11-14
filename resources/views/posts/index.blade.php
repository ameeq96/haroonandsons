@extends('layouts.public')
@section('title', __('site.updates_heading').' | HAROON & SONS')
@section('content')
<main class="posts-page">
    <section class="posts-hero"><div class="container"><p class="eyebrow">HAROON &amp; SONS</p><h1>{{ __('site.updates_heading') }}</h1><p>{{ __('site.updates_sub') }}</p></div></section>
    <section class="posts-section"><div class="container">
        @if($categories->isNotEmpty())
            <form class="category-filter" method="GET" action="{{ route('posts.index') }}">
                <label for="category">{{ __('site.browse_category') }}</label>
                <select id="category" name="category" onchange="this.form.submit()">
                    <option value="">{{ __('site.all_updates') }}</option>
                    @foreach($categories as $category)
                        @php($categoryLabel = app()->getLocale() === 'zh' ? (\App\Models\Post::where('category', $category)->value('category_zh') ?: $category) : $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $categoryLabel }}</option>
                    @endforeach
                </select>
                <noscript><button class="gold-button" type="submit">Filter</button></noscript>
            </form>
        @endif
        @if($posts->isEmpty())
            <div class="empty-state"><i class="far fa-newspaper" aria-hidden="true"></i><h2>{{ __('site.no_updates') }}</h2><p>{{ __('site.no_updates_sub') }}</p></div>
        @else
            <div class="public-post-grid">
                @foreach($posts as $post)
                    <article class="public-post-card">
                        @if($post->featured_image_url)
                            <a class="public-post-image" href="{{ route('posts.show', $post->slug) }}"><img src="{{ $post->featured_image_url }}" alt="{{ $post->translatedTitle() }}" loading="lazy"></a>
                        @endif
                        @if($post->video_url)
                            <div class="post-card-video-player"><video controls playsinline preload="metadata" style="width:100%;max-height:260px;background:#000"><source src="{{ $post->video_url }}" type="{{ $post->video_mime_type ?: 'video/mp4' }}"></video></div>
                        @endif
                        <div class="public-post-copy">
                            @if($post->translatedCategory())<span class="post-category">{{ $post->translatedCategory() }}</span>@endif
                            <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->format('F j, Y') }}</time>
                            <h2><a href="{{ route('posts.show', $post->slug) }}">{{ $post->translatedTitle() }}</a></h2>
                            <p>{{ $post->translatedExcerpt() ?: \Illuminate\Support\Str::limit(strip_tags($post->translatedContent()), 180) }}</p>
                            <a class="read-more" href="{{ route('posts.show', $post->slug) }}">{{ __('site.read_more') }} <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $posts->links() }}</div>
        @endif
    </div></section>
</main>
@endsection
