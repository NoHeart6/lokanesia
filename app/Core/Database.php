<?php

namespace App\Core;

use MongoDB\Client;
use MongoDB\Database as MongoDatabase;

class Database {
    private static ?Database $instance = null;
    private MongoDatabase $database;
    private string $dbName = 'lokanesia';

    private function __construct() {
        try {
            $mongoUri = $_ENV['MONGODB_URI'] ?? 'mongodb://localhost:27017';
            $dbName = $_ENV['MONGODB_DB'] ?? $this->dbName;
            
            error_log("Connecting to MongoDB: " . $mongoUri);
            
            $options = [
                'connectTimeoutMS' => 5000,
                'serverSelectionTimeoutMS' => 5000,
                'retryWrites' => true
            ];
            
            $client = new Client($mongoUri, $options);
            $this->database = $client->selectDatabase($dbName);
            
            // Test connection
            $this->database->command(['ping' => 1]);
            error_log("MongoDB connection successful");
            
        } catch (\Exception $e) {
            error_log("MongoDB connection error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDatabase(): MongoDatabase {
        return $this->database;
    }

    public function getCollection(string $collection) {
        return $this->database->selectCollection($collection);
    }
} 