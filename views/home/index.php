<?php $this->extends('app'); ?>

<?php $this->section('title'); ?>
<?= $title ?? 'Home - Retrina Framework' ?>
<?php $this->endSection(); ?>

<?php $this->section('page-title'); ?>
<?= $title ?? 'Welcome to Retrina Framework' ?>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="welcome-section">
    <!-- Hero Section -->
    <div class="jumbotron bg-gradient p-5 rounded-3 mb-5 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container-fluid py-3">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-rocket-takeoff me-3"></i>
                <?= $this->escape($message ?? 'A custom PHP MVC Framework') ?>
            </h1>
            <p class="col-md-8 mx-auto fs-5 mb-4">
                Build powerful web applications with our lightweight, feature-rich PHP framework that includes advanced routing, template engine, and security features.
            </p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="<?= $this->url('/about') ?>" class="btn btn-light btn-lg px-4 me-md-2">
                    <i class="bi bi-info-circle me-2"></i>Learn More
                </a>
                <a href="<?= $this->url('/user/demo') ?>" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-play-circle me-2"></i>View Demo
                </a>
            </div>
        </div>
    </div>
    
    <!-- Features Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm card-hover">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-diagram-3 text-white fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">MVC Architecture</h5>
                    <p class="card-text text-muted">Clean separation of concerns with Models, Views, and Controllers for maintainable and scalable code.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm card-hover">
                <div class="card-body text-center p-4">
                    <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-speedometer2 text-white fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">Advanced Router</h5>
                    <p class="card-text text-muted">Flexible routing system with parameter support, method spoofing, and closure routes for dynamic web applications.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm card-hover">
                <div class="card-body text-center p-4">
                    <div class="bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-palette text-white fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">View Engine</h5>
                    <p class="card-text text-muted">Powerful template system with layouts, sections, template inheritance, and Bootstrap integration.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm card-hover">
                <div class="card-body text-center p-4">
                    <div class="bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-gear text-white fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">Auto-loading</h5>
                    <p class="card-text text-muted">PSR-4 compliant autoloader for easy class loading without manual includes or complex configurations.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm card-hover">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-database text-white fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">Database Ready</h5>
                    <p class="card-text text-muted">PDO-based database abstraction layer with prepared statements and ORM-like features for secure data handling.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm card-hover">
                <div class="card-body text-center p-4">
                    <div class="bg-secondary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-check text-white fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold">Security</h5>
                    <p class="card-text text-muted">Built-in CSRF protection, XSS prevention, and secure session handling to keep your applications safe.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Section -->
    <div class="row text-center mb-5">
        <div class="col-6 col-md-3">
            <div class="bg-light rounded-3 p-3">
                <h3 class="text-primary fw-bold mb-1">8+</h3>
                <small class="text-muted">Core Features</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-light rounded-3 p-3">
                <h3 class="text-success fw-bold mb-1">100%</h3>
                <small class="text-muted">Pure PHP</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-light rounded-3 p-3">
                <h3 class="text-warning fw-bold mb-1">0</h3>
                <small class="text-muted">Dependencies</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-light rounded-3 p-3">
                <h3 class="text-info fw-bold mb-1">∞</h3>
                <small class="text-muted">Possibilities</small>
            </div>
        </div>
    </div>
    
    <?php if (isset($data) && !empty($data)): ?>
    <!-- Debug Data Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="bi bi-code-square me-2"></i>
                Data Passed to View
            </h5>
        </div>
        <div class="card-body">
            <pre class="bg-dark text-light p-3 rounded overflow-auto" style="max-height: 300px;"><code><?= $this->escape(print_r($data, true)) ?></code></pre>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Call to Action -->
    <div class="text-center">
        <div class="card border-0 shadow">
            <div class="card-body p-5" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                <h3 class="mb-3">Ready to Start Building?</h3>
                <p class="text-muted mb-4">Explore the framework features and start building your next amazing web application!</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= $this->url('/user/123') ?>" class="btn btn-primary btn-lg px-4 me-md-2">
                        <i class="bi bi-person me-2"></i>View User Demo
                    </a>
                    <a href="<?= $this->url('/api') ?>" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-cloud me-2"></i>Test API
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('styles'); ?>
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
</style>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Retrina Framework loaded successfully with Bootstrap!');
        
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
                showToast('Feature card clicked!', 'info');
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
<?php $this->endSection(); ?> 