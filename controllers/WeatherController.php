<?php

namespace App\Controllers;

class WeatherController extends Controller
{
    public function getWeather()
    {
        try {
            // Terima koordinat dari request
            $requestData = json_decode(file_get_contents('php://input'), true);
            
            // Gunakan koordinat dari request atau default ke Jepara
            $lat = $requestData['lat'] ?? -6.5888;
            $lon = $requestData['lon'] ?? 110.6684;
            $apiKey = '3fe4dce1ebca2119d114a7cd38fa4cc1';
            
            // Buat request ke OpenWeatherMap API
            $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=id";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds
            
            $response = curl_exec($ch);
            $error = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);

            // Log untuk debugging
            error_log("Weather API Request - Lat: {$lat}, Lon: {$lon}");
            error_log("Weather API Response - HTTP Code: " . $httpCode);
            error_log("Weather API Response: " . $response);
            
            if ($error) {
                throw new \Exception('Curl error: ' . $error);
            }
            
            if ($httpCode !== 200) {
                throw new \Exception('API returned error code: ' . $httpCode);
            }
            
            // Decode dan validasi response
            $data = json_decode($response, true);
            if (!$data || !isset($data['main']) || !isset($data['weather'])) {
                throw new \Exception('Invalid response format from weather API');
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
            return;
            
        } catch (\Exception $e) {
            error_log("Weather API Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'error' => true,
                'message' => 'Gagal mengambil data cuaca: ' . $e->getMessage()
            ]);
        }
    }
} 