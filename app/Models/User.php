<?php

namespace App\Models;

use Core\Database\DB;

/**
 * User Model
 * 
 * This model demonstrates how to use the Retrina Framework ORM
 * with Laravel-like syntax for database operations.
 */
class User
{
    protected static $table = 'users';
    
    /**
     * Get all users
     */
    public static function all()
    {
        return DB::table(static::$table)->get();
    }
    
    /**
     * Find user by ID
     */
    public static function find($id)
    {
        return DB::table(static::$table)->where('id', $id)->first();
    }
    
    /**
     * Find user by email
     */
    public static function findByEmail($email)
    {
        return DB::table(static::$table)->where('email', $email)->first();
    }
    
    /**
     * Find user by username
     */
    public static function findByUsername($username)
    {
        return DB::table(static::$table)->where('username', $username)->first();
    }
    
    /**
     * Get only active users
     */
    public static function active()
    {
        return DB::table(static::$table)->where('is_active', 1);
    }
    
    /**
     * Get only verified users
     */
    public static function verified()
    {
        return DB::table(static::$table)->whereNotNull('email_verified_at');
    }
    
    /**
     * Search users by term
     */
    public static function search($term)
    {
        return DB::table(static::$table)
            ->where('first_name', 'LIKE', "%{$term}%")
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
        return DB::table(static::$table)->where('created_at', '>=', $date);
    }
    
    /**
     * Create a new user
     */
    public static function create(array $data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Add timestamps
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $id = DB::table(static::$table)->insertGetId($data);
        return static::find($id);
    }
    
    /**
     * Update user by ID
     */
    public static function updateById($id, array $data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Update timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return DB::table(static::$table)->where('id', $id)->update($data);
    }
    
    /**
     * Delete user by ID
     */
    public static function deleteById($id)
    {
        return DB::table(static::$table)->where('id', $id)->delete();
    }
    
    /**
     * Count users
     */
    public static function count()
    {
        return DB::table(static::$table)->count();
    }
    
    /**
     * Check if user exists
     */
    public static function exists($id)
    {
        return DB::table(static::$table)->where('id', $id)->exists();
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
     * Update last login timestamp
     */
    public static function updateLastLogin($id)
    {
        return static::updateById($id, ['last_login_at' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Mark email as verified
     */
    public static function markEmailAsVerified($id)
    {
        return static::updateById($id, ['email_verified_at' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Get paginated users
     */
    public static function paginate($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        return [
            'data' => DB::table(static::$table)
                ->orderBy('created_at', 'desc')
                ->limit($perPage)
                ->offset($offset)
                ->get(),
            'total' => static::count(),
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil(static::count() / $perPage)
        ];
    }
    
    /**
     * Get user statistics
     */
    public static function getStats()
    {
        return [
            'total' => DB::table(static::$table)->count(),
            'active' => DB::table(static::$table)->where('is_active', 1)->count(),
            'verified' => DB::table(static::$table)->whereNotNull('email_verified_at')->count(),
            'recent' => DB::table(static::$table)
                ->where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))
                ->count()
        ];
    }
}
