<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Database\Database;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Model\BSONDocument;
use MongoDB\Database as MongoDatabase;

class TouristSpotController extends Controller {
    protected MongoDatabase $db;
    protected Collection $collection;

    private function convertBSONToArray($bsonDocument): array {
        if (!$bsonDocument) {
            return [];
        }

            $array = [];
        foreach ($bsonDocument as $key => $value) {
            if ($value instanceof ObjectId) {
                $array[$key] = (string) $value;
            } elseif ($value instanceof UTCDateTime) {
                $array[$key] = $value->toDateTime()->format('Y-m-d H:i:s');
            } elseif (is_object($value) && method_exists($value, 'getArrayCopy')) {
                $array[$key] = $this->convertBSONToArray($value->getArrayCopy());
            } elseif (is_array($value) || is_object($value)) {
                $array[$key] = $this->convertBSONToArray((array) $value);
        } else {
                $array[$key] = $value;
        }
        }

        return $array;
    }

    public function __construct() {
        parent::__construct();
        try {
            // Inisialisasi koneksi database
            $database = Database::getInstance();
            $this->db = $database->getConnection();
            
            if (!$this->db) {
                throw new \Exception("Koneksi database tidak tersedia");
            }

            // Cek dan buat collection jika belum ada
            $collections = $this->db->listCollections();
            $collectionExists = false;
            foreach ($collections as $collection) {
                if ($collection->getName() === 'tourist_spots') {
                    $collectionExists = true;
                    break;
                }
            }

            if (!$collectionExists) {
                $this->db->createCollection('tourist_spots');
                error_log("Created tourist_spots collection");
            }

            $this->collection = $this->db->selectCollection('tourist_spots');
            
            // Pastikan ada data test
            $this->ensureTestData();
            
            // Tambahkan default gambar untuk semua wisata yang belum memiliki gambar
            $this->updateDefaultImages();
            
            error_log("TouristSpotController initialized successfully");
        } catch (\Exception $e) {
            error_log("Error initializing TouristSpotController: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Gagal menginisialisasi TouristSpotController: " . $e->getMessage());
        }
    }

