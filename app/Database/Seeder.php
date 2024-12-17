<?php

namespace App\Database;

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Seeder {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function seed(): void {
        $this->seedTouristSpots();
        $this->seedArticles();
        echo "Seeding completed successfully!\n";
    }

    private function seedTouristSpots(): void {
        // Hapus data lama
        $this->db->getCollection('tourist_spots')->deleteMany([]);

        $spots = [
            // Wisata Alam
            [
                '_id' => new ObjectId(),
                'name' => 'Gunung Bromo',
                'description' => 'Gunung berapi aktif yang terkenal dengan pemandangan sunrise yang spektakuler',
                'address' => 'Tengger, Probolinggo, Jawa Timur',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [112.9533, -7.9425]
                ],
                'category' => 'Alam',
                'rating' => 4.8,
                'ticket_price' => 25000,
                'facilities' => ['Parkir', 'Toilet', 'Warung', 'Penyewaan Kuda'],
                'operating_hours' => '24 jam',
                'images' => [
                    'https://th.bing.com/th/id/R.4a5d05e51f332985ffb46c3cfb16626b?rik=VfxxFCRHnAWsNg&riu=http%3a%2f%2frestlessea.com%2fwp-content%2fuploads%2f2017%2f10%2fDSC_006201h.jpg&ehk=SuWVl6Dy34mEMa%2bQajTAfACTpHPSTnSQgGTwC%2f18mrI%3d&risl=&pid=ImgRaw&r=0',
                    'https://th.bing.com/th/id/R.e6dd37856b63e5842fb2c3a8c0065f8c?rik=sSPwmrr%2bRDkZGw&riu=http%3a%2f%2f1.bp.blogspot.com%2f-8QG_a2HHmhY%2fUUWwQPXBk9I%2fAAAAAAAAKLs%2fXpNGfOIHVJ0%2fs1600%2fbromo-sunrise-1.jpg&ehk=Ij7wZhBqMCWitPKvCDHNQvvF%2fXHgGvYJCM%2bFPFNgvOE%3d&risl=&pid=ImgRaw&r=0'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'name' => 'Raja Ampat',
                'description' => 'Surga diving dan snorkeling dengan keindahan bawah laut yang menakjubkan',
                'address' => 'Kepulauan Raja Ampat, Papua Barat',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [130.8779, -0.5897]
                ],
                'category' => 'Alam',
                'rating' => 4.9,
                'ticket_price' => 500000,
                'facilities' => ['Penginapan', 'Diving Center', 'Boat Tour', 'Restaurant'],
                'operating_hours' => '06:00 - 18:00',
                'images' => [
                    'https://th.bing.com/th/id/R.e6c50c6d7dfc1c85774d9c5bba22953f?rik=GzVQDWY0pxoLcQ&riu=http%3a%2f%2f4.bp.blogspot.com%2f-QiTXcwqN4Ks%2fUYxhUrzJWwI%2fAAAAAAAAHxY%2fRXxpRX3rZ6U%2fs1600%2fraja-ampat-islands.jpg&ehk=Hs%2fQkAAbZo7XXQOzqwO%2fYQz7UHoHGZHKqF%2bVXl%2bGWQY%3d&risl=&pid=ImgRaw&r=0',
                    'https://th.bing.com/th/id/R.e0c1e6e80c0616d4ef2d9d0313c6f43e?rik=kYpYQ%2fZBGhRZrw&riu=http%3a%2f%2f1.bp.blogspot.com%2f-7OdxufQWxZs%2fUYxhVEEQa_I%2fAAAAAAAAHxg%2fPLR_nTJDKFk%2fs1600%2fraja-ampat-islands1.jpg&ehk=Hy%2bKPWUXnwNQVZVHqJ9qr7o%2bvBRc7m5dqIsvUKtWwxM%3d&risl=&pid=ImgRaw&r=0'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'name' => 'Danau Toba',
                'description' => 'Danau vulkanik terbesar di dunia dengan pemandangan alam yang memukau',
                'address' => 'Sumatera Utara',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [98.8557, 2.6833]
                ],
                'category' => 'Alam',
                'rating' => 4.7,
                'ticket_price' => 0,
                'facilities' => ['Hotel', 'Restaurant', 'Boat Tour', 'Souvenir Shop'],
                'operating_hours' => '24 jam',
                'images' => [
                    'https://th.bing.com/th/id/R.e6f3c93e272e8c7b7b8f3a0c6e4d2f0f?rik=P%2bfWtqaKA2nqrw&riu=http%3a%2f%2f4.bp.blogspot.com%2f-Z3yR9mO6Zn0%2fUTYCXGBRk9I%2fAAAAAAAAJvs%2fXr_npj-pvP0%2fs1600%2fDanau%2bToba%2b1.jpg&ehk=8%2fPz%2bZq%2bELQQqB%2bEpvxvs%2fYoZqS%2bVZWbLs%2bTSqS%2bQXk%3d&risl=&pid=ImgRaw&r=0',
                    'https://th.bing.com/th/id/R.f8f8f8f8f8f8f8f8f8f8f8f8f8f8f8f8?rik=AAAAAAAAAAAAAAAA&riu=http%3a%2f%2f1.bp.blogspot.com%2f-Z3yR9mO6Zn0%2fUTYCXGBRk9I%2fAAAAAAAAJvs%2fXr_npj-pvP0%2fs1600%2fDanau%2bToba%2b2.jpg&ehk=AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA%3d&risl=&pid=ImgRaw&r=0'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            // Wisata Kuliner
            [
                '_id' => new ObjectId(),
                'name' => 'Kawasan Kuliner Pecenongan',
                'description' => 'Surga kuliner malam Jakarta yang terkenal dengan berbagai hidangan khas Betawi dan Chinese Food',
                'address' => 'Jl. Pecenongan, Jakarta Pusat',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [106.8283, -6.1682]
                ],
                'category' => 'Kuliner',
                'rating' => 4.6,
                'ticket_price' => 0,
                'facilities' => ['Parkir', 'Toilet', 'WiFi', 'Area Makan Indoor/Outdoor'],
                'operating_hours' => '17:00 - 02:00',
                'images' => [
                    'https://www.jakarta.go.id/uploads/images/pecenongan-street-food.jpg',
                    'https://www.jakarta.go.id/uploads/images/pecenongan-night.jpg'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'name' => 'Saung Udang Situ Cibubur',
                'description' => 'Restoran seafood dengan konsep lesehan di pinggir danau',
                'address' => 'Jl. Situ Cibubur, Jakarta Timur',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [106.8866, -6.3569]
                ],
                'category' => 'Kuliner',
                'rating' => 4.5,
                'ticket_price' => 0,
                'facilities' => ['Parkir', 'Toilet', 'Musholla', 'Area Makan Outdoor'],
                'operating_hours' => '11:00 - 22:00',
                'images' => [
                    'https://source.unsplash.com/random/800x600?seafood',
                    'https://source.unsplash.com/random/800x600?restaurant'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            // Wisata Religi
            [
                '_id' => new ObjectId(),
                'name' => 'Masjid Istiqlal',
                'description' => 'Masjid terbesar di Asia Tenggara yang menjadi simbol toleransi',
                'address' => 'Jl. Taman Wijaya Kusuma, Jakarta Pusat',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [106.8315, -6.1701]
                ],
                'category' => 'Religi',
                'rating' => 4.8,
                'ticket_price' => 0,
                'facilities' => ['Parkir', 'Toilet', 'Tempat Wudhu', 'Perpustakaan'],
                'operating_hours' => '04:00 - 22:00',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/b/b3/Istiqlal_Mosque_Monas.jpg',
                    'https://upload.wikimedia.org/wikipedia/commons/7/7a/Istiqlal_Mosque_Interior.jpg'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'name' => 'Vihara Tian Tan Buddha',
                'description' => 'Vihara dengan patung Buddha terbesar di Indonesia',
                'address' => 'Jl. Raya Singkawang, Kalimantan Barat',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [108.9871, 0.9063]
                ],
                'category' => 'Religi',
                'rating' => 4.6,
                'ticket_price' => 10000,
                'facilities' => ['Parkir', 'Toilet', 'Toko Suvenir'],
                'operating_hours' => '08:00 - 17:00',
                'images' => [
                    'https://source.unsplash.com/random/800x600?buddha-temple',
                    'https://source.unsplash.com/random/800x600?buddhist-temple'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            // Wisata Sejarah
            [
                '_id' => new ObjectId(),
                'name' => 'Candi Borobudur',
                'description' => 'Candi Buddha terbesar di dunia yang merupakan warisan budaya dunia UNESCO',
                'address' => 'Magelang, Jawa Tengah',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [110.2036, -7.6079]
                ],
                'category' => 'Sejarah',
                'rating' => 4.7,
                'ticket_price' => 50000,
                'facilities' => ['Parkir', 'Toilet', 'Pemandu Wisata', 'Toko Suvenir'],
                'operating_hours' => '06:00 - 17:00',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/8/8c/Borobudur-Nothwest-view.jpg',
                    'https://upload.wikimedia.org/wikipedia/commons/a/a2/Borobudur_Sunrise_View.jpg'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            // Wisata Budaya
            [
                '_id' => new ObjectId(),
                'name' => 'Kraton Yogyakarta',
                'description' => 'Istana Sultan Yogyakarta yang masih mempertahankan tradisi Jawa',
                'address' => 'Jl. Rotowijayan Blok No. 1, Yogyakarta',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [110.3642, -7.8052]
                ],
                'category' => 'Budaya',
                'rating' => 4.6,
                'ticket_price' => 15000,
                'facilities' => ['Parkir', 'Toilet', 'Pemandu Wisata', 'Museum'],
                'operating_hours' => '08:30 - 14:00',
                'images' => [
                    'https://source.unsplash.com/random/800x600?yogyakarta-palace',
                    'https://source.unsplash.com/random/800x600?javanese-culture'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'name' => 'Desa Wisata Sade',
                'description' => 'Desa tradisional suku Sasak yang masih menjaga adat istiadat',
                'address' => 'Rembitan, Pujut, Lombok Tengah',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [116.3128, -8.8544]
                ],
                'category' => 'Budaya',
                'rating' => 4.5,
                'ticket_price' => 10000,
                'facilities' => ['Parkir', 'Toilet', 'Pemandu Lokal', 'Toko Kerajinan'],
                'operating_hours' => '08:00 - 18:00',
                'images' => [
                    'https://source.unsplash.com/random/800x600?traditional-village',
                    'https://source.unsplash.com/random/800x600?lombok-culture'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            // Wisata Hiburan
            [
                '_id' => new ObjectId(),
                'name' => 'Dufan Ancol',
                'description' => 'Taman hiburan terbesar di Indonesia dengan berbagai wahana seru',
                'address' => 'Jl. Lodan Timur No.7, Jakarta Utara',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [106.8344, -6.1252]
                ],
                'category' => 'Hiburan',
                'rating' => 4.5,
                'ticket_price' => 250000,
                'facilities' => ['Parkir', 'Toilet', 'Food Court', 'Toko Suvenir'],
                'operating_hours' => '10:00 - 20:00',
                'images' => [
                    'https://source.unsplash.com/random/800x600?theme-park',
                    'https://source.unsplash.com/random/800x600?amusement-park'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'name' => 'Trans Studio Bandung',
                'description' => 'Taman hiburan indoor terbesar di Indonesia',
                'address' => 'Jl. Gatot Subroto No.289, Bandung',
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [107.6391, -6.9175]
                ],
                'category' => 'Hiburan',
                'rating' => 4.6,
                'ticket_price' => 200000,
                'facilities' => ['Parkir', 'Toilet', 'Food Court', 'Shopping Center'],
                'operating_hours' => '10:00 - 22:00',
                'images' => [
                    'https://source.unsplash.com/random/800x600?indoor-park',
                    'https://source.unsplash.com/random/800x600?entertainment'
                ],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ]
        ];

        // Insert data baru
        foreach ($spots as $spot) {
            $this->db->getCollection('tourist_spots')->insertOne($spot);
        }

        echo "Added " . count($spots) . " tourist spots\n";
    }

    private function seedArticles(): void {
        $articles = [
            [
                '_id' => new ObjectId(),
                'title' => '10 Destinasi Wisata Terbaik di Indonesia',
                'content' => 'Indonesia memiliki banyak destinasi wisata yang menakjubkan...',
                'thumbnail' => 'https://source.unsplash.com/random/800x600?indonesia-tourism',
                'author_id' => new ObjectId(),
                'category' => 'Travel Guide',
                'tags' => ['wisata', 'indonesia', 'travel'],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'title' => 'Tips Hemat Traveling di Indonesia',
                'content' => 'Traveling tidak harus mahal. Berikut tips untuk traveling hemat...',
                'thumbnail' => 'https://source.unsplash.com/random/800x600?backpacker',
                'author_id' => new ObjectId(),
                'category' => 'Tips & Tricks',
                'tags' => ['tips', 'hemat', 'travel'],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ],
            [
                '_id' => new ObjectId(),
                'title' => 'Kuliner Khas Indonesia yang Wajib Dicoba',
                'content' => 'Indonesia terkenal dengan beragam kuliner khasnya...',
                'thumbnail' => 'https://source.unsplash.com/random/800x600?indonesian-food',
                'author_id' => new ObjectId(),
                'category' => 'Culinary',
                'tags' => ['kuliner', 'makanan', 'indonesia'],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ]
        ];

        foreach ($articles as $article) {
            $this->db->getCollection('articles')->insertOne($article);
        }

        echo "Added " . count($articles) . " articles\n";
    }
} 