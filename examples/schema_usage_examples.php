<?php
/**
 * Retrina Framework - Schema Usage Examples
 * 
 * This file demonstrates how to use the new database schema wrapper
 * with Laravel-like syntax for creating and modifying tables.
 */

require_once __DIR__ . '/../core/Database/Connection.php';
require_once __DIR__ . '/../core/Database/Schema/Blueprint.php';
require_once __DIR__ . '/../core/Database/Schema/Builder.php';
require_once __DIR__ . '/../core/Database/Schema/Schema.php';

use Core\Database\Schema\Schema;

echo "🚀 Retrina Framework Schema Usage Examples\n";
echo "==========================================\n\n";

// =============================================================================
// 1. BASIC TABLE CREATION
// =============================================================================

echo "1. Basic Table Creation:\n";
echo "----------------------\n";

// Create a simple table
echo "Creating 'posts' table...\n";
Schema::create('posts', function($table) {
    $table->id(); // Auto-incrementing primary key
    $table->string('title');
    $table->text('content');
    $table->boolean('published')->default(false);
    $table->timestamps(); // created_at and updated_at
});
echo "✅ Posts table created successfully!\n\n";

// =============================================================================
// 2. ADVANCED COLUMN TYPES
// =============================================================================

echo "2. Advanced Column Types:\n";
echo "------------------------\n";

echo "Creating 'products' table with various column types...\n";
Schema::create('products', function($table) {
    $table->id();
    
    // String columns
    $table->string('name', 100)->nullable(false);
    $table->string('slug', 150)->unique();
    $table->text('description');
    $table->longText('detailed_description')->nullable();
    
    // Numeric columns
    $table->decimal('price', 10, 2);
    $table->integer('stock_quantity')->default(0);
    $table->unsignedBigInteger('category_id');
    $table->float('weight', 8, 2)->nullable();
    
    // Boolean and enum
    $table->boolean('is_active')->default(true);
    $table->enum('status', ['draft', 'active', 'inactive', 'discontinued']);
    
    // Date and time
    $table->date('release_date')->nullable();
    $table->dateTime('last_updated')->nullable();
    
    // JSON and UUID
    $table->json('metadata')->nullable();
    $table->uuid('external_id')->nullable();
    
    // Timestamps
    $table->timestamps();
});
echo "✅ Products table created with advanced column types!\n\n";

// =============================================================================
// 3. INDEXES AND CONSTRAINTS
// =============================================================================

echo "3. Indexes and Constraints:\n";
echo "-------------------------\n";

echo "Creating 'orders' table with indexes and foreign keys...\n";
Schema::create('orders', function($table) {
    $table->id();
    $table->string('order_number', 50)->unique();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('product_id');
    $table->decimal('total_amount', 10, 2);
    $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
    $table->timestamps();
    
    // Indexes
    $table->index('user_id');
    $table->index('product_id');
    $table->index(['status', 'created_at']);
    
    // Foreign keys (would work if referenced tables exist)
    // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
    // $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
});
echo "✅ Orders table created with indexes!\n\n";

// =============================================================================
// 4. TABLE MODIFICATIONS
// =============================================================================

echo "4. Table Modifications:\n";
echo "---------------------\n";

// Add columns to existing table
echo "Adding columns to 'posts' table...\n";
Schema::table('posts', function($table) {
    $table->string('author_name', 100)->nullable();
    $table->integer('view_count')->default(0);
    $table->text('excerpt')->nullable();
    $table->boolean('featured')->default(false);
});
echo "✅ Columns added to posts table!\n\n";

// =============================================================================
// 5. COLUMN MODIFIERS AND OPTIONS
// =============================================================================

echo "5. Column Modifiers and Options:\n";
echo "-------------------------------\n";

