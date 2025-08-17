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
    $router->get('/', function() {
        // Start session to check authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
        $user = $isAuthenticated ? [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'] ?? 'User',
            'role' => $_SESSION['user_role'] ?? 'user'
        ] : null;
        
        return view('home/index', compact('isAuthenticated', 'user'));
    });
    
    $router->get('/about', function() {
        return view('pages/about');
    });
    
    $router->get('/docs', function() {
        return view('pages/docs');
    });
    
    $router->get('/api', function() {
        return view('pages/api');
    });
    
    $router->get('/hello', function() {
        return view('pages/hello');
    });
    
    // Guest-only routes (for non-authenticated users)
    $router->group(['middleware' => ['guest']], function($router) {
        $router->get('/login', function() {
            return view('auth/login');
        });
        
        $router->get('/register', function() {
            return view('auth/register');
        });
        
        $router->post('/demo/login', function() {
            session_start();
            
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Simple demo authentication (use proper authentication in production)
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['user_id'] = 1;
                $_SESSION['user_role'] = 'admin';
                $_SESSION['username'] = 'admin';
                
                header('Location: /dashboard');
                exit;
            } elseif ($username === 'user' && $password === 'user123') {
                $_SESSION['user_id'] = 2;
                $_SESSION['user_role'] = 'user';
                $_SESSION['username'] = 'user';
                
                header('Location: /dashboard');
                exit;
            } else {
                // Redirect back to login with error
                $_SESSION['login_error'] = 'Invalid credentials. Try admin/admin123 or user/user123';
                header('Location: /login');
                exit;
            }
        });
        
        $router->post('/demo/register', function() {
            session_start();
            
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Simple validation
            if (!$firstName || !$lastName || !$username || !$email || !$password) {
                $_SESSION['register_error'] = 'Please fill in all required fields';
                header('Location: /register');
                exit;
            }
            
            // Demo registration success
            $_SESSION['register_success'] = 'Account created successfully! You can now login.';
            header('Location: /login');
            exit;
        });
    });
    
});

