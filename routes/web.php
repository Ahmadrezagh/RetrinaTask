<?php

/**
 * Web Routes
 * 
 * These routes are loaded by the Router and typically use the 'web' middleware group
 * which includes session handling and CSRF protection.
 */

// Public routes (no authentication required)
$router->group(['middleware' => ['web']], function($router) {
    
    // Main navigation pages
    $router->get('/', 'HomeController@index');
    $router->get('/about', 'HomeController@about');
    $router->get('/docs', 'HomeController@docs');
    $router->get('/api', 'HomeController@api');
    $router->get('/hello', 'HomeController@hello');
    
    // Profile image serving route
    $router->get('/storage/uploads/profiles/{filename}', function($filename) {
        $filePath = __DIR__ . '/../storage/uploads/profiles/' . $filename;
        
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo '404 - File not found';
            return;
        }
        
        $mimeType = mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
    });
    
    // Static file serving for profile images
    $router->get('/storage/uploads/profiles/{filename}', function($filename) {
        $filePath = __DIR__ . '/../storage/uploads/profiles/' . $filename;
        
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo '404 - File not found';
            return;
        }
        
        $mimeType = mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
    });
    
    // Guest-only routes (for non-authenticated users)
    $router->group(['middleware' => ['guest']], function($router) {
        // Authentication routes
        $router->get('/login', 'AuthController@showLogin');
        $router->post('/login', 'AuthController@login');
        
        $router->get('/register', 'AuthController@showRegister');
        $router->post('/register', 'AuthController@register');
    });

});

// Test auth routes without middleware
$router->get('/test-login', 'AuthController@showLogin');
$router->post('/test-login', 'AuthController@login');

// Protected routes (authentication required)
$router->group(['middleware' => ['web', 'auth']], function($router) {
    
    $router->get('/dashboard', 'HomeController@dashboard');
    $router->get('/profile', 'HomeController@profile');
    $router->get('/settings', 'HomeController@settings');
    
    // Profile management routes
    $router->get('/profile/edit', 'ProfileController@edit');
    $router->post('/profile/update', 'ProfileController@update');
    $router->post('/profile/change-password', 'ProfileController@changePassword');
    $router->post('/profile/upload-image', 'ProfileController@uploadImage');
    $router->post('/profile/delete-image', 'ProfileController@deleteImage');
    
    // Admin routes (require admin role)
    $router->group(['middleware' => ['admin']], function($router) {
        $router->get('/admin', 'AdminController@index');
        $router->get('/admin/users/create', 'AdminController@create');
        $router->post('/admin/users/store', 'AdminController@store');
        $router->get('/admin/users/{id}/edit', 'AdminController@edit');
        $router->post('/admin/users/{id}/update', 'AdminController@update');
        $router->post('/admin/users/{id}/delete', 'AdminController@destroy');
    });
    
    // Logout routes (both POST and GET for compatibility)
    $router->post('/logout', 'AuthController@logout');
    $router->get('/logout', 'AuthController@logout');

});

// Admin-only routes
$router->group(['middleware' => ['web', 'admin'], 'prefix' => 'admin'], function($router) {
    $router->get('/', 'AdminController@index');
});

// Test and Debug Routes
$router->group(['middleware' => ['web']], function($router) {
    
    // Demo/Test routes for middleware demonstration
    $router->group(['middleware' => ['throttle:5,1']], function($router) {
        $router->get('/demo/throttle', function() {
            return json_encode([
                'message' => 'This route is rate limited to 5 requests per minute',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        });
    });
    
    $router->group(['middleware' => ['log']], function($router) {
        $router->get('/demo/logged', function() {
            return json_encode([
                'message' => 'This request is being logged',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        });
    });
    
    // Test routes for debugging
    $router->get('/test', function() {
        return json_encode([
            'status' => 'success',
            'message' => 'Test route working',
            'session' => $_SESSION ?? [],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    });
    
    $router->get('/test-controller', 'TestController@test');
    
    $router->get('/test-view', function() {
        return view('home/index', ['test' => true]);
    });
    
    $router->get('/test-home', function() {
        $data = [
            'title' => 'Test Home',
            'message' => 'This is a test'
        ];
        return view('home/index', $data);
    });
    
});



// Template demo route (existing)
$router->get('/demo/template-syntax', function() {
    return view('demo/template-syntax', [
        'title' => 'Template Syntax Demo',
        'items' => ['Apple', 'Banana', 'Cherry'],
        'user' => ['name' => 'John Doe', 'email' => 'john@example.com']
    ]);
});

 