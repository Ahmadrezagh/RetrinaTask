<?php
/**
 * Retrina Framework - Middleware Usage Examples
 * 
 * This file demonstrates how to use the comprehensive middleware system
 * to control access to routes, handle authentication, rate limiting, and more.
 */

echo "🛡️ Retrina Framework Middleware Usage Examples\n";
echo "==============================================\n\n";

echo "The Retrina Framework includes a powerful middleware system that allows you to:\n";
echo "- Control access to routes\n";
echo "- Handle authentication and authorization\n";
echo "- Implement rate limiting\n";
echo "- Add CORS support for APIs\n";
echo "- Protect against CSRF attacks\n";
echo "- Log requests\n";
echo "- Manage sessions\n";
echo "- Handle maintenance mode\n\n";

// =============================================================================
// BASIC MIDDLEWARE USAGE
// =============================================================================

echo "1. Basic Middleware Usage\n";
echo "========================\n\n";

echo "// Apply middleware to individual routes:\n";
echo "\\$router->get('/profile', 'UserController@profile', ['auth']);\n";
echo "\\$router->get('/admin', 'AdminController@index', ['auth', 'admin']);\n";
echo "\\$router->get('/api/data', 'ApiController@data', ['cors', 'throttle:60,1']);\n\n";

echo "// Apply middleware using the fluent interface:\n";
echo "\\$router->middleware(['auth'])->get('/dashboard', 'DashboardController@index');\n\n";

// =============================================================================
// ROUTE GROUPS WITH MIDDLEWARE
// =============================================================================

echo "2. Route Groups with Middleware\n";
echo "==============================\n\n";

echo "// Group routes with shared middleware:\n";
echo "\\$router->group(['middleware' => ['web']], function(\\$router) {\n";
echo "    \\$router->get('/', 'HomeController@index');\n";
echo "    \\$router->get('/about', 'HomeController@about');\n";
echo "});\n\n";

echo "// Protected routes group:\n";
echo "\\$router->group(['middleware' => ['web', 'auth']], function(\\$router) {\n";
echo "    \\$router->get('/dashboard', 'DashboardController@index');\n";
echo "    \\$router->get('/profile', 'UserController@profile');\n";
echo "});\n\n";

echo "// Admin routes with prefix:\n";
echo "\\$router->group(['middleware' => ['web', 'admin'], 'prefix' => 'admin'], function(\\$router) {\n";
echo "    \\$router->get('/', 'AdminController@dashboard');\n";
echo "    \\$router->get('/users', 'AdminController@users');\n";
echo "});\n\n";

echo "// API routes with CORS and rate limiting:\n";
echo "\\$router->group(['middleware' => ['api'], 'prefix' => 'api'], function(\\$router) {\n";
echo "    \\$router->get('/users', 'ApiController@users');\n";
echo "    \\$router->post('/users', 'ApiController@createUser');\n";
echo "});\n\n";

// =============================================================================
// AVAILABLE MIDDLEWARE
// =============================================================================

echo "3. Available Middleware\n";
echo "======================\n\n";

$middleware = [
    'auth' => [
        'description' => 'Ensures user is authenticated',
        'usage' => "['auth']",
        'behavior' => 'Redirects to login if not authenticated'
    ],
    'admin' => [
        'description' => 'Ensures user has admin privileges',
        'usage' => "['admin']",
        'behavior' => 'Returns 403 if not admin, checks database for role'
    ],
    'guest' => [
        'description' => 'Only allows non-authenticated users',
        'usage' => "['guest']",
        'behavior' => 'Redirects to dashboard if already authenticated'
    ],
    'cors' => [
        'description' => 'Adds CORS headers for API requests',
        'usage' => "['cors']",
        'behavior' => 'Sets CORS headers, handles OPTIONS requests'
    ],
    'throttle' => [
        'description' => 'Rate limiting middleware',
        'usage' => "['throttle:60,1'] // 60 requests per 1 minute",
        'behavior' => 'Returns 429 if rate limit exceeded'
    ],
    'csrf' => [
        'description' => 'CSRF protection for forms',
        'usage' => "['csrf']",
        'behavior' => 'Validates CSRF tokens on POST/PUT/DELETE requests'
    ],
    'session' => [
        'description' => 'Session management',
        'usage' => "['session']",
        'behavior' => 'Starts sessions, handles security regeneration'
    ],
    'json' => [
        'description' => 'JSON API request handling',
        'usage' => "['json']",
        'behavior' => 'Parses JSON input, sets JSON headers'
    ],
    'log' => [
        'description' => 'Request logging',
        'usage' => "['log']",
        'behavior' => 'Logs requests and responses with timing'
    ],
    'maintenance' => [
        'description' => 'Maintenance mode',
        'usage' => "['maintenance']",
        'behavior' => 'Shows maintenance page if enabled'
    ]
];

