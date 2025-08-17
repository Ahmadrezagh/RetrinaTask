<?php $this->extends('auth'); ?>

<?php $this->section('title'); ?>
Login - Retrina Framework
<?php $this->endSection(); ?>

<?php $this->section('page-title'); ?>
Welcome Back
<?php $this->endSection(); ?>

<?php $this->section('page-description'); ?>
Please sign in to your account to continue
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<form action="<?= $this->url('/login') ?>" method="POST" novalidate>
    <?= $this->csrfField() ?>
    
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="bi bi-envelope me-1"></i>Email Address
        </label>
        <input type="email" class="form-control form-control-lg" id="email" name="email" 
               placeholder="Enter your email" value="<?= $this->old('email') ?>" required>
        <div class="invalid-feedback">
            Please provide a valid email address.
        </div>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="bi bi-lock me-1"></i>Password
        </label>
        <div class="input-group">
            <input type="password" class="form-control form-control-lg" id="password" name="password" 
                   placeholder="Enter your password" required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                <i class="bi bi-eye" id="toggleIcon"></i>
            </button>
        </div>
        <div class="invalid-feedback">
            Password is required.
        </div>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">
            Remember me
        </label>
    </div>
    
    <button type="submit" class="btn btn-gradient btn-lg w-100 mb-3">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
    
    <div class="text-center">
        <a href="#" class="text-decoration-none">
            <i class="bi bi-question-circle me-1"></i>Forgot your password?
        </a>
    </div>
</form>
<?php $this->endSection(); ?>

<?php $this->section('footer-links'); ?>
<p class="mb-0">
    Don't have an account? 
    <a href="#" class="text-decoration-none fw-bold">Sign up here</a>
</p>
<hr class="my-2">
<div class="d-flex justify-content-center gap-3">
    <a href="#" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-google"></i>
    </a>
    <a href="#" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-facebook"></i>
    </a>
    <a href="#" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-github"></i>
    </a>
</div>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent actual submission for demo
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Reset validation classes
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });
            
            let isValid = true;
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('email').classList.add('is-valid');
            }
            
            // Password validation
            if (!password || password.length < 6) {
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('password').classList.add('is-valid');
            }
            
            if (isValid) {
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing In...';
                submitBtn.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    alert('Demo login successful! (This is just a demo - no actual authentication)');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            }
        });
        
        // Real-time validation
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value.trim() && emailRegex.test(this.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.trim()) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        
        passwordInput.addEventListener('blur', function() {
            if (this.value.trim() && this.value.length >= 6) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.trim()) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
</script>
<?php $this->endSection(); ?> 