<?php

use App\Http\Middleware\CacheResponse;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\SanitizeInput;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role.admin' => EnsureAdminRole::class,
            'admin.web' => EnsureAdmin::class,
            'cache.response' => CacheResponse::class,
        ]);

        $middleware->web(prepend: [
            SanitizeInput::class,
            SecurityHeaders::class,
        ]);

        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->redirectTo(function (Request $request) {
            return $request->expectsJson()
                ? null
                : route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
