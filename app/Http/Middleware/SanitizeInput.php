<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Block 4 — Input Sanitization.
 *
 * Strips script tags and inline event-handler attributes from all string
 * input destined for the web application. Passwords, tokens and CSRF
 * payloads are never touched, and file inputs are left intact.
 */
class SanitizeInput
{
    /** Attributes that must never be altered. */
    protected const EXEMPT = [
        '_token', '_method', 'password', 'password_confirmation', 'current_password',
        'code', 'remember', 'two_factor_secret',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isJson() || $request->hasHeader('Accept') && str_contains($request->header('Accept'), 'text/event-stream')) {
            return $next($request);
        }

        $sanitized = $this->clean($request->input());

        $request->merge($sanitized);

        return $next($request);
    }

    protected function clean(mixed $value): mixed
    {
        if (is_string($value)) {
            return $this->sanitizeString($value);
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $key => $item) {
                if (! in_array($key, self::EXEMPT, true)) {
                    $out[$key] = $this->clean($item);
                } else {
                    $out[$key] = $item;
                }
            }

            return $out;
        }

        return $value;
    }

    protected function sanitizeString(string $value): string
    {
        $value = preg_replace('#<script\b[^>]*>.*?</script\s*>#is', '', $value) ?? $value;
        $value = preg_replace('#\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $value) ?? $value;
        $value = preg_replace('#(javascript|vbscript)\s*:#i', '$1&#58;', $value) ?? $value;

        return $value;
    }
}
