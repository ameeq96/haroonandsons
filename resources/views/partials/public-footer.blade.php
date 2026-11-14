<footer>
    <div class="container"><div class="footer-content">
        <div class="footer-logo">
            <a class="logo" href="{{ route('home') }}"><img src="{{ asset('assets/images/logoo.png') }}" alt="Haroon &amp; Sons Logo" class="logo-img"><span class="logo-main">HAROON &amp; SONS</span><span class="logo-sub">EXPORT / IMPORT</span></a>
            <p style="margin-top:20px;color:rgba(255,255,255,.8);">{{ __('site.footer_tagline') }}</p>
        </div>
        <div class="footer-links"><h3>{{ __('site.footer_quick') }}</h3><ul>
            <li><a href="{{ route('home') }}#home">{{ __('site.nav_home') }}</a></li><li><a href="{{ route('home') }}#about">{{ __('site.nav_about') }}</a></li><li><a href="{{ route('home') }}#vision">{{ __('site.nav_vision') }}</a></li><li><a href="{{ route('home') }}#products">{{ __('site.nav_products') }}</a></li><li><a href="{{ route('posts.index') }}">{{ __('site.nav_updates') }}</a></li><li><a href="{{ route('home') }}#contact">{{ __('site.nav_contact') }}</a></li>
        </ul></div>
        <div class="footer-links"><h3>{{ __('site.footer_products') }}</h3><ul>
            <li><a href="{{ route('home') }}#products">{{ __('site.product_duck_down_title') }}</a></li><li><a href="{{ route('home') }}#products">{{ __('site.product_feathers_title') }}</a></li><li><a href="{{ route('home') }}#products">{{ __('site.product_feed_title') }}</a></li><li><a href="{{ route('home') }}#products">{{ __('site.product_goose_title') }}</a></li><li><a href="{{ route('home') }}#products">{{ __('site.product_clothing_title') }}</a></li>
        </ul></div>
    </div><div class="copyright"><p>{{ __('site.copyright', ['year' => date('Y')]) }}</p></div></div>
</footer>
