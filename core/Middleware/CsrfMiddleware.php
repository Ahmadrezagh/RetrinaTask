<?php

namespace Core\Middleware;

/**
 * CSRF Middleware
 * 
 * Protects against Cross-Site Request Forgery attacks
 */
class CsrfMiddleware implements MiddlewareInterface
{
    protected $except = [
        '/api/*', // Exclude API routes by default
    ];
    
    public function handle($request, $next)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Skip CSRF check for GET, HEAD, OPTIONS requests
        if (in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }
        
        // Skip CSRF check for excluded routes
        if ($this->shouldSkip($request)) {
            return $next($request);
        }
        
        // Verify CSRF token
        if (!$this->verifyCsrfToken()) {
            return $this->handleTokenMismatch($request);
        }
        
        return $next($request);
    }
    
    /**
     * Check if CSRF check should be skipped for this route
     */
    protected function shouldSkip($request)
    {
        $uri = $request['uri'] ?? $_SERVER['REQUEST_URI'] ?? '';
        
        foreach ($this->except as $pattern) {
            if ($this->matchesPattern($uri, $pattern)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if URI matches pattern
     */
    protected function matchesPattern($uri, $pattern)
    {
        // Convert wildcard pattern to regex
        $regex = str_replace(['*', '/'], ['.*', '\/'], $pattern);
        return preg_match("/^{$regex}$/", $uri);
    }
    
    /**
     * Verify CSRF token
     */
    protected function verifyCsrfToken()
    {
        $token = $this->getTokenFromRequest();
        $sessionToken = $_SESSION['csrf_token'] ?? null;
        
        if (!$token || !$sessionToken) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * Get CSRF token from request
     */
    protected function getTokenFromRequest()
    {
        // Check POST data
        if (isset($_POST['_token'])) {
            return $_POST['_token'];
        }
        
        // Check headers
        $headers = [
            'HTTP_X_CSRF_TOKEN',
            'HTTP_X_XSRF_TOKEN',
        ];
        
        foreach ($headers as $header) {
            if (isset($_SERVER[$header])) {
                return $_SERVER[$header];
            }
        }
        
        return null;
    }
    
    /**
     * Handle token mismatch
     */
    protected function handleTokenMismatch($request)
    {
        // Check if this is an API request
        if ($this->isApiRequest($request)) {
            // Return JSON response for API requests
            header('Content-Type: application/json');
            http_response_code(419);
            echo json_encode([
                'error' => 'CSRF token mismatch',
                'message' => 'CSRF token mismatch',
                'code' => 419
            ]);
            exit;
        }
        
        // Return HTML response for web requests
        http_response_code(419);
        echo '<h1>419 Page Expired</h1><p>CSRF token mismatch. Please refresh the page and try again.</p>';
        exit;
    }
    
    /**
     * Check if request is for API endpoint
     */
    protected function isApiRequest($request)
    {
        $uri = $request['uri'] ?? $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/api/') === 0 || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Get current CSRF token
     */
    public static function getToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['csrf_token'] ?? null;
    }
} 