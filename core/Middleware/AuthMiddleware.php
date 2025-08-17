<?php

namespace Core\Middleware;

/**
 * Authentication Middleware
 * 
 * Ensures user is authenticated before accessing protected routes
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin($request);
        }
        
        // User is authenticated, proceed to next middleware or route
        return $next($request);
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Get authenticated user ID
     */
    protected function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Redirect to login page or return JSON error
     */
    protected function redirectToLogin(array $request)
    {
        // Check if it's an API request
        if ($this->isApiRequest($request)) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Authentication required',
                'code' => 401
            ]);
            exit;
        }
        
        // Web request - redirect to login
        $loginUrl = '/login';
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Save intended URL for redirect after login
        $_SESSION['intended_url'] = $currentUrl;
        
        header("Location: {$loginUrl}");
        exit;
    }
    
    /**
     * Check if request is for API endpoint
     */
    protected function isApiRequest(array $request)
    {
        $uri = $request['uri'] ?? $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/api/') === 0 || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
} 