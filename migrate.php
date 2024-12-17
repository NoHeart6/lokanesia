<?php

require_once __DIR__ . '/config/config.php';

use App\Database\Migration;

try {
    $migration = new Migration();
    $migration->migrate();
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    exit(1);
} 