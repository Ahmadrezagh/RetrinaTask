<?php

namespace Core;

class MigrationRunner
{
    private $migrationPath;
    private $migrations = [];
    
    public function __construct($migrationPath = null)
    {
        $this->migrationPath = $migrationPath ?: __DIR__ . '/../database/migrations/';
        $this->loadMigrations();
    }
    
    /**
     * Load migration files and classes
     */
    private function loadMigrations()
    {
        // Load required classes first
        require_once __DIR__ . '/Migration.php';
        require_once __DIR__ . '/Database/Connection.php';
        require_once __DIR__ . '/Database/Schema/Blueprint.php';
        require_once __DIR__ . '/Database/Schema/Builder.php';
        require_once __DIR__ . '/Database/Schema/Schema.php';
        
        $files = glob($this->migrationPath . '*.php');
        sort($files); // Sort files by filename (which includes timestamp)
        
        foreach ($files as $file) {
            require_once $file;
            $content = file_get_contents($file);
            if (preg_match('/class\s+(\w+)\s+extends/', $content, $matches)) {
                $className = $matches[1];
                $this->migrations[] = $className;
            }
        }
        // Don't sort migrations by class name - keep the file order
    }
    
    /**
     * Run pending migrations
     */
    public function migrate()
    {
        foreach ($this->migrations as $migrationClass) {
            try {
                $migration = new $migrationClass();
                
                if (!$migration->hasBeenRun()) {
                    $migration->runUp();
                } else {
                    echo "Migration {$migrationClass} has already been run.\n";
                }
            } catch (\Exception $e) {
                echo "❌ Error running migration {$migrationClass}: " . $e->getMessage() . "\n";
                throw $e;
            }
        }
        
        echo "✅ All migrations completed successfully!\n";
        return true;
    }
    
    /**
     * Rollback migrations
     */
    public function rollback($steps = 1)
    {
        $migrations = array_reverse($this->migrations);
        $rolledBack = 0;
        
        foreach ($migrations as $migrationClass) {
            if ($rolledBack >= $steps) {
                break;
            }
            
            try {
                $migration = new $migrationClass();
                
                if ($migration->hasBeenRun()) {
                    $migration->runDown();
                    $rolledBack++;
                } else {
                    echo "Migration {$migrationClass} has not been run.\n";
                }
            } catch (\Exception $e) {
                echo "❌ Error rolling back migration {$migrationClass}: " . $e->getMessage() . "\n";
                throw $e;
            }
        }
        
        echo "✅ Rolled back {$rolledBack} migration(s) successfully!\n";
    }
    
    /**
     * Rollback all migrations
     */
    public function rollbackAll()
    {
        echo "🔄 Rolling back ALL migrations...\n\n";
        
        $rolledBack = 0;
        $reversedMigrations = array_reverse($this->migrations);
        
        foreach ($reversedMigrations as $migrationClass) {
            try {
                $migration = new $migrationClass();
                
                if ($migration->rollback()) {
                    $rolledBack++;
                }
                
            } catch (\Exception $e) {
                echo "❌ Failed to rollback migration {$migrationClass}: " . $e->getMessage() . "\n";
                return false;
            }
        }
        
        echo "\n🎉 All migrations rolled back!\n";
        echo "   🔄 Rolled back: {$rolledBack} migrations\n";
        
        return true;
    }
    
    /**
     * Show migration status
     */
    public function status()
    {
        echo "📊 Migration Status\n";
        echo "================\n\n";
        
        foreach ($this->migrations as $migrationClass) {
            try {
                $migration = new $migrationClass();
                $hasRun = $this->checkMigrationStatus($migration);
                
                $status = $hasRun ? "✅ Executed" : "⏳ Pending";
                echo sprintf("%-40s %s\n", $migrationClass, $status);
                
            } catch (\Exception $e) {
                echo sprintf("%-40s ❌ Error: %s\n", $migrationClass, $e->getMessage());
            }
        }
        
        echo "\n";
    }
    
    /**
     * Check if a specific migration has run
     */
    private function checkMigrationStatus($migration)
    {
        // Use reflection to access the protected hasRun method
        $reflection = new \ReflectionClass($migration);
        $hasRunMethod = $reflection->getMethod('hasRun');
        $hasRunMethod->setAccessible(true);
        
        return $hasRunMethod->invoke($migration);
    }
    
    /**
     * Run a specific migration
     */
    public function runMigration($migrationName)
    {
        if (!in_array($migrationName, $this->migrations)) {
            echo "❌ Migration '{$migrationName}' not found.\n";
            return false;
        }
        
        try {
            $migration = new $migrationName();
            return $migration->run();
        } catch (\Exception $e) {
            echo "❌ Failed to run migration {$migrationName}: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Rollback a specific migration
     */
    public function rollbackMigration($migrationName)
    {
        if (!in_array($migrationName, $this->migrations)) {
            echo "❌ Migration '{$migrationName}' not found.\n";
            return false;
        }
        
        try {
            $migration = new $migrationName();
            return $migration->rollback();
        } catch (\Exception $e) {
            echo "❌ Failed to rollback migration {$migrationName}: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * List all available migrations
     */
    public function listMigrations()
    {
        echo "📋 Available Migrations\n";
        echo "======================\n\n";
        
        foreach ($this->migrations as $index => $migrationClass) {
            echo ($index + 1) . ". {$migrationClass}\n";
        }
        
        echo "\n";
    }
} 