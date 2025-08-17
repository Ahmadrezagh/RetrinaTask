<?php

namespace Core;

use PDO;
use PDOException;
use Core\Database\Schema\Schema;

abstract class Migration
{
    protected $db;
    protected $migrationName;
    
    public function __construct()
    {
        $this->connectDatabase();
        $this->migrationName = get_class($this);
        $this->ensureMigrationsTable();
    }
    
    /**
     * Connect to the database
     */
    protected function connectDatabase()
    {
        if (!class_exists('Core\Environment')) {
            require_once __DIR__ . '/Environment.php';
            \Core\Environment::load();
        }
        
        $config = require __DIR__ . '/../config/database.php';
        
        try {
            if ($config['driver'] === 'sqlite') {
                $dsn = "sqlite:" . $config['database'];
                $this->db = new PDO($dsn);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } elseif ($config['driver'] === 'mysql') {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
                $this->db = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } elseif ($config['driver'] === 'postgres' || $config['driver'] === 'postgresql') {
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                $this->db = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            }
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Create the migrations table if it doesn't exist
     */
    protected function ensureMigrationsTable()
    {
        if (!Schema::hasTable('migrations')) {
            Schema::create('migrations', function($table) {
                $table->id();
                $table->string('migration');
                $table->timestamp('executed_at')->nullable();
            });
        }
    }
    
    /**
     * Create a new table using Schema builder
     */
    protected function create($table, $callback)
    {
        return Schema::create($table, $callback);
    }
    
    /**
     * Modify an existing table using Schema builder
     */
    protected function table($table, $callback)
    {
        return Schema::table($table, $callback);
    }
    
    /**
     * Drop a table using Schema builder
     */
    protected function drop($table)
    {
        return Schema::drop($table);
    }
    
    /**
     * Drop a table if it exists using Schema builder
     */
    protected function dropIfExists($table)
    {
        return Schema::dropIfExists($table);
    }
    
    /**
     * Check if a table exists
     */
    protected function hasTable($table)
    {
        return Schema::hasTable($table);
    }
    
    /**
     * Check if a column exists
     */
    protected function hasColumn($table, $column)
    {
        return Schema::hasColumn($table, $column);
    }
    
    /**
     * Rename a table
     */
    protected function rename($from, $to)
    {
        return Schema::rename($from, $to);
    }
    
    // =============================================================================
    // LEGACY METHODS (for backward compatibility)
    // =============================================================================
    
    /**
     * Create a table (legacy method)
     */
    protected function createTable($table, array $columns)
    {
        $sql = $this->buildCreateTableSql($table, $columns);
        return $this->executeSQL($sql, "Creating table: $table");
    }
    
    /**
     * Drop a table (legacy method)
     */
    protected function dropTable($table)
    {
        $sql = "DROP TABLE IF EXISTS `$table`";
        return $this->executeSQL($sql, "Dropping table: $table");
    }
    
    /**
     * Add a column to a table (legacy method)
     */
    protected function addColumn($table, $column, $type, $options = '')
    {
        $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type $options";
        return $this->executeSQL($sql, "Adding column $column to table $table");
    }
    
    /**
     * Drop a column from a table (legacy method)
     */
    protected function dropColumn($table, $column)
    {
        $sql = "ALTER TABLE `$table` DROP COLUMN `$column`";
        return $this->executeSQL($sql, "Dropping column $column from table $table");
    }
    
    /**
     * Create an index (legacy method)
     */
    protected function createIndex($table, $columns, $name = null, $unique = false)
    {
        $type = $unique ? 'UNIQUE INDEX' : 'INDEX';
        $indexName = $name ?: ($table . '_' . implode('_', (array)$columns) . '_index');
        $columnList = is_array($columns) ? implode(', ', $columns) : $columns;
        
        $sql = "CREATE $type `$indexName` ON `$table` ($columnList)";
        return $this->executeSQL($sql, "Creating index $indexName on table $table");
    }
    
    /**
     * Drop an index (legacy method)
     */
    protected function dropIndex($table, $indexName)
    {
        $config = require __DIR__ . '/../config/database.php';
        
        if ($config['driver'] === 'sqlite') {
            $sql = "DROP INDEX IF EXISTS `$indexName`";
        } else {
            $sql = "DROP INDEX `$indexName` ON `$table`";
        }
        
        return $this->executeSQL($sql, "Dropping index $indexName from table $table");
    }
    
    /**
     * Helper method to generate timestamp columns
     */
    protected function timestamps()
    {
        $config = require __DIR__ . '/../config/database.php';
        
        if ($config['driver'] === 'sqlite') {
            return [
                "`created_at` DATETIME DEFAULT (datetime('now'))",
                "`updated_at` DATETIME DEFAULT (datetime('now'))"
            ];
        } else {
            return [
                "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
                "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
            ];
        }
    }
    
    /**
     * Execute SQL with error handling
     */
    protected function executeSQL($sql, $description = null)
    {
        // Replace database-specific functions
        $config = require __DIR__ . '/../config/database.php';
        if ($config['driver'] === 'sqlite') {
            $sql = str_replace(['NOW()', 'CURRENT_TIMESTAMP'], "datetime('now')", $sql);
        } else {
            // For MySQL/PostgreSQL, replace SQLite datetime('now') with NOW()
            $sql = str_replace("datetime('now')", 'NOW()', $sql);
        }
        
        try {
            if ($description) {
                echo "  - $description\n";
            }
            
            $result = $this->db->exec($sql);
            
            if ($result === false) {
                $errorInfo = $this->db->errorInfo();
                throw new \Exception("SQL execution failed: " . $errorInfo[2]);
            }
            
            return true;
        } catch (PDOException $e) {
            throw new \Exception("Database error in migration: " . $e->getMessage() . "\nSQL: $sql");
        }
    }
    
    /**
     * Build CREATE TABLE SQL
     */
    protected function buildCreateTableSql($table, array $columns)
    {
        $config = require __DIR__ . '/../config/database.php';
        
        if ($config['driver'] === 'sqlite') {
            // Convert MySQL syntax to SQLite
            $columns = $this->convertColumnsForSQLite($columns);
        }
        
        $columnDefinitions = implode(',', $columns);
        return "CREATE TABLE IF NOT EXISTS `$table` ($columnDefinitions)";
    }
    
    /**
     * Convert MySQL column definitions to SQLite
     */
    protected function convertColumnsForSQLite(array $columns)
    {
        return array_map(function($column) {
            // Remove MySQL-specific syntax
            $column = preg_replace('/AUTO_INCREMENT/', 'AUTOINCREMENT', $column);
            $column = preg_replace('/UNSIGNED/', '', $column);
            $column = preg_replace('/BIGINT/', 'INTEGER', $column);
            $column = preg_replace('/TINYINT\(1\)/', 'INTEGER', $column);
            $column = preg_replace('/DATETIME/', 'DATETIME', $column);
            
            return trim($column);
        }, $columns);
    }
    
    /**
     * Get database-specific current timestamp function
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
     * Check if this migration has been run
     */
    public function hasBeenRun()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
        $stmt->execute([$this->migrationName]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Mark this migration as run
     */
    protected function markAsRun()
    {
        $now = $this->now();
        $stmt = $this->db->prepare("INSERT INTO migrations (migration, executed_at) VALUES (?, {$now})");
        $stmt->execute([$this->migrationName]);
    }
    
    /**
     * Mark this migration as not run (for rollbacks)
     */
    protected function markAsNotRun()
    {
        $stmt = $this->db->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$this->migrationName]);
    }
    
    /**
     * Run the migration up
     */
    public function runUp()
    {
        if ($this->hasBeenRun()) {
            echo "Migration {$this->migrationName} has already been run.\n";
            return;
        }
        
        echo "Running migration: {$this->migrationName}\n";
        $this->up();
        $this->markAsRun();
        echo "Migration {$this->migrationName} completed successfully.\n";
    }
    
    /**
     * Run the migration down
     */
    public function runDown()
    {
        if (!$this->hasBeenRun()) {
            echo "Migration {$this->migrationName} has not been run.\n";
            return;
        }
        
        echo "Rolling back migration: {$this->migrationName}\n";
        $this->down();
        $this->markAsNotRun();
        echo "Migration {$this->migrationName} rolled back successfully.\n";
    }
    
    // Abstract methods that must be implemented by each migration
    abstract public function up();
    abstract public function down();
}