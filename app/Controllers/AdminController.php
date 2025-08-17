<?php

namespace App\Controllers;

use App\Models\User;

class AdminController extends BaseController
{
    /**
     * Admin panel dashboard with user management
     */
    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $search = trim($_GET['search'] ?? '');
        $roleFilter = $_GET['role'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        
        // Build query
        $query = User::query();
        
        // Apply search filters (simplified without closures)
        if (!empty($search)) {
            $query->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        }
        
        // Apply role filter
        if (!empty($roleFilter)) {
            $query->where('role', $roleFilter);
        }
        
        // Apply status filter
        if (!empty($statusFilter)) {
            $isActive = $statusFilter === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }
        
        // Get total count for pagination
        $totalUsers = $query->count();
        $totalPages = ceil($totalUsers / $perPage);
        
        // Get paginated results
        $users = $query->orderBy('created_at', 'DESC')
                      ->offset(($page - 1) * $perPage)
                      ->limit($perPage)
                      ->get();
        
        // Convert to arrays for view compatibility
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = $user->toArray();
        }
        
        $data = [
            'users' => $usersArray,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers,
            'perPage' => $perPage,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'statusFilter' => $statusFilter,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1
        ];
        
        return $this->view('admin/index', $data);
    }
    
    /**
     * Show create user form
     */
    public function create()
    {
        return $this->view('admin/create');
    }
    
    /**
     * Store new user
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = 'Invalid request method.';
            header('Location: /admin');
            exit;
        }
        
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        // Validation
        if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'All fields are required.';
            header('Location: /admin/users/create');
            exit;
        }
        
        if (User::findByUsername($username)) {
            $_SESSION['flash_error'] = 'Username is already taken.';
            header('Location: /admin/users/create');
            exit;
        }
        
        if (User::findByEmail($email)) {
            $_SESSION['flash_error'] = 'Email is already registered.';
            header('Location: /admin/users/create');
            exit;
        }
        
        try {
            User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
                'is_active' => $isActive,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $_SESSION['flash_success'] = 'User created successfully!';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to create user: ' . $e->getMessage();
        }
        
        header('Location: /admin');
        exit;
    }
    
    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $userObj = User::find($id);
        if (!$userObj) {
            $_SESSION['flash_error'] = 'User not found.';
            header('Location: /admin');
            exit;
        }
        
        $user = $userObj->toArray();
        return $this->view('admin/edit', ['user' => $user]);
    }
    
    /**
     * Update user
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = 'Invalid request method.';
            header('Location: /admin');
            exit;
        }
        
        $userObj = User::find($id);
        if (!$userObj) {
            $_SESSION['flash_error'] = 'User not found.';
            header('Location: /admin');
            exit;
        }
        
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $password = $_POST['password'] ?? '';
        
        // Validation
        if (empty($firstName) || empty($lastName) || empty($username) || empty($email)) {
            $_SESSION['flash_error'] = 'All fields except password are required.';
            header('Location: /admin/users/' . $id . '/edit');
            exit;
        }
        
        // Check username uniqueness
        $existingUsername = User::findByUsername($username);
        if ($existingUsername && $existingUsername['id'] != $id) {
            $_SESSION['flash_error'] = 'Username is already taken by another user.';
            header('Location: /admin/users/' . $id . '/edit');
            exit;
        }
        
        // Check email uniqueness
        $existingEmail = User::findByEmail($email);
        if ($existingEmail && $existingEmail['id'] != $id) {
            $_SESSION['flash_error'] = 'Email is already registered by another user.';
            header('Location: /admin/users/' . $id . '/edit');
            exit;
        }
        
        try {
            $userObj->setAttribute('first_name', $firstName);
            $userObj->setAttribute('last_name', $lastName);
            $userObj->setAttribute('username', $username);
            $userObj->setAttribute('email', $email);
            $userObj->setAttribute('role', $role);
            $userObj->setAttribute('is_active', $isActive);
            $userObj->setAttribute('updated_at', date('Y-m-d H:i:s'));
            
            // Update password if provided
            if (!empty($password)) {
                $userObj->setAttribute('password', password_hash($password, PASSWORD_DEFAULT));
            }
            
            $userObj->save();
            
            $_SESSION['flash_success'] = 'User updated successfully!';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to update user: ' . $e->getMessage();
        }
        
        header('Location: /admin');
        exit;
    }
    
    /**
     * Delete user
     */
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = 'Invalid request method.';
            header('Location: /admin');
            exit;
        }
        
        // Prevent admin from deleting themselves
        if ($id == $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'You cannot delete your own account.';
            header('Location: /admin');
            exit;
        }
        
        $userObj = User::find($id);
        if (!$userObj) {
            $_SESSION['flash_error'] = 'User not found.';
            header('Location: /admin');
            exit;
        }
        
        try {
            $userObj->delete();
            $_SESSION['flash_success'] = 'User deleted successfully!';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to delete user: ' . $e->getMessage();
        }
        
        header('Location: /admin');
        exit;
    }
}
