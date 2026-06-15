<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            abort(401);
        }

        if ($role === 'admin' && !$request->user()->isAdmin()) {
            abort(403, 'Accès interdit. Rôle Administrateur requis.');
        }

        if ($role === 'direction' && !$request->user()->isDirection()) {
            abort(403, 'Accès interdit. Rôle Direction requis.');
        }

        return $next($request);
    }
}
