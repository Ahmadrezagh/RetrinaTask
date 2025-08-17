<?php

namespace Core;

use Core\Middleware\MiddlewareManager;

class Router
{
    private $routes = [];
    private $currentMiddleware = [];
    private $currentPrefix = '';
    
    public function __construct()
    {
        // Bootstrap middleware system
        MiddlewareManager::bootstrap();
    }
    
    /**
     * Add a GET route with optional middleware
     */
    public function get($pattern, $handler, $middleware = [])
    {
        $this->addRoute('GET', $pattern, $handler, $middleware);
    }
    
    /**
     * Add a POST route with optional middleware
     */
    public function post($pattern, $handler, $middleware = [])
    {
        $this->addRoute('POST', $pattern, $handler, $middleware);
    }
    
    /**
     * Add a PUT route with optional middleware
     */
    public function put($pattern, $handler, $middleware = [])
    {
        $this->addRoute('PUT', $pattern, $handler, $middleware);
    }
    
    /**
     * Add a DELETE route with optional middleware
     */
    public function delete($pattern, $handler, $middleware = [])
    {
        $this->addRoute('DELETE', $pattern, $handler, $middleware);
    }
    
    /**
     * Add a PATCH route with optional middleware
     */
    public function patch($pattern, $handler, $middleware = [])
    {
        $this->addRoute('PATCH', $pattern, $handler, $middleware);
    }
    
    /**
     * Add a route that responds to any HTTP method
     */
    public function any($pattern, $handler, $middleware = [])
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'];
        foreach ($methods as $method) {
            $this->addRoute($method, $pattern, $handler, $middleware);
        }
    }
    
    /**
     * Group routes with shared middleware and/or prefix
     */
    public function group(array $attributes, callable $callback)
    {
        $previousMiddleware = $this->currentMiddleware;
        $previousPrefix = $this->currentPrefix;
        
        // Apply group middleware
        if (isset($attributes['middleware'])) {
            $middleware = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            $this->currentMiddleware = array_merge($this->currentMiddleware, $middleware);
        }
        
        // Apply group prefix
        if (isset($attributes['prefix'])) {
            $this->currentPrefix .= '/' . trim($attributes['prefix'], '/');
        }
        
        // Execute the callback to register grouped routes
        $callback($this);
        
        // Restore previous state
        $this->currentMiddleware = $previousMiddleware;
        $this->currentPrefix = $previousPrefix;
    }
    
    /**
     * Apply middleware to subsequent routes
     */
    public function middleware($middleware)
    {
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        $this->currentMiddleware = array_merge($this->currentMiddleware, $middleware);
        return $this;
    }
    
    /**
     * Set prefix for subsequent routes
     */
    public function prefix($prefix)
    {
        $this->currentPrefix .= '/' . trim($prefix, '/');
        return $this;
    }
    
    /**
     * Add a route to the routing table
     */
    private function addRoute($method, $pattern, $handler, $middleware = [])
    {
        // Apply current prefix
        $fullPattern = $this->currentPrefix . '/' . ltrim($pattern, '/');
        $fullPattern = '/' . trim($fullPattern, '/');
        if ($fullPattern === '/') {
            $fullPattern = '/';
        } else {
            $fullPattern = rtrim($fullPattern, '/');
        }
        
        // Combine current middleware with route-specific middleware
        $allMiddleware = array_merge($this->currentMiddleware, is_array($middleware) ? $middleware : [$middleware]);
        
        $this->routes[] = [
            'method' => $method,
            'pattern' => $fullPattern,
            'handler' => $handler,
            'middleware' => array_filter($allMiddleware), // Remove empty values
            'originalUri' => $pattern
        ];
    }
    
    /**
     * Handle the current request
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash except for root
        if ($requestUri !== '/' && substr($requestUri, -1) === '/') {
            $requestUri = rtrim($requestUri, '/');
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchRoute($route['pattern'], $requestUri, $matches)) {
                $response = $this->executeRoute($route, $matches, $requestUri);
                if ($response !== null) {
                    echo $response;
                }
                return;
            }
        }
        
        // No route matched
        $this->handleNotFound();
    }
    
    /**
     * Execute a matched route with middleware
     */
    private function executeRoute($route, $matches, $requestUri)
    {
        $request = [
            'uri' => $requestUri,
            'method' => $_SERVER['REQUEST_METHOD'],
            'matches' => $matches,
            'route' => $route
        ];
        
        // Create the final destination (route handler)
        $destination = function($request) use ($route, $matches) {
            return $this->callHandler($route['handler'], $matches);
        };
        
        // Execute middleware stack
        try {
            return MiddlewareManager::execute($route['middleware'], $request, $destination);
        } catch (\Exception $e) {
            $this->handleMiddlewareError($e);
        }
    }
    
    /**
     * Call the route handler
     */
    private function callHandler($handler, $matches)
    {
        if (is_callable($handler)) {
            // Remove the full match from parameters
            $params = array_slice($matches, 1);
            return call_user_func_array($handler, $params);
        }
        
        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controller, $method] = explode('@', $handler);
            
            if (class_exists($controller)) {
                $instance = new $controller();
                if (method_exists($instance, $method)) {
                    $params = array_slice($matches, 1);
                    return call_user_func_array([$instance, $method], $params);
                }
            }
        }
        
        throw new \Exception("Invalid route handler: " . (is_string($handler) ? $handler : 'Closure'));
    }
    
    /**
     * Check if route pattern matches the URI
     */
    private function matchRoute($pattern, $uri, &$matches)
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        $regex = '/^' . $pattern . '$/';
        
        return preg_match($regex, $uri, $matches);
    }
    
    /**
     * Handle 404 Not Found
     */
    private function handleNotFound()
    {
        http_response_code(404);
        
        // Check if it's an API request
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Route not found',
                'code' => 404
            ]);
        } else {
            echo '<h1>404 Not Found</h1><p>The requested page could not be found.</p>';
        }
    }
    
    /**
     * Handle middleware errors
     */
    private function handleMiddlewareError(\Exception $e)
    {
        http_response_code(500);
        
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Middleware error: ' . $e->getMessage(),
                'code' => 500
            ]);
        } else {
            echo '<h1>500 Internal Server Error</h1><p>Middleware error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
    
    /**
     * Get all registered routes (for debugging/route listing)
     */
    public function getRoutes()
    {
        return $this->routes;
    }
} 