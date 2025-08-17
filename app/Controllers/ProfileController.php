<?php

namespace App\Controllers;

use App\Models\User;

class ProfileController extends BaseController
{
    /**
     * Show profile edit form
     */
    public function edit()
    {
        try {
            $userId = $_SESSION['user_id'];
            $userObj = User::find($userId);
            
            if (!$userObj) {
                $_SESSION['flash_error'] = 'User not found.';
                header('Location: /dashboard');
                exit;
            }
            
            $user = $userObj->toArray();
            
            return $this->view('profile/edit', ['user' => $user]);
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /dashboard');
            exit;
        }
    }

    /**
     * Update profile information
     */
    public function update()
    {
        try {
            $userId = $_SESSION['user_id'];
            
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '');
            
            if (empty($firstName) || empty($lastName) || empty($email) || empty($username)) {
                $_SESSION['flash_error'] = 'All fields are required.';
                header('Location: /profile/edit');
                exit;
            }
            
            // Check if email is already taken by another user
            $existingEmailUser = User::findByEmail($email);
            if ($existingEmailUser && $existingEmailUser['id'] != $userId) {
                $_SESSION['flash_error'] = 'Email is already taken by another user.';
                header('Location: /profile/edit');
                exit;
            }
            
            // Check if username is already taken by another user
            $existingUsernameUser = User::findByUsername($username);
            if ($existingUsernameUser && $existingUsernameUser['id'] != $userId) {
                $_SESSION['flash_error'] = 'Username is already taken by another user.';
                header('Location: /profile/edit');
                exit;
            }
            
            $userObj = User::find($userId);
            if (!$userObj) {
                $_SESSION['flash_error'] = 'User not found.';
                header('Location: /dashboard');
                exit;
            }
            
            $userObj->setAttribute('first_name', $firstName);
            $userObj->setAttribute('last_name', $lastName);
            $userObj->setAttribute('email', $email);
            $userObj->setAttribute('username', $username);
            $userObj->setAttribute('updated_at', date('Y-m-d H:i:s'));
            
            $userObj->save();
            
            // Update session data
            $_SESSION['username'] = $username;
            
            $_SESSION['flash_success'] = 'Profile updated successfully!';
            header('Location: /profile/edit');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /profile/edit');
            exit;
        }
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        try {
            $userId = $_SESSION['user_id'];
            
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['flash_error'] = 'All password fields are required.';
                header('Location: /profile/edit#password-section');
                exit;
            }
            
            if ($newPassword !== $confirmPassword) {
                $_SESSION['flash_error'] = 'New passwords do not match.';
                header('Location: /profile/edit#password-section');
                exit;
            }
            
            if (strlen($newPassword) < 6) {
                $_SESSION['flash_error'] = 'New password must be at least 6 characters long.';
                header('Location: /profile/edit#password-section');
                exit;
            }
            
            $userObj = User::find($userId);
            if (!$userObj) {
                $_SESSION['flash_error'] = 'User not found.';
                header('Location: /dashboard');
                exit;
            }
            
            $user = $userObj->toArray();
            
            if (!User::verifyPassword($user, $currentPassword)) {
                $_SESSION['flash_error'] = 'Current password is incorrect.';
                header('Location: /profile/edit#password-section');
                exit;
            }
            
            $userObj->setAttribute('password', password_hash($newPassword, PASSWORD_DEFAULT));
            $userObj->setAttribute('updated_at', date('Y-m-d H:i:s'));
            
            $userObj->save();
            
            $_SESSION['flash_success'] = 'Password changed successfully!';
            header('Location: /profile/edit#password-section');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /profile/edit#password-section');
            exit;
        }
    }

    /**
     * Upload profile image
     */
    public function uploadImage()
    {
        try {
            $userId = $_SESSION['user_id'];
            
            if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['flash_error'] = 'Please select a valid image file.';
                header('Location: /profile/edit#image-section');
                exit;
            }
            
            $file = $_FILES['profile_image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['flash_error'] = 'Only JPEG, PNG, GIF, and WebP images are allowed.';
                header('Location: /profile/edit#image-section');
                exit;
            }
            
            if ($file['size'] > $maxSize) {
                $_SESSION['flash_error'] = 'Image size must be less than 2MB.';
                header('Location: /profile/edit#image-section');
                exit;
            }
            
            // Create upload directory if it doesn't exist
            $uploadDir = __DIR__ . '/../../storage/uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $filename;
            
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $_SESSION['flash_error'] = 'Failed to upload image.';
                header('Location: /profile/edit#image-section');
                exit;
            }
            
            // Delete old profile image if exists
            $userObj = User::find($userId);
            if ($userObj) {
                $user = $userObj->toArray();
                if (!empty($user['profile_image'])) {
                    $oldImagePath = $uploadDir . basename($user['profile_image']);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $userObj->setAttribute('profile_image', $filename);
                $userObj->setAttribute('updated_at', date('Y-m-d H:i:s'));
                $userObj->save();
            }
            
            $_SESSION['flash_success'] = 'Profile image uploaded successfully!';
            header('Location: /profile/edit#image-section');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /profile/edit#image-section');
            exit;
        }
    }

    /**
     * Delete profile image
     */
    public function deleteImage()
    {
        try {
            $userId = $_SESSION['user_id'];
            
            $userObj = User::find($userId);
            if (!$userObj) {
                $_SESSION['flash_error'] = 'User not found.';
                header('Location: /dashboard');
                exit;
            }
            
            $user = $userObj->toArray();
            
            if (!empty($user['profile_image'])) {
                // Delete image file
                $uploadDir = __DIR__ . '/../../storage/uploads/profiles/';
                $imagePath = $uploadDir . basename($user['profile_image']);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                
                // Update database
                $userObj->setAttribute('profile_image', null);
                $userObj->setAttribute('updated_at', date('Y-m-d H:i:s'));
                $userObj->save();
            }
            
            $_SESSION['flash_success'] = 'Profile image deleted successfully!';
            header('Location: /profile/edit#image-section');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /profile/edit#image-section');
            exit;
        }
    }
}
