<?php

namespace App\Models;

use App\Core\Database;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;

class Wisata
{
    private Database $db;
    private string $collection = 'tourist_spots';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        return $this->db->getCollection($this->collection)->find()->toArray();
    }

    public function findById($id)
    {
        return $this->db->getCollection($this->collection)->findOne([
            '_id' => new ObjectId($id)
        ]);
    }

    public function incrementViewCount($id)
    {
        return $this->db->getCollection($this->collection)->updateOne(
            ['_id' => new ObjectId($id)],
            ['$inc' => ['view_count' => 1]]
        );
    }

    public function getRecommendations($longitude, $latitude, $category, $excludeId, $limit = 3)
    {
        return $this->db->getCollection($this->collection)->find(
            [
                '_id' => ['$ne' => new ObjectId($excludeId)],
                'category' => $category,
                'location' => [
                    '$near' => [
                        '$geometry' => [
                            'type' => 'Point',
                            'coordinates' => [$longitude, $latitude]
                        ],
                        '$maxDistance' => 50000 // 50km dalam meter
                    ]
                ]
            ],
            [
                'limit' => $limit
            ]
        )->toArray();
    }

    public function getPopular($limit = 5)
    {
        try {
            error_log("=== Starting Wisata::getPopular ===");
            error_log("Limit: " . $limit);
            
            // Pastikan koneksi database tersedia
            if (!$this->db) {
                throw new \Exception("Database connection not available");
            }
            
            error_log("Getting collection: " . $this->collection);
            $collection = $this->db->getCollection($this->collection);
            
            if (!$collection) {
                throw new \Exception("Failed to get collection: " . $this->collection);
            }
            
            error_log("Executing MongoDB query...");
            $cursor = $collection->find(
                [],
                [
                    'sort' => [
                        'rating' => -1,
                        'review_count' => -1
                    ],
                    'limit' => (int)$limit
                ]
            );
            
            error_log("Converting cursor to array...");
            $result = $cursor->toArray();
            
            error_log("Query executed successfully");
            error_log("Results count: " . count($result));
            
            if (empty($result)) {
                error_log("Warning: No results found");
                return [];
            }
            
            return $result;
            
        } catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            error_log("=== MongoDB Connection Timeout Error ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Gagal terhubung ke database: Koneksi timeout");
            
        } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {
            error_log("=== MongoDB Authentication Error ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Gagal terhubung ke database: Autentikasi gagal");
            
        } catch (\Exception $e) {
            error_log("=== ERROR in Wisata::getPopular ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            error_log("File: " . $e->getFile() . " on line " . $e->getLine());
            error_log("=== End of error log ===");
            throw new \Exception("Gagal mengambil data tempat wisata populer: " . $e->getMessage());
        }
    }

    public function getNearby($longitude, $latitude, $maxDistance = 50000)
    {
        return $this->db->getCollection($this->collection)->find([
            'location' => [
                '$near' => [
                    '$geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$longitude, $latitude]
                    ],
                    '$maxDistance' => $maxDistance
                ]
            ]
        ])->toArray();
    }

    public function search($filters)
    {
        try {
            $query = [];

            // Filter berdasarkan keyword pencarian
            if (isset($filters['keyword']) && !empty(trim($filters['keyword']))) {
                $keyword = trim($filters['keyword']);
                $regex = new Regex($keyword, 'i');
                $query['$or'] = [
                    ['name' => $regex],
                    ['description' => $regex],
                    ['location.address' => $regex]
                ];
            }

            // Filter berdasarkan kategori
            if (isset($filters['category']) && !empty(trim($filters['category']))) {
                $query['category'] = trim($filters['category']);
            }

            // Filter berdasarkan rentang harga
            if (isset($filters['price_min']) || isset($filters['price_max'])) {
                $priceQuery = [];
                
                if (isset($filters['price_min']) && is_numeric($filters['price_min'])) {
                    $priceQuery['$gte'] = (float) $filters['price_min'];
                }
                
                if (isset($filters['price_max']) && is_numeric($filters['price_max'])) {
                    $priceQuery['$lte'] = (float) $filters['price_max'];
                }
                
                if (!empty($priceQuery)) {
                    $query['price'] = $priceQuery;
                }
            }

            // Filter berdasarkan rating
            if (isset($filters['rating']) && is_numeric($filters['rating'])) {
                $query['rating'] = ['$gte' => (float) $filters['rating']];
            }

            // Filter berdasarkan fasilitas
            if (isset($filters['facilities']) && is_array($filters['facilities'])) {
                $facilities = array_filter($filters['facilities'], function($facility) {
                    return !empty(trim($facility));
                });
                
                if (!empty($facilities)) {
                    $query['facilities'] = ['$all' => $facilities];
                }
            }

            // Eksekusi query
            $result = empty($query) ? 
                $this->getAll() : 
                $this->db->getCollection($this->collection)->find($query)->toArray();

            return $result;

        } catch (\Exception $e) {
            // Log error untuk debugging
            error_log("Error in Wisata search: " . $e->getMessage());
            throw new \Exception("Terjadi kesalahan saat mencari tempat wisata: " . $e->getMessage());
        }
    }
} 