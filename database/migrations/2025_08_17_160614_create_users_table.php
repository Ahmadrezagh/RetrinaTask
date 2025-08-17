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
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('email');
            $table->index('username');
            $table->index(['is_active', 'email_verified_at']);
        });
    }

    public function down()
    {
        $this->dropIfExists('users');
    }
}
