<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $this->csrf() ?>">
    <title><?= $this->yield('title', 'Authentication - Retrina Framework') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        .auth-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .auth-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .auth-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-shapes::before,
        .floating-shapes::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-shapes::before {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-shapes::after {
            bottom: 10%;
            right: 10%;
            animation-delay: 3s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
    
    <?= $this->yield('styles') ?>
</head>
<body class="auth-bg">
    <div class="floating-shapes"></div>
    
    <a href="<?= $this->url('/') ?>" class="back-link btn btn-light btn-sm rounded-pill">
        <i class="bi bi-arrow-left me-1"></i>Back to Home
    </a>
    
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">
                <div class="card auth-card">
                    <div class="card-body p-5">
                        <!-- Auth Header -->
                        <div class="text-center mb-4">
                            <div class="auth-logo">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h2 class="card-title mb-2">
                                <?= $this->yield('page-title', 'Authentication') ?>
                            </h2>
                            <p class="text-muted">
                                <?= $this->yield('page-description', 'Please sign in to continue') ?>
                            </p>
                        </div>

                        <!-- Flash Messages -->
                        <?php if (isset($_SESSION['flash_error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small><?= $this->escape($_SESSION['flash_error']) ?></small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php unset($_SESSION['flash_error']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['flash_success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <small><?= $this->escape($_SESSION['flash_success']) ?></small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php unset($_SESSION['flash_success']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Content -->
                        <?= $this->yield('content') ?>

                        <!-- Footer Links -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <?= $this->yield('footer-links') ?>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Info -->
                <div class="text-center mt-3">
                    <small class="text-white-50">
                        <i class="bi bi-shield-check me-1"></i>
                        Secured by Retrina Framework
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
                        submitBtn.disabled = true;
                    }
                });
            });

            // Enhanced form validation
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                });
            });
        });
    </script>

    <?= $this->yield('scripts') ?>
</body>
</html> 