<?php

namespace Core\Database\Schema;

use Core\Database\Connection;
use PDO;

/**
 * Schema Builder - Converts blueprints to SQL and executes schema operations
 */
class Builder
{
    protected $connection;
    protected $grammar;
    
    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection ?: Connection::getInstance();
        $this->grammar = $this->getGrammar();
    }
    
    /**
     * Create a new table
     */
    public function create($table, $callback)
    {
        $blueprint = new Blueprint($table, $callback);
        return $this->build($blueprint, 'create');
    }
    
    /**
     * Modify an existing table
     */
    public function table($table, $callback)
    {
        $blueprint = new Blueprint($table, $callback);
        return $this->build($blueprint, 'alter');
    }
    
    /**
     * Drop a table
     */
    public function drop($table)
    {
        $sql = $this->grammar->compileDrop($table);
        return $this->connection->statement($sql);
    }
    
    /**
     * Drop a table if it exists
     */
    public function dropIfExists($table)
    {
        $sql = $this->grammar->compileDropIfExists($table);
        return $this->connection->statement($sql);
    }
    
    /**
     * Check if a table exists
     */
    public function hasTable($table)
    {
        $sql = $this->grammar->compileTableExists($table);
        $result = $this->connection->query($sql);
        return count($result) > 0;
    }
    
    /**
     * Check if a column exists
     */
    public function hasColumn($table, $column)
    {
        $sql = $this->grammar->compileColumnExists($table, $column);
        $result = $this->connection->query($sql);
        return count($result) > 0;
    }
    
    /**
     * Get column listing for a table
     */
    public function getColumnListing($table)
    {
        $sql = $this->grammar->compileColumnListing($table);
        $results = $this->connection->query($sql);
        
        return array_map(function($row) {
            return reset($row); // Get first column value
        }, $results);
    }
    
    /**
     * Rename a table
     */
    public function rename($from, $to)
    {
        $sql = $this->grammar->compileRename($from, $to);
        return $this->connection->statement($sql);
    }
    
    /**
     * Build the blueprint into SQL statements and execute them
     */
    protected function build(Blueprint $blueprint, $operation)
    {
        $statements = $this->grammar->compile($blueprint, $operation);
        
        $results = [];
        foreach ($statements as $statement) {
            $results[] = $this->connection->statement($statement);
        }
        
        return !in_array(false, $results);
    }
    
    /**
     * Get the appropriate grammar for the connection
     */
    protected function getGrammar()
    {
        $driver = $this->getConnectionDriver();
        
        switch ($driver) {
            case 'mysql':
                return new MySqlGrammar();
            case 'postgres':
            case 'postgresql':
                return new PostgresGrammar();
            case 'sqlite':
                return new SQLiteGrammar();
            default:
                return new MySqlGrammar(); // Default fallback
        }
    }
    
    /**
     * Get the connection driver
     */
    protected function getConnectionDriver()
    {
        try {
            $pdo = $this->connection->getPdo();
            return $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        } catch (\Exception $e) {
            // Fallback to config
            $config = require __DIR__ . '/../../../config/database.php';
            return $config['driver'] ?? 'mysql';
        }
    }
}

/**
 * Base Grammar class for SQL generation
 */
abstract class Grammar
{
    /**
     * Compile a blueprint into SQL statements
     */
    public function compile(Blueprint $blueprint, $operation)
    {
        $statements = [];
        
        if ($operation === 'create') {
            $statements[] = $this->compileCreate($blueprint);
        } elseif ($operation === 'alter') {
            $statements = array_merge($statements, $this->compileAlter($blueprint));
        }
        
        return array_filter($statements);
    }
    
    /**
     * Compile CREATE TABLE statement
     */
    protected function compileCreate(Blueprint $blueprint)
    {
        $columns = $this->compileColumns($blueprint);
        $commands = $this->compileCommands($blueprint);
        
        $sql = 'CREATE TABLE ' . $this->wrapTable($blueprint->getTable()) . ' (';
        $sql .= implode(', ', array_merge($columns, $commands));
        $sql .= ')';
        
        return $sql;
    }
    
