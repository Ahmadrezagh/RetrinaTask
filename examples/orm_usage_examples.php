<?php
/**
 * Retrina Framework - ORM Usage Examples
 * 
 * This file demonstrates how to use the Laravel-like ORM features
 * in the Retrina Framework for database operations.
 */

require_once __DIR__ . '/../core/Database/Connection.php';
require_once __DIR__ . '/../core/Database/QueryBuilder.php';
require_once __DIR__ . '/../core/Database/DB.php';
require_once __DIR__ . '/../core/helpers.php';

use Core\Database\DB;

echo "🚀 Retrina Framework ORM Usage Examples\n";
echo "=====================================\n\n";

// =============================================================================
// 1. BASIC SELECT OPERATIONS
// =============================================================================

echo "1. Basic SELECT Operations:\n";
echo "-------------------------\n";

// Get all records
$allUsers = DB::table('users')->get();
echo "DB::table('users')->get() - Found " . count($allUsers) . " users\n";

// Get first record
$firstUser = DB::table('users')->first();
echo "DB::table('users')->first() - First user: " . $firstUser['username'] . "\n";

// Find by ID
$user = DB::table('users')->find(1);
echo "DB::table('users')->find(1) - User: " . $user['email'] . "\n";

// Using helper function
$users = db('users')->get();
echo "db('users')->get() - Found " . count($users) . " users\n\n";

// =============================================================================
// 2. WHERE CLAUSES
// =============================================================================

echo "2. WHERE Clauses:\n";
echo "---------------\n";

// Basic where
$activeUsers = DB::table('users')->where('is_active', 1)->get();
echo "->where('is_active', 1) - Active users: " . count($activeUsers) . "\n";

// Where with operator
$admins = DB::table('users')->where('username', '=', 'admin')->get();
echo "->where('username', '=', 'admin') - Admins: " . count($admins) . "\n";

// Multiple where conditions
$activeAdmin = DB::table('users')
    ->where('is_active', 1)
    ->where('username', 'admin')
    ->first();
echo "Multiple where conditions - Found: " . ($activeAdmin ? 'Yes' : 'No') . "\n";

// Or where
$adminOrDemo = DB::table('users')
    ->where('username', 'admin')
    ->orWhere('username', 'demo')
    ->get();
echo "->orWhere() - Admin or Demo users: " . count($adminOrDemo) . "\n";

// Where with LIKE
$emailSearch = DB::table('users')
    ->where('email', 'LIKE', '%@retrina.local')
    ->get();
echo "->where('email', 'LIKE', '%@retrina.local') - Found: " . count($emailSearch) . "\n";

// Where IN
$specificUsers = DB::table('users')
    ->whereIn('username', ['admin', 'demo', 'test'])
    ->get();
echo "->whereIn('username', [...]) - Found: " . count($specificUsers) . "\n";

// Where NULL
$unverified = DB::table('users')
    ->whereNull('email_verified_at')
    ->get();
echo "->whereNull('email_verified_at') - Unverified: " . count($unverified) . "\n\n";

// =============================================================================
// 3. ORDERING AND LIMITING
// =============================================================================

echo "3. Ordering and Limiting:\n";
echo "-----------------------\n";

// Order by
$orderedUsers = DB::table('users')
    ->orderBy('username', 'asc')
    ->get();
echo "->orderBy('username', 'asc') - First user: " . $orderedUsers[0]['username'] . "\n";

// Limit
$limitedUsers = DB::table('users')
    ->limit(1)
    ->get();
echo "->limit(1) - Limited to: " . count($limitedUsers) . " record(s)\n";

// Offset
$offsetUsers = DB::table('users')
    ->offset(1)
    ->limit(1)
    ->get();
echo "->offset(1)->limit(1) - User: " . $offsetUsers[0]['username'] . "\n";

// Using take() and skip() aliases
$takenUsers = DB::table('users')
    ->orderBy('id')
    ->take(2)
    ->get();
echo "->take(2) - Taken: " . count($takenUsers) . " users\n\n";

