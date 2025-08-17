<?php

namespace Core\Middleware;

/**
 * Guest Middleware
 * 
 * Ensures only non-authenticated users can access routes (like login/register pages)
 */
class GuestMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next)
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
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Redirect authenticated users away from guest-only routes
     */
    protected function redirectAuthenticated(array $request)
    {
        if ($this->isApiRequest($request)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Already authenticated',
                'code' => 403
            ]);
            exit;
        }
        
        // Redirect to dashboard or home page
        $redirectUrl = $_SESSION['intended_url'] ?? '/dashboard';
        unset($_SESSION['intended_url']);
        
        header("Location: {$redirectUrl}");
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
} 