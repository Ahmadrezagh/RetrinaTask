<?php $this->extends('app'); ?>

<?php $this->section('title'); ?>
User Profile - <?= $this->escape($user_id ?? 'Unknown') ?> - Retrina Framework
<?php $this->endSection(); ?>

<?php $this->section('page-title'); ?>
User Profile
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="profile-section">
    <div class="profile-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div class="avatar" style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                👤
            </div>
            <div>
                <h2 style="margin: 0; font-size: 1.8rem;">User #<?= $this->escape($user_id ?? 'N/A') ?></h2>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Profile information and details</p>
            </div>
        </div>
    </div>

    <div class="profile-content" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div class="profile-info">
            <h3 style="color: #333; margin-bottom: 1rem; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">Profile Information</h3>
            
            <div class="info-item" style="margin-bottom: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                <strong style="color: #333;">User ID:</strong>
                <span style="color: #666; margin-left: 0.5rem;"><?= $this->escape($user_id ?? 'Not provided') ?></span>
            </div>
            
            <div class="info-item" style="margin-bottom: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                <strong style="color: #333;">Route Parameter:</strong>
                <span style="color: #666; margin-left: 0.5rem;"><?= $this->escape($route_param ?? 'Successfully captured from URL') ?></span>
            </div>
            
            <div class="info-item" style="margin-bottom: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                <strong style="color: #333;">Framework:</strong>
                <span style="color: #666; margin-left: 0.5rem;">Retrina Framework</span>
            </div>
            
            <div class="info-item" style="margin-bottom: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 4px;">
                <strong style="color: #333;">View Engine:</strong>
                <span style="color: #666; margin-left: 0.5rem;">Template with Layout Support</span>
            </div>
        </div>

        <div class="profile-actions">
            <h3 style="color: #333; margin-bottom: 1rem; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem;">Quick Actions</h3>
            
            <div class="action-buttons" style="display: flex; flex-direction: column; gap: 1rem;">
                <button onclick="editProfile()" class="btn" style="background: #28a745;">Edit Profile</button>
                <button onclick="viewSettings()" class="btn" style="background: #17a2b8;">Account Settings</button>
                <button onclick="changePassword()" class="btn" style="background: #ffc107; color: #333;">Change Password</button>
                <button onclick="deleteAccount()" class="btn" style="background: #dc3545;">Delete Account</button>
            </div>
            
            <div class="demo-form" style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                <h4 style="color: #333; margin-bottom: 1rem;">Demo Form with CSRF</h4>
                <form action="<?= $this->url('/user/' . ($user_id ?? '1') . '/update') ?>" method="POST">
                    <?= $this->csrfField() ?>
                    
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" value="<?= $this->old('name', 'Demo User') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" value="<?= $this->old('email', 'demo@example.com') ?>">
                    </div>
                    
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    
    <?php if (isset($data) && !empty($data)): ?>
    <div class="debug-section" style="margin-top: 2rem; padding: 1.5rem; background: #e9ecef; border-radius: 8px;">
        <h3 style="color: #333; margin-bottom: 1rem;">🔍 Debug Information</h3>
        <pre style="background: white; padding: 1rem; border-radius: 4px; overflow-x: auto; font-size: 0.9rem;"><?= $this->escape(print_r($data, true)) ?></pre>
    </div>
    <?php endif; ?>
</div>
<?php $this->endSection(); ?>

<?php $this->section('styles'); ?>
<style>
    .profile-section {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .action-buttons .btn {
        transition: all 0.3s ease;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    function editProfile() {
        alert('Edit Profile functionality would be implemented here!');
    }
    
    function viewSettings() {
        alert('Account Settings functionality would be implemented here!');
    }
    
    function changePassword() {
        alert('Change Password functionality would be implemented here!');
    }
    
    function deleteAccount() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            alert('Delete Account functionality would be implemented here!');
        }
    }
    
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!name || !email) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
                
                console.log('Form submitted with CSRF protection');
            });
        }
    });
</script>
<?php $this->endSection(); ?> 