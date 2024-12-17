<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use MongoDB\BSON\ObjectId;

class HomeController extends Controller {
    public function index(Request $request, Response $response): void {
        try {
            // Data default
            $data = [
                'spots' => [
                    [
                        'name' => 'Raja Ampat',
                        'location' => 'Papua Barat',
                        'description' => 'Surga diving dengan keindahan bawah laut yang menakjubkan',
                        'image' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1000&q=80',
                        'rating' => 4.9
                    ],
                    [
                        'name' => 'Borobudur',
                        'location' => 'Jawa Tengah',
                        'description' => 'Candi Buddha terbesar di dunia dengan arsitektur yang megah',
                        'image' => 'https://images.unsplash.com/photo-1518709766631-a6a7f45921c3?auto=format&fit=crop&w=1000&q=80',
                        'rating' => 4.8
                    ],
                    [
                        'name' => 'Nusa Penida',
                        'location' => 'Bali',
                        'description' => 'Pulau eksotis dengan pantai dan tebing yang spektakuler',
                        'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1000&q=80',
                        'rating' => 4.7
                    ]
                ],
                'articles' => [
                    [
                        'title' => '10 Destinasi Wisata Tersembunyi di Indonesia',
                        'excerpt' => 'Temukan keindahan alam yang belum banyak terjamah di pelosok Nusantara',
                        'image' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1000&q=80',
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'title' => 'Tips Traveling Hemat ke Destinasi Populer',
                        'excerpt' => 'Panduan lengkap menjelajah Indonesia dengan budget terbatas',
                        'image' => 'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?auto=format&fit=crop&w=1000&q=80',
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    [
                        'title' => 'Kuliner Khas yang Wajib Dicoba',
                        'excerpt' => 'Jelajahi kekayaan rasa masakan tradisional Indonesia',
                        'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=1000&q=80',
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                ],
                'user' => $request->getSession('user'),
                'title' => 'Beranda - Lokanesia',
                'description' => 'Jelajahi keindahan Indonesia bersama Lokanesia',
                'isHomePage' => true
            ];

            // Render view
            $this->view('home', $data);

        } catch (\Exception $e) {
            error_log("HomeController::index - Error: " . $e->getMessage());
            $this->view('error', [
                'message' => 'Maaf, terjadi kesalahan pada server. Silakan coba beberapa saat lagi.',
                'debug' => $_ENV['APP_DEBUG'] ?? false,
                'e' => $e
            ]);
        }
    }
} 