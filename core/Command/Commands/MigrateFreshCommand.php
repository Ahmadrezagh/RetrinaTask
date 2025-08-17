<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;
use Core\MigrationRunner;

class MigrateFreshCommand extends BaseCommand
{
    protected $signature = 'migrate:fresh {--seed : Run seeders after migration} {--force : Force the operation to run in production}';
    protected $description = 'Drop all tables and re-run all migrations';
    protected $help = 'This command drops all database tables and re-runs all migrations from scratch.

Usage:
  migrate:fresh [options]

Options:
  --seed               Run database seeders after migrations
  --force              Force run in production environment

Examples:
  php retrina migrate:fresh
  php retrina migrate:fresh --seed
  php retrina migrate:fresh --force';

    public function handle(array $arguments = [], array $options = []): int
    {
        // Load required files
        require_once dirname(__DIR__, 2) . '/MigrationRunner.php';
        require_once dirname(__DIR__, 2) . '/Database/Connection.php';
        require_once dirname(__DIR__, 2) . '/Database/QueryBuilder.php';
        require_once dirname(__DIR__, 2) . '/Database/DB.php';
        
        $runner = new MigrationRunner();
        
        // Check if we're in production and warn
        if (!isset($options['force']) && $this->isProduction()) {
            $this->error("⚠️  WARNING: You are running this command in production!");
            $this->line("This will DROP ALL TABLES and could cause data loss!");
            $this->line("Use --force flag if you really want to proceed.");
            return 1;
        }
        
        try {
            $this->info("🗑️  Dropping all tables...");
            $this->dropAllTables();
            
            $this->info("🔄 Running fresh migrations...");
            $runner->migrate();
            $this->success("✅ Fresh migration completed successfully!");
            
            // Run seeders if requested
            if (isset($options['seed'])) {
                $this->info("🌱 Running database seeders...");
                $this->runSeeders();
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Drop all tables from the database
     */
    private function dropAllTables()
    {
        $connection = \Core\Database\DB::connection();
        $pdo = $connection->getPdo();
        
        // Get database driver
        $driver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
        
        if ($driver === 'mysql') {
            $this->dropAllTablesMysql($pdo);
        } elseif ($driver === 'sqlite') {
            $this->dropAllTablesSqlite($pdo);
        } elseif ($driver === 'pgsql') {
            $this->dropAllTablesPostgres($pdo);
        } else {
            throw new \Exception("Unsupported database driver: {$driver}");
        }
    }
    
    /**
     * Drop all tables for MySQL
     */
    private function dropAllTablesMysql($pdo)
    {
        // Disable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        
        // Get all tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $this->line("   Dropping table: {$table}");
            $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
        }
        
        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
    
    /**
     * Drop all tables for SQLite
     */
    private function dropAllTablesSqlite($pdo)
    {
        // Get all tables
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $this->line("   Dropping table: {$table}");
            $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
        }
    }
    
    /**
     * Drop all tables for PostgreSQL
     */
    private function dropAllTablesPostgres($pdo)
    {
        // Get all tables
        $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
        $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $this->line("   Dropping table: {$table}");
            $pdo->exec("DROP TABLE IF EXISTS \"{$table}\" CASCADE");
        }
    }
    
    /**
     * Run database seeders
     */
    private function runSeeders()
    {
        try {
            // Load seeder files
            require_once dirname(__DIR__, 2) . '/Database/Seeder.php';
            require_once dirname(__DIR__, 2) . '/Database/SeederRunner.php';
            
            // Load the actual seeder classes
            $seedersPath = dirname(__DIR__, 3) . '/database/seeders/';
            require_once $seedersPath . 'DatabaseSeeder.php';
            require_once $seedersPath . 'UserSeeder.php';
            
            $seederRunner = new \Core\Database\SeederRunner();
            $seederRunner->run();
            
            $this->success("✅ Seeders completed successfully!");
            
        } catch (\Exception $e) {
            $this->error("❌ Seeder error: " . $e->getMessage());
        }
    }
    
    /**
     * Check if we're in production environment
     */
    private function isProduction(): bool
    {
        // Load .env if exists
        $envFile = dirname(__DIR__, 3) . '/.env';
        if (file_exists($envFile)) {
            // Use file_get_contents to avoid parse_ini_file issues with parentheses in comments
            $envContent = file_get_contents($envFile);
            return strpos($envContent, 'APP_ENV=production') !== false;
        }
        
        return false;
    }
} 