foreach ($middleware as $name => $info) {
    echo "🔹 {$name}:\n";
    echo "   Description: {$info['description']}\n";
    echo "   Usage: {$info['usage']}\n";
    echo "   Behavior: {$info['behavior']}\n\n";
}

// =============================================================================
// MIDDLEWARE GROUPS
// =============================================================================

echo "4. Predefined Middleware Groups\n";
echo "==============================\n\n";

echo "🔹 'web' group: ['csrf', 'session']\n";
echo "   Used for web routes that need CSRF protection and sessions\n\n";

echo "🔹 'api' group: ['cors', 'throttle:60,1']\n";
echo "   Used for API routes with CORS support and rate limiting\n\n";

echo "Usage:\n";
echo "\\$router->group(['middleware' => ['web']], function(\\$router) {\n";
echo "    // Web routes with CSRF and session\n";
echo "});\n\n";

echo "\\$router->group(['middleware' => ['api']], function(\\$router) {\n";
echo "    // API routes with CORS and rate limiting\n";
echo "});\n\n";

// =============================================================================
// REAL-WORLD EXAMPLES
// =============================================================================

echo "5. Real-World Examples\n";
echo "======================\n\n";

echo "Example 1: E-commerce Application\n";
echo "---------------------------------\n";
echo "// Public routes\n";
echo "\\$router->group(['middleware' => ['web']], function(\\$router) {\n";
echo "    \\$router->get('/', 'HomeController@index');\n";
echo "    \\$router->get('/products', 'ProductController@index');\n";
echo "    \\$router->get('/products/{id}', 'ProductController@show');\n";
echo "});\n\n";

echo "// Authentication routes (guest only)\n";
echo "\\$router->group(['middleware' => ['web', 'guest']], function(\\$router) {\n";
echo "    \\$router->get('/login', 'AuthController@showLogin');\n";
echo "    \\$router->post('/login', 'AuthController@login');\n";
echo "    \\$router->get('/register', 'AuthController@showRegister');\n";
echo "    \\$router->post('/register', 'AuthController@register');\n";
echo "});\n\n";

echo "// User dashboard (authenticated)\n";
echo "\\$router->group(['middleware' => ['web', 'auth']], function(\\$router) {\n";
echo "    \\$router->get('/dashboard', 'UserController@dashboard');\n";
echo "    \\$router->get('/orders', 'OrderController@index');\n";
echo "    \\$router->post('/cart/add', 'CartController@add');\n";
echo "});\n\n";

echo "// Admin panel (admin only)\n";
echo "\\$router->group(['middleware' => ['web', 'admin'], 'prefix' => 'admin'], function(\\$router) {\n";
echo "    \\$router->get('/', 'AdminController@dashboard');\n";
echo "    \\$router->get('/products', 'AdminController@products');\n";
echo "    \\$router->get('/orders', 'AdminController@orders');\n";
echo "    \\$router->get('/users', 'AdminController@users');\n";
echo "});\n\n";

echo "// Public API (with rate limiting)\n";
echo "\\$router->group(['middleware' => ['api'], 'prefix' => 'api/v1'], function(\\$router) {\n";
echo "    \\$router->get('/products', 'ApiController@products');\n";
echo "    \\$router->get('/categories', 'ApiController@categories');\n";
echo "});\n\n";

echo "// Protected API (authentication required)\n";
echo "\\$router->group(['middleware' => ['api', 'auth'], 'prefix' => 'api/v1'], function(\\$router) {\n";
echo "    \\$router->get('/user', 'ApiController@user');\n";
echo "    \\$router->get('/orders', 'ApiController@orders');\n";
echo "    \\$router->post('/orders', 'ApiController@createOrder');\n";
echo "});\n\n";

echo "Example 2: Rate-Limited Contact Form\n";
echo "------------------------------------\n";
echo "\\$router->group(['middleware' => ['web', 'throttle:5,10']], function(\\$router) {\n";
echo "    \\$router->get('/contact', 'ContactController@show');\n";
echo "    \\$router->post('/contact', 'ContactController@submit'); // 5 submissions per 10 minutes\n";
echo "});\n\n";