// =============================================================================
// 4. AGGREGATE FUNCTIONS
// =============================================================================

echo "4. Aggregate Functions:\n";
echo "---------------------\n";

$userCount = DB::table('users')->count();
echo "->count() - Total users: " . $userCount . "\n";

$maxId = DB::table('users')->max('id');
echo "->max('id') - Highest ID: " . $maxId . "\n";

$minId = DB::table('users')->min('id');
echo "->min('id') - Lowest ID: " . $minId . "\n";

// Check if records exist
$hasActiveUsers = DB::table('users')->where('is_active', 1)->exists();
echo "->exists() - Has active users: " . ($hasActiveUsers ? 'Yes' : 'No') . "\n\n";

// =============================================================================
// 5. INSERT OPERATIONS
// =============================================================================

echo "5. Insert Operations:\n";
echo "-------------------\n";

// Insert and get ID
$newUserId = DB::table('users')->insertGetId([
    'username' => 'orm_example_' . time(),
    'email' => 'orm_example_' . time() . '@example.com',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
    'first_name' => 'ORM',
    'last_name' => 'Example',
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
]);
echo "->insertGetId([...]) - Created user with ID: " . $newUserId . "\n";

// Regular insert
$inserted = DB::table('users')->insert([
    'username' => 'batch_user_' . time(),
    'email' => 'batch_' . time() . '@example.com',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
    'first_name' => 'Batch',
    'last_name' => 'User',
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
]);
echo "->insert([...]) - Insert successful: " . ($inserted ? 'Yes' : 'No') . "\n\n";

// =============================================================================
// 6. UPDATE OPERATIONS
// =============================================================================

echo "6. Update Operations:\n";
echo "-------------------\n";

// Update specific record
$updated = DB::table('users')
    ->where('id', $newUserId)
    ->update([
        'last_name' => 'Updated',
        'updated_at' => date('Y-m-d H:i:s')
    ]);
echo "->update([...]) - Updated " . $updated . " record(s)\n";

// Update multiple records
$updatedMultiple = DB::table('users')
    ->where('first_name', 'ORM')
    ->update(['last_name' => 'Multiple Updated']);
echo "Update multiple - Updated " . $updatedMultiple . " record(s)\n\n";

// =============================================================================
// 7. ADVANCED QUERIES
// =============================================================================

echo "7. Advanced Queries:\n";
echo "------------------\n";

// Method chaining
$complexQuery = DB::table('users')
    ->where('is_active', 1)
    ->where('email', 'LIKE', '%@%')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();
echo "Complex chained query - Found: " . count($complexQuery) . " users\n";

// Select specific columns
$selectedColumns = DB::table('users')
    ->select('id', 'username', 'email')
    ->where('is_active', 1)
    ->get();
echo "->select('id', 'username', 'email') - Got " . count($selectedColumns) . " users\n";

// Where between
$recent = DB::table('users')
    ->whereBetween('id', [1, 5])
    ->get();
echo "->whereBetween('id', [1, 5]) - Found: " . count($recent) . " users\n\n";

// =============================================================================
// 8. RAW QUERIES
// =============================================================================

echo "8. Raw Queries:\n";
echo "--------------\n";

// Raw select
$rawResults = DB::query('SELECT COUNT(*) as total, is_active FROM users GROUP BY is_active');
foreach ($rawResults as $result) {
    $status = $result['is_active'] ? 'Active' : 'Inactive';
    echo "Raw query - " . $status . " users: " . $result['total'] . "\n";
}

// Raw statement
$executed = DB::statement('UPDATE users SET updated_at = ? WHERE id = ?', [
    date('Y-m-d H:i:s'),
    $newUserId
]);
echo "Raw statement executed: " . ($executed ? 'Success' : 'Failed') . "\n\n";

// =============================================================================
// 9. TRANSACTIONS
// =============================================================================

echo "9. Transactions:\n";
echo "--------------\n";

DB::transaction(function() use ($newUserId) {
    // Multiple operations in a transaction
    DB::table('users')
        ->where('id', $newUserId)
        ->update(['first_name' => 'Transaction']);
    
    echo "Transaction operations completed successfully\n";
});

