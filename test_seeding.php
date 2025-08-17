<?php

// Test script for database seeding
require_once 'core/Database/Connection.php';
require_once 'core/Database/QueryBuilder.php';
require_once 'core/Database/DB.php';
require_once 'core/Database/Seeder.php';
require_once 'core/Database/SeederRunner.php';

try {
    echo "🌱 Testing Database Seeding System\n";
    echo "==================================\n\n";
    
    // Load seeder files
    require_once 'database/seeders/DatabaseSeeder.php';
    require_once 'database/seeders/UserSeeder.php';
    
    // Test creating just the UserSeeder
    echo "🧑‍💼 Running UserSeeder...\n";
    $userSeeder = new UserSeeder();
    $userSeeder->run();
    
    echo "\n✅ Seeding test completed!\n";
    
    // Verify the users were created
    echo "\n📋 Verifying users in database:\n";
    $users = \Core\Database\DB::table('users')->select(['username', 'email', 'role'])->get();
    
    foreach ($users as $user) {
        echo "   👤 {$user['username']} ({$user['email']}) - Role: {$user['role']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 