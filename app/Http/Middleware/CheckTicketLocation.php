<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\GeolocationService;
use App\Models\SuspiciousLogin;
use App\Notifications\SuspiciousLocationNotification;
use App\Models\User;

class CheckTicketLocation
{
    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        // Skip check for super admins or if no user is authenticated
        if (!$user || $user->is_super_admin) {
            return $next($request);
        }

        // Only check for ticket creation/update requests
        if ($this->isTicketRequest($request)) {

            $ticketData = $request->all();
            
            // Check if we have location data in the request
            if (isset($ticketData['check_in_latitude']) && isset($ticketData['check_in_longitude'])) {
                $this->checkTicketLocation($user, $ticketData, $request);
            }
        }

        return $next($request);
    }

    protected function isTicketRequest(Request $request): bool
    {
        return $request->isMethod('post');
    }


    protected function checkTicketLocation($user, $ticketData, $request): void
    {
        $ticketLat = (float)$ticketData['check_in_latitude'];
        $ticketLng = (float)$ticketData['check_in_longitude'];
        
        // If user has no location set, use the first ticket's location as their allowed location
        if (is_null($user->latitude) || is_null($user->longitude)) {
            $user->update([
                'latitude' => $ticketLat,
                'longitude' => $ticketLng,
                'allowed_radius_km' => $user->allowed_radius_km ?? 1.0
            ]);
            return;
        }

        // Check if the ticket location is within the allowed radius
        $result = $this->geolocationService->isWithinAllowedRadius(
            $user->latitude,
            $user->longitude,
            $ticketLat,
            $ticketLng,
            $user->allowed_radius_km ?? 1.0
        );

        if (!$result['is_within_radius']) {
            $this->logSuspiciousActivity($user, $ticketLat, $ticketLng, $result['distance_km'], $request);
        }
    }

    protected function logSuspiciousActivity($user, $latitude, $longitude, $distance, $request): void
    {
        $suspiciousLogin = SuspiciousLogin::create([
            'user_id' => $user->id,
            'login_latitude' => $latitude,
            'login_longitude' => $longitude,
            'allowed_latitude' => $user->latitude,
            'allowed_longitude' => $user->longitude,
            'distance_km' => $distance,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notify super admins
        $this->notifySuperAdmins($suspiciousLogin);
    }

    protected function notifySuperAdmins($suspiciousLogin): void
    {
        $superAdmins = User::where('is_super_admin', true)->get();
        
        foreach ($superAdmins as $admin) {
            $admin->notify(new SuspiciousLocationNotification($suspiciousLogin));
        }
    }
}