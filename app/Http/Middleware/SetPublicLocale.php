<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetPublicLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('locales.supported', ['en']);
        $route = $request->route();
        $routeLocale = strtolower((string) ($route?->parameter('locale') ?? ''));
        // Locale is encoded in the public URL. Unprefixed routes are canonical English.
        // Do not let an old DE/AR session silently turn an English URL into localized content.
        $locale = in_array($routeLocale, $supported, true) ? $routeLocale : 'en';

        $request->session()->put('public_locale', $locale);
        app()->setLocale($locale);

        // Preserve the locale for URL generation while keeping English URLs clean.
        URL::defaults(['locale' => $locale === 'en' ? false : $locale]);

        // IMPORTANT: {locale?} is a routing concern, not a controller argument.
        // Laravel dispatches route parameters positionally. If this parameter remains,
        // localized model-bound actions receive "de"/"ar" as argument #1 and crash
        // before Specialty/Procedure/Clinic/etc. can reach their typed controller slots.
        // Remove it after the locale has been captured and model binding has run.
        if ($route !== null && $route->hasParameter('locale')) {
            $route->forgetParameter('locale');
        }

        return $next($request);
    }
}
