<?php

namespace App\Services;

class LocationService
{
    /**
     * Get user's location from session or IP geolocation
     */
    public function getUserLocation()
    {
        // Check if location is stored in session
        if (isset($_SESSION['user_location'])) {
            return $_SESSION['user_location'];
        }

        // Try to get location from IP
        $location = $this->getLocationFromIP();
        if ($location) {
            $_SESSION['user_location'] = $location;
            return $location;
        }

        return null;
    }

    /**
     * Get location from IP using ipapi.co service
     */
    private function getLocationFromIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($ip === '127.0.0.1' || $ip === '::1') {
            // For local development, return Jakarta's coordinates
            return [
                'longitude' => 106.8456, // Longitude first for GeoJSON
                'latitude' => -6.2088,   // Latitude second for GeoJSON
                'city' => 'Jakarta',
                'country' => 'Indonesia'
            ];
        }

        try {
            $response = file_get_contents("http://ip-api.com/json/{$ip}");
            $data = json_decode($response, true);

            if ($data && $data['status'] === 'success') {
                return [
                    'longitude' => $data['lon'], // Longitude first for GeoJSON
                    'latitude' => $data['lat'],  // Latitude second for GeoJSON
                    'city' => $data['city'],
                    'country' => $data['country']
                ];
            }
        } catch (\Exception $e) {
            error_log("Error getting location from IP: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Calculate distance between two points in kilometers
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta/2) * sin($lonDelta/2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
} 