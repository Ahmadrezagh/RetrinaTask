<?php

require_once __DIR__ . '/../../core/Migration.php';

use Core\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migration
     */
    public function up()
    {
        $columns = [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            '`user_id` INT NOT NULL',
            '`title` VARCHAR(255) NOT NULL',
            '`slug` VARCHAR(255) NOT NULL UNIQUE',
            '`content` TEXT NOT NULL',
            '`excerpt` VARCHAR(500) NULL',
            '`featured_image` VARCHAR(255) NULL',
            '`status` ENUM("draft", "published", "archived") DEFAULT "draft"',
            '`published_at` TIMESTAMP NULL',
            '`view_count` INT DEFAULT 0',
            '`meta_title` VARCHAR(255) NULL',
            '`meta_description` TEXT NULL',
            ...$this->timestamps(),
            '',
            '-- Foreign Keys',
            'FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE',
            '',
            '-- Indexes',
            'INDEX `idx_user_id` (`user_id`)',
            'INDEX `idx_slug` (`slug`)',
            'INDEX `idx_status` (`status`)',
            'INDEX `idx_published_at` (`published_at`)',
            'INDEX `idx_created_at` (`created_at`)'
        ];
        
        $this->createTable('posts', $columns);
        
        // Insert some demo posts
        $nowFunction = $this->now();
        $this->executeSQL("
            INSERT INTO posts (user_id, title, slug, content, excerpt, status, published_at, view_count) VALUES
            (1, 'Welcome to Retrina Framework', 'welcome-to-retrina-framework', 
             'This is the first post in our Retrina Framework blog. The framework provides a powerful, yet simple way to build web applications with PHP.',
             'Introduction to the Retrina Framework and its features.',
             'published', {$nowFunction}, 125),
            (1, 'Understanding MVC Architecture', 'understanding-mvc-architecture',
             'Model-View-Controller (MVC) is a fundamental architectural pattern that separates application logic into three interconnected components.',
             'Learn about the MVC pattern and how it applies to web development.',
             'published', {$nowFunction}, 89),
            (2, 'Template Engine Deep Dive', 'template-engine-deep-dive',
             'The Retrina Framework includes a powerful template engine with Blade-like syntax for creating beautiful, maintainable views.',
             'Exploring the template engine features and syntax.',
             'draft', NULL, 0)
        ", "Inserting demo posts");
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        $this->dropTable('posts');
    }
} 