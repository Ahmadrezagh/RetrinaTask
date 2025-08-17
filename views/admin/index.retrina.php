@extends('layouts.app')

@section('title', 'Admin Panel - User Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-shield-check text-warning me-2"></i>
                Admin Panel
            </h1>
            <p class="text-muted mb-0">Manage users and system settings</p>
        </div>
        <a href="/admin/users/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Add New User
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Users</h6>
                            <h3 class="mb-0">{{ $totalUsers }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Users</h6>
                            <h3 class="mb-0">{{ array_sum(array_map(function($user) { return $user['is_active']; }, $users)) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-check fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Admins</h6>
                            <h3 class="mb-0">{{ array_sum(array_map(function($user) { return $user['role'] === 'admin' ? 1 : 0; }, $users)) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-shield-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Page {{ $currentPage }}</h6>
                            <h3 class="mb-0">of {{ $totalPages }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-files fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/admin" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Name, username, or email..." 
                           value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">All Roles</option>
                        <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ $roleFilter === 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <a href="/admin" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-table me-2"></i>
                Users Management
            </h5>
        </div>
        <div class="card-body p-0">
            @if(empty($users))
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No users found</h5>
                    <p class="text-muted">Try adjusting your search filters</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="fw-bold">#{{ $user['id'] }}</td>
                                <td>
                                    @if(isset($user['profile_image']) && $user['profile_image'])
                                        <img src="/storage/uploads/profiles/{{ $user['profile_image'] }}" 
                                             alt="Avatar" 
                                             class="rounded-circle"
                                             width="40" height="40"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px; font-size: 16px;">
                                            {{ strtoupper(substr($user['username'], 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $user['first_name'] ?? '' }} {{ $user['last_name'] ?? '' }}</div>
                                        @if(!empty($user['last_login_at']))
                                            <small class="text-muted">Last login: {{ date('M j, Y', strtotime($user['last_login_at'])) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $user['username'] }}</span>
                                </td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $user['role'] === 'admin' ? 'warning' : 'primary' }}">
                                        {{ ucfirst($user['role']) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user['is_active'] ? 'success' : 'danger' }}">
                                        {{ $user['is_active'] ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ date('M j, Y', strtotime($user['created_at'])) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/admin/users/{{ $user['id'] }}/edit" 
                                           class="btn btn-outline-primary btn-sm"
                                           data-bs-toggle="tooltip" 
                                           title="Edit User">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user['id'] != $_SESSION['user_id'])
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $user['id'] }}"
                                                title="Delete User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        @if($totalPages > 1)
        <div class="card-footer">
            <!-- Pagination -->
            <nav aria-label="Users pagination">
                <ul class="pagination justify-content-center mb-0">
                    @if($hasPrevPage)
                        <li class="page-item">
                            <a class="page-link" href="/admin?page={{ $currentPage - 1 }}{{ $search ? '&search=' . urlencode($search) : '' }}{{ $roleFilter ? '&role=' . $roleFilter : '' }}{{ $statusFilter ? '&status=' . $statusFilter : '' }}">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    @endif
                    
                    @php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                    @endphp
                    
                    @if($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="/admin?page=1{{ $search ? '&search=' . urlencode($search) : '' }}{{ $roleFilter ? '&role=' . $roleFilter : '' }}{{ $statusFilter ? '&status=' . $statusFilter : '' }}">1</a>
                        </li>
                        @if($startPage > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        <li class="page-item {{ $i === $currentPage ? 'active' : '' }}">
                            <a class="page-link" href="/admin?page={{ $i }}{{ $search ? '&search=' . urlencode($search) : '' }}{{ $roleFilter ? '&role=' . $roleFilter : '' }}{{ $statusFilter ? '&status=' . $statusFilter : '' }}">{{ $i }}</a>
                        </li>
                    @endfor
                    
                    @if($endPage < $totalPages)
                        @if($endPage < $totalPages - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="/admin?page={{ $totalPages }}{{ $search ? '&search=' . urlencode($search) : '' }}{{ $roleFilter ? '&role=' . $roleFilter : '' }}{{ $statusFilter ? '&status=' . $statusFilter : '' }}">{{ $totalPages }}</a>
                        </li>
                    @endif
                    
                    @if($hasNextPage)
                        <li class="page-item">
                            <a class="page-link" href="/admin?page={{ $currentPage + 1 }}{{ $search ? '&search=' . urlencode($search) : '' }}{{ $roleFilter ? '&role=' . $roleFilter : '' }}{{ $statusFilter ? '&status=' . $statusFilter : '' }}">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Delete Modals -->
@foreach($users as $user)
    @if($user['id'] != $_SESSION['user_id'])
    <div class="modal fade" id="deleteModal{{ $user['id'] }}" tabindex="-1">
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
                        This action cannot be undone.
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
@endforeach

<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endsection 