@extends('layouts.public')

@section('title', ($post->translatedSeoTitle() ?: $post->translatedTitle()).' | HAROON & SONS')
@section('meta_description', $post->translatedMetaDescription() ?: ($post->translatedExcerpt() ?: \Illuminate\Support\Str::limit(strip_tags($post->translatedContent()), 155)))

@section('content')
<main class="posts-page">
    <section class="article-wrap">
        <div class="container article-container">
            <a class="back-link" href="{{ route('posts.index') }}">{{ __('site.back_to_updates') }}</a>
            @if(!empty($isPreview) && $isPreview)
                <div class="preview-notice">{{ __('site.admin_preview') }}</div>
            @endif
            @if(session('status'))<div class="public-feedback-alert" role="status">{{ session('status') }}</div>@endif
            @if($errors->any())
                <div class="public-feedback-alert is-error" role="alert"><strong>Please check the form:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <article class="post-article">
                @if(count($post->image_urls) > 1)
                    <div class="post-slider post-gallery" data-post-slider role="region" aria-label="Post image gallery">
                        <div class="post-slider-track" data-slider-track tabindex="0" aria-label="Post images">
                            @foreach($post->image_urls as $imageUrl)
                                <figure class="post-slider-item" data-slider-item><img class="article-image" src="{{ $imageUrl }}" alt="{{ $post->translatedTitle() }} — {{ $loop->iteration }}" @if(!$loop->first)loading="lazy"@endif></figure>
                            @endforeach
                        </div>
                        <div class="post-slider-controls">
                            <button class="post-slider-button" data-slider-prev type="button" aria-label="Previous image" hidden>←</button>
                            <span class="post-slider-status" data-slider-status aria-live="polite"></span>
                            <button class="post-slider-button" data-slider-next type="button" aria-label="Next image" hidden>→</button>
                        </div>
                    </div>
                @elseif($post->featured_image_url)
                    <img class="article-image" src="{{ $post->featured_image_url }}" alt="{{ $post->translatedTitle() }}">
                @endif

                @if($post->video_url)
                    <div class="post-video-player">
                        <video controls playsinline preload="metadata" poster="{{ $post->featured_image_url ?: '' }}">
                            <source src="{{ $post->video_url }}" type="{{ $post->video_mime_type ?: 'video/mp4' }}">
                            Your browser does not support HTML video.
                        </video>
                    </div>
                @endif

                <div class="article-meta">
                    @if($post->translatedCategory())<span class="post-category">{{ $post->translatedCategory() }}</span>@endif
                    @if($post->published_at)<time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->format('F j, Y') }}</time>@endif
                </div>
                <h1>{{ $post->translatedTitle() }}</h1>
                @if($post->translatedExcerpt())<p class="article-excerpt">{{ $post->translatedExcerpt() }}</p>@endif
                <div class="article-content">{!! nl2br(e($post->translatedContent())) !!}</div>

                @unless(!empty($isPreview) && $isPreview)
                    <div class="post-engagement-bar">
                        <form method="POST" action="{{ route('posts.like.toggle', $post->slug) }}">
                            @csrf
                            <button class="post-like-button {{ $likedByVisitor ? 'is-liked' : '' }}" type="submit" aria-pressed="{{ $likedByVisitor ? 'true' : 'false' }}">
                                <span aria-hidden="true">{{ $likedByVisitor ? '♥' : '♡' }}</span>
                                {{ $likedByVisitor ? __('site.unlike_this') : __('site.like_this') }}
                            </button>
                        </form>
                        <span class="post-stat">{{ trans_choice('site.likes_count', $likesCount, ['count' => $likesCount]) }}</span>
                        <span class="post-stat">{{ trans_choice('site.views_count', $viewsCount, ['count' => $viewsCount]) }}</span>
                    </div>

                    <section class="post-feedback-section" id="reviews">
                        <div class="feedback-heading">
                            <h2>{{ __('site.reviews_heading') }}</h2>
                            <div class="rating-summary">
                                <span class="rating-stars" aria-label="{{ __('site.stars_label', ['count' => $averageRating ?: 0]) }}">@for($i=1;$i<=5;$i++){{ $averageRating && $i <= round($averageRating) ? '★' : '☆' }}@endfor</span>
                                <strong>{{ $averageRating ? number_format($averageRating, 1) : '—' }}</strong>
                                <small>{{ trans_choice('site.reviews_count', $post->approved_reviews_count, ['count' => $post->approved_reviews_count]) }}</small>
                            </div>
                        </div>

                        @forelse($approvedReviews as $review)
                            <article class="customer-feedback-card">
                                <div class="feedback-card-top"><strong>{{ $review->reviewer_name }}</strong><span class="rating-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></div>
                                <p>{{ $review->review_text }}</p>
                                <time>{{ $review->created_at->format('M j, Y') }}</time>
                            </article>
                        @empty
                            <p class="feedback-empty">{{ __('site.reviews_no_reviews') }}</p>
                        @endforelse

                        <form class="public-feedback-form" method="POST" action="{{ route('posts.reviews.store', $post->slug) }}">
                            @csrf
                            <h3>{{ __('site.reviews_form_heading') }}</h3>
                            <label>{{ __('site.reviews_name') }}<input name="reviewer_name" value="{{ old('reviewer_name') }}" maxlength="100" required></label>
                            <label>{{ __('site.reviews_email') }}<input type="email" name="reviewer_email" value="{{ old('reviewer_email') }}" maxlength="255"></label>
                            <label>{{ __('site.reviews_rating') }}
                                <select name="rating" required>
                                    <option value="">{{ __('site.reviews_select_rating') }}</option>
                                    @for($rating=5;$rating>=1;$rating--)
                                        <option value="{{ $rating }}" @selected(old('rating') == $rating)>{{ $rating }} / 5 {{ str_repeat('★', $rating) }}</option>
                                    @endfor
                                </select>
                            </label>
                            <label>{{ __('site.reviews_text') }}<textarea name="review_text" rows="4" minlength="5" maxlength="2000" required>{{ old('review_text') }}</textarea></label>
                            <button type="submit" class="gold-button">{{ __('site.reviews_submit') }}</button>
                        </form>
                    </section>

                    <section class="post-feedback-section" id="comments">
                        <h2>{{ __('site.comments_heading') }}</h2>
                        @forelse($approvedComments as $comment)
                            <article class="customer-feedback-card comment-card">
                                <strong>{{ $comment->commenter_name }}</strong>
                                <p>{{ $comment->comment_text }}</p>
                                <time>{{ $comment->created_at->format('M j, Y') }}</time>
                            </article>
                        @empty
                            <p class="feedback-empty">{{ __('site.comments_no_comments') }}</p>
                        @endforelse

                        <form class="public-feedback-form" method="POST" action="{{ route('posts.comments.store', $post->slug) }}">
                            @csrf
                            <h3>{{ __('site.comments_form_heading') }}</h3>
                            <label>{{ __('site.comments_name') }}<input name="commenter_name" value="{{ old('commenter_name') }}" maxlength="100" required></label>
                            <label>{{ __('site.comments_email') }}<input type="email" name="commenter_email" value="{{ old('commenter_email') }}" maxlength="255"></label>
                            <label>{{ __('site.comments_text') }}<textarea name="comment_text" rows="4" minlength="2" maxlength="2000" required>{{ old('comment_text') }}</textarea></label>
                            <button type="submit" class="gold-button">{{ __('site.comments_submit') }}</button>
                        </form>
                    </section>
                @endunless
            </article>
        </div>
    </section>
</main>
@endsection