// Protected routes (authentication required)
$router->group(['middleware' => ['web', 'auth']], function($router) {
    
    $router->get('/dashboard', function() {
        session_start();
        $user = [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ];
        
        echo '<div class="container py-5">';
        echo '<div class="row">';
        echo '<div class="col-12">';
        echo '<div class="bg-gradient-primary text-white p-5 rounded mb-4">';
        echo '<h1 class="display-4 mb-3">Welcome to your Dashboard, ' . htmlspecialchars($user['username']) . '! 🎉</h1>';
        echo '<p class="lead">You are successfully logged in as a ' . htmlspecialchars($user['role']) . '.</p>';
        echo '</div>';
        
        echo '<div class="row g-4">';
        
        // Profile Card
        echo '<div class="col-md-4">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<div class="card-body text-center">';
        echo '<i class="bi bi-person-circle display-4 text-primary mb-3"></i>';
        echo '<h5 class="card-title">Profile</h5>';
        echo '<p class="card-text">Manage your personal information and account settings.</p>';
        echo '<a href="/profile" class="btn btn-primary">View Profile</a>';
        echo '</div></div></div>';
        
        // Settings Card
        echo '<div class="col-md-4">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<div class="card-body text-center">';
        echo '<i class="bi bi-gear display-4 text-success mb-3"></i>';
        echo '<h5 class="card-title">Settings</h5>';
        echo '<p class="card-text">Configure your preferences and application settings.</p>';
        echo '<a href="/settings" class="btn btn-success">Open Settings</a>';
        echo '</div></div></div>';
        
        // Admin Panel (if admin)
        if ($user['role'] === 'admin') {
            echo '<div class="col-md-4">';
            echo '<div class="card h-100 shadow-sm">';
            echo '<div class="card-body text-center">';
            echo '<i class="bi bi-shield-check display-4 text-warning mb-3"></i>';
            echo '<h5 class="card-title">Admin Panel</h5>';
            echo '<p class="card-text">Manage users, settings, and system administration.</p>';
            echo '<a href="/admin" class="btn btn-warning">Admin Panel</a>';
            echo '</div></div></div>';
        }
        
        echo '</div>'; // row
        
        // Quick Actions
        echo '<div class="mt-5">';
        echo '<h3>Quick Actions</h3>';
        echo '<div class="d-flex gap-3 flex-wrap">';
        echo '<a href="/" class="btn btn-outline-primary"><i class="bi bi-house"></i> Home</a>';
        echo '<a href="/docs" class="btn btn-outline-info"><i class="bi bi-book"></i> Documentation</a>';
        echo '<a href="/api" class="btn btn-outline-secondary"><i class="bi bi-api"></i> API Reference</a>';
        echo '<a href="/hello" class="btn btn-outline-success"><i class="bi bi-hand-wave"></i> Hello Page</a>';
        echo '</div>';
        echo '</div>';
        
        echo '</div></div></div>';
    });
    
    $router->get('/profile', function() {
        session_start();
        $user = [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ];
        
        echo '<div class="container py-5">';
        echo '<div class="row justify-content-center">';
        echo '<div class="col-lg-8">';
        echo '<div class="card shadow-lg">';
        echo '<div class="card-header bg-primary text-white">';
        echo '<h3 class="mb-0"><i class="bi bi-person-circle"></i> User Profile</h3>';
        echo '</div>';
        echo '<div class="card-body p-5">';
        
        echo '<div class="row">';
        echo '<div class="col-md-4 text-center mb-4">';
        echo '<div class="user-avatar mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">';
        echo strtoupper(substr($user['username'], 0, 1));
        echo '</div>';
        echo '<h4>' . htmlspecialchars($user['username']) . '</h4>';
        echo '<span class="badge bg-' . ($user['role'] === 'admin' ? 'warning' : 'primary') . ' fs-6">' . ucfirst($user['role']) . '</span>';
        echo '</div>';
        
        echo '<div class="col-md-8">';
        echo '<h5 class="mb-3">Account Information</h5>';
        echo '<table class="table table-borderless">';
        echo '<tr><td><strong>User ID:</strong></td><td>' . $user['id'] . '</td></tr>';
        echo '<tr><td><strong>Username:</strong></td><td>' . htmlspecialchars($user['username']) . '</td></tr>';
        echo '<tr><td><strong>Role:</strong></td><td>' . ucfirst($user['role']) . '</td></tr>';
        echo '<tr><td><strong>Status:</strong></td><td><span class="badge bg-success">Active</span></td></tr>';
        echo '<tr><td><strong>Login Time:</strong></td><td>' . date('Y-m-d H:i:s') . '</td></tr>';
        echo '</table>';
        echo '</div>';
        
        echo '</div>';
        
        echo '<hr>';
        echo '<div class="d-flex gap-3 justify-content-center">';
        echo '<a href="/dashboard" class="btn btn-primary"><i class="bi bi-speedometer2"></i> Back to Dashboard</a>';
        echo '<a href="/settings" class="btn btn-outline-secondary"><i class="bi bi-gear"></i> Settings</a>';
        echo '</div>';
        
        echo '</div></div></div></div></div>';
    });
    
    $router->get('/settings', function() {
        session_start();
        $user = [
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ];
        
        echo '<div class="container py-5">';
        echo '<div class="row justify-content-center">';
        echo '<div class="col-lg-8">';
        echo '<div class="card shadow-lg">';
        echo '<div class="card-header bg-info text-white">';
        echo '<h3 class="mb-0"><i class="bi bi-gear"></i> Settings</h3>';
        echo '</div>';
        echo '<div class="card-body p-5">';
        
        echo '<div class="alert alert-info">';
        echo '<i class="bi bi-info-circle"></i> This is a demo settings page. In a real application, you would have forms to update user preferences, change passwords, etc.';
        echo '</div>';
        
        echo '<h5 class="mb-3">Account Settings</h5>';
        echo '<div class="list-group mb-4">';
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<div><i class="bi bi-person me-2"></i> Update Profile Information</div>';
        echo '<button class="btn btn-sm btn-outline-primary">Edit</button>';
        echo '</div>';
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<div><i class="bi bi-lock me-2"></i> Change Password</div>';
        echo '<button class="btn btn-sm btn-outline-primary">Change</button>';
        echo '</div>';
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<div><i class="bi bi-bell me-2"></i> Notification Preferences</div>';
        echo '<button class="btn btn-sm btn-outline-primary">Configure</button>';
        echo '</div>';
        echo '</div>';
        
        echo '<h5 class="mb-3">Application Settings</h5>';
        echo '<div class="list-group mb-4">';
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<div><i class="bi bi-palette me-2"></i> Theme</div>';
        echo '<select class="form-select form-select-sm" style="width: auto;"><option>Light</option><option>Dark</option></select>';
        echo '</div>';
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<div><i class="bi bi-globe me-2"></i> Language</div>';
        echo '<select class="form-select form-select-sm" style="width: auto;"><option>English</option><option>Spanish</option></select>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="d-flex gap-3 justify-content-center">';
        echo '<a href="/dashboard" class="btn btn-primary"><i class="bi bi-speedometer2"></i> Back to Dashboard</a>';
        echo '<a href="/profile" class="btn btn-outline-secondary"><i class="bi bi-person"></i> Profile</a>';
        echo '</div>';
        
        echo '</div></div></div></div></div>';
    });
    
    $router->post('/logout', function() {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    });
    
});