    /**
     * Compile ALTER TABLE statements
     */
    protected function compileAlter(Blueprint $blueprint)
    {
        $statements = [];
        $table = $this->wrapTable($blueprint->getTable());
        
        // Compile column additions
        $columns = $this->compileColumns($blueprint);
        foreach ($columns as $column) {
            $statements[] = "ALTER TABLE {$table} ADD COLUMN {$column}";
        }
        
        // Compile other commands
        foreach ($blueprint->getCommands() as $command) {
            $method = 'compile' . ucfirst($command['name']);
            if (method_exists($this, $method)) {
                $statements[] = $this->{$method}($blueprint, $command);
            }
        }
        
        return $statements;
    }
    
    /**
     * Compile columns
     */
    protected function compileColumns(Blueprint $blueprint)
    {
        $columns = [];
        
        foreach ($blueprint->getColumns() as $column) {
            $columns[] = $this->compileColumn($column);
        }
        
        return $columns;
    }
    
    /**
     * Compile a single column
     */
    protected function compileColumn(ColumnDefinition $column)
    {
        $sql = $this->wrap($column->get('name'));
        $sql .= ' ' . $this->getType($column);
        
        // Add modifiers
        $modifiers = ['nullable', 'default', 'autoIncrement'];
        foreach ($modifiers as $modifier) {
            $method = 'modify' . ucfirst($modifier);
            if (method_exists($this, $method)) {
                $sql = $this->{$method}($sql, $column);
            }
        }
        
        return $sql;
    }
    
    /**
     * Compile commands (indexes, constraints, etc.)
     */
    protected function compileCommands(Blueprint $blueprint)
    {
        $commands = [];
        
        foreach ($blueprint->getCommands() as $command) {
            $method = 'compile' . ucfirst($command['name']);
            if (method_exists($this, $method)) {
                $result = $this->{$method}($blueprint, $command);
                if ($result) {
                    $commands[] = $result;
                }
            }
        }
        
        return $commands;
    }
    
    /**
     * Wrap a table name
     */
    protected function wrapTable($table)
    {
        return $this->wrap($table);
    }
    
    /**
     * Wrap a column name
     */
    protected function wrap($value)
    {
        return '`' . str_replace('`', '``', $value) . '`';
    }
    
    // Abstract methods to be implemented by specific grammars
    abstract protected function getType(ColumnDefinition $column);
    abstract public function compileDrop($table);
    abstract public function compileDropIfExists($table);
    abstract public function compileTableExists($table);
    abstract public function compileColumnExists($table, $column);
    abstract public function compileColumnListing($table);
    abstract public function compileRename($from, $to);
}

/**
 * MySQL Grammar
 */
class MySqlGrammar extends Grammar
{
    protected function getType(ColumnDefinition $column)
    {
        switch ($column->get('type')) {
            case 'bigIncrements':
                return 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY';
            case 'increments':
                return 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY';
            case 'string':
                return 'VARCHAR(' . ($column->get('length', 255)) . ')';
            case 'text':
                return 'TEXT';
            case 'mediumText':
                return 'MEDIUMTEXT';
            case 'longText':
                return 'LONGTEXT';
            case 'integer':
                return 'INT';
            case 'bigInteger':
                return 'BIGINT';
            case 'smallInteger':
                return 'SMALLINT';
            case 'tinyInteger':
                return 'TINYINT';
            case 'unsignedInteger':
                return 'INT UNSIGNED';
            case 'unsignedBigInteger':
                return 'BIGINT UNSIGNED';
            case 'float':
                return 'FLOAT(' . $column->get('precision', 8) . ',' . $column->get('scale', 2) . ')';
            case 'double':
                return 'DOUBLE(' . $column->get('precision', 8) . ',' . $column->get('scale', 2) . ')';
            case 'decimal':
                return 'DECIMAL(' . $column->get('precision', 8) . ',' . $column->get('scale', 2) . ')';
            case 'boolean':
                return 'BOOLEAN';
            case 'enum':
                $values = implode("','", $column->get('allowed', []));
                return "ENUM('{$values}')";
            case 'json':
                return 'JSON';
            case 'date':
                return 'DATE';
            case 'dateTime':
                return 'DATETIME';
            case 'timestamp':
                return 'TIMESTAMP';
            case 'time':
                return 'TIME';
            case 'binary':
                return 'BLOB';
            case 'uuid':
                return 'CHAR(36)';
            default:
                return 'VARCHAR(255)';
        }
    }
    
