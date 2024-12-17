<?php

namespace App\Database;

use MongoDB\Client;
use MongoDB\Database as MongoDatabase;
use Exception;

class Database {
    private static ?Database $instance = null;
    private MongoDatabase $database;
    private Client $client;
    private string $dbName;

    private function __construct() {
        // Load environment variables jika belum
        if (!isset($_ENV['MONGODB_URI']) || !isset($_ENV['MONGODB_DB'])) {
            $envFile = __DIR__ . '/../../.env';
            if (file_exists($envFile)) {
                $envVars = parse_ini_file($envFile);
                foreach ($envVars as $key => $value) {
                    $_ENV[$key] = $value;
                }
            }
        }

        $mongoUri = $_ENV['MONGODB_URI'] ?? 'mongodb://localhost:27017';
        $this->dbName = $_ENV['MONGODB_DB'] ?? 'lokanesia_db';

        try {
            error_log("Connecting to MongoDB at: {$mongoUri}");
            
            // Set options untuk koneksi MongoDB
            $options = [
                'retryWrites' => true,
                'w' => 'majority',
                'readPreference' => 'primary',
                'connectTimeoutMS' => 10000,
                'retryReads' => true
            ];

            $this->client = new Client($mongoUri, $options);
            $this->database = $this->client->selectDatabase($this->dbName);
            
            // Test connection
            $this->database->command(['ping' => 1]);
            error_log("Successfully connected to MongoDB database: {$this->dbName}");
        } catch (Exception $e) {
            error_log("MongoDB connection error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Could not connect to MongoDB: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): MongoDatabase {
        return $this->database;
    }

    public function getDatabase(): MongoDatabase {
        return $this->database;
    }

    public function getCollection(string $collection) {
        try {
            return $this->database->selectCollection($collection);
        } catch (Exception $e) {
            error_log("Error getting collection {$collection}: " . $e->getMessage());
            throw $e;
        }
    }

    public function dropCollection(string $collectionName): bool {
        try {
            return $this->database->dropCollection($collectionName);
        } catch (Exception $e) {
            error_log("Error dropping collection {$collectionName}: " . $e->getMessage());
            return false;
        }
    }

    public function createCollection(string $collectionName, array $options = []): bool {
        try {
            $this->database->createCollection($collectionName, $options);
            return true;
        } catch (Exception $e) {
            error_log("Error creating collection {$collectionName}: " . $e->getMessage());
            return false;
        }
    }

    public function listCollections(): array {
        try {
            return iterator_to_array($this->database->listCollections());
        } catch (Exception $e) {
            error_log("Error listing collections: " . $e->getMessage());
            return [];
        }
    }

    public function createIndex(string $collectionName, array $keys, array $options = []): string {
        try {
            return $this->getCollection($collectionName)->createIndex($keys, $options);
        } catch (Exception $e) {
            error_log("Error creating index on {$collectionName}: " . $e->getMessage());
            throw $e;
        }
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
} 