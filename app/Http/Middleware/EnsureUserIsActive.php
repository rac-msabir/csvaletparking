<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Ticket;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Skip checks for super admins
        if ($user && $user->is_super_admin) {
            return $next($request);
        }

        // For logged-in users
        if ($user) {
            // For employees, check their tenant's status
            if ($user->is_employee && $user->tenant_id) {
                $tenant = User::find($user->tenant_id);
                if ($tenant && !$tenant->is_active) {
                    return $this->handleInactiveUser($request);
                }
            }
            // For tenant admins, check their own status
            elseif ($user->is_tenant_admin && !$user->is_active) {
                return $this->handleInactiveUser($request);
            }
        }
        // For public routes with tenant_id parameter
        // In the handle method, replace the existing tenant_id check with:
        else {
            // For public ticket routes with token
            if ($request->route('token')) {
                $ticket = Ticket::where('public_token', $request->route('token'))->first();
                $tenant = User::find($ticket->tenant_id);

                if ($tenant && !$tenant->is_active) {
                    abort(404); // Return 404 for inactive tenants
                }
            }
            // Keep existing tenant_id check for other public routes
            elseif ($request->has('tenant_id')) {
                $tenant = User::find($request->tenant_id);
                if ($tenant && !$tenant->is_active) {
                    abort(404);
                }
            }
        }

        return $next($request);
    }

    /**
     * Handle inactive user logout and redirect
     */
    protected function handleInactiveUser(Request $request)
    {
        Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'This account is currently inactive. Please contact support for assistance.',
        ]);
    }
}