    protected function modifyNullable($sql, ColumnDefinition $column)
    {
        return $sql . ($column->get('nullable') ? ' NULL' : ' NOT NULL');
    }
    
    protected function modifyDefault($sql, ColumnDefinition $column)
    {
        if ($column->get('default') !== null) {
            $default = $column->get('default');
            if (is_string($default)) {
                $default = "'{$default}'";
            } elseif (is_bool($default)) {
                $default = $default ? '1' : '0';
            }
            return $sql . " DEFAULT {$default}";
        }
        return $sql;
    }
    
    protected function modifyAutoIncrement($sql, ColumnDefinition $column)
    {
        if ($column->get('autoIncrement')) {
            return $sql . ' AUTO_INCREMENT';
        }
        return $sql;
    }
    
    public function compileDrop($table)
    {
        return 'DROP TABLE ' . $this->wrapTable($table);
    }
    
    public function compileDropIfExists($table)
    {
        return 'DROP TABLE IF EXISTS ' . $this->wrapTable($table);
    }
    
    public function compileTableExists($table)
    {
        return "SELECT * FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = '{$table}'";
    }
    
    public function compileColumnExists($table, $column)
    {
        return "SELECT * FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '{$table}' AND column_name = '{$column}'";
    }
    
    public function compileColumnListing($table)
    {
        return "SELECT column_name FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '{$table}' ORDER BY ordinal_position";
    }
    
    public function compileRename($from, $to)
    {
        return 'RENAME TABLE ' . $this->wrapTable($from) . ' TO ' . $this->wrapTable($to);
    }
}

/**
 * SQLite Grammar
 */
class SQLiteGrammar extends Grammar
{
    protected function getType(ColumnDefinition $column)
    {
        switch ($column->get('type')) {
            case 'bigIncrements':
            case 'increments':
                return 'INTEGER PRIMARY KEY AUTOINCREMENT';
            case 'string':
                return 'VARCHAR(' . ($column->get('length', 255)) . ')';
            case 'text':
            case 'mediumText':
            case 'longText':
                return 'TEXT';
            case 'integer':
            case 'bigInteger':
            case 'smallInteger':
            case 'tinyInteger':
            case 'unsignedInteger':
            case 'unsignedBigInteger':
                return 'INTEGER';
            case 'float':
            case 'double':
            case 'decimal':
                return 'REAL';
            case 'boolean':
                return 'INTEGER';
            case 'enum':
                return 'TEXT';
            case 'json':
                return 'TEXT';
            case 'date':
            case 'dateTime':
            case 'timestamp':
                return 'DATETIME';
            case 'time':
                return 'TIME';
            case 'binary':
                return 'BLOB';
            case 'uuid':
                return 'TEXT';
            default:
                return 'TEXT';
        }
    }
    
    protected function modifyNullable($sql, ColumnDefinition $column)
    {
        return $sql . ($column->get('nullable') ? '' : ' NOT NULL');
    }
    
    protected function modifyDefault($sql, ColumnDefinition $column)
    {
        if ($column->get('default') !== null) {
            $default = $column->get('default');
            if (is_string($default)) {
                $default = "'{$default}'";
            } elseif (is_bool($default)) {
                $default = $default ? '1' : '0';
            }
            return $sql . " DEFAULT {$default}";
        }
        return $sql;
    }
    
    protected function modifyAutoIncrement($sql, ColumnDefinition $column)
    {
        // Auto increment is handled by the type for SQLite
        return $sql;
    }
    
    public function compileDrop($table)
    {
        return 'DROP TABLE ' . $this->wrapTable($table);
    }
    
