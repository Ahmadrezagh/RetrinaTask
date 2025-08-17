<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return $this->view('auth/login');
    }
    
    public function showRegister()
    {
        return $this->view('auth/register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $user = User::findByUsername($username);
            
            if (!$user || !User::verifyPassword($user, $password)) {
                $_SESSION['flash_error'] = 'Invalid username or password.';
                header('Location: /login');
                exit;
            }
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            
            $_SESSION['flash_success'] = 'Welcome back, ' . $user['username'] . '!';
            
            // Redirect to intended URL or dashboard if none
            $redirectUrl = $_SESSION['intended_url'] ?? '/dashboard';
            unset($_SESSION['intended_url']);
            
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        return $this->view('auth/login');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['password_confirmation'] ?? '';
            
            if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
                $_SESSION['flash_error'] = 'All fields are required.';
                header('Location: /register');
                exit;
            }
            
            if ($password !== $confirmPassword) {
                $_SESSION['flash_error'] = 'Passwords do not match.';
                header('Location: /register');
                exit;
            }
            
            if (User::findByUsername($username)) {
                $_SESSION['flash_error'] = 'Username is already taken.';
                header('Location: /register');
                exit;
            }
            
            if (User::findByEmail($email)) {
                $_SESSION['flash_error'] = 'Email is already registered.';
                header('Location: /register');
                exit;
            }
            
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'role' => 'user',
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $createdUser = User::create($userData);
            
            $user = User::findByUsername($username);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            
            $_SESSION['flash_success'] = 'Registration successful! Welcome ' . $firstName . '!';
            
            // Redirect to intended URL or dashboard if none
            $redirectUrl = $_SESSION['intended_url'] ?? '/dashboard';
            unset($_SESSION['intended_url']);
            
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        return $this->view('auth/register');
    }

    public function logout()
    {
        session_destroy();
        $_SESSION['flash_success'] = 'You have been logged out successfully.';
        header('Location: /');
        exit;
    }
}
