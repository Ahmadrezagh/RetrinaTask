<?php

use Core\Database\Seeder;
use Core\Database\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run()
    {
        // Clear existing users (optional - be careful in production!)
        // $this->delete('users');
        
        echo "   🧑‍💼 Creating admin and user accounts...\n";
        
        // Create admin user
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@retrina.local',
            'password' => $this->hash('admin123'),
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => $this->now(),
            'created_at' => $this->now(),
            'updated_at' => $this->now(),
        ];
        
        // Create regular user
        $userData = [
            'username' => 'user',
            'email' => 'user@retrina.local',
            'password' => $this->hash('user123'),
            'first_name' => 'Regular',
            'last_name' => 'User',
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => $this->now(),
            'created_at' => $this->now(),
            'updated_at' => $this->now(),
        ];
        
        // Check if users already exist
        $existingAdmin = DB::table('users')->where('username', 'admin')->first();
        $existingUser = DB::table('users')->where('username', 'user')->first();
        
        if (!$existingAdmin) {
            DB::table('users')->insert($adminData);
            echo "   ✅ Admin user created (admin/admin123)\n";
        } else {
            echo "   ⚠️  Admin user already exists, skipping...\n";
        }
        
        if (!$existingUser) {
            DB::table('users')->insert($userData);
            echo "   ✅ Regular user created (user/user123)\n";
        } else {
            echo "   ⚠️  Regular user already exists, skipping...\n";
        }
        
        echo "   📧 Admin email: admin@retrina.local\n";
        echo "   📧 User email: user@retrina.local\n";
        echo "   🔐 Both accounts are active and email verified\n";
    }
} 