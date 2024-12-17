<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->lokanesia_db;
    
    // Hapus data yang ada
    $db->notifications->deleteMany([]);
    
    $notifications = [];
    $users = [
        ['id' => new ObjectId(), 'name' => 'User 1'],
        ['id' => new ObjectId(), 'name' => 'User 2'],
        ['id' => new ObjectId(), 'name' => 'User 3']
    ];
    
    $types = [
        'review' => [
            'title' => 'Review Baru',
            'message' => 'memberikan review pada tempat wisata'
        ],
        'like' => [
            'title' => 'Like Baru',
            'message' => 'menyukai review Anda pada'
        ],
        'comment' => [
            'title' => 'Komentar Baru',
            'message' => 'mengomentari review Anda pada'
        ],
        'follow' => [
            'title' => 'Pengikut Baru',
            'message' => 'mulai mengikuti Anda'
        ],
        'itinerary' => [
            'title' => 'Itinerary Dibagikan',
            'message' => 'membagikan itinerary dengan Anda'
        ]
    ];
    
    // Get some tourist spots for references
    $spots = $db->tourist_spots->find([], ['projection' => ['_id' => 1, 'name' => 1]])->toArray();
    
    for ($i = 1; $i <= 100; $i++) {
        $type = array_rand($types);
        $typeInfo = $types[$type];
        $user = $users[array_rand($users)];
        $recipient = $users[array_rand($users)];
        
        // Make sure recipient is different from user
        while ($recipient['id'] == $user['id']) {
            $recipient = $users[array_rand($users)];
        }
        
        $date = new UTCDateTime(strtotime("-" . rand(0, 48) . " hours") * 1000);
        
        $notification = [
            'type' => $type,
            'title' => $typeInfo['title'],
            'message' => $user['name'] . ' ' . $typeInfo['message'],
            'user_id' => $recipient['id'],
            'actor' => [
                '_id' => $user['id'],
                'name' => $user['name']
            ],
            'read' => rand(0, 1) == 1,
            'created_at' => $date
        ];
        
        // Add reference data based on type
        if (in_array($type, ['review', 'like', 'comment'])) {
            $spot = $spots[array_rand($spots)];
            $notification['reference'] = [
                'type' => 'tourist_spot',
                'id' => $spot->_id,
                'name' => $spot->name
            ];
            $notification['message'] .= ' ' . $spot->name;
        } elseif ($type == 'itinerary') {
            $notification['reference'] = [
                'type' => 'itinerary',
                'id' => new ObjectId(),
                'title' => 'Itinerary #' . rand(1, 100)
            ];
            $notification['message'] .= ': ' . $notification['reference']['title'];
        }
        
        $notifications[] = $notification;
    }
    
    $result = $db->notifications->insertMany($notifications);
    echo "Berhasil menambahkan " . count($result->getInsertedIds()) . " notifikasi\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 