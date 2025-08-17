<?php

// Test script for migrate:fresh command
echo "🧪 Testing migrate:fresh command\n";
echo "================================\n\n";

try {
    // Load required files
    require_once 'core/Command/BaseCommand.php';
    require_once 'core/Command/CommandInterface.php';
    require_once 'core/Command/Commands/MigrateFreshCommand.php';
    require_once 'core/MigrationRunner.php';
    require_once 'core/Database/Connection.php';
    require_once 'core/Database/QueryBuilder.php';
    require_once 'core/Database/DB.php';
    
    echo "✅ All required files loaded successfully\n";
    
    // Create command instance
    $command = new \Core\Command\Commands\MigrateFreshCommand();
    
    echo "✅ MigrateFreshCommand instantiated successfully\n";
    echo "📋 Signature: " . $command->getSignature() . "\n";
    echo "📋 Description: " . $command->getDescription() . "\n\n";
    
    // Check database connection
    $connection = \Core\Database\DB::connection();
    $driver = $connection->getAttribute(\PDO::ATTR_DRIVER_NAME);
    echo "🗄️  Database driver: {$driver}\n\n";
    
    echo "💡 To run migrate:fresh:\n";
    echo "   php retrina migrate:fresh\n";
    echo "   php retrina migrate:fresh --seed  (with seeders)\n";
    echo "   php retrina migrate:fresh --force (in production)\n\n";
    
    echo "✅ migrate:fresh command is ready to use!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 