<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables if .env file exists
if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
}

// Run migration
require_once __DIR__ . '/Migration.php';
require_once __DIR__ . '/Database.php';

try {
    (new App\Database\Migration())->migrate();
    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
} 