    private function updateDefaultImages(): void {
        try {
            // Array gambar default dari internet yang reliable
            $defaultImages = [
                'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg', // Borobudur
                'https://images.pexels.com/photos/2474689/pexels-photo-2474689.jpeg', // Beach
                'https://images.pexels.com/photos/2166559/pexels-photo-2166559.jpeg', // Temple
                'https://images.pexels.com/photos/2161467/pexels-photo-2161467.jpeg', // Mountain
                'https://images.pexels.com/photos/2440009/pexels-photo-2440009.jpeg', // Lake
                'https://images.pexels.com/photos/2440024/pexels-photo-2440024.jpeg', // Waterfall
                'https://images.pexels.com/photos/2440061/pexels-photo-2440061.jpeg', // Forest
                'https://images.pexels.com/photos/2440079/pexels-photo-2440079.jpeg', // Cave
                'https://images.pexels.com/photos/2440085/pexels-photo-2440085.jpeg', // River
                'https://images.pexels.com/photos/2440091/pexels-photo-2440091.jpeg'  // Island
            ];

            // Update semua dokumen yang tidak memiliki image_url atau memiliki image_url default
            $spots = $this->collection->find([
                '$or' => [
                    ['image_url' => ['$exists' => false]],
                    ['image_url' => null],
                    ['image_url' => ''],
                    ['image_url' => '/assets/images/default-spot.jpg']
                ]
            ]);

            foreach ($spots as $spot) {
                // Pilih gambar secara acak dari array
                $randomImage = $defaultImages[array_rand($defaultImages)];
                
                try {
                    // Konfigurasi stream context untuk SSL
                    $arrContextOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ]
                    ];

                    // Verifikasi apakah gambar bisa diakses dengan stream context
                    $headers = get_headers($randomImage, 1, stream_context_create($arrContextOptions));
                    
                    if ($headers && (strpos($headers[0], '200') !== false || strpos($headers[0], '301') !== false || strpos($headers[0], '302') !== false)) {
                        $this->collection->updateOne(
                            ['_id' => $spot->_id],
                            ['$set' => ['image_url' => $randomImage]]
                        );
                    } else {
                        // Jika gambar tidak bisa diakses, gunakan gambar default lokal
                        $this->collection->updateOne(
                            ['_id' => $spot->_id],
                            ['$set' => ['image_url' => '/assets/images/default-spot.jpg']]
                        );
                    }
                } catch (\Exception $e) {
                    error_log("Error verifying image URL: " . $e->getMessage());
                    // Gunakan URL gambar default lokal jika gagal
                    $this->collection->updateOne(
                        ['_id' => $spot->_id],
                        ['$set' => ['image_url' => 'https://placehold.co/600x400?text=Wisata+Indonesia']]
                    );
                }
            }
        } catch (\Exception $e) {
            error_log("Error updating default images: " . $e->getMessage());
        }
    }

    private function ensureTestData(): void {
        try {
            // Cek apakah sudah ada data
            $count = $this->collection->countDocuments([]);
            error_log("Current document count: " . $count);
            
            if ($count === 0) {
                error_log("No data found, inserting test data...");
                
                // Data test untuk kategori wisata
                $testData = [
                    [
                        'name' => 'Pantai Kuta',
                        'description' => 'Pantai terkenal di Bali dengan pemandangan sunset yang indah',
                        'category' => 'Wisata Alam',
                        'address' => 'Kuta, Bali',
                        'ticket_price' => 10000,
                        'rating' => 4.5,
                        'image_url' => 'https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg',
                        'operating_hours' => '24 jam',
                        'location' => [
                            'type' => 'Point',
                            'coordinates' => [115.1667, -8.7167]
                        ],
                        'created_at' => new UTCDateTime()
                    ],
                    [
                        'name' => 'Candi Borobudur',
                        'description' => 'Candi Buddha terbesar di dunia',
                        'category' => 'Wisata Sejarah',
                        'address' => 'Magelang, Jawa Tengah',
                        'ticket_price' => 50000,
                        'rating' => 4.8,
                        'image_url' => 'https://images.pexels.com/photos/2161467/pexels-photo-2161467.jpeg',
                        'operating_hours' => '06.00 - 17.00',
                        'created_at' => new UTCDateTime()
                    ],
                    [
                        'name' => 'Kawah Putih',
                        'description' => 'Danau kawah dengan air berwarna putih kehijauan',
                        'category' => 'Wisata Alam',
                        'address' => 'Bandung, Jawa Barat',
                        'ticket_price' => 30000,
                        'rating' => 4.6,
                        'image_url' => 'https://images.pexels.com/photos/2440009/pexels-photo-2440009.jpeg',
                        'operating_hours' => '07.00 - 17.00',
                        'created_at' => new UTCDateTime()
                    ],
                    [
                        'name' => 'Taman Mini Indonesia Indah',
                        'description' => 'Taman rekreasi yang menampilkan kebudayaan Indonesia',
                        'category' => 'Wisata Budaya',
                        'address' => 'Jakarta Timur',
                        'ticket_price' => 20000,
                        'rating' => 4.4,
                        'image_url' => 'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg',
                        'operating_hours' => '08.00 - 17.00',
                        'created_at' => new UTCDateTime()
                    ],
                    [
                        'name' => 'Dufan',
                        'description' => 'Taman hiburan dengan berbagai wahana seru',
                        'category' => 'Wisata Hiburan',
                        'address' => 'Jakarta Utara',
                        'ticket_price' => 200000,
                        'rating' => 4.5,
                        'image_url' => 'https://images.pexels.com/photos/784916/pexels-photo-784916.jpeg',
                        'operating_hours' => '10.00 - 20.00',
                        'created_at' => new UTCDateTime()
                    ]
                ];

                // Insert data test
                $result = $this->collection->insertMany($testData);
                error_log("Inserted " . count($result->getInsertedIds()) . " test documents");

                // Verifikasi data
                $insertedCount = $this->collection->countDocuments([]);
                error_log("Total documents after insert: " . $insertedCount);

                // Debug: Cek kategori yang tersedia
                $categories = $this->collection->distinct('category');
                error_log("Available categories after insert: " . json_encode($categories));
            } else {
                error_log("Test data already exists");
            }
        } catch (\Exception $e) {
            error_log("Error in ensureTestData: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
        }
    }

    public function index(Request $request, Response $response): void {
        try {
            // Get popular spots (sorted by rating)
            $popularSpots = $this->collection->find(
                [],
                [
                    'sort' => ['rating' => -1],
                    'limit' => 9
                ]
            )->toArray();

            // Convert BSON to array
            $popularSpots = array_map(function($spot) {
                $spotArray = $this->convertBSONToArray($spot);
                // Pastikan review_count selalu ada
                if (!isset($spotArray['review_count'])) {
                    $spotArray['review_count'] = 0;
                }
                return $spotArray;
            }, $popularSpots);

            // Get user data if logged in
            $user = null;
            if (isset($_SESSION['user_id'])) {
                try {
                    $usersCollection = $this->db->selectCollection('users');
                    $userDoc = $usersCollection->findOne(['_id' => new \MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
                    if ($userDoc) {
                        $user = $this->convertBSONToArray($userDoc);
                    }
                } catch (\Exception $e) {
                    error_log("Error fetching user data: " . $e->getMessage());
                }
            }

            // Debug info for development
            $debug_info = [];
            if (defined('APP_ENV') && APP_ENV === 'development') {
                $debug_info = [
                    'session' => $_SESSION,
                    'request' => [
                        'method' => $_SERVER['REQUEST_METHOD'],
                        'uri' => $_SERVER['REQUEST_URI']
                    ],
                    'user' => $user
                ];
            }

            // Render view with data
            $response->view('tourist-spots/index', [
                'popularSpots' => $popularSpots,
                'user' => $user,
                'debug_info' => $debug_info
            ]);

        } catch (\Exception $e) {
            error_log("Error in TouristSpotController::index: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $response->serverError("Terjadi kesalahan saat memuat data tempat wisata");
        }
    }

    public function show(Request $request, Response $response): void {
        try {
            $id = $request->getParam('id');
            error_log("Showing tourist spot with ID: " . $id);

            if (!$id) {
                throw new \Exception('ID tidak valid');
            }

            // Cek session user
            $user = null;
            if (isset($_SESSION['user_id'])) {
                $usersCollection = $this->db->selectCollection('users');
                $user = $usersCollection->findOne(['_id' => new \MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
            }

            // Find the tourist spot
            $spot = $this->collection->findOne([
                '_id' => new \MongoDB\BSON\ObjectId($id)
            ]);

            if (!$spot) {
                throw new \Exception('Tempat wisata tidak ditemukan');
            }

            // Get reviews with user details
            $reviewsCollection = $this->db->selectCollection('reviews');
            $reviews = $reviewsCollection->find(
                ['spot_id' => new \MongoDB\BSON\ObjectId($id)],
                [
                    'sort' => ['created_at' => -1]
                ]
            )->toArray();

            // Get saved status if user is logged in
            $savedStatus = false;
            if ($user) {
                $savedSpotsCollection = $this->db->selectCollection('saved_spots');
                $savedSpot = $savedSpotsCollection->findOne([
                    'user_id' => new \MongoDB\BSON\ObjectId($user['_id']),
                    'spot_id' => new \MongoDB\BSON\ObjectId($id)
                ]);
                $savedStatus = (bool) $savedSpot;
            }

            // Get nearby spots
            $nearbySpots = [];
            if (isset($spot->location) && isset($spot->location->coordinates)) {
                $nearbySpots = $this->collection->find([
                    '_id' => ['$ne' => new \MongoDB\BSON\ObjectId($id)],
                    'location' => [
                        '$near' => [
                            '$geometry' => [
                                'type' => 'Point',
                                'coordinates' => $spot->location->coordinates
                            ],
                            '$maxDistance' => 5000 // 5km
                        ]
                    ]
                ],
                [
                    'limit' => 3,
                    'sort' => ['rating' => -1]
                ])->toArray();
            }

            // Convert BSON to array for easier handling in view
            $spotData = $this->convertBSONToArray($spot);
            $reviewsData = array_map([$this, 'convertBSONToArray'], $reviews);
            $nearbyData = array_map([$this, 'convertBSONToArray'], $nearbySpots);

            $response->view('tourist-spots/show', [
                'user' => $user ? $this->convertBSONToArray($user) : null,
                'spot' => $spotData,
                'reviews' => $reviewsData,
                'nearbySpots' => $nearbyData,
                'savedStatus' => $savedStatus
            ]);

        } catch (\Exception $e) {
            error_log("Error in TouristSpotController::show: " . $e->getMessage());
            $response->notFound('Tempat wisata tidak ditemukan');
        }
    }

    public function nearby(Request $request, Response $response): void {
        try {
            $lat = (float) $request->getQuery('lat');
            $lng = (float) $request->getQuery('lng');
            $radius = (float) $request->getQuery('radius', 5000); // Default 5km
            $limit = (int) $request->getQuery('limit', 10);

            error_log("Searching nearby spots - lat: {$lat}, lng: {$lng}, radius: {$radius}m, limit: {$limit}");

            if (!$lat || !$lng) {
                throw new \Exception('Latitude dan longitude diperlukan');
            }

            $spots = $this->collection
                ->find([
                    'location' => [
                        '$near' => [
                            '$geometry' => [
                                'type' => 'Point',
                                'coordinates' => [$lng, $lat]
                            ],
                            '$maxDistance' => $radius
                        ]
                    ]
                ], [
                    'limit' => $limit,
                    'projection' => [
                        'name' => 1,
                        'location' => 1,
                        'rating' => 1,
                        'image_url' => 1,
                        'category' => 1,
                        'address' => 1,
                        '_id' => 1
                    ]
                ])->toArray();

            $spotsCount = count($spots);
            error_log("Found {$spotsCount} nearby spots");

            // Convert BSON to array
            $spotsArray = array_map(function($spot) {
                return [
                    '_id' => (string) $spot->_id,
                    'name' => (string) ($spot->name ?? ''),
                    'category' => (string) ($spot->category ?? 'Umum'),
                    'address' => (string) ($spot->address ?? ''),
                    'image_url' => (string) ($spot->image_url ?? '/assets/images/default-spot.jpg'),
                    'rating' => (float) ($spot->rating ?? 0),
                    'location' => isset($spot->location) ? [
                        'type' => (string) ($spot->location->type ?? 'Point'),
                        'coordinates' => [
                            (float) ($spot->location->coordinates[0] ?? 0),
                            (float) ($spot->location->coordinates[1] ?? 0)
                        ]
                    ] : null
                ];
            }, $spots);

            if ($spotsCount > 0) {
                $firstSpot = json_encode($spotsArray[0], JSON_UNESCAPED_UNICODE);
                error_log("Sample nearby spot: {$firstSpot}");
            }

            $response->json([
                'status' => 'success',
                'data' => $spotsArray
            ]);
        } catch (\Exception $e) {
            error_log("Error in TouristSpotController::nearby: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $response->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mencari tempat wisata terdekat'
            ]);
        }
    }

    public function search(Request $request, Response $response): void {
        try {
            $query = trim($request->getQuery('q', ''));
            $category = trim($request->getQuery('category', ''));
            $minPrice = (int) $request->getQuery('minPrice', 0);
            $maxPrice = (int) $request->getQuery('maxPrice', PHP_INT_MAX);
            $minRating = (float) $request->getQuery('minRating', 0);
            
            error_log("Search started with parameters:");
            error_log("Query: " . $query);
            error_log("Category: " . $category);
            error_log("Price Range: " . $minPrice . "-" . $maxPrice);
            error_log("Min Rating: " . $minRating);

            // Debug: Tampilkan semua kategori yang ada di database
            $availableCategories = $this->collection->distinct('category');
            error_log("Available categories in database: " . json_encode($availableCategories));

            // Build search filter
            $filter = [];
            
            if (!empty($query)) {
                $filter['$or'] = [
                    ['name' => ['$regex' => $query, '$options' => 'i']],
                    ['description' => ['$regex' => $query, '$options' => 'i']],
                    ['address' => ['$regex' => $query, '$options' => 'i']]
                ];
            }
            
            if (!empty($category)) {
                // Ubah pencarian kategori menjadi case-insensitive
                $filter['category'] = ['$regex' => '^' . preg_quote($category) . '$', '$options' => 'i'];
                error_log("Category filter: " . json_encode($filter['category']));
            }
            
            if ($minPrice > 0 || $maxPrice < PHP_INT_MAX) {
                $filter['ticket_price'] = [
                    '$gte' => $minPrice,
                    '$lte' => $maxPrice
                ];
            }
            
            if ($minRating > 0) {
                $filter['rating'] = ['$gte' => $minRating];
            }

            error_log("Final search filter: " . json_encode($filter));

            // Execute search
            $spots = $this->collection->find($filter)->toArray();
            error_log("Found " . count($spots) . " spots");

            // Debug: Tampilkan beberapa data pertama yang ditemukan
            if (count($spots) > 0) {
                error_log("First spot found: " . json_encode($spots[0]));
            }

            // Convert BSON to array
            $results = array_map(function($spot) {
                return $this->convertBSONToArray($spot);
            }, $spots);

            // Send JSON response
            $response->json([
                'status' => 'success',
                'data' => $results,
                'debug' => [
                    'filter' => $filter,
                    'availableCategories' => $availableCategories,
                    'requestedCategory' => $category,
                    'totalResults' => count($results)
                ]
            ]);

        } catch (\Exception $e) {
            error_log("Search error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $response->serverError("Terjadi kesalahan saat mencari tempat wisata: " . $e->getMessage());
        }
    }

    public function store(Request $request, Response $response): void {
        $this->validateJson();

        $rules = [
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'category' => 'required',
            'address' => 'required',
            'ticket_price' => 'required|numeric',
            'operating_hours' => 'required',
            'coordinates' => 'required|array'
        ];

        $data = $request->getBody();
        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        // Array gambar default yang reliable
        $defaultImages = [
            'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg',
            'https://images.pexels.com/photos/2474689/pexels-photo-2474689.jpeg',
            'https://images.pexels.com/photos/2166559/pexels-photo-2166559.jpeg',
            'https://images.pexels.com/photos/2161467/pexels-photo-2161467.jpeg',
            'https://images.pexels.com/photos/2440009/pexels-photo-2440009.jpeg'
        ];

        // Pilih gambar secara acak
        $randomImage = $defaultImages[array_rand($defaultImages)];

        try {
            // Verifikasi apakah gambar bisa diakses
            $headers = get_headers($randomImage);
            if (!$headers || strpos($headers[0], '200') === false) {
                $randomImage = 'https://placehold.co/600x400?text=Wisata+Indonesia';
            }
        } catch (\Exception $e) {
            $randomImage = 'https://placehold.co/600x400?text=Wisata+Indonesia';
        }

        // Create location object for MongoDB
        $location = [
            'type' => 'Point',
            'coordinates' => [
                (float) $data['coordinates']['lng'],
                (float) $data['coordinates']['lat']
            ]
        ];

        $spotData = [
            'name' => $data['name'],
            'description' => $data['description'],
            'category' => $data['category'],
            'address' => $data['address'],
            'ticket_price' => (float) $data['ticket_price'],
            'operating_hours' => $data['operating_hours'],
            'location' => $location,
            'image_url' => $randomImage,
            'rating' => 0,
            'review_count' => 0,
            'created_by' => new ObjectId($request->user->_id),
            'created_at' => new UTCDateTime(),
            'updated_at' => new UTCDateTime()
        ];

        $result = $this->collection->insertOne($spotData);

        if (!$result->getInsertedId()) {
            $response->serverError('Failed to create tourist spot');
            return;
        }

        $spot = $this->collection->findOne([
            '_id' => $result->getInsertedId()
        ]);

        $response->setStatus(201);
        $response->json(['data' => $spot]);
    }

    public function update(Request $request, Response $response): void {
        $this->validateJson();

        $id = $request->getParam('id');
        $data = $request->getBody();

        $rules = [
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'category' => 'required',
            'address' => 'required',
            'ticket_price' => 'required|numeric',
            'operating_hours' => 'required',
            'coordinates' => 'required|array'
        ];

        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        try {
            $spot = $this->collection->findOne([
                '_id' => new ObjectId($id)
            ]);

            if (!$spot) {
                $response->notFound('Tourist spot not found');
                return;
            }

            // Check if user is the creator
            if ((string) $spot->created_by !== (string) $request->user->_id) {
                $response->forbidden('You are not authorized to update this tourist spot');
                return;
            }

            // Update location object
            $location = [
                'type' => 'Point',
                'coordinates' => [
                    (float) $data['coordinates']['lng'],
                    (float) $data['coordinates']['lat']
                ]
            ];

            $updateData = [
                'name' => $data['name'],
                'description' => $data['description'],
                'category' => $data['category'],
                'address' => $data['address'],
                'ticket_price' => (float) $data['ticket_price'],
                'operating_hours' => $data['operating_hours'],
                'location' => $location,
                'updated_at' => new UTCDateTime()
            ];

            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );

            if ($result->getModifiedCount() === 0) {
                $response->serverError('Failed to update tourist spot');
                return;
            }

            $updatedSpot = $this->collection->findOne([
                '_id' => new ObjectId($id)
            ]);

            $response->json(['data' => $updatedSpot]);

        } catch (\Exception $e) {
            $response->notFound('Tourist spot not found');
        }
    }

    public function destroy(Request $request, Response $response): void {
        $id = $request->getParam('id');

        try {
            $spot = $this->collection->findOne([
                '_id' => new ObjectId($id)
            ]);

            if (!$spot) {
                $response->notFound('Tourist spot not found');
                return;
            }

            // Check if user is the creator
            if ((string) $spot->created_by !== (string) $request->user->_id) {
                $response->forbidden('You are not authorized to delete this tourist spot');
                return;
            }

            // Delete associated reviews
            $this->db->selectCollection('reviews')->deleteMany([
                'tourist_spot_id' => new ObjectId($id)
            ]);

            // Delete the tourist spot
            $result = $this->collection->deleteOne([
                '_id' => new ObjectId($id)
            ]);

            if ($result->getDeletedCount() === 0) {
                $response->serverError('Failed to delete tourist spot');
                return;
            }

            $response->json(['message' => 'Tourist spot deleted successfully']);

        } catch (\Exception $e) {
            $response->notFound('Tourist spot not found');
        }
    }

    // Fungsi untuk menyimpan tempat wisata ke favorit
    public function saveSpot(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                error_log("User not logged in");
                $response->json([
                    'status' => 'error',
                    'message' => 'Anda harus login terlebih dahulu'
                ], 401);
                return;
            }

            $spotId = $request->getParam('id');
            $userId = $_SESSION['user_id'];
            
            error_log("Attempting to save spot ID: " . $spotId . " for user ID: " . $userId);

            // Cek apakah tempat wisata ada
            $touristSpotsCollection = $this->db->selectCollection('tourist_spots');
            $spot = $touristSpotsCollection->findOne([
                '_id' => new \MongoDB\BSON\ObjectId($spotId)
            ]);

            if (!$spot) {
                error_log("Tourist spot not found with ID: " . $spotId);
                $response->json([
                    'status' => 'error',
                    'message' => 'Tempat wisata tidak ditemukan'
                ], 404);
                return;
            }

            error_log("Found tourist spot: " . $spot->name);

            // Cek apakah sudah disimpan sebelumnya
            $savedSpotsCollection = $this->db->selectCollection('saved_spots');
            $existingSave = $savedSpotsCollection->findOne([
                'user_id' => new \MongoDB\BSON\ObjectId($userId),
                'spot_id' => new \MongoDB\BSON\ObjectId($spotId)
            ]);

            if ($existingSave) {
                error_log("Spot already saved");
                $response->json([
                    'status' => 'error',
                    'message' => 'Tempat wisata sudah disimpan sebelumnya'
                ], 400);
                return;
            }

            // Data yang akan disimpan
            $saveData = [
                'user_id' => new \MongoDB\BSON\ObjectId($userId),
                'spot_id' => new \MongoDB\BSON\ObjectId($spotId),
                'created_at' => new \MongoDB\BSON\UTCDateTime(),
                'spot_name' => $spot->name, // Menyimpan nama spot untuk referensi
                'spot_address' => $spot->address // Menyimpan alamat spot untuk referensi
            ];

            error_log("Saving data: " . json_encode($saveData));

            // Simpan ke koleksi saved_spots
            $result = $savedSpotsCollection->insertOne($saveData);

            if (!$result->getInsertedId()) {
                throw new \Exception("Failed to save spot");
            }

            error_log("Save successful. Inserted ID: " . $result->getInsertedId());

            $response->json([
                'status' => 'success',
                'message' => 'Tempat wisata berhasil disimpan',
                'data' => [
                    'saved_id' => (string)$result->getInsertedId()
                ]
            ]);

        } catch (\Exception $e) {
            error_log("Error in saveSpot: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $response->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan tempat wisata: ' . $e->getMessage()
            ], 500);
        }
    }

    // Fungsi untuk menambah ulasan
    public function addReview(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Anda harus login terlebih dahulu'
                ], 401);
                return;
            }

            $spotId = $request->getParam('id');
            $userId = $_SESSION['user_id'];
            $data = $request->getBody();

            // Validasi input
            if (!isset($data['rating']) || !isset($data['comment'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Rating dan komentar harus diisi'
                ], 400);
                return;
            }

            // Cek apakah tempat wisata ada
            $spot = $this->collection->findOne([
                '_id' => new \MongoDB\BSON\ObjectId($spotId)
            ]);

            if (!$spot) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Tempat wisata tidak ditemukan'
                ], 404);
                return;
            }

            // Ambil data user
            $usersCollection = $this->db->selectCollection('users');
            $user = $usersCollection->findOne([
                '_id' => new \MongoDB\BSON\ObjectId($userId)
            ]);

            // Simpan review
            $reviewsCollection = $this->db->selectCollection('reviews');
            $reviewsCollection->insertOne([
                'user_id' => new \MongoDB\BSON\ObjectId($userId),
                'user_name' => $user->name,
                'spot_id' => new \MongoDB\BSON\ObjectId($spotId),
                'rating' => (float) $data['rating'],
                'comment' => $data['comment'],
                'created_at' => new \MongoDB\BSON\UTCDateTime()
            ]);

            // Update rating rata-rata tempat wisata
            $allReviews = $reviewsCollection->find([
                'spot_id' => new \MongoDB\BSON\ObjectId($spotId)
            ]);

            $totalRating = 0;
            $reviewCount = 0;
            foreach ($allReviews as $review) {
                $totalRating += $review->rating;
                $reviewCount++;
            }

            $averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;

            // Update tempat wisata
            $this->collection->updateOne(
                ['_id' => new \MongoDB\BSON\ObjectId($spotId)],
                [
                    '$set' => [
                        'rating' => $averageRating,
                        'review_count' => $reviewCount
                    ]
                ]
            );

            $response->json([
                'status' => 'success',
                'message' => 'Ulasan berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            error_log("Error in addReview: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan ulasan'
            ], 500);
        }
    }

    // Fungsi untuk menambah ke rencana perjalanan
    public function addToItinerary(Request $request, Response $response): void {
        try {
            if (!isset($_SESSION['user_id'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Anda harus login terlebih dahulu'
                ], 401);
                return;
            }

            $spotId = $request->getParam('id');
            $userId = $_SESSION['user_id'];
            $data = $request->getBody();

            // Validasi input
            if (!isset($data['date']) || !isset($data['notes'])) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Tanggal dan catatan harus diisi'
                ], 400);
                return;
            }

            // Cek apakah tempat wisata ada
            $spot = $this->collection->findOne([
                '_id' => new \MongoDB\BSON\ObjectId($spotId)
            ]);

            if (!$spot) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Tempat wisata tidak ditemukan'
                ], 404);
                return;
            }

            // Cek apakah sudah ada rencana di tanggal yang sama
            $itinerariesCollection = $this->db->selectCollection('itineraries');
            $existingItinerary = $itinerariesCollection->findOne([
                'user_id' => new \MongoDB\BSON\ObjectId($userId),
                'spot_id' => new \MongoDB\BSON\ObjectId($spotId),
                'date' => new \MongoDB\BSON\UTCDateTime(strtotime($data['date']) * 1000)
            ]);

            if ($existingItinerary) {
                $response->json([
                    'status' => 'error',
                    'message' => 'Anda sudah memiliki rencana untuk tempat ini di tanggal yang sama'
                ], 400);
                return;
            }

            // Simpan ke koleksi itineraries
            $result = $itinerariesCollection->insertOne([
                'user_id' => new \MongoDB\BSON\ObjectId($userId),
                'spot_id' => new \MongoDB\BSON\ObjectId($spotId),
                'date' => new \MongoDB\BSON\UTCDateTime(strtotime($data['date']) * 1000),
                'notes' => $data['notes'],
                'created_at' => new \MongoDB\BSON\UTCDateTime(),
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ]);

            if (!$result->getInsertedId()) {
                throw new \Exception('Gagal menambahkan ke rencana perjalanan');
            }

            $response->json([
                'status' => 'success',
                'message' => 'Berhasil ditambahkan ke rencana perjalanan'
            ]);

        } catch (\Exception $e) {
            error_log("Error in addToItinerary: " . $e->getMessage());
            $response->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan ke rencana perjalanan'
            ], 500);
        }
    }
} 