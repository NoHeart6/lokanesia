<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->lokanesia_db;
    
    // Hapus data yang ada
    $db->reviews->deleteMany([]);
    
    // Get tourist spots for references
    $spots = $db->tourist_spots->find()->toArray();
    
    $reviews = [];
    $users = [
        ['id' => new ObjectId(), 'name' => 'Andi Pratama', 'avatar' => 'https://ui-avatars.com/api/?name=Andi+Pratama'],
        ['id' => new ObjectId(), 'name' => 'Nina Safitri', 'avatar' => 'https://ui-avatars.com/api/?name=Nina+Safitri'],
        ['id' => new ObjectId(), 'name' => 'Dedi Kurniawan', 'avatar' => 'https://ui-avatars.com/api/?name=Dedi+Kurniawan'],
        ['id' => new ObjectId(), 'name' => 'Maya Sari', 'avatar' => 'https://ui-avatars.com/api/?name=Maya+Sari'],
        ['id' => new ObjectId(), 'name' => 'Rizki Ramadhan', 'avatar' => 'https://ui-avatars.com/api/?name=Rizki+Ramadhan']
    ];
    
    $reviewTemplates = [
        5 => [
            "Tempat wisata yang luar biasa! %s sangat indah dan terawat dengan baik. Fasilitas lengkap dan pelayanan ramah. Sangat recommended untuk dikunjungi.",
            "Pengalaman yang menakjubkan di %s. Pemandangan spektakuler dan suasana yang menyenangkan. Pasti akan kembali lagi.",
            "Tidak menyesal mengunjungi %s. Tempatnya bersih, nyaman, dan sangat instagramable. Worth it dengan harga tiketnya."
        ],
        4 => [
            "Tempat yang bagus untuk liburan di %s. Fasilitasnya cukup lengkap, tapi masih bisa ditingkatkan. Overall satisfied.",
            "%s cukup menarik untuk dikunjungi. Pemandangannya bagus dan cocok untuk foto-foto. Sayangnya agak ramai di weekend.",
            "Pengalaman yang menyenangkan di %s. Akses mudah dan tempat parkir luas. Hanya saja harga makanan agak mahal."
        ],
        3 => [
            "Tempat wisata biasa saja di %s. Masih perlu banyak perbaikan terutama kebersihan toilet dan area parkir.",
            "%s lumayan untuk dikunjungi sekali. Tidak ada yang terlalu spesial, tapi tidak mengecewakan juga.",
            "Ekspektasi lebih tinggi untuk %s. Fasilitas standar dan agak kurang terawat. Semoga kedepannya bisa lebih baik."
        ],
        2 => [
            "Kurang terkesan dengan %s. Harga tiket tidak sebanding dengan fasilitas yang ada. Perlu banyak pembenahan.",
            "Kecewa dengan kondisi %s. Tempat kurang terawat dan kotor. Pelayanan juga kurang ramah.",
            "Tidak recommended untuk mengunjungi %s. Terlalu ramai dan tidak teratur. Banyak sampah dimana-mana."
        ]
    ];
    
    for ($i = 1; $i <= 100; $i++) {
        $spot = $spots[array_rand($spots)];
        $user = $users[array_rand($users)];
        $rating = rand(2, 5); // Bias towards positive ratings
        $date = new UTCDateTime(strtotime("-" . rand(1, 90) . " days") * 1000);
        
        $reviews[] = [
            'tourist_spot_id' => $spot->_id,
            'tourist_spot' => [
                'name' => $spot->name,
                'image_url' => $spot->image_url
            ],
            'user' => [
                '_id' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar']
            ],
            'rating' => $rating,
            'review' => sprintf($reviewTemplates[$rating][array_rand($reviewTemplates[$rating])], $spot->name),
            'images' => [
                'https://source.unsplash.com/800x600/?bali,travel,' . rand(1, 1000),
                'https://source.unsplash.com/800x600/?indonesia,tourism,' . rand(1, 1000)
            ],
            'like_count' => rand(0, 100),
            'comment_count' => rand(0, 20),
            'visit_date' => new UTCDateTime(strtotime("-" . rand(1, 180) . " days") * 1000),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
    
    $result = $db->reviews->insertMany($reviews);
    echo "Berhasil menambahkan " . count($result->getInsertedIds()) . " review\n";
    
    // Update tourist spots with average rating
    foreach ($spots as $spot) {
        $spotReviews = array_filter($reviews, function($review) use ($spot) {
            return $review['tourist_spot_id'] == $spot->_id;
        });
        
        if (!empty($spotReviews)) {
            $avgRating = array_sum(array_column($spotReviews, 'rating')) / count($spotReviews);
            $db->tourist_spots->updateOne(
                ['_id' => $spot->_id],
                ['$set' => [
                    'rating' => round($avgRating, 1),
                    'review_count' => count($spotReviews)
                ]]
            );
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 