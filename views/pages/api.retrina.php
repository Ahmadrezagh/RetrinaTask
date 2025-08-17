@extends('layouts.app')

@section('title', 'API Reference - Retrina Framework')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="bi bi-api display-3 text-danger"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3">API Reference</h1>
            <p class="lead text-muted">
                Complete reference for the Retrina Framework REST API
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <span class="badge bg-success fs-6">
                    <i class="bi bi-check-circle"></i> RESTful
                </span>
                <span class="badge bg-info fs-6">
                    <i class="bi bi-shield-check"></i> Authenticated
                </span>
                <span class="badge bg-warning fs-6">
                    <i class="bi bi-lightning"></i> Rate Limited
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Info -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3">
                                <h5 class="text-primary">Base URL</h5>
                                <code class="fs-6">http://localhost:8585/api</code>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h5 class="text-success">Format</h5>
                                <code class="fs-6">application/json</code>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h5 class="text-warning">Rate Limit</h5>
                                <code class="fs-6">60 requests/minute</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-5">
            <div class="position-sticky" style="top: 2rem;">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list"></i> Endpoints
                        </h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#authentication" class="list-group-item list-group-item-action">
                            <i class="bi bi-shield-lock"></i> Authentication
                        </a>
                        <a href="#users" class="list-group-item list-group-item-action">
                            <i class="bi bi-people"></i> Users
                        </a>
                        <a href="#admin" class="list-group-item list-group-item-action">
                            <i class="bi bi-shield-check"></i> Admin
                        </a>
                        <a href="#health" class="list-group-item list-group-item-action">
                            <i class="bi bi-heart-pulse"></i> Health Check
                        </a>
                        <a href="#errors" class="list-group-item list-group-item-action">
                            <i class="bi bi-exclamation-triangle"></i> Error Codes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Authentication -->
            <section id="authentication" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-shield-lock text-primary"></i> Authentication
                        </h2>
                        
                        <h4>Login</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-success fs-6">POST</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/auth/login</code>
                            </div>
                        </div>
                        
                        <h5>Request Body</h5>
                        <div class="bg-dark text-light p-3 rounded mb-3">
                            <code>
{<br>
&nbsp;&nbsp;"username": "admin",<br>
&nbsp;&nbsp;"password": "admin123"<br>
}
                            </code>
                        </div>
                        
                        <h5>Response</h5>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
{<br>
&nbsp;&nbsp;"status": "success",<br>
&nbsp;&nbsp;"message": "Login successful",<br>
&nbsp;&nbsp;"user": {<br>
&nbsp;&nbsp;&nbsp;&nbsp;"id": 1,<br>
&nbsp;&nbsp;&nbsp;&nbsp;"username": "admin",<br>
&nbsp;&nbsp;&nbsp;&nbsp;"role": "admin"<br>
&nbsp;&nbsp;}<br>
}
                            </code>
                        </div>

                        <h4>Logout</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-success fs-6">POST</span>
                                <span class="badge bg-warning text-dark fs-6">Auth</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/auth/logout</code>
                            </div>
                        </div>

                        <h4>Get Current User</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-primary fs-6">GET</span>
                                <span class="badge bg-warning text-dark fs-6">Auth</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/auth/user</code>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Users -->
            <section id="users" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-people text-info"></i> Users
                        </h2>
                        
                        <h4>Get All Users</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-primary fs-6">GET</span>
                                <span class="badge bg-warning text-dark fs-6">Auth</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/users</code>
                            </div>
                        </div>
                        
                        <h5>Response</h5>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
{<br>
&nbsp;&nbsp;"status": "success",<br>
&nbsp;&nbsp;"data": [<br>
&nbsp;&nbsp;&nbsp;&nbsp;{<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 1,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"username": "admin",<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"email": "admin@retrina.local",<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"first_name": "Admin",<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"last_name": "User",<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"is_active": true,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"created_at": "2025-01-17T20:00:00+00:00"<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br>
&nbsp;&nbsp;],<br>
&nbsp;&nbsp;"count": 3<br>
}
                            </code>
                        </div>

                        <h4>Get User by ID</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-primary fs-6">GET</span>
                                <span class="badge bg-warning text-dark fs-6">Auth</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/users/{id}</code>
                            </div>
                        </div>
                        
                        <h5>Example</h5>
                        <div class="bg-dark text-light p-3 rounded mb-3">
                            <code>GET /api/users/1</code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Admin Endpoints -->
            <section id="admin" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-shield-check text-warning"></i> Admin Endpoints
                        </h2>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Admin Only:</strong> These endpoints require admin privileges.
                        </div>
                        
                        <h4>Dashboard Stats</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-primary fs-6">GET</span>
                                <span class="badge bg-danger fs-6">Admin</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/admin</code>
                            </div>
                        </div>
                        
                        <h5>Response</h5>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
{<br>
&nbsp;&nbsp;"status": "success",<br>
&nbsp;&nbsp;"data": {<br>
&nbsp;&nbsp;&nbsp;&nbsp;"total_users": 3,<br>
&nbsp;&nbsp;&nbsp;&nbsp;"active_users": 3,<br>
&nbsp;&nbsp;&nbsp;&nbsp;"inactive_users": 0<br>
&nbsp;&nbsp;}<br>
}
                            </code>
                        </div>

                        <h4>Create User</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-success fs-6">POST</span>
                                <span class="badge bg-danger fs-6">Admin</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/admin/users</code>
                            </div>
                        </div>
                        
                        <h5>Request Body</h5>
                        <div class="bg-dark text-light p-3 rounded mb-3">
                            <code>
{<br>
&nbsp;&nbsp;"username": "newuser",<br>
&nbsp;&nbsp;"email": "user@example.com",<br>
&nbsp;&nbsp;"password": "securepassword"<br>
}
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Health Check -->
            <section id="health" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-heart-pulse text-success"></i> Health Check
                        </h2>
                        
                        <h4>API Health</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-primary fs-6">GET</span>
                                <span class="badge bg-success fs-6">Public</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api/health</code>
                            </div>
                        </div>
                        
                        <h5>Response</h5>
                        <div class="bg-light p-3 rounded mb-4">
                            <code>
{<br>
&nbsp;&nbsp;"status": "success",<br>
&nbsp;&nbsp;"message": "API is healthy",<br>
&nbsp;&nbsp;"timestamp": "2025-01-17T20:00:00+00:00",<br>
&nbsp;&nbsp;"uptime": "N/A"<br>
}
                            </code>
                        </div>

                        <h4>API Information</h4>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <span class="badge bg-primary fs-6">GET</span>
                                <span class="badge bg-success fs-6">Public</span>
                            </div>
                            <div class="col-md-9">
                                <code>/api</code>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Error Codes -->
            <section id="errors" class="mb-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="h3 mb-4">
                            <i class="bi bi-exclamation-triangle text-danger"></i> Error Codes
                        </h2>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>200</code></td>
                                        <td>OK</td>
                                        <td>Request successful</td>
                                    </tr>
                                    <tr>
                                        <td><code>201</code></td>
                                        <td>Created</td>
                                        <td>Resource created successfully</td>
                                    </tr>
                                    <tr>
                                        <td><code>400</code></td>
                                        <td>Bad Request</td>
                                        <td>Invalid request data</td>
                                    </tr>
                                    <tr>
                                        <td><code>401</code></td>
                                        <td>Unauthorized</td>
                                        <td>Authentication required</td>
                                    </tr>
                                    <tr>
                                        <td><code>403</code></td>
                                        <td>Forbidden</td>
                                        <td>Insufficient privileges</td>
                                    </tr>
                                    <tr>
                                        <td><code>404</code></td>
                                        <td>Not Found</td>
                                        <td>Resource not found</td>
                                    </tr>
                                    <tr>
                                        <td><code>419</code></td>
                                        <td>Page Expired</td>
                                        <td>CSRF token mismatch</td>
                                    </tr>
                                    <tr>
                                        <td><code>429</code></td>
                                        <td>Too Many Requests</td>
                                        <td>Rate limit exceeded</td>
                                    </tr>
                                    <tr>
                                        <td><code>500</code></td>
                                        <td>Internal Server Error</td>
                                        <td>Server error occurred</td>
                                    </tr>
                                    <tr>
                                        <td><code>503</code></td>
                                        <td>Service Unavailable</td>
                                        <td>Maintenance mode active</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h4>Error Response Format</h4>
                        <div class="bg-light p-3 rounded">
                            <code>
{<br>
&nbsp;&nbsp;"status": "error",<br>
&nbsp;&nbsp;"message": "Error description",<br>
&nbsp;&nbsp;"code": 404<br>
}
                            </code>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testing -->
            <div class="card border-0 bg-primary text-white">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-4">
                        <i class="bi bi-play-circle"></i> Test the API
                    </h3>
                    <p class="mb-4">
                        Try out the API endpoints directly from your browser or with tools like Postman.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="/api" class="btn btn-light btn-lg" target="_blank">
                            <i class="bi bi-api"></i> API Info
                        </a>
                        <a href="/api/health" class="btn btn-outline-light btn-lg" target="_blank">
                            <i class="bi bi-heart-pulse"></i> Health Check
                        </a>
                        <a href="/demo/middleware" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-shield-check"></i> Test Auth
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 