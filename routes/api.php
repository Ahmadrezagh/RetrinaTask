<?php
/**
 * API Routes
 * These routes are loaded by the Router and use the 'api' middleware group
 * which includes CORS and rate limiting but excludes CSRF protection.
 */

// Health check endpoint
$router->get('/api/health', 'ApiController@health');

// API routes requiring authentication
$router->group(['middleware' => ['web', 'auth', 'json']], function($router) {
    
    // Users API
    $router->get('/api/users', 'Api\UserController@index');
    $router->get('/api/users/{id}', 'Api\UserController@show');
    $router->post('/api/users', 'Api\UserController@store');
    $router->put('/api/users/{id}', 'Api\UserController@update');
    $router->delete('/api/users/{id}', 'Api\UserController@destroy');
    
}); 