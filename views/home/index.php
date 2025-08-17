<?php $this->extends('app'); ?>

<?php $this->section('title'); ?>
<?= $title ?? 'Home - Retrina Framework' ?>
<?php $this->endSection(); ?>

<?php $this->section('page-title'); ?>
<?= $title ?? 'Welcome to Retrina Framework' ?>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="welcome-section">
    <h2><?= $this->escape($message ?? 'A custom PHP MVC Framework') ?></h2>
    
    <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin: 2rem 0;">
        <div class="feature-card" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea;">
            <h3 style="color: #333; margin-bottom: 1rem;">✅ MVC Architecture</h3>
            <p style="color: #666;">Clean separation of concerns with Models, Views, and Controllers for maintainable code.</p>
        </div>
        
        <div class="feature-card" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #28a745;">
            <h3 style="color: #333; margin-bottom: 1rem;">🚀 Advanced Router</h3>
            <p style="color: #666;">Flexible routing system with parameter support, method spoofing, and closure routes.</p>
        </div>
        
        <div class="feature-card" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #ffc107;">
            <h3 style="color: #333; margin-bottom: 1rem;">🎨 View Engine</h3>
            <p style="color: #666;">Powerful template system with layouts, sections, and template inheritance.</p>
        </div>
        
        <div class="feature-card" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #dc3545;">
            <h3 style="color: #333; margin-bottom: 1rem;">🔧 Auto-loading</h3>
            <p style="color: #666;">PSR-4 compliant autoloader for easy class loading without manual includes.</p>
        </div>
        
        <div class="feature-card" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #6f42c1;">
            <h3 style="color: #333; margin-bottom: 1rem;">💾 Database Ready</h3>
            <p style="color: #666;">PDO-based database abstraction layer with prepared statements and ORM-like features.</p>
        </div>
        
        <div class="feature-card" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #17a2b8;">
            <h3 style="color: #333; margin-bottom: 1rem;">🛡️ Security</h3>
            <p style="color: #666;">Built-in CSRF protection, XSS prevention, and secure session handling.</p>
        </div>
    </div>
    
    <?php if (isset($data) && !empty($data)): ?>
    <div class="data-showcase" style="background: #e9ecef; padding: 1.5rem; border-radius: 8px; margin-top: 2rem;">
        <h3 style="color: #333; margin-bottom: 1rem;">📊 Data Passed to View:</h3>
        <pre style="background: white; padding: 1rem; border-radius: 4px; overflow-x: auto; font-size: 0.9rem;"><?= $this->escape(print_r($data, true)) ?></pre>
    </div>
    <?php endif; ?>
    
    <div class="cta-section" style="text-align: center; margin-top: 3rem; padding: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; color: white;">
        <h3 style="margin-bottom: 1rem;">Ready to Start Building?</h3>
        <p style="margin-bottom: 1.5rem; opacity: 0.9;">Explore the framework features and start building your application!</p>
        <a href="<?= $this->url('/about') ?>" class="btn" style="background: white; color: #667eea; margin-right: 1rem;">Learn More</a>
        <a href="<?= $this->url('/user/demo') ?>" class="btn" style="background: rgba(255,255,255,0.2); color: white;">View Demo</a>
    </div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('styles'); ?>
<style>
    .welcome-section h2 {
        text-align: center;
        color: #333;
        margin-bottom: 2rem;
        font-size: 1.8rem;
        font-weight: 300;
    }
    
    .feature-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .cta-section .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    console.log('Retrina Framework loaded successfully!');
    
    // Add some interactive functionality
    document.addEventListener('DOMContentLoaded', function() {
        const featureCards = document.querySelectorAll('.feature-card');
        
        featureCards.forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    });
</script>
<?php $this->endSection(); ?> 