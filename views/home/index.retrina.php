@extends('app')

@section('title')
{{ $title ?? 'Home - Retrina Framework' }}
@endsection

@section('page-title')
{{ $title ?? 'Welcome to Retrina Framework' }}
@endsection

@section('content')
<div class="welcome-section">
    {{-- Hero Section --}}
    <div class="jumbotron bg-gradient p-5 rounded-3 mb-5 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container-fluid py-3">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-rocket-takeoff me-3"></i>
                {{{ $message ?? 'A custom PHP MVC Framework' }}}
            </h1>
            <p class="col-md-8 mx-auto fs-5 mb-4">
                Build powerful web applications with our lightweight, feature-rich PHP framework that now includes 
                <strong>beautiful template syntax</strong> similar to Laravel Blade!
            </p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="@url('/about')" class="btn btn-light btn-lg px-4 me-md-2">
                    <i class="bi bi-info-circle me-2"></i>Learn More
                </a>
                <a href="@url('/demo/template-syntax')" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-palette me-2"></i>Template Demo
                </a>
            </div>
        </div>
    </div>
    
    {{-- Features Grid --}}
    <div class="row g-4 mb-5">
        @foreach([
            ['icon' => 'diagram-3', 'color' => 'primary', 'title' => 'MVC Architecture', 'desc' => 'Clean separation of concerns with Models, Views, and Controllers for maintainable and scalable code.'],
            ['icon' => 'speedometer2', 'color' => 'success', 'title' => 'Advanced Router', 'desc' => 'Flexible routing system with parameter support, method spoofing, and closure routes for dynamic web applications.'],
            ['icon' => 'palette', 'color' => 'warning', 'title' => 'Template Engine', 'desc' => 'Beautiful template syntax with layouts, sections, inheritance, and Bootstrap integration - just like Blade!'],
            ['icon' => 'gear', 'color' => 'danger', 'title' => 'Auto-loading', 'desc' => 'PSR-4 compliant autoloader for easy class loading without manual includes or complex configurations.'],
            ['icon' => 'database', 'color' => 'info', 'title' => 'Database Ready', 'desc' => 'PDO-based database abstraction layer with prepared statements and ORM-like features for secure data handling.'],
            ['icon' => 'shield-check', 'color' => 'secondary', 'title' => 'Security', 'desc' => 'Built-in CSRF protection, XSS prevention, and secure session handling to keep your applications safe.']
        ] as $feature)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                        <div class="bg-{{ $feature['color'] }} bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-{{ $feature['icon'] }} text-white fs-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">{{ $feature['title'] }}</h5>
                        <p class="card-text text-muted">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    {{-- New Template Syntax Showcase --}}
    @card('✨ New Template Syntax Features')
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary">Before (Old PHP syntax):</h6>
                <pre class="bg-light p-3 rounded"><code>&lt;?php $this->section('title'); ?&gt;
My Page Title
&lt;?php $this->endSection(); ?&gt;

&lt;?php if(isset($user)): ?&gt;
    Hello &lt;?php echo $user; ?&gt;!
&lt;?php endif; ?&gt;</code></pre>
            </div>
            <div class="col-md-6">
                <h6 class="text-success">After (Beautiful template syntax):</h6>
                <pre class="bg-success text-white p-3 rounded"><code>@section('title')
My Page Title
@endsection

@if(isset($user))
    Hello {{ $user }}!
@endif</code></pre>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="@url('/demo/template-syntax')" class="btn btn-primary">
                <i class="bi bi-eye me-2"></i>See Full Template Demo
            </a>
        </div>
    @endcard
    
    {{-- Statistics Section --}}
    <div class="row text-center mb-5">
        @foreach([
            ['number' => '20+', 'color' => 'primary', 'label' => 'Template Directives'],
            ['number' => '100%', 'color' => 'success', 'label' => 'Pure PHP'],
            ['number' => '0', 'color' => 'warning', 'label' => 'Dependencies'],
            ['number' => '∞', 'color' => 'info', 'label' => 'Possibilities']
        ] as $stat)
            <div class="col-6 col-md-3">
                <div class="bg-light rounded-3 p-3">
                    <h3 class="text-{{ $stat['color'] }} fw-bold mb-1">{{ $stat['number'] }}</h3>
                    <small class="text-muted">{{ $stat['label'] }}</small>
                </div>
            </div>
        @endforeach
    </div>
    
    @isset($data)
    {{-- Debug Data Section --}}
    @card('📊 Data Passed to View')
        <pre class="bg-dark text-light p-3 rounded overflow-auto" style="max-height: 300px;"><code>{{{ print_r($data, true) }}}</code></pre>
    @endcard
    @endisset
    
    {{-- Call to Action --}}
    <div class="text-center">
        <div class="card border-0 shadow">
            <div class="card-body p-5" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                <h3 class="mb-3">Ready to Start Building?</h3>
                <p class="text-muted mb-4">
                    Explore the framework features and start building your next amazing web application 
                    with beautiful template syntax!
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="@url('/user/123')" class="btn btn-primary btn-lg px-4 me-md-2">
                        <i class="bi bi-person me-2"></i>View User Demo
                    </a>
                    <a href="@url('/demo/template-syntax')" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-palette me-2"></i>Template Syntax
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .welcome-section {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
    }
    
    .jumbotron {
        position: relative;
        overflow: hidden;
    }
    
    .jumbotron::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    code {
        font-size: 0.85em;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Retrina Framework loaded successfully with new template syntax!');
        
        // Add click effects to feature cards
        const featureCards = document.querySelectorAll('.card-hover');
        featureCards.forEach(card => {
            card.addEventListener('click', function() {
                // Create ripple effect
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // Show toast notification
                showToast('Feature card clicked! Now with template syntax 🎨', 'info');
            });
        });
        
        // Initialize tooltips if any
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Animate statistics on scroll
        const observeStats = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('h3');
                    statNumbers.forEach(num => {
                        num.style.animation = 'pulse 0.6s ease-in-out';
                    });
                }
            });
        });
        
        const statsSection = document.querySelector('.row.text-center');
        if (statsSection) {
            observeStats.observe(statsSection);
        }
    });
</script>
@endsection 