<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['en', 'zh'];
    private const COOKIE_NAME = 'hs_locale';
    private const COOKIE_DAYS = 365;

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale')
            ?? $request->cookie(self::COOKIE_NAME)
            ?? config('app.locale', 'en');

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        $response = $next($request);

        // Persist locale cookie if set in session
        if ($request->session()->has('locale')) {
            $response->withCookie(cookie(
                self::COOKIE_NAME,
                $locale,
                60 * 24 * self::COOKIE_DAYS,
                '/',
                null,
                false,
                true, // HttpOnly
                false,
                'Lax'
            ));
        }

        return $response;
    }
}
