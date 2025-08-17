<?php

namespace App\Controllers;

use App\Models\User;
use Core\Database\DB;

class HomeController extends BaseController
{
    /**
     * Home page - handles authentication state
     */
    public function index()
    {
        $isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
        $user = $isAuthenticated ? [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'] ?? 'User',
            'role' => $_SESSION['user_role'] ?? 'user'
        ] : null;
        
        return view('home/index', compact('isAuthenticated', 'user'));
    }
    
    /**
     * About page
     */
    public function about()
    {
        return view('pages/about');
    }
    
    /**
     * Documentation page
     */
    public function docs()
    {
        return view('pages/docs');
    }
    
    /**
     * API reference page
     */
    public function api()
    {
        return view('pages/api');
    }
    
    /**
     * Hello page
     */
    public function hello()
    {
        return view('pages/hello');
    }
    
    /**
     * Dashboard page (authenticated users only)
     */
    public function dashboard()
    {
        $user = [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ];
        
        return view('auth/dashboard', compact('user'));
    }
    
    /**
     * User profile page (authenticated users only)
     */
    public function profile()
    {
        try {
            $userId = $_SESSION['user_id'];
            $userData = DB::table('users')->where('id', $userId)->first();
            
            $user = [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['user_role'],
                'profile_image' => $userData['profile_image'] ?? null
            ];
        } catch (Exception $e) {
            // Fallback to session data if database query fails
            $user = [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['user_role'],
                'profile_image' => null
            ];
        }
        
        return view('auth/profile', compact('user'));
    }
    
    /**
     * Settings page (authenticated users only)
     */
    public function settings()
    {
        $user = [
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ];
        
        return view('auth/settings', compact('user'));
    }
} 