echo "Example 3: Admin API with Strict Limits\n";
echo "---------------------------------------\n";
echo "\\$router->group([\n";
echo "    'middleware' => ['api', 'admin', 'throttle:100,1'], // 100 requests per minute for admins\n";
echo "    'prefix' => 'api/admin'\n";
echo "], function(\\$router) {\n";
echo "    \\$router->get('/stats', 'AdminApiController@stats');\n";
echo "    \\$router->post('/users', 'AdminApiController@createUser');\n";
echo "    \\$router->delete('/users/{id}', 'AdminApiController@deleteUser');\n";
echo "});\n\n";

// =============================================================================
// CUSTOM MIDDLEWARE
// =============================================================================

echo "6. Creating Custom Middleware\n";
echo "============================\n\n";

echo "To create custom middleware, implement the MiddlewareInterface:\n\n";

echo "<?php\n";
echo "namespace App\\Middleware;\n\n";
echo "use Core\\Middleware\\MiddlewareInterface;\n\n";
echo "class CustomMiddleware implements MiddlewareInterface\n";
echo "{\n";
echo "    public function handle(array \\$request, callable \\$next)\n";
echo "    {\n";
echo "        // Before logic\n";
echo "        if (\\$this->shouldBlock(\\$request)) {\n";
echo "            return \\$this->blockRequest();\n";
echo "        }\n\n";
echo "        // Continue to next middleware or route\n";
echo "        \\$response = \\$next(\\$request);\n\n";
echo "        // After logic\n";
echo "        \\$this->logResponse(\\$response);\n\n";
echo "        return \\$response;\n";
echo "    }\n\n";
echo "    private function shouldBlock(array \\$request): bool\n";
echo "    {\n";
echo "        // Custom blocking logic\n";
echo "        return false;\n";
echo "    }\n\n";
echo "    private function blockRequest()\n";
echo "    {\n";
echo "        http_response_code(403);\n";
echo "        echo 'Access denied';\n";
echo "        exit;\n";
echo "    }\n\n";
echo "    private function logResponse(\\$response)\n";
echo "    {\n";
echo "        // Custom logging logic\n";
echo "    }\n";
echo "}\n\n";

echo "Register your custom middleware:\n";
echo "MiddlewareManager::register('custom', App\\Middleware\\CustomMiddleware::class);\n\n";

echo "Use it in routes:\n";
echo "\\$router->get('/special', 'SpecialController@index', ['custom']);\n\n";

// =============================================================================
// TESTING MIDDLEWARE
// =============================================================================

echo "7. Testing Middleware\n";
echo "====================\n\n";

echo "The middleware system is now active in your Retrina Framework.\n";
echo "You can test it by visiting these URLs:\n\n";

echo "🔗 Test Routes:\n";
echo "- http://localhost:8585/demo/middleware - Middleware demo page\n";
echo "- http://localhost:8585/demo/auth-test - Test authentication\n";
echo "- http://localhost:8585/demo/admin-test - Test admin privileges\n";
echo "- http://localhost:8585/demo/guest-test - Test guest-only access\n";
echo "- http://localhost:8585/demo/throttle-test - Test rate limiting\n\n";

echo "🔗 Demo Login:\n";
echo "Use these credentials for testing:\n";
echo "- Admin: username=admin, password=admin123\n";
echo "- User: username=user, password=user123\n\n";

echo "🔗 API Testing:\n";
echo "- GET http://localhost:8585/api/demo/public - Public API endpoint\n";
echo "- GET http://localhost:8585/api/demo/protected - Protected API endpoint\n";
echo "- GET http://localhost:8585/api/demo/admin - Admin API endpoint\n\n";

echo "✅ Middleware System Features:\n";
echo "- ✅ Authentication & Authorization\n";
echo "- ✅ Rate Limiting with configurable limits\n";
echo "- ✅ CORS support for APIs\n";
echo "- ✅ CSRF protection for forms\n";
echo "- ✅ Session management\n";
echo "- ✅ Request logging\n";
echo "- ✅ Maintenance mode\n";
echo "- ✅ JSON API handling\n";
echo "- ✅ Guest-only routes\n";
echo "- ✅ Route grouping with shared middleware\n";
echo "- ✅ Configurable middleware groups\n";
echo "- ✅ Custom middleware support\n\n";

echo "🚀 Your middleware system is ready for production use!\n";
?> 