<?php

namespace Larazoul\Larazoul\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = config('larazoul.guard', 'web');

        if (! auth($guard)->check()) {
            return redirect()->route('larazoul.login');
        }

        $user = auth($guard)->user();
        $column = config('larazoul.admin_role_column', 'is_admin');
        $value = config('larazoul.admin_role_value', true);

        // Allow access when the admin column equals the configured value.
        $isAdmin = $user->{$column} ?? null;

        if ($isAdmin != $value) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
