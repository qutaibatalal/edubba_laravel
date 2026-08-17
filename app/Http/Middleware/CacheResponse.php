<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /** @var array<null|string> */
    protected $except = [];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->expectsJson()) {
            return $next($request);
        }

        if (app()->isProduction()) {
            return $next($request);
        }

        $cacheName = 'response:'.$request->method().':'.$request->getRequestUri();

        if ($request->method() === 'GET') {
            $cached = Cache::remember($cacheName, 300, function () use ($request, $next) {
                $response = $next($request);

                if ($response->headers->has('Cache-Control')) {
                    return $response;
                }

                return response($response->getContent(), $response->getStatusCode())
                    ->headers($response->headers);
            });

            return $cached;
        }

        return $next($request);
    }
}
