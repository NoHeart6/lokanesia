<?php

namespace App\Database;

class Migration {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function migrate(): void {
        $this->dropExistingIndexes();
        $this->createCollections();
        $this->createIndexes();
        echo "Migration completed successfully!\n";
    }

    private function dropExistingIndexes(): void {
        $collections = [
            'users',
            'tourist_spots',
            'reviews',
            'itineraries',
            'articles',
            'notifications'
        ];

        foreach ($collections as $collection) {
            try {
                $this->db->getCollection($collection)->dropIndexes();
                echo "Dropped indexes for collection: {$collection}\n";
            } catch (\Exception $e) {
                // Ignore if collection doesn't exist
                continue;
            }
        }
    }

    private function createCollections(): void {
        $db = $this->db->getDatabase();
        
        // Create collections if they don't exist
        $collections = [
            'users',
            'tourist_spots',
            'reviews',
            'itineraries',
            'articles',
            'notifications',
            'saved_spots'  // Koleksi baru untuk menyimpan tempat wisata favorit
        ];

        $existingCollections = iterator_to_array($db->listCollections(), false);
        $existingCollectionNames = array_map(function($collection) {
            return $collection->getName();
        }, $existingCollections);

        foreach ($collections as $collection) {
            if (!in_array($collection, $existingCollectionNames)) {
                $db->createCollection($collection);
                echo "Created collection: {$collection}\n";
            }
        }
    }

    private function createIndexes(): void {
        // Users collection indexes
        $this->db->getCollection('users')->createIndexes([
            [
                'key' => ['email' => 1],
                'unique' => true
            ],
            [
                'key' => ['username' => 1],
                'unique' => true
            ]
        ]);

        // Tourist spots collection indexes
        $this->db->getCollection('tourist_spots')->createIndexes([
            [
                'key' => ['location' => '2dsphere']
            ],
            [
                'key' => ['name' => 'text', 'description' => 'text', 'category' => 'text']
            ],
            [
                'key' => ['category' => 1]
            ],
            [
                'key' => ['rating' => -1]
            ]
        ]);

        // Reviews collection indexes
        $this->db->getCollection('reviews')->createIndexes([
            [
                'key' => ['tourist_spot_id' => 1]
            ],
            [
                'key' => ['user_id' => 1]
            ],
            [
                'key' => ['rating' => 1]
            ],
            [
                'key' => ['created_at' => -1]
            ]
        ]);

        // Itineraries collection indexes
        $this->db->getCollection('itineraries')->createIndexes([
            [
                'key' => ['user_id' => 1]
            ],
            [
                'key' => ['spot_id' => 1]
            ],
            [
                'key' => ['date' => 1]
            ],
            [
                'key' => ['created_at' => -1]
            ]
        ]);

        // Saved spots collection indexes
        $this->db->getCollection('saved_spots')->createIndexes([
            [
                'key' => ['user_id' => 1],
                'name' => 'user_id_idx'
            ],
            [
                'key' => ['spot_id' => 1],
                'name' => 'spot_id_idx'
            ],
            [
                'key' => ['created_at' => -1],
                'name' => 'created_at_idx'
            ],
            [
                'key' => ['user_id' => 1, 'spot_id' => 1],
                'unique' => true,
                'name' => 'user_spot_unique_idx'
            ]
        ]);

        // Articles collection indexes
        $this->db->getCollection('articles')->createIndexes([
            [
                'key' => ['title' => 'text', 'content' => 'text']
            ],
            [
                'key' => ['category' => 1]
            ],
            [
                'key' => ['created_at' => -1]
            ]
        ]);

        // Notifications collection indexes
        $this->db->getCollection('notifications')->createIndexes([
            [
                'key' => ['user_id' => 1]
            ],
            [
                'key' => ['created_at' => -1]
            ],
            [
                'key' => ['read' => 1]
            ]
        ]);

        echo "Created all required indexes\n";
    }
} 