// Admin-only routes
$router->group(['middleware' => ['web', 'admin'], 'prefix' => 'admin'], function($router) {
    
    $router->get('/', function() {
        session_start();
        $user = $_SESSION['username'];
        
        echo '<div class="container py-5">';
        echo '<div class="bg-warning text-dark p-5 rounded mb-4">';
        echo '<h1 class="display-4 mb-3"><i class="bi bi-shield-check"></i> Admin Panel</h1>';
        echo '<p class="lead">Welcome to the admin panel, ' . htmlspecialchars($user) . '! You have full administrative privileges.</p>';
        echo '</div>';
        
        echo '<div class="row g-4">';
        
        // Users Management
        echo '<div class="col-md-4">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<div class="card-body text-center">';
        echo '<i class="bi bi-people display-4 text-primary mb-3"></i>';
        echo '<h5 class="card-title">User Management</h5>';
        echo '<p class="card-text">Manage system users, roles, and permissions.</p>';
        echo '<a href="/admin/users" class="btn btn-primary">Manage Users</a>';
        echo '</div></div></div>';
        
        // System Settings
        echo '<div class="col-md-4">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<div class="card-body text-center">';
        echo '<i class="bi bi-gear display-4 text-success mb-3"></i>';
        echo '<h5 class="card-title">System Settings</h5>';
        echo '<p class="card-text">Configure system-wide settings and preferences.</p>';
        echo '<a href="/admin/settings" class="btn btn-success">System Settings</a>';
        echo '</div></div></div>';
        
        // Analytics
        echo '<div class="col-md-4">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<div class="card-body text-center">';
        echo '<i class="bi bi-graph-up display-4 text-info mb-3"></i>';
        echo '<h5 class="card-title">Analytics</h5>';
        echo '<p class="card-text">View system analytics and usage statistics.</p>';
        echo '<a href="/admin/analytics" class="btn btn-info">View Analytics</a>';
        echo '</div></div></div>';
        
        echo '</div>';
        
        echo '<div class="mt-5">';
        echo '<div class="alert alert-warning">';
        echo '<h5><i class="bi bi-exclamation-triangle"></i> Admin Notice</h5>';
        echo '<p class="mb-0">You are logged in as an administrator. Please use these privileges responsibly.</p>';
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
    });
    
    $router->get('/users', function() {
        echo '<div class="container py-5">';
        echo '<h1 class="mb-4"><i class="bi bi-people"></i> User Management</h1>';
        echo '<div class="alert alert-info">This would show a list of all users with management options.</div>';
        echo '<a href="/admin" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Admin Panel</a>';
        echo '</div>';
    });
    
    $router->get('/settings', function() {
        echo '<div class="container py-5">';
        echo '<h1 class="mb-4"><i class="bi bi-gear"></i> System Settings</h1>';
        echo '<div class="alert alert-info">This would show system configuration options.</div>';
        echo '<a href="/admin" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Admin Panel</a>';
        echo '</div>';
    });
    
});

