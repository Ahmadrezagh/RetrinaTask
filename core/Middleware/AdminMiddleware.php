<?php

namespace Core\Middleware;

/**
 * Admin Middleware
 * 
 * Ensures user is authenticated and has admin privileges
 */
class AdminMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            return $this->handleUnauthenticated($request);
        }
        
        // Check if user is admin
        if (!$this->isAdmin()) {
            return $this->handleUnauthorized($request);
        }
        
        // User is admin, proceed to next middleware or route
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
     * Check if user has admin privileges
     */
    protected function isAdmin()
    {
        // Check session for admin status
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            return true;
        }
        
        // Fallback: Check database for user role
        return $this->checkAdminStatusFromDatabase();
    }
    
    /**
     * Check admin status from database
     */
    protected function checkAdminStatusFromDatabase()
    {
        try {
            require_once __DIR__ . '/../Database/Connection.php';
            require_once __DIR__ . '/../Database/QueryBuilder.php';
            require_once __DIR__ . '/../Database/DB.php';
            
            $userId = $_SESSION['user_id'];
            
            $user = \Core\Database\DB::table('users')
                ->where('id', $userId)
                ->where('is_active', 1)
                ->first();
            
            if ($user && isset($user['role']) && $user['role'] === 'admin') {
                // Cache in session for future requests
                $_SESSION['user_role'] = 'admin';
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            // Log error and deny access
            error_log("Admin middleware database check failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Handle unauthenticated user
     */
    protected function handleUnauthenticated(array $request)
    {
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
        
        // Redirect to login
        header('Location: /login');
        exit;
    }
    
    /**
     * Handle unauthorized user (authenticated but not admin)
     */
    protected function handleUnauthorized(array $request)
    {
        if ($this->isApiRequest($request)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Admin privileges required',
                'code' => 403
            ]);
            exit;
        }
        
        // Show 403 page or redirect
        http_response_code(403);
        echo '<h1>403 Forbidden</h1><p>Admin privileges required to access this page.</p>';
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