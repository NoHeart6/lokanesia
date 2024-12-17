<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables if .env file exists
if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
}

// Run seeder
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Seeder.php';

try {
    (new App\Database\Seeder())->seed();
} catch (Exception $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
} 