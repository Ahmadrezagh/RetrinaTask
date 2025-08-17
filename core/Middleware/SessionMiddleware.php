<?php

namespace Core\Middleware;

/**
 * Session Middleware
 * 
 * Handles session initialization and configuration
 */
class SessionMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            $this->configureSession();
            session_start();
        }
        
        // Regenerate session ID periodically for security
        $this->handleSessionSecurity();
        
        return $next($request);
    }
    
    /**
     * Configure session settings
     */
    protected function configureSession()
    {
        // Set session cookie parameters
        session_set_cookie_params([
            'lifetime' => 7200, // 2 hours
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        
        // Set session name
        session_name('retrina_session');
    }
    
    /**
     * Handle session security
     */
    protected function handleSessionSecurity()
    {
        // Regenerate session ID every 30 minutes
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        }
        
        if (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
        
        // Set user activity timestamp
        $_SESSION['last_activity'] = time();
    }
} 