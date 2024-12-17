<?php

namespace App\Core;

use MongoDB\Database as MongoDatabase;

class Controller {
    protected MongoDatabase $db;

    public function __construct() {
        try {
            $database = Database::getInstance();
            $this->db = $database->getDatabase();
            error_log("Controller base class initialized successfully");
        } catch (\Exception $e) {
            error_log("Database connection error in Controller: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    protected function view(string $view, array $data = []): void {
        $viewPath = dirname(dirname(__DIR__)) . "/views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$viewPath}");
        }

        // Extract data to make it available in view
        extract($data);

        // Start output buffering
        ob_start();
        
        // Include the view file
        require $viewPath;
        
        // Get the contents and clean the buffer
        echo ob_get_clean();
    }
} 