<?php

namespace Core;

use PDO;
use PDOException;

abstract class Migration
{
    protected $db;
    protected $migrationName;
    
    public function __construct()
    {
        $this->db = $this->getConnection();
        $this->migrationName = $this->getMigrationName();
    }
    
    /**
     * Get database connection
     */
    protected function getConnection()
    {
        try {
            $config = require __DIR__ . '/../config/database.php';
            
            if ($config['driver'] === 'sqlite') {
                // Create SQLite database directory if it doesn't exist
                $dbPath = $config['database'];
                $dbDir = dirname($dbPath);
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0755, true);
                }
                
                $dsn = "sqlite:{$dbPath}";
                return new PDO($dsn, null, null, $config['options']);
            } else {
                // MySQL connection
                $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
                return new PDO($dsn, $config['username'], $config['password'], $config['options']);
            }
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get migration name from class name
     */
    protected function getMigrationName()
    {
        $className = get_class($this);
        $parts = explode('\\', $className);
        return end($parts);
    }
    
    /**
     * Run the migration
     */
    public function run()
    {
        if ($this->hasRun()) {
            echo "Migration {$this->migrationName} has already been executed.\n";
            return false;
        }
        
        try {
            $this->db->beginTransaction();
            
            echo "Running migration: {$this->migrationName}...\n";
            $this->up();
            
            $this->markAsRun();
            $this->db->commit();
            
            echo "✅ Migration {$this->migrationName} completed successfully.\n";
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            echo "❌ Migration {$this->migrationName} failed: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
    
    /**
     * Rollback the migration
     */
    public function rollback()
    {
        if (!$this->hasRun()) {
            echo "Migration {$this->migrationName} has not been executed yet.\n";
            return false;
        }
        
        try {
            $this->db->beginTransaction();
            
            echo "Rolling back migration: {$this->migrationName}...\n";
            $this->down();
            
            $this->markAsNotRun();
            $this->db->commit();
            
            echo "✅ Migration {$this->migrationName} rolled back successfully.\n";
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            echo "❌ Migration rollback {$this->migrationName} failed: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
    
    /**
     * Check if migration has been run
     */
    protected function hasRun()
    {
        $this->ensureMigrationsTable();
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
        $stmt->execute([$this->migrationName]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Mark migration as run
     */
    protected function markAsRun()
    {
        $this->ensureMigrationsTable();
        
        $stmt = $this->db->prepare("
            INSERT INTO migrations (migration, executed_at) 
            VALUES (?, NOW())
        ");
        $stmt->execute([$this->migrationName]);
    }
    
    /**
     * Mark migration as not run (for rollbacks)
     */
    protected function markAsNotRun()
    {
        $stmt = $this->db->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$this->migrationName]);
    }
    
    /**
     * Ensure migrations table exists
     */
    protected function ensureMigrationsTable()
    {
        $config = require __DIR__ . '/../config/database.php';
        
        if ($config['driver'] === 'sqlite') {
            $sql = "
                CREATE TABLE IF NOT EXISTS migrations (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    migration TEXT NOT NULL UNIQUE,
                    executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ";
        } else {
            $sql = "
                CREATE TABLE IF NOT EXISTS migrations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255) NOT NULL UNIQUE,
                    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_migration (migration)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
        }
        
        $this->db->exec($sql);
    }
    
    /**
     * Helper method to create table
     */
    protected function createTable($tableName, $columns, $options = [])
    {
        $config = require __DIR__ . '/../config/database.php';
        
        // Filter out MySQL-specific syntax for SQLite
        if ($config['driver'] === 'sqlite') {
            $columns = array_map(function($column) {
                // Skip comments and empty lines
                if (strpos($column, '-- ') === 0 || trim($column) === '') {
                    return null;
                }
                
                // Skip MySQL-specific constraints and indexes
                if (strpos($column, 'FOREIGN KEY') !== false ||
                    strpos($column, 'INDEX ') !== false ||
                    strpos($column, 'ENGINE=') !== false) {
                    return null;
                }
                
                // Convert MySQL AUTO_INCREMENT primary key to SQLite
                if (strpos($column, 'AUTO_INCREMENT PRIMARY KEY') !== false) {
                    $column = str_replace('INT AUTO_INCREMENT PRIMARY KEY', 'INTEGER PRIMARY KEY AUTOINCREMENT', $column);
                    $column = str_replace('AUTO_INCREMENT PRIMARY KEY', 'PRIMARY KEY AUTOINCREMENT', $column);
                }
                
                // Convert other MySQL types to SQLite equivalents
                $column = str_replace('BOOLEAN', 'INTEGER', $column);
                
                // Handle timestamps more carefully
                if (strpos($column, 'TIMESTAMP') !== false) {
                    $column = str_replace('TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'DATETIME DEFAULT (datetime(\'now\'))', $column);
                    $column = str_replace('TIMESTAMP DEFAULT CURRENT_TIMESTAMP', 'DATETIME DEFAULT (datetime(\'now\'))', $column);
                    $column = str_replace('TIMESTAMP NULL', 'DATETIME NULL', $column);
                    $column = str_replace('TIMESTAMP NOT NULL', 'DATETIME NOT NULL', $column);
                    $column = str_replace('TIMESTAMP', 'DATETIME', $column);
                }
                
                // Clean up any remaining ON UPDATE clauses
                $column = preg_replace('/\s+ON\s+UPDATE\s+\S+/', '', $column);
                
                // Clean up extra spaces
                $column = preg_replace('/\s+/', ' ', trim($column));
                
                return $column;
            }, $columns);
            
            // Remove null entries
            $columns = array_filter($columns, function($column) {
                return $column !== null;
            });
            
            $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (\n";
            $sql .= "    " . implode(",\n    ", $columns) . "\n";
            $sql .= ")";
        } else {
            // MySQL version
            $defaultOptions = [
                'engine' => 'InnoDB',
                'charset' => 'utf8mb4',
                'collate' => 'utf8mb4_unicode_ci'
            ];
            
            $options = array_merge($defaultOptions, $options);
            
            $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (\n";
            $sql .= "    " . implode(",\n    ", $columns) . "\n";
            $sql .= ") ENGINE={$options['engine']} DEFAULT CHARSET={$options['charset']} COLLATE={$options['collate']}";
        }
        
        echo "Creating table: {$tableName}\n";
        $this->db->exec($sql);
    }
    
    /**
     * Helper method to drop table
     */
    protected function dropTable($tableName)
    {
        $sql = "DROP TABLE IF EXISTS `{$tableName}`";
        echo "Dropping table: {$tableName}\n";
        $this->db->exec($sql);
    }
    
    /**
     * Helper method to add column
     */
    protected function addColumn($tableName, $columnDefinition, $after = null)
    {
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN {$columnDefinition}";
        if ($after) {
            $sql .= " AFTER `{$after}`";
        }
        
        echo "Adding column to {$tableName}: {$columnDefinition}\n";
        $this->db->exec($sql);
    }
    
    /**
     * Helper method to drop column
     */
    protected function dropColumn($tableName, $columnName)
    {
        $sql = "ALTER TABLE `{$tableName}` DROP COLUMN `{$columnName}`";
        echo "Dropping column {$columnName} from {$tableName}\n";
        $this->db->exec($sql);
    }
    
    /**
     * Helper method to add index
     */
    protected function addIndex($tableName, $columns, $indexName = null, $type = 'INDEX')
    {
        if (!$indexName) {
            $indexName = 'idx_' . implode('_', (array)$columns);
        }
        
        $columnList = is_array($columns) ? implode('`, `', $columns) : $columns;
        $sql = "ALTER TABLE `{$tableName}` ADD {$type} `{$indexName}` (`{$columnList}`)";
        
        echo "Adding {$type} {$indexName} to {$tableName}\n";
        $this->db->exec($sql);
    }
    
    /**
     * Helper method to drop index
     */
    protected function dropIndex($tableName, $indexName)
    {
        $sql = "ALTER TABLE `{$tableName}` DROP INDEX `{$indexName}`";
        echo "Dropping index {$indexName} from {$tableName}\n";
        $this->db->exec($sql);
    }
    
    /**
     * Execute raw SQL with database-specific adjustments
     */
    protected function executeSQL($sql, $description = null)
    {
        if ($description) {
            echo $description . "\n";
        }
        
        // Get database driver for SQL adjustments
        $config = require __DIR__ . '/../config/database.php';
        
        if ($config['driver'] === 'sqlite') {
            // Replace MySQL-specific functions with SQLite equivalents
            $sql = str_replace('NOW()', "datetime('now')", $sql);
            $sql = str_replace('CURRENT_TIMESTAMP', "datetime('now')", $sql);
        }
        
        $this->db->exec($sql);
    }
    
    /**
     * Get database-specific NOW() function
     */
    protected function now()
    {
        $config = require __DIR__ . '/../config/database.php';
        
        if ($config['driver'] === 'sqlite') {
            return "datetime('now')";
        } else {
            return "NOW()";
        }
    }
    
    /**
     * Get current timestamp for default values
     */
    protected function timestamp()
    {
        return 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP';
    }
    
    /**
     * Get updated timestamp for default values
     */
    protected function timestamps()
    {
        $config = require __DIR__ . '/../config/database.php';
        
        if ($config['driver'] === 'sqlite') {
            return [
                '`created_at` DATETIME DEFAULT (datetime(\'now\'))',
                '`updated_at` DATETIME DEFAULT (datetime(\'now\'))'
            ];
        } else {
            return [
                '`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                '`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            ];
        }
    }
    
    /**
     * Migration up - implement in child classes
     */
    abstract public function up();
    
    /**
     * Migration down - implement in child classes
     */
    abstract public function down();
}