// Rate-limited routes
$router->group(['middleware' => ['web', 'throttle:10,1']], function($router) {
    
    $router->get('/contact', function() {
        echo '<div class="container py-5">';
        echo '<h1 class="mb-4"><i class="bi bi-envelope"></i> Contact Us</h1>';
        echo '<div class="alert alert-warning">This page is rate-limited to 10 requests per minute.</div>';
        echo '<p>Contact form would go here.</p>';
        echo '</div>';
    });
    
    $router->post('/contact', function() {
        echo 'Contact form submitted!';
    });
    
});

// Demonstration routes for testing middleware
$router->group(['middleware' => ['log']], function($router) {
    
    $router->get('/demo/middleware', function() {
        echo '<div class="container py-5">';
        echo '<h1 class="mb-4"><i class="bi bi-shield-check"></i> Middleware Demo</h1>';
        echo '<p class="lead">This route uses the log middleware. Check the logs!</p>';
        echo '<div class="row g-3">';
        echo '<div class="col-md-6"><a href="/demo/auth-test" class="btn btn-primary w-100">Test Auth Middleware</a></div>';
        echo '<div class="col-md-6"><a href="/demo/admin-test" class="btn btn-warning w-100">Test Admin Middleware</a></div>';
        echo '<div class="col-md-6"><a href="/demo/guest-test" class="btn btn-info w-100">Test Guest Middleware</a></div>';
        echo '<div class="col-md-6"><a href="/demo/throttle-test" class="btn btn-danger w-100">Test Rate Limiting</a></div>';
        echo '</div>';
        echo '</div>';
    });
    
});

// Test routes for different middleware
$router->get('/demo/auth-test', function() {
    echo '<div class="container py-5">';
    echo '<h1 class="text-success">✅ Auth Test Passed</h1>';
    echo '<p>This route requires authentication. You are logged in!</p>';
    echo '<a href="/demo/middleware" class="btn btn-secondary">Back to Demo</a>';
    echo '</div>';
}, ['auth']);

$router->get('/demo/admin-test', function() {
    echo '<div class="container py-5">';
    echo '<h1 class="text-warning">⚡ Admin Test Passed</h1>';
    echo '<p>This route requires admin privileges. You have admin access!</p>';
    echo '<a href="/demo/middleware" class="btn btn-secondary">Back to Demo</a>';
    echo '</div>';
}, ['admin']);

$router->get('/demo/guest-test', function() {
    echo '<div class="container py-5">';
    echo '<h1 class="text-info">👋 Guest Test</h1>';
    echo '<p>This route is only for non-authenticated users.</p>';
    echo '<a href="/demo/middleware" class="btn btn-secondary">Back to Demo</a>';
    echo '</div>';
}, ['guest']);

$router->get('/demo/throttle-test', function() {
    echo '<div class="container py-5">';
    echo '<h1 class="text-danger">🚦 Throttle Test</h1>';
    echo '<p>This route is rate-limited to 5 requests per minute. Try refreshing multiple times!</p>';
    echo '<a href="/demo/middleware" class="btn btn-secondary">Back to Demo</a>';
    echo '</div>';
}, ['throttle:5,1']);

// Template demo route (existing)
$router->get('/demo/template-syntax', function() {
    return view('demo/template-syntax', [
        'title' => 'Template Syntax Demo',
        'items' => ['Apple', 'Banana', 'Cherry'],
        'user' => ['name' => 'John Doe', 'email' => 'john@example.com']
    ]);
});

 