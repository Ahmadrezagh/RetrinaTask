<?php

require_once __DIR__ . '/../../core/Migration.php';

use Core\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $columns = [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            '`username` VARCHAR(50) NOT NULL UNIQUE',
            '`email` VARCHAR(100) NOT NULL UNIQUE',
            '`password` VARCHAR(255) NOT NULL',
            '`first_name` VARCHAR(50) NOT NULL',
            '`last_name` VARCHAR(50) NOT NULL',
            '`avatar` VARCHAR(255) NULL',
            '`email_verified_at` TIMESTAMP NULL',
            '`is_active` BOOLEAN NOT NULL DEFAULT TRUE',
            '`last_login_at` TIMESTAMP NULL',
            '`remember_token` VARCHAR(100) NULL',
            ...$this->timestamps()
        ];
        
        $this->createTable('users', $columns);
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
