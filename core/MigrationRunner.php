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
     * Load all migration files
     */
    private function loadMigrations()
    {
        $files = glob($this->migrationPath . '*.php');
        
        foreach ($files as $file) {
            require_once $file;
            $className = basename($file, '.php');
            $this->migrations[] = $className;
        }
        
        // Sort migrations alphabetically for consistent order
        sort($this->migrations);
    }
    
    /**
     * Run all pending migrations
     */
    public function migrate()
    {
        echo "🚀 Starting database migrations...\n\n";
        
        $executed = 0;
        $skipped = 0;
        
        foreach ($this->migrations as $migrationClass) {
            try {
                $migration = new $migrationClass();
                
                if ($migration->run()) {
                    $executed++;
                } else {
                    $skipped++;
                }
                
            } catch (\Exception $e) {
                echo "❌ Failed to run migration {$migrationClass}: " . $e->getMessage() . "\n";
                return false;
            }
        }
        
        echo "\n🎉 Migration completed!\n";
        echo "   ✅ Executed: {$executed}\n";
        echo "   ⏭️  Skipped: {$skipped}\n";
        
        return true;
    }
    
    /**
     * Rollback the last migration
     */
    public function rollback($steps = 1)
    {
        echo "🔄 Rolling back migrations...\n\n";
        
        $rolledBack = 0;
        $reversedMigrations = array_reverse($this->migrations);
        
        foreach ($reversedMigrations as $migrationClass) {
            if ($rolledBack >= $steps) {
                break;
            }
            
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
        
        echo "\n🎉 Rollback completed!\n";
        echo "   🔄 Rolled back: {$rolledBack} migrations\n";
        
        return true;
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