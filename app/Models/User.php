<?php

namespace App\Models;

use App\Models\BaseModel;

class User extends BaseModel
{
    protected $table = 'users';
    
    protected $fillable = [
        'username',
        'email', 
        'password',
        'first_name',
        'last_name',
        'avatar',
        'email_verified_at',
        'is_active',
        'last_login_at',
        'remember_token'
    ];
    
    protected $hidden = [
        'password',
        'remember_token'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Hash password when setting
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->is_active === true;
    }

    /**
     * Check if email is verified
     */
    public function isEmailVerified()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Verify password
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->last_login_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    // Static query methods
    
    /**
     * Find user by email
     */
    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    /**
     * Find user by username
     */
    public static function findByUsername($username)
    {
        return static::where('username', $username)->first();
    }

    /**
     * Get only active users
     */
    public static function active()
    {
        return static::where('is_active', true);
    }

    /**
     * Get only verified users
     */
    public static function verified()
    {
        return static::whereNotNull('email_verified_at');
    }
}
