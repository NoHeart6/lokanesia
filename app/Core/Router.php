<?php

namespace App\Core;

class Router {
    private array $routes = [];
    private array $middlewares = [];
    private Request $request;
    private Response $response;

    public function __construct() {
        $this->request = new Request();
        $this->response = new Response();
        
        // Register default middlewares
        $this->addMiddleware('auth', new \App\Middleware\AuthMiddleware());
    }

    public function get(string $path, $handler, array $middlewares = []): void {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): void {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put(string $path, $handler, array $middlewares = []): void {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function delete(string $path, $handler, array $middlewares = []): void {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, $handler, array $middlewares = []): void {
        // Normalize path
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        }

        error_log("Adding route: {$method} {$path}");

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function addMiddleware(string $name, $middleware): void {
        $this->middlewares[$name] = $middleware;
    }

    private function convertRouteToRegex(string $route): string {
        // Escape forward slashes
        $route = preg_quote($route, '#');
        
        // Convert parameters {param} to named capture groups
        $route = preg_replace('#\\\{([a-zA-Z0-9_]+)\\\}#', '(?P<$1>[^/]+)', $route);
        
        // Add start and end anchors, make trailing slash optional
        return '#^' . $route . '/?$#';
    }

    public function dispatch(string $method, string $path): void {
        try {
            error_log("Router: Dispatching {$method} {$path}");
            
            $path = parse_url($path, PHP_URL_PATH);
            $path = rtrim($path, '/');
            if (empty($path)) {
                $path = '/';
            }

            foreach ($this->routes as $route) {
                if ($route['method'] !== $method) {
                    continue;
                }

                $pattern = $this->convertRouteToRegex($route['path']);
                error_log("Checking pattern: {$pattern} against path: {$path}");
                
                if (preg_match($pattern, $path, $matches)) {
                    error_log("Router: Route matched - " . $route['path']);
                    error_log("Route parameters: " . print_r($matches, true));
                    
                    // Extract route parameters (exclude numeric keys)
                    $params = array_filter($matches, function($key) {
                        return !is_numeric($key);
                    }, ARRAY_FILTER_USE_KEY);
                    
                    error_log("Extracted parameters: " . print_r($params, true));
                    $this->request->setParams($params);

                    // Apply middlewares
                    if (isset($route['middlewares'])) {
                        foreach ($route['middlewares'] as $middleware) {
                            error_log("Router: Applying middleware - " . $middleware);
                            
                            if (!isset($this->middlewares[$middleware])) {
                                throw new \Exception("Middleware not found: {$middleware}");
                            }
                            
                            $middlewareInstance = $this->middlewares[$middleware];
                            if (!$middlewareInstance($this->request, $this->response)) {
                                error_log("Router: Middleware rejected request - " . $middleware);
                                return;
                            }
                        }
                    }

                    // Call the handler
                    $handler = $route['handler'];
                    if (is_array($handler)) {
                        error_log("Router: Calling controller - " . $handler[0] . "::" . $handler[1]);
                        
                        $controller = new $handler[0]();
                        $action = $handler[1];
                        
                        if (!method_exists($controller, $action)) {
                            throw new \Exception("Action not found: {$action} in controller " . get_class($controller));
                        }
                        
                        $controller->$action($this->request, $this->response);
                    } else {
                        error_log("Router: Calling closure handler");
                        $handler($this->request, $this->response);
                    }
                    return;
                }
            }

            error_log("Router: No matching route found for - {$method} {$path}");
            $this->response->notFound("Route not found: {$method} {$path}");

        } catch (\Exception $e) {
            error_log("Router error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            error_log("File: " . $e->getFile() . " Line: " . $e->getLine());
            
            $this->response->serverError($e->getMessage());
        }
    }
} 