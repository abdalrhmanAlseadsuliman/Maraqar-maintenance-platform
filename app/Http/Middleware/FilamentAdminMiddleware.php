<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class FilamentAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }


        // $allowedRoles = array_diff(UserRole::all(), [UserRole::CLIENT]);
        // $allowedRoles = array_diff(UserRole::values(), [UserRole::CLIENT]);
        $allowedRoles = array_diff(UserRole::values(), ['CLT']);


        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
