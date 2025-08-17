<?php

namespace Core\Middleware;

/**
 * JSON Middleware
 * 
 * Handles JSON request/response formatting for API endpoints
 */
class JsonMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next)
    {
        // Parse JSON input for API requests
        if ($this->isApiRequest($request)) {
            $this->parseJsonInput();
            $this->setJsonHeaders();
        }
        
        return $next($request);
    }
    
    /**
     * Parse JSON input from request body
     */
    protected function parseJsonInput()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $decoded = json_decode($input, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Merge JSON data into $_POST for consistency
                $_POST = array_merge($_POST, $decoded);
                
                // Store raw JSON for access
                $_SERVER['HTTP_RAW_POST_DATA'] = $input;
            }
        }
    }
    
    /**
     * Set JSON response headers
     */
    protected function setJsonHeaders()
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
    }
    
    /**
     * Check if request is for API endpoint
     */
    protected function isApiRequest(array $request): bool
    {
        $uri = $request['uri'] ?? $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/api/') === 0 || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
} 