// Manual transaction control
DB::beginTransaction();
try {
    DB::table('users')
        ->where('id', $newUserId)
        ->update(['last_name' => 'Manual Transaction']);
    
    DB::commit();
    echo "Manual transaction committed\n";
} catch (Exception $e) {
    DB::rollback();
    echo "Manual transaction rolled back: " . $e->getMessage() . "\n";
}

echo "\n";

// =============================================================================
// 10. HELPER FUNCTIONS
// =============================================================================

echo "10. Helper Functions:\n";
echo "-------------------\n";

// Using the db() helper
$helperUsers = db('users')->where('is_active', 1)->count();
echo "db('users')->where('is_active', 1)->count() - Count: " . $helperUsers . "\n";

// Raw connection
$connection = db();
$rawUsers = $connection->query('SELECT COUNT(*) as total FROM users');
echo "Raw connection query - Total: " . $rawUsers[0]['total'] . "\n\n";

// =============================================================================
// 11. REAL-WORLD EXAMPLES
// =============================================================================

echo "11. Real-World Examples:\n";
echo "-----------------------\n";

// Pagination example
function getUsersPaginated($page = 1, $perPage = 10) {
    $offset = ($page - 1) * $perPage;
    
    return [
        'data' => DB::table('users')
            ->where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->limit($perPage)
            ->offset($offset)
            ->get(),
        'total' => DB::table('users')->where('is_active', 1)->count()
    ];
}

$page1 = getUsersPaginated(1, 2);
echo "Pagination example - Page 1: " . count($page1['data']) . " users, Total: " . $page1['total'] . "\n";

// Search function
function searchUsers($term) {
    return DB::table('users')
        ->where('first_name', 'LIKE', "%{$term}%")
        ->orWhere('last_name', 'LIKE', "%{$term}%")
        ->orWhere('email', 'LIKE', "%{$term}%")
        ->orWhere('username', 'LIKE', "%{$term}%")
        ->get();
}

$searchResults = searchUsers('Admin');
echo "Search example ('Admin') - Found: " . count($searchResults) . " users\n";

// User statistics
function getUserStats() {
    $total = DB::table('users')->count();
    $active = DB::table('users')->where('is_active', 1)->count();
    $inactive = $total - $active;
    
    return compact('total', 'active', 'inactive');
}

$stats = getUserStats();
echo "User statistics - Total: {$stats['total']}, Active: {$stats['active']}, Inactive: {$stats['inactive']}\n\n";

echo "✅ ORM Examples Complete!\n";
echo "========================\n\n";

echo "Available ORM Features:\n";
echo "- Laravel-like syntax with method chaining\n";
echo "- WHERE clauses with multiple operators (=, !=, >, <, >=, <=, LIKE)\n";
echo "- WHERE IN, WHERE NOT IN, WHERE NULL, WHERE NOT NULL\n";
echo "- WHERE BETWEEN for range queries\n";
echo "- ORDER BY with ASC/DESC\n";
echo "- LIMIT and OFFSET for pagination\n";
echo "- Aggregate functions (COUNT, MAX, MIN, AVG, SUM)\n";
echo "- INSERT with automatic ID return\n";
echo "- UPDATE with WHERE conditions\n";
echo "- DELETE operations\n";
echo "- Raw SQL queries and statements\n";
echo "- Database transactions (automatic and manual)\n";
echo "- SELECT with specific columns\n";
echo "- EXISTS checks\n";
echo "- SQLite/MySQL/PostgreSQL compatibility\n";
echo "- Boolean value handling for SQLite\n";
echo "- Connection pooling and management\n";
echo "- Helper functions for easy access\n\n";

echo "Usage Patterns:\n";
echo "- DB::table('table_name')->method()\n";
echo "- db('table_name')->method() (helper function)\n";
echo "- DB::query('raw sql', [bindings])\n";
echo "- DB::transaction(function() { ... })\n";
?> 