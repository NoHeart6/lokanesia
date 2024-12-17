<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Get all migration files
$migrationFiles = glob(__DIR__ . '/../../database/migrations/*.php');

foreach ($migrationFiles as $file) {
    require_once $file;
    
    // Get class name from file name
    $className = basename($file, '.php');
    $className = "\\App\\Database\\Migrations\\{$className}";
    
    echo "Running migration: {$className}\n";
    
    try {
        $migration = new $className();
        
        // Run migration
        $migration->up();
        
        echo "Migration completed successfully!\n";
    } catch (Exception $e) {
        echo "Migration failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "All migrations completed successfully!\n"; 