<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use DateTime;
use DateTimeZone;

class DashboardController extends Controller {
    public function itineraries(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->redirect('/login');
                return;
            }

            // Set timezone untuk Indonesia
            $timezone = new DateTimeZone('Asia/Jakarta');
            $today = new DateTime('today', $timezone);
            $tomorrow = new DateTime('tomorrow', $timezone);

            // Ambil data user
            $usersCollection = $this->db->selectCollection('users');
            $user = $usersCollection->findOne([
                '_id' => new ObjectId($_SESSION['user_id'])
            ]);

            // Ambil data tempat wisata untuk dropdown
            $spotsCollection = $this->db->selectCollection('tourist_spots');
            $spots = $spotsCollection->find([], [
                'sort' => ['name' => 1]
            ])->toArray();

            $spotsList = array_map(function($spot) {
                return [
                    '_id' => (string) $spot->_id,
                    'name' => $spot->name
                ];
            }, $spots);

            // Query rencana perjalanan
            $itinerariesCollection = $this->db->selectCollection('itineraries');
            $pipeline = [
                [
                    '$match' => [
                        'user_id' => new ObjectId($_SESSION['user_id'])
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'tourist_spots',
                        'localField' => 'spot_id',
                        'foreignField' => '_id',
                        'as' => 'spot'
                    ]
                ],
                [
                    '$unwind' => '$spot'
                ],
                [
                    '$sort' => ['date' => 1]
                ]
            ];

            $itineraries = $itinerariesCollection->aggregate($pipeline)->toArray();

            // Convert BSON to array dan tambahkan status
            $itinerariesData = array_map(function($itinerary) use ($timezone, $today, $tomorrow) {
                $visitDate = $itinerary->date->toDateTime()->setTimezone($timezone);
                
                // Tentukan status berdasarkan tanggal
                if ($visitDate >= $tomorrow) {
                    $status = 'upcoming';
                    $statusLabel = 'Akan Datang';
                    $statusClass = 'bg-info';
                } elseif ($visitDate->format('Y-m-d') === $today->format('Y-m-d')) {
                    $status = 'ongoing';
                    $statusLabel = 'Sedang Berlangsung';
                    $statusClass = 'bg-success';
                } else {
                    $status = 'completed';
                    $statusLabel = 'Selesai';
                    $statusClass = 'bg-secondary';
                }

                return [
                    '_id' => (string) $itinerary->_id,
                    'date' => $visitDate->format('Y-m-d'),
                    'notes' => $itinerary->notes ?? '',
                    'status' => $status,
                    'statusLabel' => $statusLabel,
                    'statusClass' => $statusClass,
                    'spot' => [
                        '_id' => (string) $itinerary->spot->_id,
                        'name' => $itinerary->spot->name,
                        'address' => $itinerary->spot->address,
                        'image_url' => $itinerary->spot->image_url ?? 'https://placehold.co/600x400?text=Wisata+Indonesia'
                    ]
                ];
            }, $itineraries);

