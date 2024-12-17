<?php

namespace Database\Seeds;

use App\Core\Database;
use MongoDB\BSON\UTCDateTime;

class WisataSeeder
{
    public function run()
    {
        $db = Database::getInstance();
        $collection = $db->getCollection('tourist_spots');

        // Hapus data yang ada
        $collection->deleteMany([]);

        // Insert data wisata
        $collection->insertOne([
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
        ]);

        echo "Data wisata berhasil ditambahkan\n";
    }
} 