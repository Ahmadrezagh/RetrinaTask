@extends('layouts.app')

@section('title', 'Retrina Framework - Home')

@section('content')
<div class="hero-section bg-gradient-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                @if($isAuthenticated)
                    <h1 class="display-4 fw-bold mb-4">Welcome back, {{ $user['username'] }}! 👋</h1>
                    <p class="lead mb-4">
                        You're successfully logged in to your Retrina Framework dashboard. 
                        Explore the features and manage your account from here.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="/dashboard" class="btn btn-light btn-lg">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="/profile" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-person"></i> Profile
                        </a>
                        @if($user['role'] === 'admin')
                            <a href="/admin" class="btn btn-warning btn-lg">
                                <i class="bi bi-shield-check"></i> Admin Panel
                            </a>
                        @endif
                    </div>
                @else
                    <h1 class="display-4 fw-bold mb-4">Welcome to Retrina Framework 🚀</h1>
                    <p class="lead mb-4">
                        A modern, powerful PHP framework with Laravel-like features including ORM, 
                        middleware system, template engine, CLI tools, and comprehensive database support.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="/login" class="btn btn-light btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                        <a href="/register" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-person-plus"></i> Register
                        </a>
                        <a href="/docs" class="btn btn-secondary btn-lg">
                            <i class="bi bi-book"></i> Documentation
                        </a>
                    </div>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                <div class="feature-preview p-4">
                    @if($isAuthenticated)
                        <div class="user-stats">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat-card bg-white bg-opacity-10 rounded p-3">
                                        <h3 class="h4 mb-1">{{ $user['role'] === 'admin' ? 'Admin' : 'Member' }}</h3>
                                        <small>Account Type</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card bg-white bg-opacity-10 rounded p-3">
                                        <h3 class="h4 mb-1">Active</h3>
                                        <small>Status</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="framework-logo">
                            <i class="bi bi-code-slash display-1 text-white-50"></i>
                            <h3 class="mt-3">Modern PHP Framework</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($isAuthenticated)
    <!-- Authenticated User Dashboard Section -->
    <div class="container mb-5">
        <div class="row">
            <div class="col-12">
                <h2 class="h3 mb-4">Quick Actions</h2>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person-circle display-4 text-primary mb-3"></i>
                        <h5 class="card-title">Profile</h5>
                        <p class="card-text">Manage your personal information and account settings.</p>
                        <a href="/profile" class="btn btn-primary">View Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-speedometer2 display-4 text-success mb-3"></i>
                        <h5 class="card-title">Dashboard</h5>
                        <p class="card-text">Access your personal dashboard with analytics and tools.</p>
                        <a href="/dashboard" class="btn btn-success">Open Dashboard</a>
                    </div>
                </div>
            </div>
            @if($user['role'] === 'admin')
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check display-4 text-warning mb-3"></i>
                            <h5 class="card-title">Admin Panel</h5>
                            <p class="card-text">Manage users, settings, and system administration.</p>
                            <a href="/admin" class="btn btn-warning">Admin Panel</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-gear display-4 text-info mb-3"></i>
                            <h5 class="card-title">Settings</h5>
                            <p class="card-text">Configure your preferences and account settings.</p>
                            <a href="/settings" class="btn btn-info">Settings</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    <!-- Public Features Section -->
    <div class="container mb-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="h3 mb-3">Framework Features</h2>
                <p class="text-muted">Everything you need to build modern web applications</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-database display-4 text-primary mb-3"></i>
                        <h5 class="card-title">Laravel-like ORM</h5>
                        <p class="card-text">Powerful database wrapper with fluent query builder and migrations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check display-4 text-success mb-3"></i>
                        <h5 class="card-title">Middleware System</h5>
                        <p class="card-text">Authentication, authorization, rate limiting, and CORS support.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-code-slash display-4 text-info mb-3"></i>
                        <h5 class="card-title">Template Engine</h5>
                        <p class="card-text">Blade-like template syntax with caching and component support.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-terminal display-4 text-warning mb-3"></i>
                        <h5 class="card-title">CLI Tools</h5>
                        <p class="card-text">Artisan-like command line tools for generating code and managing your app.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-api display-4 text-danger mb-3"></i>
                        <h5 class="card-title">API Ready</h5>
                        <p class="card-text">Built-in API support with JSON responses and RESTful routing.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-globe display-4 text-secondary mb-3"></i>
                        <h5 class="card-title">Cross-Platform</h5>
                        <p class="card-text">Works with MySQL, PostgreSQL, and SQLite databases seamlessly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="bg-light py-5">
        <div class="container text-center">
            <h2 class="h3 mb-3">Ready to get started?</h2>
            <p class="text-muted mb-4">Join our community and start building amazing applications today.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="/register" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Create Account
                </a>
                <a href="/docs" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-book"></i> Read Documentation
                </a>
                <a href="/api" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-api"></i> API Reference
                </a>
            </div>
        </div>
    </div>
@endif
@endsection 