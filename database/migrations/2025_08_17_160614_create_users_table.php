<?php

use Core\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // Drop table if it exists (since we're updating the migration)
        $this->dropIfExists('users');
        
        // Create users table with new schema wrapper
        $this->create('users', function($table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('email');
            $table->index('username');
            $table->index(['is_active', 'email_verified_at']);
        });
        
        // Insert demo users
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $demoPassword = password_hash('demo123', PASSWORD_DEFAULT);
        $testPassword = password_hash('test123', PASSWORD_DEFAULT);
        
        $this->executeSQL("
            INSERT INTO users (username, email, password, first_name, last_name, is_active, created_at, updated_at) VALUES
            ('admin', 'admin@retrina.local', '{$adminPassword}', 'Admin', 'User', 1, NOW(), NOW()),
            ('demo', 'demo@retrina.local', '{$demoPassword}', 'Demo', 'User', 1, NOW(), NOW()),
            ('test', 'test@retrina.local', '{$testPassword}', 'Test', 'User', 1, NOW(), NOW())
        ", "Inserting demo users");
    }

    public function down()
    {
        $this->dropIfExists('users');
    }
}
