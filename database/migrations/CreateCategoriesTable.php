<?php

require_once __DIR__ . '/../../core/Migration.php';

use Core\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migration
     */
    public function up()
    {
        $columns = [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            '`name` VARCHAR(100) NOT NULL',
            '`slug` VARCHAR(100) NOT NULL UNIQUE',
            '`description` TEXT NULL',
            '`parent_id` INT NULL',
            '`sort_order` INT DEFAULT 0',
            '`is_active` BOOLEAN DEFAULT TRUE',
            '`meta_title` VARCHAR(255) NULL',
            '`meta_description` TEXT NULL',
            ...$this->timestamps(),
            '',
            '-- Foreign Keys',
            'FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL',
            '',
            '-- Indexes',
            'INDEX `idx_slug` (`slug`)',
            'INDEX `idx_parent_id` (`parent_id`)',
            'INDEX `idx_active` (`is_active`)',
            'INDEX `idx_sort_order` (`sort_order`)'
        ];
        
        $this->createTable('categories', $columns);
        
        // Insert some demo categories
        $this->executeSQL("
            INSERT INTO categories (name, slug, description, sort_order, is_active) VALUES
            ('Technology', 'technology', 'Posts about technology and programming', 1, 1),
            ('Tutorials', 'tutorials', 'Step-by-step guides and tutorials', 2, 1),
            ('News', 'news', 'Latest news and updates', 3, 1),
            ('PHP', 'php', 'PHP programming related content', 4, 1),
            ('Web Development', 'web-development', 'General web development topics', 5, 1)
        ", "Inserting demo categories");
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        $this->dropTable('categories');
    }
} 