<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->lokanesia_db;
    
    // Hapus data yang ada
    $db->tourist_spots->deleteMany([]);
    
    // Data wisata Jepara yang akurat
    $jepara_spots = [
        [
            'name' => 'Pantai Kartini',
            'description' => 'Pantai Kartini adalah salah satu destinasi wisata populer di Jepara. Pantai ini menawarkan pemandangan laut yang indah, berbagai wahana permainan, dan kuliner seafood yang lezat.',
            'category' => 'alam',
            'address' => 'Jl. Pantai Kartini, Bulu, Kec. Jepara, Kabupaten Jepara',
            'location' => [
                'type' => 'Point',
                'coordinates' => [110.6342, -6.5881],
                'city' => 'Jepara',
                'region' => 'Jepara',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/17/d9/e2/74/pantai-kartini.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/17/d9/e2/74/pantai-kartini.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/17/d9/e2/75/pantai-kartini.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/17/d9/e2/76/pantai-kartini.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 10000
        ],
        [
            'name' => 'Pantai Bandengan',
            'description' => 'Pantai Bandengan atau Pantai Tirta Samudra terkenal dengan pasir putihnya yang lembut dan air laut yang jernih. Cocok untuk berenang dan menikmati sunset.',
            'category' => 'alam',
            'address' => 'Bandengan, Kec. Jepara, Kabupaten Jepara',
            'location' => [
                'type' => 'Point',
                'coordinates' => [110.6397, -6.5432],
                'city' => 'Jepara',
                'region' => 'Jepara',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/8c/pantai-bandengan.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/8c/pantai-bandengan.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/8d/pantai-bandengan.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/8e/pantai-bandengan.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 15000
        ],
        [
            'name' => 'Pulau Karimunjawa',
            'description' => 'Kepulauan Karimunjawa adalah surga tersembunyi dengan 27 pulau yang menawarkan keindahan bawah laut, pantai eksotis, dan snorkeling.',
            'category' => 'alam',
            'address' => 'Kepulauan Karimunjawa, Jepara',
            'location' => [
                'type' => 'Point',
                'coordinates' => [110.4383, -5.8350],
                'city' => 'Karimunjawa',
                'region' => 'Jepara',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/15/f4/8a/8c/karimunjawa-islands.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/15/f4/8a/8c/karimunjawa-islands.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/15/f4/8a/8d/karimunjawa-islands.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/15/f4/8a/8e/karimunjawa-islands.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 100000
        ],
        [
            'name' => 'Benteng Portugis',
            'description' => 'Benteng peninggalan Portugis dari abad ke-16 yang menawarkan pemandangan laut yang menakjubkan dan nilai sejarah yang tinggi.',
            'category' => 'budaya',
            'address' => 'Banyumanis, Kec. Donorojo, Kabupaten Jepara',
            'location' => [
                'type' => 'Point',
                'coordinates' => [110.9764, -6.5347],
                'city' => 'Donorojo',
                'region' => 'Jepara',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/13/86/1c/9d/benteng-portugis.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/13/86/1c/9d/benteng-portugis.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/13/86/1c/9e/benteng-portugis.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/13/86/1c/9f/benteng-portugis.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 20000
        ],
        [
            'name' => 'Museum R.A. Kartini',
            'description' => 'Museum yang menyimpan berbagai koleksi peninggalan R.A. Kartini, pahlawan emansipasi wanita Indonesia.',
            'category' => 'budaya',
            'address' => 'Jl. Alun-alun No.1, Kauman, Kec. Jepara, Kabupaten Jepara',
            'location' => [
                'type' => 'Point',
                'coordinates' => [110.6675, -6.5927],
                'city' => 'Jepara',
                'region' => 'Jepara',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/90/museum-ra-kartini.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/90/museum-ra-kartini.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/91/museum-ra-kartini.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/92/museum-ra-kartini.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 5000
        ]
    ];
    
    // Tambahkan data umum untuk setiap spot
    $common_data = [
        'operating_hours' => [
            'weekday' => '08:00 - 17:00',
            'weekend' => '07:00 - 18:00'
        ],
        'facilities' => [
            'parkir',
            'toilet',
            'musholla',
            'warung_makan',
            'toko_souvenir'
        ],
        'activities' => [
            'fotografi',
            'kuliner',
            'sunset_viewing'
        ],
        'rating' => 4.5,
        'review_count' => 100,
        'view_count' => 1000,
        'created_at' => new UTCDateTime(),
        'updated_at' => new UTCDateTime()
    ];
    
    // Gabungkan data
    $spots = [];
    foreach ($jepara_spots as $spot) {
        $spots[] = array_merge($spot, $common_data);
    }
    
    // Tambahkan data wisata Jawa Tengah lainnya
    $central_java_spots = [
        [
            'name' => 'Candi Borobudur',
            'description' => 'Candi Buddha terbesar di dunia yang merupakan situs warisan dunia UNESCO. Arsitektur megah dan relief yang menakjubkan.',
            'category' => 'budaya',
            'address' => 'Borobudur, Magelang, Jawa Tengah',
            'location' => [
                'type' => 'Point',
                'coordinates' => [110.2038, -7.6079],
                'city' => 'Magelang',
                'region' => 'Magelang',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/93/borobudur-temple.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/93/borobudur-temple.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/94/borobudur-temple.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/95/borobudur-temple.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 50000
        ],
        [
            'name' => 'Lawang Sewu',
            'description' => 'Gedung bersejarah peninggalan Belanda dengan arsitektur yang megah dan nilai sejarah yang tinggi.',
            'category' => 'budaya',
            'address' => 'Jl. Pemuda, Semarang, Jawa Tengah',
            'location' => [
                'type' => 'Point',
                'coordinates' => [110.4108, -6.9847],
                'city' => 'Semarang',
                'region' => 'Semarang',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/96/lawang-sewu.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/96/lawang-sewu.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/97/lawang-sewu.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/98/lawang-sewu.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 20000
        ],
        [
            'name' => 'Dieng Plateau',
            'description' => 'Dataran tinggi dengan pemandangan alam yang eksotis, candi kuno, dan kawah vulkanik.',
            'category' => 'alam',
            'address' => 'Dieng, Wonosobo, Jawa Tengah',
            'location' => [
                'type' => 'Point',
                'coordinates' => [109.9144, -7.2048],
                'city' => 'Wonosobo',
                'region' => 'Wonosobo',
                'province' => 'Jawa Tengah'
            ],
            'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/99/dieng-plateau.jpg?w=1200&h=-1&s=1',
            'gallery' => [
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/99/dieng-plateau.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/9a/dieng-plateau.jpg?w=1200&h=-1&s=1',
                'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/42/e7/9b/dieng-plateau.jpg?w=1200&h=-1&s=1'
            ],
            'ticket_price' => 30000
        ]
    ];
    
    // Gabungkan data Jawa Tengah
    foreach ($central_java_spots as $spot) {
        $spots[] = array_merge($spot, $common_data);
    }
    
    // Insert semua data
    $result = $db->tourist_spots->insertMany($spots);
    echo "Berhasil menambahkan " . count($result->getInsertedIds()) . " tempat wisata\n";
    
    // Create indexes
    $db->tourist_spots->createIndex(['location' => '2dsphere']);
    echo "Berhasil membuat index 2dsphere untuk field location\n";
    
    $db->tourist_spots->createIndex([
        'name' => 'text',
        'description' => 'text',
        'category' => 'text',
        'location.city' => 'text',
        'location.region' => 'text'
    ]);
    echo "Berhasil membuat text index untuk pencarian\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 