<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

// Inisialisasi koneksi database
$db = Database::getInstance();
$collection = $db->getCollection('tourist_spots');

// Data tempat wisata contoh
$spots = [
    [
        'name' => 'Candi Borobudur',
        'description' => 'Candi Buddha terbesar di dunia yang dibangun pada abad ke-8. Merupakan warisan budaya dunia yang dilindungi UNESCO.',
        'category' => 'budaya',
        'address' => 'Borobudur, Magelang, Jawa Tengah',
        'ticket_price' => 50000,
        'operating_hours' => '06:00 - 17:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [110.2037, -7.6079] // [longitude, latitude]
        ],
        'rating' => 4.8,
        'review_count' => 1250,
        'facilities' => ['Parkir', 'Toilet', 'Tempat Ibadah', 'Pusat Informasi'],
        'image_url' => 'https://example.com/images/borobudur.jpg',
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Pantai Kuta',
        'description' => 'Pantai terkenal di Bali dengan pemandangan sunset yang menakjubkan. Cocok untuk berselancar dan berjemur.',
        'category' => 'alam',
        'address' => 'Kuta, Badung, Bali',
        'ticket_price' => 0,
        'operating_hours' => '24 jam',
        'location' => [
            'type' => 'Point',
            'coordinates' => [115.1667, -8.7167]
        ],
        'rating' => 4.5,
        'review_count' => 2100,
        'facilities' => ['Parkir', 'Toilet', 'Tempat Ibadah', 'Restoran', 'Toko Suvenir'],
        'image_url' => 'https://example.com/images/kuta.jpg',
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Kawah Putih',
        'description' => 'Danau kawah vulkanik dengan air berwarna putih kehijauan. Pemandangan eksotis dengan suhu yang sejuk.',
        'category' => 'alam',
        'address' => 'Ciwidey, Bandung, Jawa Barat',
        'ticket_price' => 75000,
        'operating_hours' => '07:00 - 17:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [107.4019, -7.1660]
        ],
        'rating' => 4.6,
        'review_count' => 850,
        'facilities' => ['Parkir', 'Toilet', 'Warung Makan', 'Spot Foto'],
        'image_url' => 'https://example.com/images/kawah-putih.jpg',
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Malioboro',
        'description' => 'Jalan ikonik di Yogyakarta yang terkenal dengan pusat perbelanjaan dan kuliner tradisional.',
        'category' => 'kuliner',
        'address' => 'Malioboro, Yogyakarta',
        'ticket_price' => 0,
        'operating_hours' => '08:00 - 22:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [110.3647, -7.7925]
        ],
        'rating' => 4.7,
        'review_count' => 1800,
        'facilities' => ['Parkir', 'Toilet', 'ATM', 'Pusat Perbelanjaan'],
        'image_url' => 'https://example.com/images/malioboro.jpg',
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ],
    [
        'name' => 'Lawang Sewu',
        'description' => 'Bangunan bersejarah peninggalan Belanda yang terletak di pusat kota Semarang.',
        'category' => 'sejarah',
        'address' => 'Sekayu, Semarang, Jawa Tengah',
        'ticket_price' => 30000,
        'operating_hours' => '08:00 - 17:00',
        'location' => [
            'type' => 'Point',
            'coordinates' => [110.4108, -6.9847]
        ],
        'rating' => 4.4,
        'review_count' => 920,
        'facilities' => ['Parkir', 'Toilet', 'Pemandu Wisata', 'Toko Suvenir'],
        'image_url' => 'https://example.com/images/lawang-sewu.jpg',
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ]
];

// Hapus data lama (opsional)
$collection->deleteMany([]);

// Tambahkan data baru
foreach ($spots as $spot) {
    try {
        $result = $collection->insertOne($spot);
        echo "Berhasil menambahkan {$spot['name']}\n";
    } catch (Exception $e) {
        echo "Gagal menambahkan {$spot['name']}: " . $e->getMessage() . "\n";
    }
}

echo "\nSelesai menambahkan data tempat wisata.\n"; 