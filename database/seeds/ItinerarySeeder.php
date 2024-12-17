<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->lokanesia_db;
    
    // Hapus data yang ada
    $db->itineraries->deleteMany([]);
    
    // Get tourist spots for references
    $spots = $db->tourist_spots->find([], ['projection' => ['_id' => 1, 'name' => 1]])->toArray();
    
    $itineraries = [];
    $users = [
        ['id' => new ObjectId(), 'name' => 'John Doe', 'avatar' => 'https://ui-avatars.com/api/?name=John+Doe'],
        ['id' => new ObjectId(), 'name' => 'Jane Smith', 'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith'],
        ['id' => new ObjectId(), 'name' => 'Bob Wilson', 'avatar' => 'https://ui-avatars.com/api/?name=Bob+Wilson']
    ];
    
    for ($i = 1; $i <= 100; $i++) {
        $user = $users[array_rand($users)];
        $date = new UTCDateTime(strtotime("+" . rand(1, 30) . " days") * 1000);
        $duration = rand(1, 7);
        
        // Generate random itinerary days
        $days = [];
        for ($day = 1; $day <= $duration; $day++) {
            // Randomly select 2-4 spots for each day
            $activities = [];
            $spotCount = rand(2, 4);
            $spotIndices = array_rand($spots, min($spotCount, count($spots)));
            
            if (!is_array($spotIndices)) {
                $spotIndices = [$spotIndices];
            }
            
            foreach ($spotIndices as $index) {
                $spot = $spots[$index];
                $time = sprintf("%02d:00", rand(6, 20));
                
                $activities[] = [
                    'time' => $time,
                    'spot_id' => $spot->_id,
                    'spot_name' => $spot->name,
                    'duration' => rand(1, 4),
                    'notes' => "Mengunjungi " . $spot->name . " selama " . rand(1, 4) . " jam"
                ];
            }
            
            // Sort activities by time
            usort($activities, function($a, $b) {
                return strcmp($a['time'], $b['time']);
            });
            
            $days[] = [
                'day' => $day,
                'activities' => $activities
            ];
        }
        
        $itineraries[] = [
            'title' => "Itinerary Wisata #$i: " . $duration . " Hari di Jawa Tengah",
            'description' => "Rencana perjalanan selama $duration hari mengunjungi tempat-tempat wisata terbaik di Jawa Tengah",
            'user' => [
                '_id' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar']
            ],
            'start_date' => $date,
            'end_date' => new UTCDateTime(strtotime("+" . ($duration) . " days", $date->toDateTime()->getTimestamp()) * 1000),
            'duration' => $duration,
            'days' => $days,
            'budget' => rand(1, 10) * 1000000,
            'like_count' => rand(0, 500),
            'share_count' => rand(0, 100),
            'status' => rand(0, 10) > 2 ? 'published' : 'draft',
            'created_at' => new UTCDateTime(),
            'updated_at' => new UTCDateTime()
        ];
    }
    
    $result = $db->itineraries->insertMany($itineraries);
    echo "Berhasil menambahkan " . count($result->getInsertedIds()) . " itinerary\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 