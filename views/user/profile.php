<?php $this->extends('app'); ?>

<?php $this->section('title'); ?>
User Profile - <?= $this->escape($user_id ?? 'Unknown') ?> - Retrina Framework
<?php $this->endSection(); ?>

<?php $this->section('page-title'); ?>
User Profile
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="profile-section">
    <!-- Profile Header -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="gradient-bg text-white p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill fs-1 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <h2 class="mb-1">User #<?= $this->escape($user_id ?? 'N/A') ?></h2>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-calendar-check me-1"></i>
                        Profile information and details
                    </p>
                    <small class="opacity-50">
                        <i class="bi bi-clock me-1"></i>
                        Last updated: <?= date('M d, Y') ?>
                    </small>
                </div>
                <div class="col-auto">
                    <span class="badge bg-success fs-6">
                        <i class="bi bi-check-circle me-1"></i>Active
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block">User ID</small>
                                <strong class="text-dark"><?= $this->escape($user_id ?? 'Not provided') ?></strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block">Route Parameter</small>
                                <strong class="text-dark"><?= $this->escape($route_param ?? 'Successfully captured from URL') ?></strong>
                                <i class="bi bi-check-circle-fill text-success ms-2"></i>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block">Framework</small>
                                <strong class="text-dark">Retrina Framework</strong>
                                <span class="badge bg-primary ms-2">v1.0</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block">View Engine</small>
                                <strong class="text-dark">Template with Layout Support</strong>
                                <i class="bi bi-bootstrap-fill text-primary ms-2" title="Powered by Bootstrap"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2 text-warning"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 mb-4">
                        <button onclick="editProfile()" class="btn btn-success btn-lg">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </button>
                        <button onclick="viewSettings()" class="btn btn-info btn-lg">
                            <i class="bi bi-gear me-2"></i>Account Settings
                        </button>
                        <button onclick="changePassword()" class="btn btn-warning btn-lg">
                            <i class="bi bi-key me-2"></i>Change Password
                        </button>
                        <button onclick="deleteAccount()" class="btn btn-danger btn-lg">
                            <i class="bi bi-trash me-2"></i>Delete Account
                        </button>
                    </div>
                    
                    <!-- Demo Form -->
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-shield-lock me-2"></i>
                                Demo Form with CSRF Protection
                            </h6>
                            <form action="<?= $this->url('/user/' . ($user_id ?? '1') . '/update') ?>" method="POST">
                                <?= $this->csrfField() ?>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person me-1"></i>Name
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control" 
                                           placeholder="Enter your name" 
                                           value="<?= $this->old('name', 'Demo User') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                           placeholder="Enter your email" 
                                           value="<?= $this->old('email', 'demo@example.com') ?>" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-circle me-2"></i>Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Features -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-star-fill me-2"></i>
                        Framework Features Showcase
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <div class="text-center p-3">
                                <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-layout-text-window text-white"></i>
                                </div>
                                <h6 class="mb-1">Layout Inheritance</h6>
                                <small class="text-muted">Extends app layout</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="text-center p-3">
                                <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-code-square text-white"></i>
                                </div>
                                <h6 class="mb-1">Section Management</h6>
                                <small class="text-muted">Dynamic content blocks</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="text-center p-3">
                                <div class="bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-box-arrow-in-right text-white"></i>
                                </div>
                                <h6 class="mb-1">Parameter Passing</h6>
                                <small class="text-muted">Route parameters</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="text-center p-3">
                                <div class="bg-danger bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-shield-check text-white"></i>
                                </div>
                                <h6 class="mb-1">XSS Protection</h6>
                                <small class="text-muted">Auto-escaping</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (isset($data) && !empty($data)): ?>
    <!-- Debug Information -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bug me-2"></i>
                        Debug Information
                    </h5>
                </div>
                <div class="card-body p-0">
                    <pre class="mb-0 p-3 bg-dark text-light overflow-auto" style="max-height: 300px;"><code><?= $this->escape(print_r($data, true)) ?></code></pre>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $this->endSection(); ?>

<?php $this->section('styles'); ?>
<style>
    .profile-section {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .card {
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    @media (max-width: 768px) {
        .profile-section .col-lg-6 {
            margin-bottom: 1rem;
        }
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    function editProfile() {
        showToast('Edit Profile functionality would be implemented here!', 'info');
    }
    
    function viewSettings() {
        showToast('Account Settings functionality would be implemented here!', 'info');
    }
    
    function changePassword() {
        showToast('Change Password functionality would be implemented here!', 'warning');
    }
    
    function deleteAccount() {
        // Using Bootstrap modal would be better, but for demo we'll use confirm
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            showToast('Delete Account functionality would be implemented here!', 'danger');
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('👤 User Profile page loaded with Bootstrap!');
        
        // Enhanced form validation with Bootstrap classes
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent actual submission for demo
                
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                
                // Reset validation classes
                const inputs = form.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });
                
                let isValid = true;
                
                if (!name) {
                    document.getElementById('name').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('name').classList.add('is-valid');
                }
                
                if (!email || !email.includes('@')) {
                    document.getElementById('email').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('email').classList.add('is-valid');
                }
                
                if (isValid) {
                    showToast('Form validation passed! (Demo mode - not actually submitted)', 'success');
                    console.log('Form data:', { name, email });
                } else {
                    showToast('Please fill in all required fields correctly.', 'danger');
                }
            });
            
            // Real-time validation
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.checkValidity() && this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value.trim()) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                });
            });
        }
        
        // Add tooltips to action buttons
        const actionButtons = document.querySelectorAll('.btn[onclick]');
        actionButtons.forEach(btn => {
            btn.setAttribute('data-bs-toggle', 'tooltip');
            btn.setAttribute('title', 'Click to ' + btn.textContent.trim().toLowerCase());
        });
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $this->endSection(); ?> 