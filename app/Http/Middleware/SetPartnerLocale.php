<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPartnerLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $storedLocale = $request->user('partner')?->locale;
        $sessionLocale = $request->session()->get('partner_locale');
        // A freshly selected language lives in the session for this browser
        // immediately. The persisted preference is the fallback for a later
        // visit or a new session.
        $locale = in_array($sessionLocale, ['nb', 'en'], true)
            ? $sessionLocale
            : $storedLocale;

        app()->setLocale(in_array($locale, ['nb', 'en'], true) ? $locale : 'nb');

        return $next($request);
    }
}
