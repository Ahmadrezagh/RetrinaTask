<?php

namespace Core;

class Router
{
    private $routes = [];
    private $currentRoute = null;
    
    /**
     * Add GET route
     */
    public function get($uri, $controller)
    {
        $this->addRoute('GET', $uri, $controller);
        return $this;
    }
    
    /**
     * Add POST route
     */
    public function post($uri, $controller)
    {
        $this->addRoute('POST', $uri, $controller);
        return $this;
    }
    
    /**
     * Add PUT route
     */
    public function put($uri, $controller)
    {
        $this->addRoute('PUT', $uri, $controller);
        return $this;
    }
    
    /**
     * Add DELETE route
     */
    public function delete($uri, $controller)
    {
        $this->addRoute('DELETE', $uri, $controller);
        return $this;
    }
    
    /**
     * Add route to routes array
     */
    private function addRoute($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $this->normalizeUri($uri),
            'controller' => $controller,
            'pattern' => $this->convertToPattern($uri)
        ];
    }
    
    /**
     * Normalize URI by removing leading/trailing slashes
     */
    private function normalizeUri($uri)
    {
        return trim($uri, '/');
    }
    
    /**
     * Convert URI to regex pattern for parameter matching
     */
    private function convertToPattern($uri)
    {
        $pattern = $this->normalizeUri($uri);
        
        // Convert {param} to named capture groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }
    
    /**
     * Dispatch the current request
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getCurrentUri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                $this->currentRoute = $route;
                
                // Extract route parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                return $this->callController($route['controller'], $params);
            }
        }
        
        // No route found
        $this->handleNotFound();
    }
    
    /**
     * Get current URI from request
     */
    private function getCurrentUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        return $this->normalizeUri($uri);
    }
    
    /**
     * Call the controller method
     */
    private function callController($controllerAction, $params = [])
    {
        if (is_string($controllerAction)) {
            // Format: "ControllerName@methodName"
            list($controllerName, $methodName) = explode('@', $controllerAction);
            
            $controllerClass = "App\\Controllers\\{$controllerName}";
            
            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller {$controllerClass} not found");
            }
            
            $controller = new $controllerClass();
            
            if (!method_exists($controller, $methodName)) {
                throw new \Exception("Method {$methodName} not found in {$controllerClass}");
            }
            
            return call_user_func_array([$controller, $methodName], $params);
        }
        
        if (is_callable($controllerAction)) {
            // Closure/callable
            return call_user_func_array($controllerAction, $params);
        }
        
        throw new \Exception("Invalid controller action");
    }
    
    /**
     * Handle 404 Not Found
     */
    private function handleNotFound()
    {
        http_response_code(404);
        echo "404 - Page Not Found";
        exit;
    }
    
    /**
     * Add middleware to current route
     */
    public function middleware($middleware)
    {
        if ($this->currentRoute) {
            $lastIndex = count($this->routes) - 1;
            $this->routes[$lastIndex]['middleware'] = $middleware;
        }
        return $this;
    }
    
    /**
     * Get all registered routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }
} 