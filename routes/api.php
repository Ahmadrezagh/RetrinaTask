<?php

/**
 * API Routes
 * 
 * These routes are loaded by the Router and are automatically prefixed with /api
 * All API routes should return JSON responses
 */

// API Information endpoint
$router->get('/api', function() {
    header('Content-Type: application/json');
    
    $apiInfo = [
        'name' => 'Retrina Framework API',
        'version' => '1.0.0',
        'status' => 'active',
        'timestamp' => date('c'),
        'endpoints' => [
            'GET /api' => 'API information',
            'GET /api/health' => 'Health check',
            'GET /api/users' => 'List users',
            'GET /api/users/{id}' => 'Get user by ID',
            'POST /api/users' => 'Create user',
            'PUT /api/users/{id}' => 'Update user',
            'DELETE /api/users/{id}' => 'Delete user'
        ]
    ];
    
    http_response_code(200);
    echo json_encode($apiInfo, JSON_PRETTY_PRINT);
});

// Health check endpoint
$router->get('/api/health', function() {
    header('Content-Type: application/json');
    
    $health = [
        'status' => 'healthy',
        'timestamp' => date('c'),
        'uptime' => 'running',
        'database' => 'connected',
        'memory_usage' => memory_get_usage(true),
        'php_version' => PHP_VERSION
    ];
    
    http_response_code(200);
    echo json_encode($health, JSON_PRETTY_PRINT);
});

// Users API endpoints
$router->get('/api/users', function() {
    header('Content-Type: application/json');
    
    // TODO: Implement actual user fetching from database
    $users = [
        [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@retrina.local',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'is_active' => true,
            'created_at' => '2025-01-17T19:00:00Z'
        ],
        [
            'id' => 2,
            'username' => 'demo',
            'email' => 'demo@retrina.local',
            'first_name' => 'Demo',
            'last_name' => 'User',
            'is_active' => true,
            'created_at' => '2025-01-17T19:00:00Z'
        ]
    ];
    
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'data' => $users,
        'count' => count($users)
    ], JSON_PRETTY_PRINT);
});

$router->get('/api/users/(\d+)', function($id) {
    header('Content-Type: application/json');
    
    // TODO: Implement actual user fetching from database
    if ($id == 1) {
        $user = [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@retrina.local',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'is_active' => true,
            'created_at' => '2025-01-17T19:00:00Z',
            'updated_at' => '2025-01-17T19:00:00Z'
        ];
        
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $user
        ], JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'User not found',
            'error_code' => 'USER_NOT_FOUND'
        ], JSON_PRETTY_PRINT);
    }
});

$router->post('/api/users', function() {
    header('Content-Type: application/json');
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON input',
            'error_code' => 'INVALID_INPUT'
        ], JSON_PRETTY_PRINT);
        return;
    }
    
    // Basic validation
    $required = ['username', 'email', 'first_name', 'last_name'];
    $missing = [];
    
    foreach ($required as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            $missing[] = $field;
        }
    }
    
    if (!empty($missing)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields',
            'missing_fields' => $missing,
            'error_code' => 'VALIDATION_ERROR'
        ], JSON_PRETTY_PRINT);
        return;
    }
    
    // TODO: Implement actual user creation in database
    $newUser = [
        'id' => rand(100, 999), // Temporary ID
        'username' => $input['username'],
        'email' => $input['email'],
        'first_name' => $input['first_name'],
        'last_name' => $input['last_name'],
        'is_active' => true,
        'created_at' => date('c'),
        'updated_at' => date('c')
    ];
    
    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'message' => 'User created successfully',
        'data' => $newUser
    ], JSON_PRETTY_PRINT);
});

$router->put('/api/users/(\d+)', function($id) {
    header('Content-Type: application/json');
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON input',
            'error_code' => 'INVALID_INPUT'
        ], JSON_PRETTY_PRINT);
        return;
    }
    
    // TODO: Implement actual user update in database
    // For now, just return a mock updated user
    $updatedUser = [
        'id' => (int)$id,
        'username' => $input['username'] ?? 'admin',
        'email' => $input['email'] ?? 'admin@retrina.local',
        'first_name' => $input['first_name'] ?? 'Admin',
        'last_name' => $input['last_name'] ?? 'User',
        'is_active' => $input['is_active'] ?? true,
        'created_at' => '2025-01-17T19:00:00Z',
        'updated_at' => date('c')
    ];
    
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'User updated successfully',
        'data' => $updatedUser
    ], JSON_PRETTY_PRINT);
});

$router->delete('/api/users/(\d+)', function($id) {
    header('Content-Type: application/json');
    
    // TODO: Implement actual user deletion from database
    
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => "User {$id} deleted successfully",
        'deleted_id' => (int)$id
    ], JSON_PRETTY_PRINT);
}); 