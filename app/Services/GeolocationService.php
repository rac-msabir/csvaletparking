<?php

namespace App\Services;

class GeolocationService
{
    /**
     * Earth's radius in kilometers
     */
    private const EARTH_RADIUS_KM = 6371;

    /**
     * Calculate distance between two points using the Haversine formula
     *
     * @param float $lat1 Latitude of point 1
     * @param float $lon1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lon2 Longitude of point 2
     * @return float Distance in kilometers
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS_KM * $c;
    }

    /**
     * Check if a location is within the allowed radius
     *
     * @param float $userLat User's allowed latitude
     * @param float $userLng User's allowed longitude
     * @param float $checkLat Latitude to check
     * @param float $checkLng Longitude to check
     * @param float $allowedRadiusKm Allowed radius in kilometers
     * @return array [is_within_radius: bool, distance_km: float]
     */
    public function isWithinAllowedRadius(
        float $userLat,
        float $userLng,
        float $checkLat,
        float $checkLng,
        float $allowedRadiusKm = 1.0
    ): array {
        $distance = $this->calculateDistance($userLat, $userLng, $checkLat, $checkLng);
        
        return [
            'is_within_radius' => $distance <= $allowedRadiusKm,
            'distance_km' => $distance
        ];
    }
}