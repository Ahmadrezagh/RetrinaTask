<?php
/**
 * Complete Migration Example
 * 
 * This example demonstrates the complete database wrapper functionality
 * for migrations with Laravel-like syntax.
 */

require_once __DIR__ . '/../core/Database/Connection.php';
require_once __DIR__ . '/../core/Database/QueryBuilder.php';
require_once __DIR__ . '/../core/Database/Schema/Blueprint.php';
require_once __DIR__ . '/../core/Database/Schema/Builder.php';
require_once __DIR__ . '/../core/Database/Schema/Schema.php';
require_once __DIR__ . '/../core/Migration.php';

use Core\Database\Schema\Schema;
use Core\Migration;

echo "🚀 Complete Migration Example\n";
echo "============================\n\n";

// =============================================================================
// Example Migration Class Using New Schema Wrapper
// =============================================================================

class ExampleMigration extends Migration
{
    public function up()
    {
        echo "📝 Creating comprehensive example schema...\n\n";
        
        // 1. Create users table with all column types
        $this->create('demo_users', function($table) {
            echo "Creating demo_users table...\n";
            
            $table->id();
            
            // String columns
            $table->string('username', 50)->unique()->comment('Unique username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->text('bio')->nullable();
            
            // Numeric columns
            $table->integer('age')->unsigned()->nullable();
            $table->decimal('balance', 12, 2)->default(0.00);
            $table->float('rating', 3, 2)->default(0.00);
            
            // Boolean columns
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_premium')->default(false);
            
            // Date/time columns
            $table->date('birth_date')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            
            // Special columns
            $table->enum('role', ['user', 'admin', 'moderator'])->default('user');
            $table->json('preferences')->nullable();
            $table->uuid('external_id')->nullable();
            
            // Indexes
            $table->index('email');
            $table->index(['is_active', 'role']);
            $table->index('last_login');
            
            $table->timestamps();
        });
        
        // 2. Create posts table with relationships
        $this->create('demo_posts', function($table) {
            echo "Creating demo_posts table...\n";
            
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->text('excerpt')->nullable();
            
            // Foreign key
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id')->nullable();
            
            // Status and metadata
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Publishing
            $table->timestamp('published_at')->nullable();
            
            // Indexes for performance
            $table->index('author_id');
            $table->index('category_id');
            $table->index(['status', 'published_at']);
            $table->index('is_featured');
            $table->index('slug');
            
            $table->timestamps();
        });
        
        // 3. Create categories table
        $this->create('demo_categories', function($table) {
            echo "Creating demo_categories table...\n";
            
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->nullable(); // Hex color
            $table->string('icon', 50)->nullable();
            
            // Hierarchy support
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            
            // Stats
            $table->integer('post_count')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->index('parent_id');
            $table->index(['is_active', 'sort_order']);
            
            $table->timestamps();
        });
        
        // 4. Create tags table and pivot table
        $this->create('demo_tags', function($table) {
            echo "Creating demo_tags table...\n";
            
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 150)->unique();
            $table->string('color', 7)->nullable();
            $table->integer('usage_count')->default(0);
            
            $table->timestamps();
        });
        
        $this->create('demo_post_tags', function($table) {
            echo "Creating demo_post_tags pivot table...\n";
            
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');
            
            $table->unique(['post_id', 'tag_id']);
            $table->index('post_id');
            $table->index('tag_id');
            
            $table->timestamps();
        });
        
        // 5. Insert sample data
        echo "Inserting sample data...\n";
        
        // Users
        $this->executeSQL("
            INSERT INTO demo_users (username, email, password, first_name, last_name, role, is_active, created_at, updated_at) VALUES
            ('admin', 'admin@demo.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'Admin', 'User', 'admin', 1, datetime('now'), datetime('now')),
            ('john_doe', 'john@demo.com', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'John', 'Doe', 'user', 1, datetime('now'), datetime('now')),
            ('jane_smith', 'jane@demo.com', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'Jane', 'Smith', 'moderator', 1, datetime('now'), datetime('now'))
        ");
        
        // Categories
        $this->executeSQL("
            INSERT INTO demo_categories (name, slug, description, color, sort_order, is_active, created_at, updated_at) VALUES
            ('Technology', 'technology', 'Tech related posts', '#007bff', 1, 1, datetime('now'), datetime('now')),
            ('Lifestyle', 'lifestyle', 'Lifestyle and personal posts', '#28a745', 2, 1, datetime('now'), datetime('now')),
            ('Business', 'business', 'Business and entrepreneurship', '#ffc107', 3, 1, datetime('now'), datetime('now'))
        ");
        
        // Tags
        $this->executeSQL("
            INSERT INTO demo_tags (name, slug, color, created_at, updated_at) VALUES
            ('PHP', 'php', '#777bb4', datetime('now'), datetime('now')),
            ('JavaScript', 'javascript', '#f7df1e', datetime('now'), datetime('now')),
            ('Web Development', 'web-development', '#61dafb', datetime('now'), datetime('now')),
            ('Tutorial', 'tutorial', '#ff6b6b', datetime('now'), datetime('now'))
        ");
        
        // Posts
        $this->executeSQL("
            INSERT INTO demo_posts (title, slug, content, excerpt, author_id, category_id, status, is_featured, published_at, created_at, updated_at) VALUES
            ('Getting Started with PHP', 'getting-started-with-php', 'A comprehensive guide to PHP...', 'Learn PHP basics', 1, 1, 'published', 1, datetime('now'), datetime('now'), datetime('now')),
            ('JavaScript Best Practices', 'javascript-best-practices', 'Modern JavaScript development...', 'JS best practices', 2, 1, 'published', 0, datetime('now'), datetime('now'), datetime('now')),
            ('Building a Startup', 'building-a-startup', 'How to start your own business...', 'Startup guide', 3, 3, 'draft', 0, NULL, datetime('now'), datetime('now'))
        ");
        
        echo "\n✅ Schema created successfully!\n";
    }
    
    public function down()
    {
        echo "🗑️  Dropping demo tables...\n";
        
        $this->dropIfExists('demo_post_tags');
        $this->dropIfExists('demo_tags');
        $this->dropIfExists('demo_posts');
        $this->dropIfExists('demo_categories');
        $this->dropIfExists('demo_users');
        
        echo "✅ Demo tables dropped!\n";
    }
}

// =============================================================================
// Run the example migration
// =============================================================================

echo "1. Creating Example Migration\n";
echo "----------------------------\n";

$migration = new ExampleMigration();

echo "Running up() migration...\n";
$migration->up();

echo "\n2. Testing Created Schema\n";
echo "------------------------\n";

// Test schema introspection
$tables = ['demo_users', 'demo_posts', 'demo_categories', 'demo_tags', 'demo_post_tags'];
foreach ($tables as $table) {
    $exists = Schema::hasTable($table);
    echo "Table '{$table}': " . ($exists ? '✅' : '❌') . "\n";
}

echo "\n3. Testing Data\n";
echo "-------------\n";

// Load DB facade for testing
require_once __DIR__ . '/../core/Database/DB.php';
use Core\Database\DB;

// Test queries
$userCount = DB::table('demo_users')->count();
$postCount = DB::table('demo_posts')->count();
$publishedPosts = DB::table('demo_posts')->where('status', 'published')->count();
$featuredPosts = DB::table('demo_posts')->where('is_featured', 1)->count();

echo "Users: {$userCount}\n";
echo "Posts: {$postCount}\n";
echo "Published: {$publishedPosts}\n";
echo "Featured: {$featuredPosts}\n";

echo "\n4. Complex Query Example\n";
echo "----------------------\n";

// Join query example
$publishedWithAuthors = DB::query("
    SELECT p.title, p.status, u.username as author, c.name as category
    FROM demo_posts p 
    JOIN demo_users u ON p.author_id = u.id 
    LEFT JOIN demo_categories c ON p.category_id = c.id 
    WHERE p.status = 'published'
    ORDER BY p.created_at DESC
");

foreach ($publishedWithAuthors as $post) {
    echo "- '{$post['title']}' by {$post['author']} in {$post['category']}\n";
}

echo "\n5. Cleanup (Running down() migration)\n";
echo "------------------------------------\n";

$migration->down();

echo "\n🎉 Complete Migration Example Finished!\n";
echo "======================================\n\n";

echo "💡 Key Features Demonstrated:\n";
echo "- Laravel-like Schema builder syntax\n";
echo "- All column types (string, text, integer, decimal, boolean, etc.)\n";
echo "- Column modifiers (nullable, default, unique, etc.)\n";
echo "- Indexes (single column, multi-column, unique)\n";
echo "- Database introspection (hasTable, getColumnListing)\n";
echo "- Cross-database compatibility (SQLite, MySQL, PostgreSQL)\n";
echo "- Migration up/down methods\n";
echo "- Sample data insertion\n";
echo "- Integration with existing ORM\n";
echo "- Clean rollback functionality\n\n";

echo "📝 Usage in Real Migrations:\n";
echo "Simply extend the Migration class and use:\n";
echo "- \$this->create('table_name', function(\$table) { ... })\n";
echo "- \$this->table('table_name', function(\$table) { ... })\n";
echo "- \$this->drop('table_name')\n";
echo "- \$this->dropIfExists('table_name')\n";
?> 