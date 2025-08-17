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
    
    public function handle(array $request, callable $next)
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
    protected function shouldSkip(array $request): bool
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
    protected function matchesPattern(string $uri, string $pattern): bool
    {
        // Convert wildcard pattern to regex
        $regex = str_replace(['*', '/'], ['.*', '\/'], $pattern);
        return preg_match("/^{$regex}$/", $uri);
    }
    
    /**
     * Verify CSRF token
     */
    protected function verifyCsrfToken(): bool
    {
        $token = $this->getTokenFromRequest();
        $sessionToken = $_SESSION['_token'] ?? null;
        
        if (!$token || !$sessionToken) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * Get CSRF token from request
     */
    protected function getTokenFromRequest(): ?string
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
    protected function handleTokenMismatch(array $request)
    {
        if ($this->isApiRequest($request)) {
            http_response_code(419);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'CSRF token mismatch',
                'code' => 419
            ]);
            exit;
        }
        
        // Web request
        http_response_code(419);
        echo '<h1>419 Page Expired</h1><p>CSRF token mismatch. Please refresh the page and try again.</p>';
        exit;
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
    
    /**
     * Generate CSRF token
     */
    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['_token'];
    }
    
    /**
     * Get current CSRF token
     */
    public static function getToken(): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['_token'] ?? null;
    }
} 