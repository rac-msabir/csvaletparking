<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $geolocationService;

    public function __construct(\App\Services\GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Skip check for super admins or if user doesn't have location data
        if (!$user || $user->hasRole('super-admin') || is_null($user->latitude) || is_null($user->longitude)) {
            return $next($request);
        }

        // Get the user's IP and try to get location
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        
        // In a real app, you would use an IP geolocation service here
        // For this example, we'll use the request headers if available
        $loginLat = $request->header('X-Latitude') ? (float)$request->header('X-Latitude') : null;
        $loginLng = $request->header('X-Longitude') ? (float)$request->header('X-Longitude') : null;

        if ($loginLat && $loginLng) {
            $result = $this->geolocationService->isWithinAllowedRadius(
                $user->latitude,
                $user->longitude,
                $loginLat,
                $loginLng,
                $user->allowed_radius_km
            );

            if (!$result['is_within_radius']) {
                // Log suspicious login attempt
                $suspiciousLogin = \App\Models\SuspiciousLogin::create([
                    'user_id' => $user->id,
                    'login_latitude' => $loginLat,
                    'login_longitude' => $loginLng,
                    'allowed_latitude' => $user->latitude,
                    'allowed_longitude' => $user->longitude,
                    'distance_km' => $result['distance_km'],
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                ]);

                // In a real app, you might want to notify the super admin here
                // For example, via email, notification, or WebSocket
                
                // You could also log the user out or require additional verification
                // Auth::logout();
                // return redirect()->route('login')->with('error', 'Login from suspicious location detected.');
            }
        }

        return $next($request);
    }
}
