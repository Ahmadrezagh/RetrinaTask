@extends('layouts.app')

@section('title', 'Register - Retrina Framework')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-person-plus display-4 text-success"></i>
                        </div>
                        <h1 class="h3 mb-1">Join Retrina!</h1>
                        <p class="text-muted">Create your account to get started</p>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="/demo/register" id="registerForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           placeholder="First name" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           placeholder="Last name" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-at"></i>
                                </span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Choose a username" required>
                            </div>
                            <div class="form-text">Must be 3-50 characters long</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="your@email.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Create a strong password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">At least 8 characters with letters and numbers</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" placeholder="Confirm your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="/terms" class="text-decoration-none">Terms of Service</a> 
                                and <a href="/privacy" class="text-decoration-none">Privacy Policy</a>
                            </label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter">
                            <label class="form-check-label" for="newsletter">
                                Subscribe to our newsletter for updates and tips
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-person-plus"></i> Create Account
                            </button>
                        </div>
                    </form>

                    <!-- Demo Notice -->
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="bi bi-exclamation-triangle"></i> Demo Mode
                        </h6>
                        <small>
                            This is a demo registration form. In a real application, this would create 
                            a new user account in the database with proper validation and security measures.
                        </small>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-muted">
                            Already have an account? 
                            <a href="/login" class="text-decoration-none">
                                <i class="bi bi-box-arrow-in-right"></i> Sign in here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle password visibility
function togglePasswordField(buttonId, inputId) {
    document.getElementById(buttonId).addEventListener('click', function() {
        const password = document.getElementById(inputId);
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            password.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
}

togglePasswordField('togglePassword', 'password');
togglePasswordField('togglePasswordConfirm', 'password_confirmation');

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    const terms = document.getElementById('terms').checked;
    
    let errors = [];
    
    // Basic validation
    if (!firstName || !lastName || !username || !email || !password || !passwordConfirm) {
        errors.push('Please fill in all required fields');
    }
    
    // Username validation
    if (username.length < 3 || username.length > 50) {
        errors.push('Username must be 3-50 characters long');
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        errors.push('Please enter a valid email address');
    }
    
    // Password validation
    if (password.length < 8) {
        errors.push('Password must be at least 8 characters long');
    }
    
    // Password confirmation
    if (password !== passwordConfirm) {
        errors.push('Passwords do not match');
    }
    
    // Terms acceptance
    if (!terms) {
        errors.push('You must agree to the Terms of Service');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Please fix the following errors:\n\n' + errors.join('\n'));
        return false;
    }
});

// Real-time password confirmation check
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    
    if (confirm && password !== confirm) {
        this.setCustomValidity('Passwords do not match');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});
</script>
@endsection 