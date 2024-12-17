<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Connect to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    
    // Select database
    $db = $client->lokanesia_db;
    
    // Create tourist_spots collection if not exists
    $collections = iterator_to_array($db->listCollections());
    $collectionNames = array_map(function($collection) {
        return $collection->getName();
    }, $collections);
    
    if (!in_array('tourist_spots', $collectionNames)) {
        $db->createCollection('tourist_spots');
        echo "Collection tourist_spots created\n";
    }
    
    // Create 2dsphere index for location field
    $db->tourist_spots->createIndex(['location' => '2dsphere']);
    
    // Clear existing data
    $db->tourist_spots->deleteMany([]);
    echo "Existing data cleared\n";
    
    // Sample data wisata
    $tourist_spots = [
        [
            'name' => 'Pantai Kuta',
            'description' => 'Pantai Kuta adalah salah satu pantai paling terkenal di Bali. Dengan pasir putihnya yang lembut dan ombak yang cocok untuk berselancar, pantai ini menjadi destinasi favorit wisatawan lokal dan mancanegara.',
            'category' => 'alam',
            'address' => 'Kuta, Kabupaten Badung, Bali',
            'location' => [
                'type' => 'Point',
                'coordinates' => [115.166667, -8.716667]
            ],
            'image_url' => 'https://images.unsplash.com/photo-1588867702719-969c8ac733d6',
            'rating' => 4.5,
            'price' => 10000,
            'facilities' => ['parkir', 'toilet', 'musholla', 'warung_makan']
        ],
        [
            'name' => 'Pantai Sanur',
            'description' => 'Pantai Sanur terkenal dengan suasananya yang tenang dan matahari terbitnya yang indah. Cocok untuk keluarga dan aktivitas air yang santai.',
            'category' => 'alam',
            'address' => 'Sanur, Denpasar Selatan, Bali',
            'location' => [
                'type' => 'Point',
                'coordinates' => [115.2627, -8.7067]
            ],
            'image_url' => 'https://images.unsplash.com/photo-1519451241324-20b4ea2c4220',
            'rating' => 4.3,
            'price' => 15000,
            'facilities' => ['parkir', 'toilet', 'musholla', 'warung_makan', 'gazebo']
        ],
        [
            'name' => 'Tanah Lot',
            'description' => 'Pura yang terletak di atas batu karang di tengah laut ini merupakan salah satu ikon wisata Bali yang terkenal dengan matahari terbenamnya.',
            'category' => 'budaya',
            'address' => 'Beraban, Kediri, Kabupaten Tabanan, Bali',
            'location' => [
                'type' => 'Point',
                'coordinates' => [115.0864, -8.6215]
            ],
            'image_url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4',
            'rating' => 4.7,
            'price' => 60000,
            'facilities' => ['parkir', 'toilet', 'musholla', 'warung_makan', 'toko_souvenir']
        ],
        [
            'name' => 'Uluwatu',
            'description' => 'Pura yang berada di tebing tinggi ini menawarkan pemandangan laut yang spektakuler dan pertunjukan tari kecak yang memukau.',
            'category' => 'budaya',
            'address' => 'Pecatu, Kuta Selatan, Kabupaten Badung, Bali',
            'location' => [
                'type' => 'Point',
                'coordinates' => [115.0875, -8.8291]
            ],
            'image_url' => 'https://images.unsplash.com/photo-1588867702719-969c8ac733d6',
            'rating' => 4.6,
            'price' => 50000,
            'facilities' => ['parkir', 'toilet', 'musholla', 'warung_makan']
        ],
        [
            'name' => 'Ubud Monkey Forest',
            'description' => 'Hutan yang dihuni ratusan monyet ini juga memiliki pura kuno dan jalur trekking yang rindang.',
            'category' => 'alam',
            'address' => 'Ubud, Kabupaten Gianyar, Bali',
            'location' => [
                'type' => 'Point',
                'coordinates' => [115.2595, -8.5195]
            ],
            'image_url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4',
            'rating' => 4.4,
            'price' => 80000,
            'facilities' => ['parkir', 'toilet', 'musholla', 'warung_makan', 'toko_souvenir']
        ]
    ];
    
    // Insert all spots
    $result = $db->tourist_spots->insertMany($tourist_spots);
    echo "Berhasil menambahkan " . count($result->getInsertedIds()) . " data wisata\n";
    
    // Verify data
    $count = $db->tourist_spots->countDocuments([]);
    echo "\nTotal data tersimpan: " . $count . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 