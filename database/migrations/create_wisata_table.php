<?php

namespace App\Database\Migrations;

use App\Database\Migration;

class CreateWisataTable extends Migration
{
    public function up()
    {
        $db = $this->db->getDatabase();
        
        // Create tourist_spots collection
        if (!in_array('tourist_spots', iterator_to_array($db->listCollections(), false))) {
            $db->createCollection('tourist_spots');
            echo "Created tourist_spots collection\n";
        }

        // Create indexes
        $this->db->getCollection('tourist_spots')->createIndexes([
            [
                'key' => ['location' => '2dsphere']
            ],
            [
                'key' => ['name' => 'text', 'description' => 'text']
            ],
            [
                'key' => ['category' => 1]
            ]
        ]);

        // Insert sample data
        $this->db->getCollection('tourist_spots')->insertMany([
            [
                'name' => 'Candi Borobudur',
                'description' => 'Candi Buddha terbesar di dunia, warisan budaya yang menakjubkan.',
                'category' => 'budaya',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [110.2038, -7.6079]
                ],
                'price' => 50000,
                'facilities' => ['parking', 'restaurant', 'toilet', 'wifi'],
                'open_hours' => '06:00 - 17:00',
                'contact' => '+62123456789',
                'images' => [],
                'created_at' => new \MongoDB\BSON\UTCDateTime(),
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ],
            [
                'name' => 'Pantai Kuta',
                'description' => 'Pantai eksotis dengan pemandangan sunset yang memukau.',
                'category' => 'alam',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [115.1686, -8.7215]
                ],
                'price' => 25000,
                'facilities' => ['parking', 'restaurant', 'toilet'],
                'open_hours' => '24 jam',
                'contact' => '+62987654321',
                'images' => [],
                'created_at' => new \MongoDB\BSON\UTCDateTime(),
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ],
            [
                'name' => 'Raja Ampat',
                'description' => 'Surga bawah laut dengan keindahan terumbu karang.',
                'category' => 'alam',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [130.1018, -0.5897]
                ],
                'price' => 100000,
                'facilities' => ['parking', 'restaurant', 'diving', 'boat'],
                'open_hours' => '06:00 - 18:00',
                'contact' => '+62456789123',
                'images' => [],
                'created_at' => new \MongoDB\BSON\UTCDateTime(),
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ]
        ]);

        echo "Inserted sample data\n";
    }

    public function down()
    {
        $this->db->dropCollection('tourist_spots');
        echo "Dropped tourist_spots collection\n";
    }
} 