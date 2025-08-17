@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex align-items-center mb-4">
                <a href="/settings" class="btn btn-outline-secondary me-3">
                    <i class="bi bi-arrow-left"></i> Back to Settings
                </a>
                <h1 class="h3 mb-0">Edit Profile</h1>
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

            <div class="row g-4">
                
                <!-- Profile Image Section -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white" id="image-section">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle me-2"></i>Profile Image
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <!-- Current Profile Image -->
                            <div class="mb-4">
                                @if(isset($user['profile_image']) && $user['profile_image'])
                                    <img src="/storage/uploads/profiles/{{ $user['profile_image'] }}" 
                                         alt="Profile Image" 
                                         class="rounded-circle border border-3 border-primary"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle border border-3 border-secondary d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 150px; height: 150px; background-color: #f8f9fa;">
                                        <i class="bi bi-person display-4 text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Image Upload Form -->
                            <form action="/profile/upload-image" method="POST" enctype="multipart/form-data" class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <input type="file" 
                                           class="form-control" 
                                           id="profile_image" 
                                           name="profile_image" 
                                           accept="image/*" 
                                           required>
                                    <div class="form-text">
                                        Max file size: 5MB. Supported formats: JPEG, PNG, GIF, WebP
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload me-2"></i>Upload Image
                                </button>
                            </form>

                            <!-- Delete Image Button -->
                            @if(isset($user['profile_image']) && $user['profile_image'])
                            <form action="/profile/delete-image" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete your profile image?')">
                                    <i class="bi bi-trash me-1"></i>Delete Image
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Information & Password -->
                <div class="col-lg-8">
                    
                    <!-- Profile Information Form -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-fill me-2"></i>Profile Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="/profile/update" method="POST">
                                @csrf
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ $user['first_name'] ?? '' }}" 
                                               required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ $user['last_name'] ?? '' }}" 
                                               required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="username" 
                                               name="username" 
                                               value="{{ $user['username'] ?? '' }}" 
                                               required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="{{ $user['email'] ?? '' }}" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-info">
                                        <i class="bi bi-check-circle me-2"></i>Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password Form -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark" id="password-section">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lock-fill me-2"></i>Change Password
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="/profile/change-password" method="POST">
                                @csrf
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="current_password" 
                                                   name="current_password" 
                                                   required>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('current_password')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="new_password" 
                                                   name="new_password" 
                                                   minlength="6" 
                                                   required>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('new_password')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Minimum 6 characters</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="confirm_password" 
                                                   name="confirm_password" 
                                                   minlength="6" 
                                                   required>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('confirm_password')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-shield-lock me-2"></i>Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (newPassword !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

// Image preview
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            this.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only JPEG, PNG, GIF, and WebP images are allowed');
            this.value = '';
            return;
        }
    }
});
</script>
@endsection 