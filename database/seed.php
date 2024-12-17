<?php

require_once __DIR__ . '/../vendor/autoload.php';

echo "Mulai seeding database...\n\n";

// Jalankan seeder tourist spots terlebih dahulu karena dibutuhkan oleh seeder lain
echo "Menjalankan TouristSpotSeeder...\n";
require_once __DIR__ . '/seeds/TouristSpotSeeder.php';
echo "\n";

// Jalankan seeder lainnya
echo "Menjalankan ArticleSeeder...\n";
require_once __DIR__ . '/seeds/ArticleSeeder.php';
echo "\n";

echo "Menjalankan ItinerarySeeder...\n";
require_once __DIR__ . '/seeds/ItinerarySeeder.php';
echo "\n";

echo "Menjalankan NotificationSeeder...\n";
require_once __DIR__ . '/seeds/NotificationSeeder.php';
echo "\n";

echo "Menjalankan ReviewSeeder...\n";
require_once __DIR__ . '/seeds/ReviewSeeder.php';
echo "\n";

echo "Seeding database selesai!\n"; 