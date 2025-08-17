<?php
// Start session to check authentication for navigation
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$user = $isAuthenticated ? [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['username'] ?? 'User',
    'role' => $_SESSION['user_role'] ?? 'user'
] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Retrina Framework')</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            transform: translateY(-1px);
        }
        .hero-section {
            min-height: 60vh;
        }
        .min-vh-50 {
            min-height: 50vh;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
        }
        .dropdown-toggle::after {
            display: none;
        }
    </style>
    
    @yield('head')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-code-slash"></i> Retrina
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">
                            <i class="bi bi-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/docs">
                            <i class="bi bi-book"></i> Docs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/api">
                            <i class="bi bi-api"></i> API
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/hello">
                            <i class="bi bi-hand-wave"></i> Hello
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @if($isAuthenticated)
                        <!-- Authenticated User Menu -->
                        @if($user['role'] === 'admin')
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="/admin">
                                    <i class="bi bi-shield-check"></i> Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    {{ strtoupper(substr($user['username'], 0, 1)) }}
                                </div>
                                {{ $user['username'] }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">
                                        <i class="bi bi-person-circle"></i> Account
                                    </h6>
                                </li>
                                <li><a class="dropdown-item" href="/profile">
                                    <i class="bi bi-person"></i> Profile
                                </a></li>
                                <li><a class="dropdown-item" href="/settings">
                                    <i class="bi bi-gear"></i> Settings
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/logout" class="d-inline" id="logoutForm">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirmLogout(event)">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest User Menu -->
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-code-slash"></i> Retrina Framework</h5>
                    <p class="text-muted">A modern PHP framework with Laravel-like features.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/docs" class="text-muted">Documentation</a></li>
                        <li><a href="/api" class="text-muted">API Reference</a></li>
                        <li><a href="/demo/template-syntax" class="text-muted">Demo</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Resources</h6>
                    <ul class="list-unstyled">
                        <li><a href="https://github.com" class="text-muted">GitHub</a></li>
                        <li><a href="/contact" class="text-muted">Contact</a></li>
                        <li><a href="/about" class="text-muted">About</a></li>
                    </ul>
                </div>
            </div>
            <hr class="text-muted">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-muted mb-0">© {{ date('Y') }} Retrina Framework. Built with ❤️ and PHP.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Logout Handler -->
    <script>
    // Add click handler for logout button
    document.addEventListener('DOMContentLoaded', function() {
        const logoutForm = document.querySelector('form[action="/logout"]');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show confirmation
                if (confirm('Are you sure you want to logout?')) {
                    // Try to submit the form with proper token
                    const formData = new FormData(this);
                    
                    fetch('/logout', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (response.redirected || response.ok) {
                            window.location.href = '/';
                        } else {
                            // Fallback to GET logout if POST fails
                            window.location.href = '/logout';
                        }
                    })
                    .catch(error => {
                        console.error('Logout error:', error);
                        // Fallback to GET logout
                        window.location.href = '/logout';
                    });
                }
            });
        }
    });
    </script>
    
    @yield('scripts')
</body>
</html> 