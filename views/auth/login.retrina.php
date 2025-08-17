@extends('layouts.app')

@section('title', 'Login - Retrina Framework')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock display-4 text-primary"></i>
                        </div>
                        <h1 class="h3 mb-1">Welcome Back!</h1>
                        <p class="text-muted">Sign in to your account</p>
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

                    <!-- Login Form -->
                    <form method="POST" action="/login" id="loginForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Enter your username" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In
                            </button>
                        </div>
                    </form>

                    <!-- Demo Credentials -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle"></i> Demo Credentials
                        </h6>
                        <div class="row">
                            <div class="col-6">
                                <small>
                                    <strong>Admin:</strong><br>
                                    Username: admin<br>
                                    Password: admin123
                                </small>
                            </div>
                            <div class="col-6">
                                <small>
                                    <strong>User:</strong><br>
                                    Username: user<br>
                                    Password: user123
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Fill Buttons -->
                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-outline-warning btn-sm flex-fill" onclick="fillAdmin()">
                            <i class="bi bi-shield-check"></i> Fill Admin
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm flex-fill" onclick="fillUser()">
                            <i class="bi bi-person"></i> Fill User
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-muted">
                            Don't have an account? 
                            <a href="/register" class="text-decoration-none">
                                <i class="bi bi-person-plus"></i> Create one here
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
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        password.type = 'password';
        icon.className = 'bi bi-eye';
    }
});

// Quick fill functions
function fillAdmin() {
    document.getElementById('username').value = 'admin';
    document.getElementById('password').value = 'admin123';
}

function fillUser() {
    document.getElementById('username').value = 'user';
    document.getElementById('password').value = 'user123';
}

// Form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    
    if (!username || !password) {
        e.preventDefault();
        alert('Please fill in all fields');
        return false;
    }
});
</script>
@endsection 