@extends('layouts.app')

@section('title', 'Profile - Retrina Framework')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-person-circle"></i> User Profile</h3>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <!-- User Avatar -->
                        <div class="col-md-4 text-center mb-4">
                            @if(isset($user['profile_image']) && $user['profile_image'])
                                <img src="/storage/uploads/profiles/{{ $user['profile_image'] }}" 
                                     alt="Profile Image" 
                                     class="rounded-circle border border-3 border-primary mx-auto mb-3"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="user-avatar mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                    {{ strtoupper(substr($user['username'], 0, 1)) }}
                                </div>
                            @endif
                            <h4>{{ $user['username'] }}</h4>
                            <span class="badge bg-{{ $user['role'] === 'admin' ? 'warning' : 'primary' }} fs-6">
                                {{ ucfirst($user['role']) }}
                            </span>
                        </div>
                        
                        <!-- User Information -->
                        <div class="col-md-8">
                            <h5 class="mb-3">Account Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>User ID:</strong></td>
                                    <td>{{ $user['id'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Username:</strong></td>
                                    <td>{{ $user['username'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $user['role'] === 'admin' ? 'warning' : 'primary' }}">
                                            {{ ucfirst($user['role']) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Account Status:</strong></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Session Status:</strong></td>
                                    <td><span class="badge bg-info">Online</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Profile Actions -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Profile Actions</h5>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="/profile/edit" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit Profile
                                </a>
                                <a href="/dashboard" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                                </a>
                                @if($user['role'] === 'admin')
                                <a href="/admin" class="btn btn-warning">
                                    <i class="bi bi-shield-check"></i> Admin Panel
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Information (for demo) -->
                    <div class="mt-4">
                        <h6 class="text-muted">System Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check text-success me-1"></i>
                                    CSRF Protection: Active<br>
                                    <i class="bi bi-database text-info me-1"></i>
                                    Database: Connected<br>
                                    <i class="bi bi-lock text-warning me-1"></i>
                                    Session: Secure
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-server text-secondary me-1"></i>
                                    Framework: Retrina v1.0<br>
                                    <i class="bi bi-code-slash text-primary me-1"></i>
                                    Template Engine: Active<br>
                                    <i class="bi bi-layers text-success me-1"></i>
                                    Middleware: Enabled
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" value="{{ $user['username'] }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="user@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="John">
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Doe">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Account Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Security Settings</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="twoFactor" checked>
                        <label class="form-check-label" for="twoFactor">
                            Enable Two-Factor Authentication
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                        <label class="form-check-label" for="emailNotifications">
                            Email Notifications
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Privacy Settings</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="profileVisible">
                        <label class="form-check-label" for="profileVisible">
                            Make profile publicly visible
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSettings()">Save Settings</button>
            </div>
        </div>
    </div>
</div>

<script>
function showEditModal() {
    new bootstrap.Modal(document.getElementById('editProfileModal')).show();
}

function showSettingsModal() {
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function saveProfile() {
    // Demo function - in real app, this would submit to server
    alert('Profile updated successfully! (Demo)');
    bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
}

function saveSettings() {
    // Demo function - in real app, this would submit to server
    alert('Settings saved successfully! (Demo)');
    bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
}
</script>

<style>
.user-avatar {
    transition: transform 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.05);
}

.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.table td {
    padding: 0.75rem 0;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection 