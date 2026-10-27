<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'));
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access: Admin privileges required.');
        }

        return $next($request);
    }
}
