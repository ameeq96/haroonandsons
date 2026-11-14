<header id="header">
    <div class="container"><div class="header-content">
        <a class="logo" href="{{ route('home') }}" aria-label="Haroon and Sons home"><img src="{{ asset('assets/images/logoo.png') }}" alt="Haroon &amp; Sons Logo" class="logo-img"></a>
        <button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-label="Toggle navigation" aria-expanded="false"><i class="fas fa-bars" aria-hidden="true"></i></button>
        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('home') }}#home">{{ __('site.nav_home') }}</a></li>
            <li><a href="{{ route('home') }}#about">{{ __('site.nav_about') }}</a></li>
            <li><a href="{{ route('home') }}#vision">{{ __('site.nav_vision') }}</a></li>
            <li><a href="{{ route('home') }}#products">{{ __('site.nav_products') }}</a></li>
            <li><a href="{{ route('posts.index') }}">{{ __('site.nav_updates') }}</a></li>
            <li><a href="{{ route('home') }}#contact">{{ __('site.nav_contact') }}</a></li>
            <li><a class="admin-login-link" href="{{ route('login') }}" aria-label="Admin sign in" title="Admin sign in"><i class="fas fa-user-lock" aria-hidden="true"></i></a></li>
            <li class="updates-nav-item">
                <details class="updates-menu">
                    <summary aria-label="{{ __('site.nav_recent') }}"><i class="fa-regular fa-bell" aria-hidden="true"></i><span class="updates-label">{{ __('site.nav_recent') }}</span>@if($unreadUpdates > 0)<span class="notification-count">{{ $unreadUpdates }}</span>@endif</summary>
                    <div class="updates-dropdown"><strong>{{ __('site.nav_recent') }}</strong>
                        @forelse($updates as $update)
                            <a href="{{ route('posts.show', $update->slug) }}"><span>{{ $update->translatedTitle() }}</span><small>{{ $update->published_at->format('M j, Y') }}</small></a>
                        @empty
                            <p>{{ __('site.nav_no_updates') }}</p>
                        @endforelse
                        @if($unreadUpdates > 0)<form method="POST" action="{{ route('updates.read') }}">@csrf<button type="submit">{{ __('site.nav_mark_read') }}</button></form>@endif
                    </div>
                </details>
            </li>
        </ul>
    </div></div>
</header>
