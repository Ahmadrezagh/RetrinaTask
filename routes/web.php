<?php

// Share global data across all views
$app->shareViewData([
    'app_name' => 'Retrina Framework',
    'app_version' => '1.0.0',
    'year' => date('Y')
]);

// Home routes
$app->router()->get('/', 'HomeController@index');
$app->router()->get('/home', 'HomeController@index');
$app->router()->get('/about', 'HomeController@about');

// User routes with parameters
$app->router()->get('/user/{id}', 'HomeController@user');

// Contact routes
$app->router()->get('/contact', 'HomeController@contact');
$app->router()->post('/contact', 'HomeController@contact');

// Authentication routes (demo)
$app->router()->get('/login', 'HomeController@login');

// Demo routes
$app->router()->get('/demo', 'HomeController@demo');
$app->router()->get('/demo/template-syntax', 'HomeController@templateDemo');

// Example closure route
$app->router()->get('/hello/{name}', function($name) {
    echo "<div style='text-align: center; padding: 3rem; font-family: sans-serif;'>";
    echo "<h1 style='color: #667eea;'>Hello, " . htmlspecialchars($name) . "! 👋</h1>";
    echo "<p style='color: #666;'>This is a closure route in Retrina Framework</p>";
    echo "<a href='" . url('/') . "' style='color: #667eea; text-decoration: none;'>← Back to Home</a>";
    echo "</div>";
});

// Template cache management
$app->router()->get('/cache/clear', function() {
    $viewEngine = new \Core\ViewEngine();
    $viewEngine->clearCache();
    echo json_encode([
        'status' => 'success',
        'message' => 'Template cache cleared successfully'
    ]);
});

// Example POST route for testing
$app->router()->post('/test', function() {
    echo json_encode([
        'message' => 'POST request received!',
        'data' => $_POST,
        'csrf_token' => csrf_token()
    ]);
});

// Example route with multiple parameters
$app->router()->get('/posts/{category}/{id}', function($category, $id) {
    echo "<div style='padding: 2rem; font-family: sans-serif;'>";
    echo "<h2>Post Details</h2>";
    echo "<p><strong>Category:</strong> " . htmlspecialchars($category) . "</p>";
    echo "<p><strong>Post ID:</strong> " . htmlspecialchars($id) . "</p>";
    echo "<p>This demonstrates multiple parameter routing!</p>";
    echo "<a href='" . url('/') . "'>← Back to Home</a>";
    echo "</div>";
}); 