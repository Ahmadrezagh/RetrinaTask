<?php

require_once __DIR__ . '/../../core/Migration.php';

use Core\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migration
     */
    public function up()
    {
        $columns = [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            '`username` VARCHAR(50) NOT NULL UNIQUE',
            '`email` VARCHAR(100) NOT NULL UNIQUE',
            '`password` VARCHAR(255) NOT NULL',
            '`first_name` VARCHAR(50) NULL',
            '`last_name` VARCHAR(50) NULL',
            '`avatar` VARCHAR(255) NULL',
            '`email_verified_at` TIMESTAMP NULL',
            '`is_active` BOOLEAN DEFAULT TRUE',
            '`last_login_at` TIMESTAMP NULL',
            '`remember_token` VARCHAR(100) NULL',
            ...$this->timestamps(),
            '',
            '-- Indexes',
            'INDEX `idx_email` (`email`)',
            'INDEX `idx_username` (`username`)',
            'INDEX `idx_active` (`is_active`)',
            'INDEX `idx_last_login` (`last_login_at`)'
        ];
        
        $this->createTable('users', $columns);
        
        // Insert some demo users
        $this->executeSQL("
            INSERT INTO users (username, email, password, first_name, last_name, is_active) VALUES
            ('admin', 'admin@retrina.local', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'Admin', 'User', 1),
            ('demo', 'demo@retrina.local', '" . password_hash('demo123', PASSWORD_DEFAULT) . "', 'Demo', 'User', 1),
            ('test', 'test@retrina.local', '" . password_hash('test123', PASSWORD_DEFAULT) . "', 'Test', 'User', 1)
        ", "Inserting demo users");
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        $this->dropTable('users');
    }
} 