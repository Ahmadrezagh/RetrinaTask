@extends('layouts.app')

@section('title', 'About - Retrina Framework')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="bi bi-info-circle display-3 text-primary"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3">About Retrina Framework</h1>
            <p class="lead text-muted">
                A modern, powerful PHP framework designed for developers who want Laravel-like features 
                with simplicity and performance in mind.
            </p>
        </div>
    </div>

    <!-- Framework Story -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h2 class="h3 mb-4">
                        <i class="bi bi-lightbulb text-warning"></i> The Story Behind Retrina
                    </h2>
                    <p class="mb-4">
                        Retrina Framework was born from the need for a lightweight yet powerful PHP framework 
                        that combines the elegance of Laravel with the simplicity of modern development practices. 
                        Built with developers in mind, it provides all the essential tools needed to create 
                        robust web applications without the complexity.
                    </p>
                    <p class="mb-4">
                        Whether you're building a simple website, a complex web application, or a REST API, 
                        Retrina provides the foundation you need with features like ORM, middleware system, 
                        template engine, and CLI tools.
                    </p>
                    <p class="text-muted">
                        <em>"Great frameworks are not about having every feature, but having the right features 
                        implemented elegantly."</em>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Features -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="h3 text-center mb-5">Core Features</h2>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="feature-card h-100 p-4 border rounded shadow-sm">
                <div class="text-center mb-3">
                    <i class="bi bi-database display-4 text-primary"></i>
                </div>
                <h4 class="h5 mb-3">Laravel-like ORM</h4>
                <p class="text-muted mb-0">
                    Powerful database wrapper with fluent query builder, migrations, 
                    and support for MySQL, PostgreSQL, and SQLite.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-card h-100 p-4 border rounded shadow-sm">
                <div class="text-center mb-3">
                    <i class="bi bi-shield-check display-4 text-success"></i>
                </div>
                <h4 class="h5 mb-3">Middleware System</h4>
                <p class="text-muted mb-0">
                    Comprehensive middleware for authentication, authorization, 
                    rate limiting, CORS, CSRF protection, and more.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-card h-100 p-4 border rounded shadow-sm">
                <div class="text-center mb-3">
                    <i class="bi bi-code-slash display-4 text-info"></i>
                </div>
                <h4 class="h5 mb-3">Template Engine</h4>
                <p class="text-muted mb-0">
                    Blade-like template syntax with caching, components, 
                    and all the directives you love.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-card h-100 p-4 border rounded shadow-sm">
                <div class="text-center mb-3">
                    <i class="bi bi-terminal display-4 text-warning"></i>
                </div>
                <h4 class="h5 mb-3">CLI Tools</h4>
                <p class="text-muted mb-0">
                    Artisan-like command line tools for generating controllers, 
                    models, migrations, and managing your application.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-card h-100 p-4 border rounded shadow-sm">
                <div class="text-center mb-3">
                    <i class="bi bi-api display-4 text-danger"></i>
                </div>
                <h4 class="h5 mb-3">API Ready</h4>
                <p class="text-muted mb-0">
                    Built-in API support with JSON responses, RESTful routing, 
                    and comprehensive middleware for modern APIs.
                </p>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="feature-card h-100 p-4 border rounded shadow-sm">
                <div class="text-center mb-3">
                    <i class="bi bi-speedometer2 display-4 text-secondary"></i>
                </div>
                <h4 class="h5 mb-3">Performance</h4>
                <p class="text-muted mb-0">
                    Optimized for performance with intelligent caching, 
                    efficient routing, and minimal overhead.
                </p>
            </div>
        </div>
    </div>

    <!-- Technical Specifications -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 bg-light">
                <div class="card-body p-5">
                    <h2 class="h3 mb-4">
                        <i class="bi bi-gear text-secondary"></i> Technical Specifications
                    </h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Requirements</h5>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success"></i> PHP 8.0 or higher</li>
                                <li><i class="bi bi-check-circle text-success"></i> PDO extension</li>
                                <li><i class="bi bi-check-circle text-success"></i> JSON extension</li>
                                <li><i class="bi bi-check-circle text-success"></i> mbstring extension</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="mb-3">Database Support</h5>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-database text-primary"></i> MySQL 5.7+</li>
                                <li><i class="bi bi-database text-primary"></i> PostgreSQL 10+</li>
                                <li><i class="bi bi-database text-primary"></i> SQLite 3.8+</li>
                                <li><i class="bi bi-database text-primary"></i> MariaDB 10.2+</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Philosophy -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="h3 mb-4">
                <i class="bi bi-heart text-danger"></i> Our Philosophy
            </h2>
            <p class="lead mb-4">
                We believe that great software should be both powerful and simple. 
                Retrina Framework embodies this philosophy by providing enterprise-level 
                features in an accessible, developer-friendly package.
            </p>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="bi bi-lightning display-5 text-warning mb-3"></i>
                        <h5>Fast Development</h5>
                        <p class="text-muted small">Get up and running quickly with sensible defaults and powerful generators.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="bi bi-puzzle display-5 text-info mb-3"></i>
                        <h5>Modular Design</h5>
                        <p class="text-muted small">Use only what you need. Every component is designed to work independently.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="bi bi-people display-5 text-success mb-3"></i>
                        <h5>Developer First</h5>
                        <p class="text-muted small">Built by developers, for developers. Great DX is our top priority.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-12 text-center">
            <div class="p-5 bg-gradient-primary text-white rounded">
                <h2 class="h3 mb-3">Ready to Start Building?</h2>
                <p class="mb-4">
                    Join thousands of developers who have chosen Retrina Framework 
                    for their next project.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="/docs" class="btn btn-light btn-lg">
                        <i class="bi bi-book"></i> Read Documentation
                    </a>
                    <a href="/demo/template-syntax" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-play-circle"></i> View Demo
                    </a>
                    <a href="https://github.com" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-github"></i> View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 