    public function compileDropIfExists($table)
    {
        return 'DROP TABLE IF EXISTS ' . $this->wrapTable($table);
    }
    
    public function compileTableExists($table)
    {
        return "SELECT name FROM sqlite_master WHERE type='table' AND name='{$table}'";
    }
    
    public function compileColumnExists($table, $column)
    {
        return "PRAGMA table_info(`{$table}`)";
    }
    
    public function compileColumnListing($table)
    {
        return "PRAGMA table_info(`{$table}`)";
    }
    
    public function compileRename($from, $to)
    {
        return 'ALTER TABLE ' . $this->wrapTable($from) . ' RENAME TO ' . $this->wrapTable($to);
    }
    
    protected function wrap($value)
    {
        return '`' . str_replace('`', '``', $value) . '`';
    }
}

/**
 * PostgreSQL Grammar
 */
class PostgresGrammar extends Grammar
{
    protected function getType(ColumnDefinition $column)
    {
        switch ($column->get('type')) {
            case 'bigIncrements':
                return 'BIGSERIAL PRIMARY KEY';
            case 'increments':
                return 'SERIAL PRIMARY KEY';
            case 'string':
                return 'VARCHAR(' . ($column->get('length', 255)) . ')';
            case 'text':
            case 'mediumText':
            case 'longText':
                return 'TEXT';
            case 'integer':
                return 'INTEGER';
            case 'bigInteger':
                return 'BIGINT';
            case 'smallInteger':
                return 'SMALLINT';
            case 'tinyInteger':
                return 'SMALLINT';
            case 'unsignedInteger':
            case 'unsignedBigInteger':
                return 'INTEGER';
            case 'float':
            case 'double':
                return 'DOUBLE PRECISION';
            case 'decimal':
                return 'DECIMAL(' . $column->get('precision', 8) . ',' . $column->get('scale', 2) . ')';
            case 'boolean':
                return 'BOOLEAN';
            case 'enum':
                return 'VARCHAR(255)';
            case 'json':
                return 'JSON';
            case 'date':
                return 'DATE';
            case 'dateTime':
            case 'timestamp':
                return 'TIMESTAMP';
            case 'time':
                return 'TIME';
            case 'binary':
                return 'BYTEA';
            case 'uuid':
                return 'UUID';
            default:
                return 'VARCHAR(255)';
        }
    }
    
    protected function modifyNullable($sql, ColumnDefinition $column)
    {
        return $sql . ($column->get('nullable') ? '' : ' NOT NULL');
    }
    
    protected function modifyDefault($sql, ColumnDefinition $column)
    {
        if ($column->get('default') !== null) {
            $default = $column->get('default');
            if (is_string($default)) {
                $default = "'{$default}'";
            } elseif (is_bool($default)) {
                $default = $default ? 'true' : 'false';
            }
            return $sql . " DEFAULT {$default}";
        }
        return $sql;
    }
    
    protected function modifyAutoIncrement($sql, ColumnDefinition $column)
    {
        // Auto increment is handled by SERIAL types
        return $sql;
    }
    
    public function compileDrop($table)
    {
        return 'DROP TABLE ' . $this->wrapTable($table);
    }
    
    public function compileDropIfExists($table)
    {
        return 'DROP TABLE IF EXISTS ' . $this->wrapTable($table);
    }
    
    public function compileTableExists($table)
    {
        return "SELECT * FROM information_schema.tables WHERE table_name = '{$table}'";
    }
    
    public function compileColumnExists($table, $column)
    {
        return "SELECT * FROM information_schema.columns WHERE table_name = '{$table}' AND column_name = '{$column}'";
    }
    
    public function compileColumnListing($table)
    {
        return "SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}' ORDER BY ordinal_position";
    }
    
    public function compileRename($from, $to)
    {
        return 'ALTER TABLE ' . $this->wrapTable($from) . ' RENAME TO ' . $this->wrapTable($to);
    }
    
    protected function wrap($value)
    {
        return '"' . str_replace('"', '""', $value) . '"';
    }
} 