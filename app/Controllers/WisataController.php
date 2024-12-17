<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Wisata;
use MongoDB\BSON\ObjectId;

class WisataController extends Controller
{
    private $wisataModel;

    public function __construct()
    {
        parent::__construct();
        $this->wisataModel = new Wisata();
    }

    public function index()
    {
        $debug_info = [
            'errors' => [],
            'queries' => [],
            'timestamps' => []
        ];
        
        try {
            $debug_info['timestamps']['start'] = microtime(true);
            error_log("=== Starting WisataController::index ===");
            
            // Ambil tempat wisata populer
            error_log("Attempting to get popular spots...");
            try {
                $debug_info['timestamps']['before_query'] = microtime(true);
                $popularSpots = $this->wisataModel->getPopular(9);
                $debug_info['timestamps']['after_query'] = microtime(true);
                $debug_info['queries'][] = [
                    'type' => 'getPopular',
                    'limit' => 9,
                    'duration' => $debug_info['timestamps']['after_query'] - $debug_info['timestamps']['before_query'],
                    'result_count' => is_array($popularSpots) ? count($popularSpots) : 0
                ];
                
                if (empty($popularSpots)) {
                    $debug_info['errors'][] = "No popular spots found in database";
                    error_log("Warning: No popular spots found");
                } else {
                    error_log("Successfully retrieved popular spots. Count: " . count($popularSpots));
                }
            } catch (\Exception $e) {
                $debug_info['errors'][] = [
                    'message' => $e->getMessage(),
                    'type' => 'PopularSpotsQuery',
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ];
                error_log("Error getting popular spots: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                throw $e;
            }

            // Ambil user jika sudah login
            $user = null;
            if (isset($_SESSION['user_id'])) {
                error_log("User is logged in. User ID: " . $_SESSION['user_id']);
                try {
                    $debug_info['timestamps']['before_user_query'] = microtime(true);
                    $user = $this->db->getCollection('users')->findOne([
                        '_id' => new ObjectId($_SESSION['user_id'])
                    ]);
                    $debug_info['timestamps']['after_user_query'] = microtime(true);
                    $debug_info['queries'][] = [
                        'type' => 'getUser',
                        'user_id' => $_SESSION['user_id'],
                        'duration' => $debug_info['timestamps']['after_user_query'] - $debug_info['timestamps']['before_user_query']
                    ];
                    error_log("Successfully retrieved user data");
                } catch (\Exception $e) {
                    $debug_info['errors'][] = [
                        'message' => $e->getMessage(),
                        'type' => 'UserQuery',
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ];
                    error_log("Error getting user data: " . $e->getMessage());
                    error_log("Stack trace: " . $e->getTraceAsString());
                }
            } else {
                error_log("No user logged in");
            }

            error_log("Rendering view with data...");
            
            // Pastikan $popularSpots adalah array
            if (!is_array($popularSpots)) {
                $debug_info['errors'][] = "popularSpots is not an array, type: " . gettype($popularSpots);
                error_log("Warning: popularSpots is not an array. Converting to empty array.");
                $popularSpots = [];
            }
            
            $debug_info['timestamps']['end'] = microtime(true);
            $debug_info['total_duration'] = $debug_info['timestamps']['end'] - $debug_info['timestamps']['start'];
            
            // Simpan error terakhir ke session untuk debugging
            if (!empty($debug_info['errors'])) {
                $_SESSION['last_error'] = is_array($debug_info['errors']) ? 
                    json_encode($debug_info['errors']) : 
                    $debug_info['errors'];
            }
            
            // Render view dengan data
            return view('tourist-spots/index', [
                'title' => 'Tempat Wisata',
                'popularSpots' => $popularSpots,
                'user' => $user,
                'debug_info' => $debug_info
            ]);
        } catch (\Exception $e) {
            error_log("=== ERROR in WisataController::index ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            error_log("File: " . $e->getFile() . " on line " . $e->getLine());
            error_log("=== End of error log ===");
            
            $debug_info['errors'][] = [
                'message' => $e->getMessage(),
                'type' => 'Fatal',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            
            return view('error', [
                'message' => 'Terjadi kesalahan saat memuat data tempat wisata: ' . $e->getMessage(),
                'back_url' => '/',
                'debug_info' => $debug_info
            ]);
        }
    }

    public function search()
    {
        // Get all filters from request
        $filters = [
            'keyword' => $_GET['q'] ?? '',
            'category' => $_GET['category'] ?? '',
            'price_range' => $_GET['price_range'] ?? '',
            'rating' => $_GET['rating'] ?? '',
            'facilities' => $_GET['facilities'] ?? []
        ];

        // Convert price range to min and max values
        if (!empty($filters['price_range'])) {
            list($min, $max) = explode('-', $filters['price_range']);
            $filters['price_min'] = (int)$min;
            $filters['price_max'] = $max === '+' ? PHP_INT_MAX : (int)$max;
        }

        // Get filtered spots
        $spots = $this->wisataModel->search($filters);
        
        // If AJAX request, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            return json_encode([
                'status' => 'success',
                'data' => $spots
            ]);
        }
        
        // Otherwise, return view
        return view('wisata/search', [
            'title' => !empty($filters['keyword']) ? 'Hasil Pencarian: ' . $filters['keyword'] : 'Pencarian Wisata',
            'spots' => $spots,
            'filters' => $filters
        ]);
    }

    public function detail($id)
    {
        try {
            $spot = $this->wisataModel->findById($id);
            if (!$spot) {
                return view('error', [
                    'message' => 'Wisata tidak ditemukan',
                    'back_url' => '/wisata'
                ]);
            }

            // Get nearby spots for recommendations
            $nearbySpots = $this->wisataModel->getNearby(
                $spot['location']['coordinates'][0],
                $spot['location']['coordinates'][1],
                5000, // 5km radius
                [$id] // exclude current spot
            );

            return view('wisata/detail', [
                'title' => $spot['name'],
                'spot' => $spot,
                'nearbySpots' => $nearbySpots
            ]);
        } catch (\Exception $e) {
            return view('error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'back_url' => '/wisata'
            ]);
        }
    }

    public function popular()
    {
        // Get popular spots and convert to array
        $spots = array_map(function($spot) {
            return json_decode(json_encode($spot), true);
        }, $this->wisataModel->getPopular());

        return view('wisata/popular', [
            'title' => 'Wisata Populer',
            'spots' => $spots
        ]);
    }

    public function terdekat()
    {
        return view('wisata/nearby', [
            'title' => 'Wisata Terdekat',
            'spots' => [],
            'userLocation' => null
        ]);
    }

    public function nearby()
    {
        try {
            $lat = $_GET['lat'] ?? null;
            $lng = $_GET['lng'] ?? null;
            $radius = $_GET['radius'] ?? 5000; // default 5km

            if (!$lat || !$lng) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Latitude dan longitude diperlukan'
                ]);
            }

            // Get nearby spots
            $spots = $this->wisataModel->getNearby(
                (float)$lng, 
                (float)$lat, 
                (float)$radius
            );

            // Convert MongoDB documents to array
            $spots = array_map(function($spot) {
                return json_decode(json_encode($spot), true);
            }, $spots);

            // Return JSON response
            header('Content-Type: application/json');
            return json_encode([
                'status' => 'success',
                'data' => $spots
            ]);

        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
} 