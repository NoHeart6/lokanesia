<?php

namespace App\Core;

class Request {
    private array $params = [];
    private array $query = [];
    private array $body = [];
    private ?array $json = null;

    public function __construct() {
        $this->query = $_GET;
        $this->parseBody();
    }

    private function parseBody(): void {
        if ($this->getMethod() === 'POST') {
            $contentType = $this->getHeader('Content-Type');
            
            error_log("Content-Type: " . $contentType);
            error_log("Raw input: " . file_get_contents('php://input'));

            if (strpos($contentType, 'application/json') !== false) {
                $json = file_get_contents('php://input');
                $this->json = json_decode($json, true);
                $this->body = $this->json ?? [];
                
                error_log("Parsed JSON body: " . print_r($this->body, true));
            } else {
                $this->body = $_POST;
                error_log("POST body: " . print_r($this->body, true));
            }
        }
    }

    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath(): string {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function getHeader(string $name): ?string {
        $name = str_replace('-', '_', strtoupper($name));
        $name = 'HTTP_' . $name;
        return $_SERVER[$name] ?? null;
    }

    public function getQuery(string $key, $default = null) {
        return $this->query[$key] ?? $default;
    }

    public function getBody(): array {
        return $this->body;
    }

    public function getJson(): ?array {
        return $this->json;
    }

    public function getParam(string $key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    public function setParams(array $params): void {
        $this->params = $params;
        error_log("Set route params: " . print_r($params, true));
    }

    public function getParams(): array {
        return $this->params;
    }

    public function isJson(): bool {
        $contentType = $this->getHeader('Content-Type');
        return strpos($contentType, 'application/json') !== false;
    }

    public function isAjax(): bool {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    public function getSession(string $key = null, $default = null) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if ($key === null) {
            return $_SESSION;
        }
        
        return $_SESSION[$key] ?? $default;
    }
}