<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Database/Database.php';

use App\Database\Database;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

$db = Database::getInstance();

// Clear existing data
$db->getCollection('tourist_spots')->deleteMany([]);

// Sample tourist spots data
$touristSpots = [
    [
        'name' => 'Pantai Kuta',
        'description' => 'Pantai Kuta adalah salah satu pantai paling terkenal di Bali. Dengan pasir putihnya yang lembut dan ombak yang cocok untuk berselancar, pantai ini menjadi destinasi favorit wisatawan lokal dan mancanegara.',
        'category' => 'alam',
        'address' => 'Kuta, Kabupaten Badung, Bali',
        'ticket_price' => 10000,
        'operating_hours' => '24 jam',
        'location' => [
            'type' => 'Point',
            'coordinates' => [115.166667, -8.716667]
        ],
        'image_url' => 'https://example.com/images/kuta-beach.jpg',
        'rating' => 4.5,
        'review_count' => 1250,
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Candi Borobudur',
        'description' => 'Candi Borobudur adalah candi Buddha terbesar di dunia dan merupakan situs warisan dunia UNESCO. Dibangun pada abad ke-8, candi ini memiliki arsitektur yang menakjubkan dan relief yang menggambarkan ajaran Buddha.',
        'category' => 'budaya',
        'address' => 'Borobudur, Magelang, Jawa Tengah',
        'ticket_price' => 50000,
        'operating_hours' => '06:00 - 17:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [110.204167, -7.608333]
        ],
        'image_url' => 'https://example.com/images/borobudur.jpg',
        'rating' => 4.8,
        'review_count' => 2100,
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Kawah Putih',
        'description' => 'Kawah Putih adalah sebuah danau kawah vulkanik yang terletak di Gunung Patuha. Air danau yang berwarna putih kehijauan dan pemandangan alam yang eksotis menjadikannya destinasi wisata yang unik.',
        'category' => 'alam',
        'address' => 'Ciwidey, Bandung, Jawa Barat',
        'ticket_price' => 75000,
        'operating_hours' => '07:00 - 17:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [107.4, -7.166667]
        ],
        'image_url' => 'https://example.com/images/kawah-putih.jpg',
        'rating' => 4.6,
        'review_count' => 850,
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Malioboro',
        'description' => 'Jalan Malioboro adalah jantung kota Yogyakarta dan pusat perbelanjaan tradisional. Di sepanjang jalan ini, Anda dapat menemukan berbagai produk kerajinan, kuliner khas, dan menikmati suasana kota yang kental dengan budaya Jawa.',
        'category' => 'kuliner',
        'address' => 'Malioboro, Yogyakarta',
        'ticket_price' => 0,
        'operating_hours' => '08:00 - 22:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [110.369167, -7.7925]
        ],
        'image_url' => 'https://example.com/images/malioboro.jpg',
        'rating' => 4.4,
        'review_count' => 1800,
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Masjid Istiqlal',
        'description' => 'Masjid Istiqlal adalah masjid terbesar di Asia Tenggara. Arsitektur modern yang megah dan kapasitasnya yang besar menjadikannya salah satu landmark penting di Jakarta.',
        'category' => 'religi',
        'address' => 'Gambir, Jakarta Pusat',
        'ticket_price' => 0,
        'operating_hours' => '04:00 - 22:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [106.831389, -6.170278]
        ],
        'image_url' => 'https://example.com/images/istiqlal.jpg',
        'rating' => 4.7,
        'review_count' => 950,
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ]
];

// Insert tourist spots
$result = $db->getCollection('tourist_spots')->insertMany($touristSpots);

echo "Seeded " . count($result->getInsertedIds()) . " tourist spots\n"; 