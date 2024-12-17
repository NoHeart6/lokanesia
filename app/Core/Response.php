<?php

namespace App\Core;

class Response {
    private int $statusCode = 200;
    private array $headers = [];
    private $content = null;

    public function __construct() {
        if (ob_get_level() === 0) {
            ob_start();
        }
    }

    public function setStatusCode(int $code): void {
        $this->statusCode = $code;
    }

    public function setHeader(string $name, string $value): void {
        $this->headers[$name] = $value;
    }

    public function json($data): void {
        try {
            // Clean any output buffer first
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            // Set headers
            header('Content-Type: application/json; charset=utf-8');
            
            // Encode data with proper options
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            
            if ($json === false) {
                throw new \Exception('Failed to encode JSON: ' . json_last_error_msg());
            }
            
            $this->content = $json;
            $this->send();
        } catch (\Exception $e) {
            error_log("Error in json response: " . $e->getMessage());
            $this->serverError($e->getMessage());
        }
    }

    public function redirect(string $url): void {
        $this->setHeader('Location', $url);
        $this->setStatusCode(302);
        $this->send();
    }

    public function view(string $view, array $data = []): void {
        try {
            error_log("Attempting to render view: " . $view);
            error_log("View data: " . print_r($data, true));
            
            $viewPath = __DIR__ . '/../../views/' . $view . '.php';
            error_log("Full view path: " . $viewPath);
            
            if (!file_exists($viewPath)) {
                error_log("View file not found: " . $viewPath);
                throw new \Exception("View not found: {$view} at {$viewPath}");
            }

            // Clean output buffer
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Start new buffer
            ob_start();
            
            // Extract data to view scope
            extract($data);
            
            try {
                include $viewPath;
            } catch (\Throwable $e) {
                error_log("Error including view file: " . $e->getMessage());
                error_log("In file: " . $e->getFile() . " on line " . $e->getLine());
                throw $e;
            }
            
            $content = ob_get_clean();
            
            if ($content === false) {
                error_log("Failed to get view content from buffer");
                throw new \Exception("Failed to load view: {$view}");
            }
            
            $this->content = $content;
            error_log("View content length: " . strlen($this->content));
            
            $this->send();
        } catch (\Throwable $e) {
            error_log("Error in view method: " . $e->getMessage());
            error_log("File: " . $e->getFile() . " Line: " . $e->getLine());
            error_log("Stack trace: " . $e->getTraceAsString());
            $this->serverError($e->getMessage());
        }
    }

    public function notFound(string $message = 'Not Found'): void {
        try {
            error_log("404 Not Found: " . $message);
            
            // Clean any output buffer first
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            // Set status code
            http_response_code(404);
            
            // Set headers
            header('Content-Type: application/json; charset=utf-8');
            
            // Send JSON response
            echo json_encode([
                'status' => 'error',
                'code' => 404,
                'message' => $message
            ], JSON_PRETTY_PRINT);
            
            exit;
        } catch (\Exception $e) {
            error_log("Error in notFound method: " . $e->getMessage());
            $this->serverError($e->getMessage());
        }
    }

    public function serverError(string $message = 'Internal Server Error'): void {
        try {
            error_log("Server Error occurred: " . $message);
            
            // Clean any output buffer first
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            // Set status code
            http_response_code(500);
            
            // Set headers
            header('Content-Type: application/json; charset=utf-8');
            
            // Send JSON response
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => $_ENV['APP_ENV'] === 'development' ? $message : 'Internal Server Error',
                'debug' => $_ENV['APP_ENV'] === 'development' ? [
                    'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
                ] : null
            ], JSON_PRETTY_PRINT);
            
            exit;
        } catch (\Exception $e) {
            error_log("Critical error in serverError method: " . $e->getMessage());
            error_log("Original error: " . $message);
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Last resort error response
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'A critical error occurred'
            ]);
            exit;
        }
    }

    private function isJson(): bool {
        return isset($_SERVER['HTTP_ACCEPT']) && 
               strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }

    private function send(): void {
        // Clean any previous output
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Start new output buffer
        ob_start();

        // Set status code
        http_response_code($this->statusCode);

        // Set headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Output content
        if ($this->content !== null) {
            echo $this->content;
        }

        // Flush and end output buffer
        ob_end_flush();
        exit;
    }
} 