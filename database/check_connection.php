<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile)) {
    die(".env file tidak ditemukan di: {$envFile}\n");
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$mongoUri = getenv('MONGODB_URI');
$dbName = getenv('MONGODB_DB');

if (!$mongoUri || !$dbName) {
    echo "Konfigurasi MongoDB tidak ditemukan di .env\n";
    echo "MONGODB_URI: " . ($mongoUri ?: "tidak ada") . "\n";
    echo "MONGODB_DB: " . ($dbName ?: "tidak ada") . "\n";
    die();
}

try {
    echo "Mencoba koneksi ke MongoDB...\n";
    echo "URI: {$mongoUri}\n";
    echo "Database: {$dbName}\n";
    
    $client = new MongoDB\Client($mongoUri);
    $db = $client->selectDatabase($dbName);
    
    // Test connection with ping command
    $result = $db->command(['ping' => 1]);
    
    echo "\nKoneksi berhasil!\n";
    echo "Collections yang tersedia:\n";
    
    // List all collections
    foreach ($db->listCollections() as $collection) {
        echo "- " . $collection->getName() . "\n";
    }
    
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
}