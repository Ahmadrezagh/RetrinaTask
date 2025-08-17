@extends('layouts.app')

@section('title', 'Settings - Retrina Framework')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0"><i class="bi bi-gear"></i> Settings</h3>
                </div>
                <div class="card-body p-5">
                    
                    <!-- Account Settings -->
                    <h5 class="mb-3"><i class="bi bi-person-gear me-2"></i>Account Settings</h5>
                    <div class="list-group mb-4">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person me-2 text-primary"></i> 
                                <strong>Update Profile Information</strong>
                                <br><small class="text-muted">Change your username, email, personal details, and profile image</small>
                            </div>
                            <a href="/profile/edit" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil me-1"></i>Edit Profile
                            </a>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-shield-lock me-2 text-warning"></i> 
                                <strong>Change Password</strong>
                                <br><small class="text-muted">Update your account password securely</small>
                            </div>
                            <a href="/profile/edit#password-section" class="btn btn-sm btn-warning">
                                <i class="bi bi-key me-1"></i>Change Password
                            </a>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-camera me-2 text-success"></i> 
                                <strong>Profile Image</strong>
                                <br><small class="text-muted">Upload or change your profile picture</small>
                            </div>
                            <a href="/profile/edit#image-section" class="btn btn-sm btn-success">
                                <i class="bi bi-upload me-1"></i>Manage Image
                            </a>
                        </div>
                    </div>
                    
                    <!-- Application Settings -->
                    <h5 class="mb-3"><i class="bi bi-sliders me-2"></i>Application Settings</h5>
                    <div class="list-group mb-4">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-bell me-2 text-info"></i> 
                                <strong>Notification Preferences</strong>
                                <br><small class="text-muted">Configure how you receive notifications</small>
                            </div>
                            <button class="btn btn-sm btn-outline-info" disabled>
                                <i class="bi bi-gear me-1"></i>Coming Soon
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-palette me-2 text-purple"></i> 
                                <strong>Theme Settings</strong>
                                <br><small class="text-muted">Choose your preferred color scheme</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="bi bi-brush me-1"></i>Coming Soon
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-globe me-2 text-secondary"></i> 
                                <strong>Language & Region</strong>
                                <br><small class="text-muted">Set your language and timezone preferences</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="bi bi-translate me-1"></i>Coming Soon
                            </button>
                        </div>
                    </div>
                    
                    <!-- Privacy & Security -->
                    <h5 class="mb-3"><i class="bi bi-shield-check me-2"></i>Privacy & Security</h5>
                    <div class="list-group mb-4">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-eye me-2 text-primary"></i> 
                                <strong>Privacy Settings</strong>
                                <br><small class="text-muted">Control who can see your information</small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" disabled>
                                <i class="bi bi-lock me-1"></i>Coming Soon
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-phone me-2 text-success"></i> 
                                <strong>Two-Factor Authentication</strong>
                                <br><small class="text-muted">Add an extra layer of security to your account</small>
                            </div>
                            <button class="btn btn-sm btn-outline-success" disabled>
                                <i class="bi bi-shield me-1"></i>Coming Soon
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-clock-history me-2 text-warning"></i> 
                                <strong>Login History</strong>
                                <br><small class="text-muted">View recent login activity</small>
                            </div>
                            <button class="btn btn-sm btn-outline-warning" disabled>
                                <i class="bi bi-list me-1"></i>Coming Soon
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="bg-light rounded p-4">
                        <h6 class="mb-3">Quick Actions</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="/dashboard" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                            <a href="/profile" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-person me-1"></i>My Profile
                            </a>
                            <a href="/logout" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 