echo "Creating 'users_extended' table with column modifiers...\n";
Schema::create('users_extended', function($table) {
    $table->id();
    
    // String with various modifiers
    $table->string('username', 50)->unique()->comment('Unique username');
    $table->string('email')->unique()->nullable(false);
    $table->string('password')->comment('Hashed password');
    
    // Numbers with modifiers
    $table->integer('age')->unsigned()->nullable();
    $table->decimal('balance', 12, 2)->default(0.00);
    
    // Dates with defaults
    $table->timestamp('email_verified_at')->nullable();
    $table->timestamp('last_login_at')->nullable();
    
    // Boolean with default
    $table->boolean('is_active')->default(true);
    $table->boolean('is_admin')->default(false);
    
    // Text fields
    $table->text('bio')->nullable();
    $table->json('preferences')->nullable();
    
    $table->timestamps();
});
echo "✅ Users extended table created with modifiers!\n\n";

// =============================================================================
// 6. REAL-WORLD EXAMPLE: E-COMMERCE SCHEMA
// =============================================================================

echo "6. Real-World Example - E-commerce Schema:\n";
echo "-----------------------------------------\n";

// Categories table
echo "Creating 'categories' table...\n";
Schema::create('categories', function($table) {
    $table->id();
    $table->string('name', 100);
    $table->string('slug', 150)->unique();
    $table->text('description')->nullable();
    $table->string('image_url')->nullable();
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index('parent_id');
    $table->index(['is_active', 'sort_order']);
});

// Product variants table
echo "Creating 'product_variants' table...\n";
Schema::create('product_variants', function($table) {
    $table->id();
    $table->unsignedBigInteger('product_id');
    $table->string('sku', 100)->unique();
    $table->string('name', 150);
    $table->decimal('price', 10, 2);
    $table->decimal('compare_price', 10, 2)->nullable();
    $table->integer('stock_quantity')->default(0);
    $table->float('weight', 8, 2)->nullable();
    $table->json('attributes')->nullable(); // color, size, etc.
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index('product_id');
    $table->index('sku');
    $table->index(['is_active', 'stock_quantity']);
});

// Order items table
echo "Creating 'order_items' table...\n";
Schema::create('order_items', function($table) {
    $table->id();
    $table->unsignedBigInteger('order_id');
    $table->unsignedBigInteger('product_id');
    $table->unsignedBigInteger('variant_id')->nullable();
    $table->string('product_name'); // Snapshot of name at time of order
    $table->decimal('unit_price', 10, 2);
    $table->integer('quantity');
    $table->decimal('total_price', 10, 2);
    $table->json('product_data')->nullable(); // Snapshot of product data
    $table->timestamps();
    
    $table->index('order_id');
    $table->index('product_id');
});

echo "✅ E-commerce schema created successfully!\n\n";

// =============================================================================
// 7. SCHEMA INTROSPECTION
// =============================================================================

echo "7. Schema Introspection:\n";
echo "----------------------\n";

// Check if tables exist
$tables = ['posts', 'products', 'orders', 'categories'];
foreach ($tables as $table) {
    $exists = Schema::hasTable($table);
    echo "Table '{$table}' exists: " . ($exists ? "✅ Yes" : "❌ No") . "\n";
}

echo "\n";

// Check columns in posts table
if (Schema::hasTable('posts')) {
    echo "Columns in 'posts' table:\n";
    $columns = Schema::getColumnListing('posts');
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
}

echo "\n";

// Check specific columns
$columnsToCheck = [
    ['posts', 'title'],
    ['posts', 'author_name'],
    ['products', 'price'],
    ['nonexistent', 'column']
];

foreach ($columnsToCheck as [$table, $column]) {
    $exists = Schema::hasColumn($table, $column);
    echo "Column '{$table}.{$column}' exists: " . ($exists ? "✅ Yes" : "❌ No") . "\n";
}

echo "\n";

// =============================================================================
// 8. MIGRATION-STYLE USAGE
// =============================================================================

echo "8. Migration-Style Usage:\n";
echo "------------------------\n";

