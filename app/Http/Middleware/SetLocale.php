<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasSession()) {
            $locale = $request->session()->get('locale');

            if ($locale && in_array($locale, ['ar', 'en'], true)) {
                app()->setLocale($locale);
            } else {
                $default = config('app.locale', 'ar');
                $request->session()->put('locale', $default);
                app()->setLocale($default);
            }
        } else {
            app()->setLocale(config('app.locale', 'ar'));
        }

        return $next($request);
    }
}
