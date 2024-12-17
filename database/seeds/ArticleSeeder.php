<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->lokanesia_db;
    
    // Hapus data yang ada
    $db->articles->deleteMany([]);
    
    $categories = ['tips', 'guide', 'news', 'culture', 'food'];
    $authors = [
        ['name' => 'Ahmad Fadli', 'avatar' => 'https://ui-avatars.com/api/?name=Ahmad+Fadli'],
        ['name' => 'Siti Rahma', 'avatar' => 'https://ui-avatars.com/api/?name=Siti+Rahma'],
        ['name' => 'Budi Santoso', 'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso'],
        ['name' => 'Dewi Putri', 'avatar' => 'https://ui-avatars.com/api/?name=Dewi+Putri'],
        ['name' => 'Rudi Hermawan', 'avatar' => 'https://ui-avatars.com/api/?name=Rudi+Hermawan']
    ];
    
    $articles = [];
    
    for ($i = 1; $i <= 100; $i++) {
        $category = $categories[array_rand($categories)];
        $author = $authors[array_rand($authors)];
        $date = new UTCDateTime(strtotime("-" . rand(1, 365) . " days") * 1000);
        
        $articles[] = [
            'title' => "Artikel Wisata #$i: " . ucfirst($category),
            'slug' => "artikel-wisata-$i-" . strtolower($category),
            'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
            'category' => $category,
            'image_url' => 'https://source.unsplash.com/800x600/?travel,' . $category,
            'author' => [
                'name' => $author['name'],
                'avatar' => $author['avatar']
            ],
            'tags' => array_rand(array_flip(['wisata', 'travel', 'indonesia', 'budaya', 'kuliner', 'alam']), rand(2, 4)),
            'view_count' => rand(100, 10000),
            'like_count' => rand(10, 1000),
            'comment_count' => rand(0, 100),
            'status' => rand(0, 10) > 2 ? 'published' : 'draft',
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
    
    $result = $db->articles->insertMany($articles);
    echo "Berhasil menambahkan " . count($result->getInsertedIds()) . " artikel\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 