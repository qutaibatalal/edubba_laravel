<?php

namespace App\Http\Middleware;

use App\Models\ApiUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== ApiUser::ROLE_ADMIN) {
            abort(403, 'Not allowed');
        }

        return $next($request);
    }
}
