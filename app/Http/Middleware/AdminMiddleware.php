<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access the Admin Portal.');
        }

        $user = Auth::user();
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        if (!$user->isStaff()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Administrator privileges required.');
        }

        if ($role === 'manager' && !$user->isManager()) {
            abort(403, 'Manager privileges required.');
        }

        return $next($request);
    }
}
