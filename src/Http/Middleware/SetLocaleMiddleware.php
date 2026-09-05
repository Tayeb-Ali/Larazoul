<?php

namespace Larazoul\Larazoul\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request by setting the application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('larazoul_locale')
            ?? $request->segment(1)
            ?? config('larazoul.locale', 'en');

        if (array_key_exists($locale, (array) config('larazoul.locales', []))) {
            App::setLocale($locale);
            session()->put('larazoul_locale', $locale);
        }

        return $next($request);
    }
}
