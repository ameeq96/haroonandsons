<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'zh' ? 'zh-CN' : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HAROON & SONS | Premium Import/Export')</title>
    <meta name="description" content="@yield('meta_description', 'Haroon & Sons is a Pakistan-based import and export company specializing in duck down, feathers, animal feed, and used clothing.')">
    <link rel="icon" href="{{ asset('assets/images/logoo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/legacy.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @stack('styles')
</head>
<body>
    @include('partials.public-header')
    @yield('content')
    @include('partials.public-footer')

    <div class="language-float" aria-label="Website language">
        <span class="language-float-globe" aria-hidden="true">🌐</span>
        @if(app()->getLocale() === 'zh')
            <a href="{{ route('language.switch', 'en') }}" aria-label="Switch website to English">English</a>
            <span class="language-divider">|</span><strong aria-current="true">中文</strong>
        @else
            <strong aria-current="true">English</strong>
            <span class="language-divider">|</span><a href="{{ route('language.switch', 'zh') }}" aria-label="将网站切换为中文">中文</a>
        @endif
    </div>

    <script src="{{ asset('assets/js/legacy.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
