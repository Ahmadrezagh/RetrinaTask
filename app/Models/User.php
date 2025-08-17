<?php

namespace App\Models;

use Core\Database\Model;
use Core\Database\DB;

/**
 * User Model
 * 
 * This model demonstrates how to use the Retrina Framework ORM
 * with Laravel-like syntax for database operations.
 */
class User extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'id',
        'username',
        'email', 
        'password',
        'first_name',
        'last_name',
        'role',
        'is_active',
        'email_verified_at',
        'profile_image',
        'created_at',
        'updated_at',
        'last_login_at',
        'avatar',
        'remember_token'
    ];
    
    /**
     * Find user by email
     */
    public static function findByEmail($email)
    {
        $user = static::where('email', $email)->first();
        return $user ? $user->toArray() : null;
    }
    
    /**
     * Find user by username
     */
    public static function findByUsername($username)
    {
        $user = static::where('username', $username)->first();
        return $user ? $user->toArray() : null;
    }
    
    /**
     * Get only active users
     */
    public static function active()
    {
        return static::where('is_active', 1);
    }
    
    /**
     * Get only verified users
     */
    public static function verified()
    {
        return static::whereNotNull('email_verified_at');
    }
    
    /**
     * Search users by term
     */
    public static function search($term)
    {
        return static::where('first_name', 'LIKE', "%{$term}%")
            ->orWhere('last_name', 'LIKE', "%{$term}%")
            ->orWhere('email', 'LIKE', "%{$term}%")
            ->orWhere('username', 'LIKE', "%{$term}%");
    }
    
    /**
     * Get recently registered users
     */
    public static function recent($days = 7)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return static::where('created_at', '>=', $date);
    }
    
    /**
     * Verify password for a user
     */
    public static function verifyPassword($user, $password)
    {
        if (is_array($user) && isset($user['password'])) {
            return password_verify($password, $user['password']);
        }
        return false;
    }
    
    /**
     * Get user's full name
     */
    public static function getFullName($user)
    {
        if (is_array($user)) {
            return trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        }
        return '';
    }
    
    /**
     * Check if user has admin role
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if user has user role
     */
    public function isUser()
    {
        return $this->role === 'user';
    }
    
    /**
     * Get users by role
     */
    public static function byRole($role)
    {
        return static::where('role', $role)->get();
    }
    
    /**
     * Get all admin users
     */
    public static function admins()
    {
        return static::byRole('admin');
    }
    
    /**
     * Get all regular users
     */
    public static function users()
    {
        return static::byRole('user');
    }
}
