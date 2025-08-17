<?php

namespace Core\Middleware;

/**
 * Guest Middleware
 * 
 * Ensures only non-authenticated users can access routes (like login/register pages)
 */
class GuestMiddleware implements MiddlewareInterface
{
    public function handle($request, $next)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is authenticated
        if ($this->isAuthenticated()) {
            return $this->redirectAuthenticated($request);
        }
        
        // User is not authenticated, proceed to next middleware or route
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
     * Redirect authenticated user
     */
    protected function redirectAuthenticated($request)
    {
        // Check if this is an API request
        if ($this->isApiRequest($request)) {
            // Return JSON response for API requests
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode([
                'error' => 'Access denied',
                'message' => 'Already authenticated',
                'code' => 403
            ]);
            exit;
        }
        
        // Redirect to dashboard for web requests
        header('Location: /dashboard');
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
} 