            $response->view('dashboard/itineraries', [
                'user' => $user,
                'itineraries' => $itinerariesData,
                'spots' => $spotsList
            ]);

        } catch (\Exception $e) {
            error_log("Error in DashboardController::itineraries: " . $e->getMessage());
            $response->serverError('Terjadi kesalahan saat memuat rencana perjalanan');
        }
    }

    public function getItinerary(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            $id = $request->getParam('id');
            if (!$id) {
                throw new \Exception('ID tidak valid');
            }

            $itinerariesCollection = $this->db->selectCollection('itineraries');
            $itinerary = $itinerariesCollection->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($_SESSION['user_id'])
            ]);

            if (!$itinerary) {
                throw new \Exception('Rencana perjalanan tidak ditemukan');
            }

            $response->json([
                'status' => 'success',
                'data' => [
                    '_id' => (string) $itinerary->_id,
                    'date' => $itinerary->date->toDateTime()->format('Y-m-d'),
                    'notes' => $itinerary->notes ?? ''
                ]
            ]);

        } catch (\Exception $e) {
            error_log("Error in getItinerary: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateItinerary(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            $id = $request->getParam('id');
            if (!$id) {
                throw new \Exception('ID tidak valid');
            }

            $data = $request->getBody();
            if (!isset($data['date'])) {
                throw new \Exception('Tanggal kunjungan harus diisi');
            }

            $itinerariesCollection = $this->db->selectCollection('itineraries');
            
            // Cek apakah rencana perjalanan ada dan milik user yang sedang login
            $existingItinerary = $itinerariesCollection->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($_SESSION['user_id'])
            ]);

            if (!$existingItinerary) {
                throw new \Exception('Rencana perjalanan tidak ditemukan');
            }

            $result = $itinerariesCollection->updateOne(
                ['_id' => new ObjectId($id)],
                [
                    '$set' => [
                        'date' => new UTCDateTime(strtotime($data['date']) * 1000),
                        'notes' => $data['notes'] ?? '',
                        'updated_at' => new UTCDateTime()
                    ]
                ]
            );

            if ($result->getModifiedCount() === 0) {
                throw new \Exception('Gagal memperbarui rencana perjalanan');
            }

            $response->json([
                'status' => 'success',
                'message' => 'Rencana perjalanan berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            error_log("Error in updateItinerary: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function deleteItinerary(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            $id = $request->getParam('id');
            if (!$id) {
                throw new \Exception('ID tidak valid');
            }

            $itinerariesCollection = $this->db->selectCollection('itineraries');
            
            // Cek apakah rencana perjalanan ada dan milik user yang sedang login
            $existingItinerary = $itinerariesCollection->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($_SESSION['user_id'])
            ]);

            if (!$existingItinerary) {
                throw new \Exception('Rencana perjalanan tidak ditemukan');
            }

            $result = $itinerariesCollection->deleteOne([
                '_id' => new ObjectId($id)
            ]);

            if ($result->getDeletedCount() === 0) {
                throw new \Exception('Gagal menghapus rencana perjalanan');
            }

            $response->json([
                'status' => 'success',
                'message' => 'Rencana perjalanan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            error_log("Error in deleteItinerary: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function index(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->redirect('/login');
                return;
            }

            // Get user data
            $usersCollection = $this->db->selectCollection('users');
            $user = $usersCollection->findOne([
                '_id' => new ObjectId($_SESSION['user_id'])
            ]);

            // Get reviews data
            $reviewsCollection = $this->db->selectCollection('reviews');
            $reviews = $reviewsCollection->find([
                'user_id' => new ObjectId($_SESSION['user_id'])
            ])->toArray();

            $formattedReviews = [];
            foreach ($reviews as $review) {
                $spotsCollection = $this->db->selectCollection('tourist_spots');
                $spot = $spotsCollection->findOne(['_id' => $review->spot_id]);

                if ($spot) {
                    $formattedReviews[] = [
                        'id' => (string) $review->_id,
                        'rating' => $review->rating ?? 0,
                        'comment' => $review->comment ?? '',
                        'created_at' => $review->created_at->toDateTime()->format('Y-m-d H:i:s'),
                        'spot' => [
                            'id' => (string) $spot->_id,
                            'name' => $spot->name,
                            'image' => $spot->image_url ?? null,
                            'address' => $spot->address ?? '',
                            'category' => $spot->category ?? ''
                        ]
                    ];
                }
            }

            // Get total counts
            $totalSpots = $this->db->selectCollection('tourist_spots')->countDocuments();
            $totalReviews = count($reviews);
            $totalItineraries = $this->db->selectCollection('itineraries')->countDocuments([
                'user_id' => new ObjectId($_SESSION['user_id'])
            ]);

            $data = [
                'user' => $user,
                'totalSpots' => $totalSpots,
                'totalReviews' => $totalReviews,
                'totalItineraries' => $totalItineraries,
                'reviews' => $formattedReviews
            ];

            // Check if reviews page is requested
            $path = $request->getPath();
            if (strpos($path, '/dashboard/reviews') !== false) {
                $response->view('dashboard/reviews', $data);
                return;
            }

            $response->view('dashboard/index', $data);

        } catch (\Exception $e) {
            error_log("Error in DashboardController::index: " . $e->getMessage());
            $response->serverError('Terjadi kesalahan saat memuat halaman');
        }
    }

    public function createItinerary(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            $data = $request->getBody();
            
            // Validasi input
            if (empty($data['spot_id'])) {
                throw new \Exception('Tempat wisata harus dipilih');
            }
            if (empty($data['date'])) {
                throw new \Exception('Tanggal kunjungan harus diisi');
            }

            // Validasi format tanggal
            $date = date('Y-m-d', strtotime($data['date']));
            if ($date === false) {
                throw new \Exception('Format tanggal tidak valid');
            }

            // Validasi spot_id
            $spotsCollection = $this->db->selectCollection('tourist_spots');
            $spot = $spotsCollection->findOne(['_id' => new ObjectId($data['spot_id'])]);
            if (!$spot) {
                throw new \Exception('Tempat wisata tidak ditemukan');
            }

            // Cek apakah sudah ada rencana di tanggal yang sama
            $itinerariesCollection = $this->db->selectCollection('itineraries');
            $existingPlan = $itinerariesCollection->findOne([
                'user_id' => new ObjectId($_SESSION['user_id']),
                'spot_id' => new ObjectId($data['spot_id']),
                'date' => new UTCDateTime(strtotime($date) * 1000)
            ]);

            if ($existingPlan) {
                throw new \Exception('Anda sudah memiliki rencana ke tempat ini di tanggal yang sama');
            }

            // Simpan rencana baru
            $result = $itinerariesCollection->insertOne([
                'user_id' => new ObjectId($_SESSION['user_id']),
                'spot_id' => new ObjectId($data['spot_id']),
                'date' => new UTCDateTime(strtotime($date) * 1000),
                'notes' => trim($data['notes'] ?? ''),
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ]);

            if (!$result->getInsertedId()) {
                throw new \Exception('Gagal menambahkan rencana perjalanan');
            }

            $response->json([
                'status' => 'success',
                'message' => 'Rencana perjalanan berhasil ditambahkan',
                'data' => [
                    '_id' => (string) $result->getInsertedId()
                ]
            ]);

        } catch (\Exception $e) {
            error_log("Error in createItinerary: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function profile(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->redirect('/login');
                return;
            }

            // Ambil data user dengan pipeline aggregation untuk mendapatkan data lengkap
            $usersCollection = $this->db->selectCollection('users');
            $pipeline = [
                [
                    '$match' => [
                        '_id' => new ObjectId($_SESSION['user_id'])
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'reviews',
                        'localField' => '_id',
                        'foreignField' => 'user_id',
                        'as' => 'reviews'
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'itineraries',
                        'localField' => '_id',
                        'foreignField' => 'user_id',
                        'as' => 'itineraries'
                    ]
                ]
            ];

            $result = $usersCollection->aggregate($pipeline)->toArray();
            
            if (empty($result)) {
                throw new \Exception('User tidak ditemukan');
            }

            $user = $result[0];

            // Format data user
            $userData = [
                '_id' => (string) $user->_id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'avatar' => $user->avatar ?? null,
                'created_at' => $user->created_at->toDateTime()->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->toDateTime()->format('Y-m-d H:i:s'),
                'role' => $user->role ?? 'user',
                'status' => $user->status ?? 'active'
            ];

            // Hitung total dari hasil aggregation
            $totalReviews = count($user->reviews);
            $totalItineraries = count($user->itineraries);

            // Ambil 5 review terbaru
            $reviewsCollection = $this->db->selectCollection('reviews');
            $recentReviews = $reviewsCollection->aggregate([
                [
                    '$match' => [
                        'user_id' => new ObjectId($_SESSION['user_id'])
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'tourist_spots',
                        'localField' => 'spot_id',
                        'foreignField' => '_id',
                        'as' => 'spot'
                    ]
                ],
                [
                    '$unwind' => '$spot'
                ],
                [
                    '$sort' => ['created_at' => -1]
                ],
                [
                    '$limit' => 5
                ]
            ])->toArray();

            // Format aktivitas
            $activity = [];

            foreach ($recentReviews as $review) {
                $activity[] = [
                    'type' => 'review',
                    'created_at' => $review->created_at->toDateTime()->format('Y-m-d H:i:s'),
                    'data' => [
                        'rating' => $review->rating,
                        'comment' => $review->comment ?? '',
                        'spot' => [
                            'id' => (string) $review->spot->_id,
                            'name' => $review->spot->name,
                            'image' => $review->spot->image_url ?? null
                        ]
                    ]
                ];
            }

            // Ambil 5 itinerary terbaru
            $itinerariesCollection = $this->db->selectCollection('itineraries');
            $recentItineraries = $itinerariesCollection->aggregate([
                [
                    '$match' => [
                        'user_id' => new ObjectId($_SESSION['user_id'])
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'tourist_spots',
                        'localField' => 'spot_id',
                        'foreignField' => '_id',
                        'as' => 'spot'
                    ]
                ],
                [
                    '$unwind' => '$spot'
                ],
                [
                    '$sort' => ['created_at' => -1]
                ],
                [
                    '$limit' => 5
                ]
            ])->toArray();

            foreach ($recentItineraries as $itinerary) {
                $activity[] = [
                    'type' => 'itinerary',
                    'created_at' => $itinerary->created_at->toDateTime()->format('Y-m-d H:i:s'),
                    'data' => [
                        'date' => $itinerary->date->toDateTime()->format('Y-m-d'),
                        'spot' => [
                            'id' => (string) $itinerary->spot->_id,
                            'name' => $itinerary->spot->name,
                            'image' => $itinerary->spot->image_url ?? null
                        ],
                        'notes' => $itinerary->notes ?? ''
                    ]
                ];
            }

            // Sort activity by created_at
            usort($activity, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            // Limit to 10 most recent activities
            $activity = array_slice($activity, 0, 10);

            $response->view('dashboard/profile', [
                'user' => $userData,
                'totalReviews' => $totalReviews,
                'totalItineraries' => $totalItineraries,
                'activity' => $activity
            ]);

        } catch (\Exception $e) {
            error_log("Error in DashboardController::profile: " . $e->getMessage());
            $response->serverError('Terjadi kesalahan saat memuat profil');
        }
    }

    public function updateProfile(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            $data = $request->getBody();
            $usersCollection = $this->db->selectCollection('users');
            
            // Validasi input
            if (empty($data['name'])) {
                throw new \Exception('Nama harus diisi');
            }

            // Siapkan data update
            $updateData = [
                'name' => $data['name'],
                'updated_at' => new UTCDateTime()
            ];

            // Update profil
            $result = $usersCollection->updateOne(
                ['_id' => new ObjectId($_SESSION['user_id'])],
                ['$set' => $updateData]
            );

            if ($result->getModifiedCount() === 0) {
                throw new \Exception('Gagal memperbarui profil');
            }

            // Update session name
            $_SESSION['user_name'] = $data['name'];

            $response->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            error_log("Error in updateProfile: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function uploadAvatar(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('Tidak ada file yang diunggah');
            }

            $file = $_FILES['avatar'];
            
            // Validasi tipe file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new \Exception('Tipe file tidak didukung. Gunakan JPG, PNG, atau GIF');
            }

            // Validasi ukuran file (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                throw new \Exception('Ukuran file terlalu besar. Maksimal 2MB');
            }

            // Baca file dan konversi ke base64
            $imageData = file_get_contents($file['tmp_name']);
            $base64Image = 'data:' . $file['type'] . ';base64,' . base64_encode($imageData);

            // Update avatar di database
            $usersCollection = $this->db->selectCollection('users');
            
            // Update avatar baru
            $result = $usersCollection->updateOne(
                ['_id' => new ObjectId($_SESSION['user_id'])],
                [
                    '$set' => [
                        'avatar' => $base64Image,
                        'updated_at' => new UTCDateTime()
                    ]
                ]
            );

            if ($result->getModifiedCount() === 0) {
                throw new \Exception('Gagal memperbarui avatar');
            }

            $response->json([
                'status' => 'success',
                'message' => 'Avatar berhasil diperbarui',
                'data' => [
                    'avatar_url' => $base64Image
                ]
            ]);

        } catch (\Exception $e) {
            error_log("Error in uploadAvatar: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function reviews(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->redirect('/login');
                return;
            }

            $reviewsCollection = $this->db->selectCollection('reviews');
            $reviews = $reviewsCollection->find([
                'user_id' => new ObjectId($_SESSION['user_id'])
            ])->toArray();

            $formattedReviews = [];
            foreach ($reviews as $review) {
                $spotsCollection = $this->db->selectCollection('tourist_spots');
                $spot = $spotsCollection->findOne(['_id' => $review->spot_id]);

                if ($spot) {
                    $formattedReviews[] = [
                        'id' => (string) $review->_id,
                        'rating' => $review->rating ?? 0,
                        'comment' => $review->comment ?? '',
                        'created_at' => $review->created_at->toDateTime()->format('Y-m-d H:i:s'),
                        'spot' => [
                            'id' => (string) $spot->_id,
                            'name' => $spot->name,
                            'image' => $spot->image_url ?? null,
                            'address' => $spot->address ?? '',
                            'category' => $spot->category ?? ''
                        ]
                    ];
                }
            }

            $response->view('dashboard/reviews', [
                'reviews' => $formattedReviews
            ]);

        } catch (\Exception $e) {
            error_log("Error in reviews: " . $e->getMessage());
            $response->serverError('Terjadi kesalahan saat memuat ulasan');
        }
    }

    public function deleteReview(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            $id = $request->getParam('id');
            if (!$id) {
                throw new \Exception('ID tidak valid');
            }

            $reviewsCollection = $this->db->selectCollection('reviews');
            
            // Cek apakah review ada dan milik user yang sedang login
            $review = $reviewsCollection->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($_SESSION['user_id'])
            ]);

            if (!$review) {
                throw new \Exception('Ulasan tidak ditemukan');
            }

            $result = $reviewsCollection->deleteOne([
                '_id' => new ObjectId($id)
            ]);

            if ($result->getDeletedCount() === 0) {
                throw new \Exception('Gagal menghapus ulasan');
            }

            // Update rating tempat wisata
            $spotsCollection = $this->db->selectCollection('tourist_spots');
            $spot = $spotsCollection->findOne(['_id' => $review->spot_id]);
            
            if ($spot) {
                $reviews = $reviewsCollection->find(['spot_id' => $review->spot_id]);
                $totalRating = 0;
                $count = 0;
                
                foreach ($reviews as $r) {
                    $totalRating += $r->rating;
                    $count++;
                }

                $newRating = $count > 0 ? $totalRating / $count : 0;

                $spotsCollection->updateOne(
                    ['_id' => $review->spot_id],
                    [
                        '$set' => [
                            'rating' => $newRating,
                            'review_count' => $count
                        ]
                    ]
                );
            }

            $response->json([
                'status' => 'success',
                'message' => 'Ulasan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            error_log("Error in deleteReview: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateReview(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                return;
            }

            $id = $request->getParam('id');
            if (!$id) {
                throw new \Exception('ID tidak valid');
            }

            $data = $request->getBody();
            if (!isset($data['rating']) || !isset($data['comment'])) {
                throw new \Exception('Rating dan komentar harus diisi');
            }

            $reviewsCollection = $this->db->selectCollection('reviews');
            
            // Cek apakah review ada dan milik user yang sedang login
            $review = $reviewsCollection->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($_SESSION['user_id'])
            ]);

            if (!$review) {
                throw new \Exception('Ulasan tidak ditemukan');
            }

            // Update review
            $result = $reviewsCollection->updateOne(
                ['_id' => new ObjectId($id)],
                [
                    '$set' => [
                        'rating' => (int) $data['rating'],
                        'comment' => trim($data['comment']),
                        'updated_at' => new UTCDateTime()
                    ]
                ]
            );

            if ($result->getModifiedCount() === 0) {
                throw new \Exception('Gagal memperbarui ulasan');
            }

            // Update rating tempat wisata
            $spotsCollection = $this->db->selectCollection('tourist_spots');
            $reviews = $reviewsCollection->find(['spot_id' => $review->spot_id]);
            $totalRating = 0;
            $count = 0;
            
            foreach ($reviews as $r) {
                $totalRating += $r->rating;
                $count++;
            }

            $newRating = $count > 0 ? $totalRating / $count : 0;

            $spotsCollection->updateOne(
                ['_id' => $review->spot_id],
                [
                    '$set' => [
                        'rating' => $newRating,
                        'review_count' => $count
                    ]
                ]
            );

            $response->json([
                'status' => 'success',
                'message' => 'Ulasan berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            error_log("Error in updateReview: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
} 