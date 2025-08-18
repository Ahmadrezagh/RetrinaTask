<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use Core\Database\DB;
use Exception;
use PDO;

class UserController extends BaseController
{
    /**
     * Get list of users with pagination and filtering
     */
    public function index()
    {
        try {
            $page = (int)($_GET['page'] ?? 1);
            $perPage = (int)($_GET['per_page'] ?? 10);
            $search = trim($_GET['search'] ?? '');
            $role = $_GET['role'] ?? '';
            
            // Build query
            $query = "SELECT id, username, email, first_name, last_name, role, is_active, created_at FROM users WHERE 1=1";
            $params = [];
            
            // Add search filter
            if ($search) {
                $query .= " AND (username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
                $searchTerm = "%{$search}%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            }
            
            // Add role filter
            if ($role) {
                $query .= " AND role = ?";
                $params[] = $role;
            }
            
            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM (" . $query . ") as subquery";
            $stmt = DB::connection()->getPdo()->prepare($countQuery);
            $stmt->execute($params);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Add pagination
            $offset = ($page - 1) * $perPage;
            $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;
            
            // Execute query
            $stmt = DB::connection()->getPdo()->prepare($query);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode([
                'data' => $users,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => ceil($total / $perPage),
                    'total_items' => (int)$total
                ]
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Internal server error']);
        }
    }
    
    /**
     * Get single user by ID
     */
    public function show($id)
    {
        try {
            $stmt = DB::connection()->getPdo()->prepare(
                "SELECT id, username, email, first_name, last_name, role, is_active, created_at FROM users WHERE id = ?"
            );
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'User not found']);
                return;
            }
            
            header('Content-Type: application/json');
            echo json_encode($user);
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Internal server error']);
        }
    }
    
    /**
     * Create new user (admin only)
     */
    public function store()
    {
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
            
            $required = ['first_name', 'last_name', 'username', 'email', 'password'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    http_response_code(422);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => 'Validation failed',
                        'errors' => [$field => 'This field is required']
                    ]);
                    return;
                }
            }
            
            // Check if username exists
            $stmt = DB::connection()->getPdo()->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$input['username']]);
            if ($stmt->fetch()) {
                http_response_code(422);
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'Validation failed',
                    'errors' => ['username' => 'Username already exists']
                ]);
                return;
            }
            
            // Create user
            $userData = [
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'username' => $input['username'],
                'email' => $input['email'],
                'password' => password_hash($input['password'], PASSWORD_DEFAULT),
                'role' => $input['role'] ?? 'user',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $stmt = DB::connection()->getPdo()->prepare(
                "INSERT INTO users (first_name, last_name, username, email, password, role, is_active, created_at, updated_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            $stmt->execute(array_values($userData));
            $userId = DB::connection()->getPdo()->lastInsertId();
            
            // Return created user
            $stmt = DB::connection()->getPdo()->prepare(
                "SELECT id, username, email, first_name, last_name, role, is_active, created_at FROM users WHERE id = ?"
            );
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($user);
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Internal server error']);
        }
    }
    
    /**
     * Update user (admin only)
     */
    public function update($id)
    {
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Get current user
            $stmt = DB::connection()->getPdo()->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'User not found']);
                return;
            }
            
            // Update fields
            $updateFields = [];
            $params = [];
            
            foreach (['first_name', 'last_name', 'username', 'email', 'role', 'is_active'] as $field) {
                if (isset($input[$field])) {
                    $updateFields[] = "$field = ?";
                    $params[] = $input[$field];
                }
            }
            
            if (empty($updateFields)) {
                http_response_code(422);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No fields to update']);
                return;
            }
            
            $updateFields[] = "updated_at = ?";
            $params[] = date('Y-m-d H:i:s');
            $params[] = $id;
            
            $stmt = DB::connection()->getPdo()->prepare(
                "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?"
            );
            $stmt->execute($params);
            
            // Return updated user
            $stmt = DB::connection()->getPdo()->prepare(
                "SELECT id, username, email, first_name, last_name, role, is_active, created_at FROM users WHERE id = ?"
            );
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode($user);
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Internal server error']);
        }
    }
    
    /**
     * Delete user (admin only)
     */
    public function destroy($id)
    {
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        
        try {
            // Prevent deleting self
            if ($id == $_SESSION['user_id']) {
                http_response_code(422);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Cannot delete your own account']);
                return;
            }
            
            $stmt = DB::connection()->getPdo()->prepare("DELETE FROM users WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'User not found']);
                return;
            }
            
            header('Content-Type: application/json');
            echo json_encode(['message' => 'User deleted successfully']);
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Internal server error']);
        }
    }
} 