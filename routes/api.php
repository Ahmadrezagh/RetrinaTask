<?php
/**
 * API Routes
 * These routes are loaded by the Router and use the 'api' middleware group
 * which includes CORS and rate limiting but excludes CSRF protection.
 */

// Public API routes (no authentication required)
$router->group(['middleware' => ['api']], function($router) {
    
    // API information endpoint
    $router->get('/api', function() {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Retrina Framework API',
            'version' => '1.0.0',
            'endpoints' => [
                'GET /api' => 'API information',
                'GET /api/health' => 'Health check',
                'GET /api/users' => 'List all users (requires auth)',
                'GET /api/users/{id}' => 'Get user by ID (requires auth)',
                'POST /api/auth/login' => 'User authentication',
                'POST /api/auth/logout' => 'User logout (requires auth)'
            ]
        ]);
    });
    
    // Health check endpoint
    $router->get('/api/health', function() {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'API is healthy',
            'timestamp' => date('c'),
            'uptime' => 'N/A'
        ]);
    });
    
    // Authentication endpoints (public)
    $router->post('/api/auth/login', function() {
        header('Content-Type: application/json');
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Simple demo authentication
        if ($username === 'admin' && $password === 'admin123') {
            session_start();
            $_SESSION['user_id'] = 1;
            $_SESSION['user_role'] = 'admin';
            $_SESSION['username'] = 'admin';
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => [
                    'id' => 1,
                    'username' => 'admin',
                    'role' => 'admin'
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid credentials',
                'code' => 401
            ]);
        }
    });
    
});

// Protected API routes (authentication required)
$router->group(['middleware' => ['api', 'auth']], function($router) {
    
    // User management endpoints
    $router->get('/api/users', function() {
        require_once __DIR__ . '/../core/Database/Connection.php';
        require_once __DIR__ . '/../core/Database/QueryBuilder.php';
        require_once __DIR__ . '/../core/Database/DB.php';
        
        try {
            $users = \Core\Database\DB::table('users')
                ->select(['id', 'username', 'email', 'first_name', 'last_name', 'is_active', 'created_at'])
                ->where('is_active', 1)
                ->get();
            
            // Format created_at for JSON
            foreach ($users as &$user) {
                $user['created_at'] = date('c', strtotime($user['created_at']));
                $user['is_active'] = (bool) $user['is_active'];
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $users,
                'count' => count($users)
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    });
    
    $router->get('/api/users/(\d+)', function($id) {
        require_once __DIR__ . '/../core/Database/Connection.php';
        require_once __DIR__ . '/../core/Database/QueryBuilder.php';
        require_once __DIR__ . '/../core/Database/DB.php';
        
        try {
            $user = \Core\Database\DB::table('users')
                ->select(['id', 'username', 'email', 'first_name', 'last_name', 'is_active', 'created_at'])
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();
            
            header('Content-Type: application/json');
            
            if ($user) {
                $user['created_at'] = date('c', strtotime($user['created_at']));
                $user['is_active'] = (bool) $user['is_active'];
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $user
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'User not found',
                    'code' => 404
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    });
    
    // Logout endpoint
    $router->post('/api/auth/logout', function() {
        session_start();
        session_destroy();
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    });
    
    // User profile endpoint
    $router->get('/api/auth/user', function() {
        session_start();
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $_SESSION['user_id'] ?? null,
                'username' => $_SESSION['username'] ?? null,
                'role' => $_SESSION['user_role'] ?? null
            ]
        ]);
    });
    
});

// Admin-only API routes
$router->group(['middleware' => ['api', 'admin'], 'prefix' => 'api/admin'], function($router) {
    
    // Admin dashboard stats
    $router->get('/', function() {
        require_once __DIR__ . '/../core/Database/Connection.php';
        require_once __DIR__ . '/../core/Database/QueryBuilder.php';
        require_once __DIR__ . '/../core/Database/DB.php';
        
        try {
            $totalUsers = \Core\Database\DB::table('users')->count();
            $activeUsers = \Core\Database\DB::table('users')->where('is_active', 1)->count();
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'inactive_users' => $totalUsers - $activeUsers
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    });
    
    // Create new user (admin only)
    $router->post('/users', function() {
        header('Content-Type: application/json');
        
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (!$username || !$email || !$password) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Username, email, and password are required',
                'code' => 400
            ]);
            return;
        }
        
        // In a real application, you would validate and create the user here
        echo json_encode([
            'status' => 'success',
            'message' => 'User created successfully (demo)',
            'data' => [
                'username' => $username,
                'email' => $email
            ]
        ]);
    });
    
});

// Rate-limited API routes
$router->group(['middleware' => ['api', 'throttle:10,1']], function($router) {
    
    $router->post('/api/contact', function() {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Contact form submitted (rate-limited endpoint)',
            'rate_limit' => '10 requests per minute'
        ]);
    });
    
});

// Demonstration endpoints for testing different middleware
$router->get('/api/demo/public', function() {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'This is a public API endpoint with CORS enabled',
        'middleware' => ['api']
    ]);
}, ['api']);

$router->get('/api/demo/protected', function() {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'This is a protected API endpoint',
        'middleware' => ['api', 'auth'],
        'user' => $_SESSION['username'] ?? 'unknown'
    ]);
}, ['api', 'auth']);

$router->get('/api/demo/admin', function() {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'This is an admin-only API endpoint',
        'middleware' => ['api', 'admin'],
        'user' => $_SESSION['username'] ?? 'unknown'
    ]);
}, ['api', 'admin']); 