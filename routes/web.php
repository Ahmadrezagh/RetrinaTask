<?php

/**
 * Web Routes
 * 
 * These routes are loaded by the Router and typically use the 'web' middleware group
 * which includes session handling and CSRF protection.
 */

// Load API routes
require_once __DIR__ . '/api.php';

// Public routes (no authentication required)
$router->group(['middleware' => ['web']], function($router) {
    
    // Main navigation pages
    $router->get('/', 'HomeController@index');
    $router->get('/about', 'HomeController@about');
    $router->get('/docs', 'HomeController@docs');
    $router->get('/api', 'HomeController@api');
    $router->get('/hello', 'HomeController@hello');

    // API health endpoint
    $router->get('/api/health', 'ApiController@health');
    
    // Profile image serving route
    $router->get('/storage/uploads/profiles/{filename}', 'StorageController@serveProfileImage');
    
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
        $router->get('/demo/throttle', 'DemoController@throttle');
    });
    
    $router->group(['middleware' => ['log']], function($router) {
        $router->get('/demo/logged', 'DemoController@logged');
    });
    
    // Test routes for debugging
    $router->get('/test', 'DemoController@test');
    $router->get('/test-controller', 'TestController@test');
    $router->get('/test-view', 'DemoController@testView');
    $router->get('/test-home', 'DemoController@testHome');
});

// Template demo route
$router->get('/demo/template-syntax', 'DemoController@templateSyntax');

 