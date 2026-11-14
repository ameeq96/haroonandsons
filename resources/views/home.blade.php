@extends('layouts.public')

@section('content')
<main>
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>HAROON &amp; SONS</h1>
            <div class="hero-tagline">{{ __('site.hero_tagline') }}</div>
            <p class="hero-description">{{ __('site.hero_description') }}</p>
            <a href="#contact" class="cta-button">{{ __('site.hero_cta') }}</a>
        </div>
        <div class="floating-element floating-1"></div>
        <div class="floating-element floating-2"></div>
        <div class="floating-element floating-3"></div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <div class="section-title"><h2>{{ __('site.about_heading') }}</h2><p>{{ __('site.about_subheading') }}</p></div>
            <div class="about-content">
                <div class="about-text">
                    <p>{{ __('site.about_p1') }}</p>
                    <p>{{ __('site.about_p2') }}</p>
                    <p>{{ __('site.about_p3') }}</p>
                </div>
                <div class="about-image" id="aboutImage"><img src="{{ asset('assets/images/About.png') }}" alt="Haroon and Sons Trading"></div>
            </div>
        </div>
    </section>

    <section id="vision" class="vision-mission">
        <div class="container">
            <div class="section-title"><h2>{{ __('site.vision_heading') }}</h2><p>{{ __('site.vision_subheading') }}</p></div>
            <div class="vm-container">
                <div class="vm-box" id="visionBox"><div class="vm-icon"><i class="fas fa-eye" aria-hidden="true"></i></div><h3>{{ __('site.vision_title') }}</h3><p>{{ __('site.vision_text') }}</p></div>
                <div class="vm-box" id="missionBox"><div class="vm-icon"><i class="fas fa-bullseye" aria-hidden="true"></i></div><h3>{{ __('site.mission_title') }}</h3><p>{{ __('site.mission_text') }}</p></div>
            </div>
        </div>
    </section>

    <section id="products" class="products">
        <div class="container">
            <div class="section-title"><h2>{{ __('site.products_heading') }}</h2><p>{{ __('site.products_sub') }}</p></div>
            <div class="products-grid">
                <article class="product-card" id="product1"><div class="product-img"><img src="{{ asset('assets/images/duckdown.jpg') }}" alt="Duck down"></div><div class="product-info"><h3>{{ __('site.product_duck_down_title') }}</h3><p>{{ __('site.product_duck_down_desc') }}</p></div></article>
                <article class="product-card" id="product2"><div class="product-img"><img src="{{ asset('assets/images/DuckFeathers.webp') }}" alt="Duck feathers"></div><div class="product-info"><h3>{{ __('site.product_feathers_title') }}</h3><p>{{ __('site.product_feathers_desc') }}</p></div></article>
                <article class="product-card" id="product4"><div class="product-img"><img src="{{ asset('assets/images/goosedown.avif') }}" alt="Goose down"></div><div class="product-info"><h3>{{ __('site.product_goose_title') }}</h3><p>{{ __('site.product_goose_desc') }}</p></div></article>
                <article class="product-card" id="product3"><div class="product-img"><img src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&amp;fit=crop&amp;w=1350&amp;q=80" alt="Animal feed ingredients"></div><div class="product-info"><h3>{{ __('site.product_feed_title') }}</h3><p>{{ __('site.product_feed_desc') }}</p></div></article>
                <article class="product-card" id="product5"><div class="product-img"><img src="{{ asset('assets/images/useclothees.avif') }}" alt="Used clothing material"></div><div class="product-info"><h3>{{ __('site.product_clothing_title') }}</h3><p>{{ __('site.product_clothing_desc') }}</p></div></article>

<article class="product-card" id="product6">
    <div class="product-img">
        <img src="{{ asset('assets/images/Global.jpg') }}" alt="Premium branded vintage clothing">
    </div>
    <div class="product-info">
        <h3>{{ __('site.product_vintage_title') }}</h3>
        <p>{{ __('site.product_vintage_desc') }}</p>
    </div>
</article>

            </div>
        </div>
    </section>

    @if($latestPosts->isNotEmpty())
        <section id="updates" class="posts-section">
            <div class="container">
                <div class="section-title"><h2>{{ __('site.updates_heading') }}</h2><p>{{ __('site.updates_sub') }}</p></div>
                <div class="post-slider homepage-post-slider" data-post-slider role="region" aria-label="Latest posts">
                    <div class="post-slider-track" data-slider-track tabindex="0" aria-label="Latest posts">
                        @foreach($latestPosts as $post)
                            <article class="public-post-card post-slider-item" data-slider-item>
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
                                    <a class="read-more" href="{{ route('posts.show', $post->slug) }}">{{ __('site.read_more') }} <span aria-hidden="true">&#8594;</span></a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="post-slider-controls">
                        <button class="post-slider-button" data-slider-prev type="button" aria-label="Previous posts" hidden><span aria-hidden="true">&#8592;</span></button>
                        <span class="post-slider-status" data-slider-status aria-live="polite" aria-atomic="true"></span>
                        <button class="post-slider-button" data-slider-next type="button" aria-label="Next posts" hidden><span aria-hidden="true">&#8594;</span></button>
                    </div>
                </div>
                <div class="homepage-posts-link"><a class="read-more" href="{{ route('posts.index') }}">{{ __('site.view_all_updates') }} <span aria-hidden="true">&#8594;</span></a></div>
            </div>
        </section>
    @endif

    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-title"><h2>{{ __('site.contact_heading') }}</h2><p>{{ __('site.contact_sub') }}</p></div>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item"><div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div><div class="contact-text"><h4>{{ __('site.contact_location') }}</h4><p>{{ __('site.contact_address') }}</p></div></div>
                    <div class="contact-item"><div class="contact-icon"><i class="fas fa-phone-alt"></i></div><div class="contact-text"><h4>{{ __('site.contact_phone_h') }}</h4><p>{{ __('site.contact_phone') }}</p></div></div>
                    <div class="contact-item"><div class="contact-icon"><i class="fas fa-envelope"></i></div><div class="contact-text"><h4>{{ __('site.contact_email_h') }}</h4><p>{{ __('site.contact_email') }}</p></div></div>
                    <div class="contact-item"><div class="contact-icon"><i class="fab fa-weixin"></i></div><div class="contact-text"><h4>{{ __('site.contact_wechat_h') }}</h4><p>{{ __('site.contact_wechat') }}</p></div></div>
                    <div class="contact-item"><div class="contact-icon"><i class="fas fa-clock"></i></div><div class="contact-text"><h4>{{ __('site.contact_hours_h') }}</h4><p>{{ __('site.contact_hours_1') }}</p><p>{{ __('site.contact_hours_2') }}</p></div></div>
                </div>
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-group"><input type="text" class="form-control" placeholder="{{ __('site.form_name') }}" required></div>
                        <div class="form-group"><input type="email" class="form-control" placeholder="{{ __('site.form_email') }}" required></div>
                        <div class="form-group"><input type="text" class="form-control" placeholder="{{ __('site.form_subject') }}" required></div>
                        <div class="form-group"><textarea class="form-control" rows="5" placeholder="{{ __('site.form_message') }}" required></textarea></div>
                        <button type="submit" class="submit-btn">{{ __('site.form_send') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
