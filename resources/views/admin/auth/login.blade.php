<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin sign in | Haroon &amp; Sons</title>
    <link rel="icon" href="{{ asset('assets/images/logoo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="login-body">
    <main class="login-frame">
        <section class="login-story" aria-label="Haroon and Sons publishing workspace">
            <a class="login-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logoo.png') }}" alt="Haroon &amp; Sons">
                <span class="login-wordmark"><strong>Haroon &amp; Sons</strong><small>THE PUBLISHING WORKSPACE</small></span>
            </a>
            <div class="login-story-copy">
                <p class="admin-kicker">THOUGHTFULLY SHARED</p>
                <h2>A considered space<br>for your next update.</h2>
                <p>Company news, new collections, and the stories behind your business. Bring them together in one place.</p>
            </div>
            <div class="login-story-visual" aria-hidden="true">
                <span class="login-visual-ring ring-one"></span>
                <span class="login-visual-ring ring-two"></span>
                <span class="login-visual-ring ring-three"></span>
                <span class="login-visual-core"></span>
            </div>
            <div class="login-story-footer"><span>PRIVATE WORKSPACE</span><p>For the people behind the business.</p></div>
        </section>
        <section class="login-entry" aria-labelledby="login-title">
            <div class="login-card">
                <div class="login-card-heading">
                    <p class="admin-kicker">CONTENT ADMINISTRATION</p>
                    <h1 id="login-title">Welcome back</h1>
                    <p class="login-intro">Sign in to manage your posts, images, and company announcements.</p>
                </div>
                @if(session('status'))<div class="flash-message" role="status">{{ session('status') }}</div>@endif
                @if($errors->any())<div class="form-errors" role="alert"><p>Please check the email and password, then try again.</p></div>@endif
                <form class="login-form" method="POST" action="{{ route('admin.login.store') }}">
                    @csrf
                    <label for="email">Email address</label>
                    <div class="login-input">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="3"></rect><path d="m4 7 8 6 8-6"></path></svg>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                    </div>
                    @error('email')<small class="field-error">{{ $message }}</small>@enderror
                    <label for="password">Password</label>
                    <div class="login-input">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><rect x="4" y="10" width="16" height="11" rx="3"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"></path></svg>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    @error('password')<small class="field-error">{{ $message }}</small>@enderror
                    <label class="remember-option"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
                    <button class="admin-button primary full-width" type="submit"><span>Sign in securely</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true" focusable="false"><path d="M5 12h14m-5-5 5 5-5 5" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                </form>
                <a class="login-back" href="{{ route('home') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true" focusable="false"><path d="M19 12H5m5-5-5 5 5 5" stroke-linecap="round" stroke-linejoin="round"></path></svg><span>Return to website</span></a>
            </div>
            <p class="login-entry-note">Haroon &amp; Sons <span aria-hidden="true">&middot;</span> Website administration</p>
        </section>
    </main>
</body>
</html>
