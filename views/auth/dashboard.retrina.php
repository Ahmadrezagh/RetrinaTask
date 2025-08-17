@extends('layouts.app')

@section('title', 'Dashboard - Retrina Framework')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Welcome Header -->
            <div class="bg-gradient-primary text-white p-5 rounded mb-4">
                <h1 class="display-4 mb-3">Welcome to your Dashboard, {{ $user['username'] }}! 🎉</h1>
                <p class="lead">You are successfully logged in as a {{ $user['role'] }}.</p>
            </div>
            
            <!-- Dashboard Cards -->
            <div class="row g-4">
                <!-- Profile Card -->
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
                
                <!-- Settings Card -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-gear display-4 text-success mb-3"></i>
                            <h5 class="card-title">Settings</h5>
                            <p class="card-text">Configure your preferences and application settings.</p>
                            <a href="/settings" class="btn btn-success">Open Settings</a>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Panel (if admin) -->
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
                @endif
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-5">
                <h3>Quick Actions</h3>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/" class="btn btn-outline-primary">
                        <i class="bi bi-house"></i> Home
                    </a>
                    <a href="/docs" class="btn btn-outline-info">
                        <i class="bi bi-book"></i> Documentation
                    </a>
                    <a href="/api" class="btn btn-outline-secondary">
                        <i class="bi bi-globe"></i> API Reference
                    </a>
                    <a href="/hello" class="btn btn-outline-success">
                        <i class="bi bi-hand-wave"></i> Hello Page
                    </a>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="mt-5">
                <h3>Recent Activity</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">System Status</h6>
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Framework running smoothly</li>
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Database connected</li>
                                    <li><i class="bi bi-check-circle text-success me-2"></i>CSRF protection active</li>
                                    <li><i class="bi bi-check-circle text-success me-2"></i>Session middleware enabled</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Quick Stats</h6>
                                <ul class="list-unstyled">
                                    <li><strong>User ID:</strong> {{ $user['id'] }}</li>
                                    <li><strong>Role:</strong> <span class="badge bg-{{ $user['role'] === 'admin' ? 'warning' : 'primary' }}">{{ ucfirst($user['role']) }}</span></li>
                                    <li><strong>Session:</strong> Active</li>
                                    <li><strong>Last Login:</strong> Now</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.btn {
    transition: all 0.2s ease-in-out;
}
</style>
@endsection 