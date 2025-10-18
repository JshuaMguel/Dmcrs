<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized - Not authenticated');
        }

        $userRole = Auth::user()->role;
        $allowedRoles = ['super_admin', 'super admin', 'admin', 'superadmin'];

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized - Role: ' . $userRole . ' not allowed');
        }

        return $next($request);
    }
}