// Example of how this would be used in a migration
class CreateBlogSchema
{
    public function up()
    {
        // Blog posts
        Schema::create('blog_posts', function($table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('view_count')->default(0);
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index('author_id');
            $table->index('category_id');
            $table->index(['status', 'published_at']);
            $table->index('featured');
        });
        
        // Blog comments
        Schema::create('blog_comments', function($table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'spam', 'rejected'])->default('pending');
            $table->unsignedBigInteger('parent_id')->nullable(); // For threaded comments
            $table->timestamps();
            
            $table->index('post_id');
            $table->index('user_id');
            $table->index(['status', 'created_at']);
            $table->index('parent_id');
        });
        
        // Tags
        Schema::create('blog_tags', function($table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->nullable(); // Hex color
            $table->integer('post_count')->default(0);
            $table->timestamps();
        });
        
        // Post-Tag pivot table
        Schema::create('blog_post_tags', function($table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
            
            $table->unique(['post_id', 'tag_id']);
            $table->index('post_id');
            $table->index('tag_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('blog_post_tags');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_posts');
    }
}

echo "Running blog schema creation...\n";
$blogMigration = new CreateBlogSchema();
$blogMigration->up();
echo "✅ Blog schema created successfully!\n\n";

// =============================================================================
// 9. CLEANUP EXAMPLE
// =============================================================================

echo "9. Cleanup Example:\n";
echo "------------------\n";

// Drop some test tables
$tablesToDrop = ['users_extended', 'product_variants', 'order_items'];
foreach ($tablesToDrop as $table) {
    if (Schema::hasTable($table)) {
        echo "Dropping table '{$table}'...\n";
        Schema::dropIfExists($table);
        echo "✅ Table '{$table}' dropped!\n";
    }
}

echo "\n";

// =============================================================================
// SUMMARY
// =============================================================================

echo "✅ Schema Usage Examples Complete!\n";
echo "==================================\n\n";

echo "Available Schema Methods:\n";
echo "- Schema::create(\$table, \$callback) - Create new table\n";
echo "- Schema::table(\$table, \$callback) - Modify existing table\n";
echo "- Schema::drop(\$table) - Drop table\n";
echo "- Schema::dropIfExists(\$table) - Drop table if exists\n";
echo "- Schema::hasTable(\$table) - Check if table exists\n";
echo "- Schema::hasColumn(\$table, \$column) - Check if column exists\n";
echo "- Schema::getColumnListing(\$table) - Get all columns\n";
echo "- Schema::rename(\$from, \$to) - Rename table\n\n";

echo "Available Column Types:\n";
echo "- \$table->id() - Auto-incrementing primary key\n";
echo "- \$table->string(\$name, \$length) - VARCHAR column\n";
echo "- \$table->text(\$name) - TEXT column\n";
echo "- \$table->integer(\$name) - INTEGER column\n";
echo "- \$table->decimal(\$name, \$precision, \$scale) - DECIMAL column\n";
echo "- \$table->boolean(\$name) - BOOLEAN column\n";
echo "- \$table->date(\$name) - DATE column\n";
echo "- \$table->dateTime(\$name) - DATETIME column\n";
echo "- \$table->timestamp(\$name) - TIMESTAMP column\n";
echo "- \$table->json(\$name) - JSON column\n";
echo "- \$table->uuid(\$name) - UUID column\n";
echo "- \$table->enum(\$name, \$values) - ENUM column\n";
echo "- \$table->timestamps() - created_at and updated_at\n\n";

echo "Available Column Modifiers:\n";
echo "- ->nullable() - Allow NULL values\n";
echo "- ->default(\$value) - Set default value\n";
echo "- ->unique() - Add unique constraint\n";
echo "- ->index() - Add index\n";
echo "- ->primary() - Set as primary key\n";
echo "- ->unsigned() - Make unsigned (numbers)\n";
echo "- ->comment(\$text) - Add comment\n\n";

echo "Available Indexes and Constraints:\n";
echo "- \$table->primary(\$columns) - Primary key\n";
echo "- \$table->unique(\$columns) - Unique index\n";
echo "- \$table->index(\$columns) - Regular index\n";
echo "- \$table->foreign(\$column)->references(\$column)->on(\$table) - Foreign key\n\n";

echo "Database Compatibility:\n";
echo "✅ SQLite - Full support with type conversion\n";
echo "✅ MySQL - Full support with all features\n";
echo "✅ PostgreSQL - Full support with type mapping\n\n";

echo "Usage in Migrations:\n";
echo "Simply use \$this->create(), \$this->table(), etc. in your migration files!\n";
?> 