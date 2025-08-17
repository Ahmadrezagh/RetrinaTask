@extends('layouts.app')

@section('title', 'Register - Retrina Framework')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-person-plus display-4 text-success"></i>
                        </div>
                        <h1 class="h3 mb-1">Create Account</h1>
                        <p class="text-muted">Join the Retrina Framework community</p>
                    </div>

                    <!-- Flash Messages -->
                    @if(isset($_SESSION['flash_error']))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! $_SESSION['flash_error'] !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @php unset($_SESSION['flash_error']); @endphp
                    @endif

                    @if(isset($_SESSION['flash_success']))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! $_SESSION['flash_success'] !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @php unset($_SESSION['flash_success']); @endphp
                    @endif

                    <!-- Registration Form -->
                    <form method="POST" action="/register" id="registerForm" novalidate>
                        @csrf
                        
                        <!-- Name Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ $_SESSION['old_input']['first_name'] ?? '' }}"
                                           placeholder="John" required maxlength="50">
                                </div>
                                <div class="invalid-feedback">
                                    Please enter your first name.
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ $_SESSION['old_input']['last_name'] ?? '' }}"
                                           placeholder="Doe" required maxlength="50">
                                </div>
                                <div class="invalid-feedback">
                                    Please enter your last name.
                                </div>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-at"></i>
                                </span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="{{ $_SESSION['old_input']['username'] ?? '' }}"
                                       placeholder="johndoe" required minlength="3" maxlength="30"
                                       pattern="[a-zA-Z0-9_-]+" title="Username can only contain letters, numbers, hyphens, and underscores">
                            </div>
                            <div class="form-text">3-30 characters. Letters, numbers, hyphens, and underscores only.</div>
                            <div class="invalid-feedback">
                                Please choose a valid username.
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $_SESSION['old_input']['email'] ?? '' }}"
                                       placeholder="john@example.com" required maxlength="100">
                            </div>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter your password" required minlength="6" maxlength="255">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Minimum 6 characters.</div>
                            <div class="invalid-feedback">
                                Password must be at least 6 characters.
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                       placeholder="Confirm your password" required minlength="6" maxlength="255">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Passwords do not match.
                            </div>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> 
                                and <a href="#" class="text-decoration-none">Privacy Policy</a> 
                                <span class="text-danger">*</span>
                            </label>
                            <div class="invalid-feedback">
                                You must agree to the terms and conditions.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-person-plus"></i> Create Account
                            </button>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">
                            Already have an account? 
                            <a href="/login" class="text-decoration-none fw-medium">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

    // Password visibility toggles
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });

    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirm.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });

    // Real-time password confirmation validation
    passwordConfirm.addEventListener('input', function() {
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('Passwords do not match');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    });

    // Form validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Username validation
    document.getElementById('username').addEventListener('input', function() {
        const username = this.value;
        const pattern = /^[a-zA-Z0-9_-]+$/;
        
        if (username && !pattern.test(username)) {
            this.setCustomValidity('Username can only contain letters, numbers, hyphens, and underscores');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<style>
.was-validated .form-control:valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.94-.94 1.93 1.93 3.53-3.53.94.94L4.16 9.16z'/%3e%3c/svg%3e");
}

.was-validated .form-control:invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 2.4 2.8m0-2.8L5.8 7.4'/%3e%3c/svg%3e");
}

.card {
    border: none;
    border-radius: 15px;
}

.btn {
    border-radius: 8px;
}

.form-control {
    border-radius: 8px;
}

.input-group-text {
    border-radius: 8px 0 0 8px;
}

.input-group .form-control {
    border-radius: 0 8px 8px 0;
}

.input-group .btn {
    border-radius: 0 8px 8px 0;
}
</style>

@php
// Clear old input after displaying
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}
@endphp
@endsection 