<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (!in_array($locale, ['en', 'zh'], true)) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        App::setLocale($locale);

        return redirect()->back();
    }
}