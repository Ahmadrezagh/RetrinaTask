<?php

namespace App\Models;

class User extends BaseModel
{
    protected $table = 'users';
    
    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Create a new user with validation
     */
    public function createUser($userData)
    {
        // Basic validation
        if (empty($userData['name']) || empty($userData['email'])) {
            throw new \Exception("Name and email are required");
        }
        
        // Check if email already exists
        if ($this->findByEmail($userData['email'])) {
            throw new \Exception("Email already exists");
        }
        
        // Hash password if provided
        if (isset($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        // Add timestamps
        $userData['created_at'] = date('Y-m-d H:i:s');
        $userData['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->create($userData);
    }
    
    /**
     * Update user with timestamps
     */
    public function updateUser($id, $userData)
    {
        // Add updated timestamp
        $userData['updated_at'] = date('Y-m-d H:i:s');
        
        // Hash password if being updated
        if (isset($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        
        return $this->update($id, $userData);
    }
    
    /**
     * Get all active users
     */
    public function getActiveUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 