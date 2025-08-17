@extends('layouts.app')

@section('title', 'Edit User - Admin Panel')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="/admin" class="btn btn-outline-secondary me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h3 mb-1">Edit User</h1>
                    <p class="text-muted mb-0">Update user information for <strong>{{ $user['username'] }}</strong></p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-gear me-2"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/users/{{ $user['id'] }}/update">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="{{ $user['first_name'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="{{ $user['last_name'] ?? '' }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="{{ $user['username'] }}" required>
                                <div class="form-text">Must be unique</div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $user['email'] }}" required>
                                <div class="form-text">Must be unique</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Leave blank to keep current password</div>
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="user" {{ $user['role'] === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user['role'] === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ $user['is_active'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Account
                                </label>
                                <div class="form-text">Inactive users cannot log in</div>
                            </div>
                        </div>

                        <!-- User Info Display -->
                        <div class="bg-light rounded p-3 mb-4">
                            <h6 class="mb-2">Account Details</h6>
                            <div class="row text-sm">
                                <div class="col-md-4">
                                    <strong>User ID:</strong> #{{ $user['id'] }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Created:</strong> {{ date('M j, Y', strtotime($user['created_at'])) }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Last Updated:</strong> {{ date('M j, Y', strtotime($user['updated_at'])) }}
                                </div>
                            </div>
                            @if(!empty($user['last_login_at']))
                            <div class="mt-2">
                                <strong>Last Login:</strong> {{ date('M j, Y g:i A', strtotime($user['last_login_at'])) }}
                            </div>
                            @endif
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/admin" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($user['id'] != $_SESSION['user_id'])
            <!-- Danger Zone -->
            <div class="card border-danger mt-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Once you delete a user, there is no going back. Please be certain.</p>
                    <button type="button" class="btn btn-outline-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i>
                        Delete User
                    </button>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete user <strong>{{ $user['username'] }}</strong>?</p>
                            <p class="text-danger small mb-0">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                This action cannot be undone. All user data will be permanently lost.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="/admin/users/{{ $user['id'] }}/delete" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">Delete User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 