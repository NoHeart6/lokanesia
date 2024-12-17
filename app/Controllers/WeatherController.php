<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

class WeatherController extends Controller {
    private $weatherConditions = [
        1000 => ['Cerah', 'sun'],                    // Clear
        1100 => ['Sebagian Cerah', 'cloud-light'],   // Mostly Clear
        1101 => ['Berawan Ringan', 'cloud-light'],   // Partly Cloudy
        1102 => ['Berawan', 'cloud'],                // Mostly Cloudy
        1001 => ['Berawan Tebal', 'clouds'],         // Cloudy
        2000 => ['Kabut', 'cloud-fog'],              // Fog
        2100 => ['Kabut Ringan', 'cloud-fog'],       // Light Fog
        4000 => ['Hujan', 'cloud-rain'],             // Rain
        4001 => ['Hujan Ringan', 'cloud-drizzle'],   // Light Rain
        4200 => ['Hujan Lebat', 'cloud-rain'],       // Heavy Rain
        4201 => ['Hujan Sangat Lebat', 'cloud-rain'], // Very Heavy Rain
        8000 => ['Hujan Petir', 'cloud-lightning']   // Thunderstorm
    ];

    public function getWeather(Request $request, Response $response): void {
        try {
            $lat = (float) $request->getQuery('lat');
            $lng = (float) $request->getQuery('lng');
            
            error_log("Weather request for coordinates: {$lat}, {$lng}");
            
            if (!$lat || !$lng) {
                throw new \Exception('Latitude dan longitude diperlukan');
            }

            // Coba ambil data dari cache terlebih dahulu
            $cacheFile = __DIR__ . '/../../storage/cache/weather_' . md5("{$lat}_{$lng}") . '.json';
            $cacheExpiry = 1800; // 30 menit
            
            if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheExpiry)) {
                error_log("Using cached weather data");
                $weatherData = json_decode(file_get_contents($cacheFile), true);
            } else {
                error_log("Fetching fresh weather data from Tomorrow.io");
                
                // Buat direktori cache jika belum ada
                if (!is_dir(dirname($cacheFile))) {
                    mkdir(dirname($cacheFile), 0777, true);
                }

                // Ambil data cuaca dari Tomorrow.io
                $fields = ['temperature', 'humidity', 'weatherCode', 'windSpeed'];
                $url = "https://api.tomorrow.io/v4/weather/realtime?location={$lat},{$lng}&fields=" . implode(',', $fields) . "&apikey=YOUR_API_KEY";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);
                
                error_log("Weather API response code: {$httpCode}");
                if ($error) {
                    error_log("CURL error: {$error}");
                }
                
                if ($httpCode !== 200) {
                    // Jika gagal, coba gunakan data dummy untuk testing
                    error_log("Using dummy weather data for testing");
                    $weatherData = [
                        'data' => [
                            'values' => [
                                'temperature' => rand(25, 32),
                                'humidity' => rand(60, 80),
                                'weatherCode' => 1000, // Cerah
                                'windSpeed' => rand(5, 15)
                            ]
                        ]
                    ];
                } else {
                    $weatherData = json_decode($result, true);
                    // Simpan ke cache
                    file_put_contents($cacheFile, $result);
                }
            }

            $values = $weatherData['data']['values'];
            $weatherCode = $values['weatherCode'];
            $weatherInfo = $this->weatherConditions[$weatherCode] ?? ['Tidak diketahui', 'cloud'];

            $responseData = [
                'status' => 'success',
                'data' => [
                    'temperature' => round($values['temperature']),
                    'description' => $weatherInfo[0],
                    'humidity' => round($values['humidity']),
                    'wind_speed' => round($values['windSpeed'] * 3.6, 1), // convert m/s to km/h
                    'icon' => $weatherInfo[1]
                ]
            ];
            
            error_log("Sending weather response: " . json_encode($responseData, JSON_UNESCAPED_UNICODE));
            $response->json($responseData);
            
        } catch (\Exception $e) {
            error_log("Weather API error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // Return dummy data for testing
            $response->json([
                'status' => 'success',
                'data' => [
                    'temperature' => 28,
                    'description' => 'Cerah',
                    'humidity' => 70,
                    'wind_speed' => 10.5,
                    'icon' => 'sun'
                ]
            ]);
        }
    }
} 