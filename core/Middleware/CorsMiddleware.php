<?php

namespace Core\Middleware;

/**
 * CORS Middleware
 * 
 * Handles Cross-Origin Resource Sharing (CORS) headers for API requests
 */
class CorsMiddleware implements MiddlewareInterface
{
    protected $allowedOrigins = ['*'];
    protected $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];
    protected $allowedHeaders = ['Content-Type', 'Authorization', 'X-Requested-With', 'X-CSRF-TOKEN'];
    protected $maxAge = 3600;
    
    public function handle(array $request, callable $next)
    {
        // Set CORS headers
        $this->setCorsHeaders();
        
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        
        return $next($request);
    }
    
    /**
     * Set CORS headers
     */
    protected function setCorsHeaders()
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        
        // Check if origin is allowed
        if ($this->isOriginAllowed($origin)) {
            header("Access-Control-Allow-Origin: {$origin}");
        }
        
        header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Max-Age: {$this->maxAge}");
        
        // Expose headers that client can access
        header('Access-Control-Expose-Headers: Content-Length, X-JSON');
    }
    
    /**
     * Check if origin is allowed
     */
    protected function isOriginAllowed(string $origin)
    {
        if (in_array('*', $this->allowedOrigins)) {
            return true;
        }
        
        return in_array($origin, $this->allowedOrigins);
    }
    
    /**
     * Set allowed origins
     */
    public function setAllowedOrigins(array $origins)
    {
        $this->allowedOrigins = $origins;
        return $this;
    }
    
    /**
     * Set allowed methods
     */
    public function setAllowedMethods(array $methods)
    {
        $this->allowedMethods = $methods;
        return $this;
    }
    
    /**
     * Set allowed headers
     */
    public function setAllowedHeaders(array $headers)
    {
        $this->allowedHeaders = $headers;
        return $this;
    }
} 