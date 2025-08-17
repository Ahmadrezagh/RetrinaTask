<?php

namespace Core\Database\Schema;

/**
 * Blueprint for defining table schema with fluent interface
 * Provides Laravel-like syntax for creating and modifying tables
 */
class Blueprint
{
    protected $table;
    protected $columns = [];
    protected $indexes = [];
    protected $foreignKeys = [];
    protected $commands = [];
    protected $engine;
    protected $charset = 'utf8mb4';
    protected $collation = 'utf8mb4_unicode_ci';
    
    public function __construct($table, $callback = null)
    {
        $this->table = $table;
        
        if ($callback) {
            $callback($this);
        }
    }
    
    /**
     * Get the table name
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * Get all columns
     */
    public function getColumns()
    {
        return $this->columns;
    }
    
    /**
     * Get all commands
     */
    public function getCommands()
    {
        return $this->commands;
    }
    
    // =============================================================================
    // COLUMN TYPES
    // =============================================================================
    
    /**
     * Add an auto-incrementing integer column
     */
    public function id($column = 'id')
    {
        return $this->bigIncrements($column);
    }
    
    /**
     * Add a big auto-incrementing integer column
     */
    public function bigIncrements($column)
    {
        return $this->addColumn('bigIncrements', $column);
    }
    
    /**
     * Add an auto-incrementing integer column
     */
    public function increments($column)
    {
        return $this->addColumn('increments', $column);
    }
    
    /**
     * Add a string column
     */
    public function string($column, $length = 255)
    {
        return $this->addColumn('string', $column, compact('length'));
    }
    
    /**
     * Add a text column
     */
    public function text($column)
    {
        return $this->addColumn('text', $column);
    }
    
    /**
     * Add a medium text column
     */
    public function mediumText($column)
    {
        return $this->addColumn('mediumText', $column);
    }
    
    /**
     * Add a long text column
     */
    public function longText($column)
    {
        return $this->addColumn('longText', $column);
    }
    
    /**
     * Add an integer column
     */
    public function integer($column)
    {
        return $this->addColumn('integer', $column);
    }
    
    /**
     * Add a big integer column
     */
    public function bigInteger($column)
    {
        return $this->addColumn('bigInteger', $column);
    }
    
    /**
     * Add a small integer column
     */
    public function smallInteger($column)
    {
        return $this->addColumn('smallInteger', $column);
    }
    
    /**
     * Add a tiny integer column
     */
    public function tinyInteger($column)
    {
        return $this->addColumn('tinyInteger', $column);
    }
    
    /**
     * Add an unsigned integer column
     */
    public function unsignedInteger($column)
    {
        return $this->addColumn('unsignedInteger', $column);
    }
    
    /**
     * Add an unsigned big integer column
     */
    public function unsignedBigInteger($column)
    {
        return $this->addColumn('unsignedBigInteger', $column);
    }
    
    /**
     * Add a float column
     */
    public function float($column, $precision = 8, $scale = 2)
    {
        return $this->addColumn('float', $column, compact('precision', 'scale'));
    }
    
    /**
     * Add a double column
     */
    public function double($column, $precision = 8, $scale = 2)
    {
        return $this->addColumn('double', $column, compact('precision', 'scale'));
    }
    
    /**
     * Add a decimal column
     */
    public function decimal($column, $precision = 8, $scale = 2)
    {
        return $this->addColumn('decimal', $column, compact('precision', 'scale'));
    }
    
    /**
     * Add a boolean column
     */
    public function boolean($column)
    {
        return $this->addColumn('boolean', $column);
    }
    
    /**
     * Add an enum column
     */
    public function enum($column, array $allowed)
    {
        return $this->addColumn('enum', $column, compact('allowed'));
    }
    
    /**
     * Add a JSON column
     */
    public function json($column)
    {
        return $this->addColumn('json', $column);
    }
    
    /**
     * Add a date column
     */
    public function date($column)
    {
        return $this->addColumn('date', $column);
    }
    
    /**
     * Add a datetime column
     */
    public function dateTime($column)
    {
        return $this->addColumn('dateTime', $column);
    }
    
    /**
     * Add a timestamp column
     */
    public function timestamp($column)
    {
        return $this->addColumn('timestamp', $column);
    }
    
    /**
     * Add nullable timestamp columns for created_at and updated_at
     */
    public function timestamps()
    {
        $this->timestamp('created_at')->nullable();
        $this->timestamp('updated_at')->nullable();
    }
    
    /**
     * Add a time column
     */
    public function time($column)
    {
        return $this->addColumn('time', $column);
    }
    
    /**
     * Add a binary column
     */
    public function binary($column)
    {
        return $this->addColumn('binary', $column);
    }
    
    /**
     * Add a UUID column
     */
    public function uuid($column)
    {
        return $this->addColumn('uuid', $column);
    }
    
    // =============================================================================
    // INDEXES
    // =============================================================================
    
    /**
     * Add a primary key
     */
    public function primary($columns, $name = null)
    {
        $this->addCommand('primary', compact('columns', 'name'));
        return $this;
    }
    
    /**
     * Add a unique index
     */
    public function unique($columns, $name = null)
    {
        $this->addCommand('unique', compact('columns', 'name'));
        return $this;
    }
    
    /**
     * Add an index
     */
    public function index($columns, $name = null)
    {
        $this->addCommand('index', compact('columns', 'name'));
        return $this;
    }
    
    /**
     * Add a foreign key constraint
     */
    public function foreign($columns)
    {
        return new ForeignKeyDefinition($this, $columns);
    }
    
    // =============================================================================
    // TABLE OPERATIONS
    // =============================================================================
    
    /**
     * Drop a column
     */
    public function dropColumn($columns)
    {
        $columns = is_array($columns) ? $columns : func_get_args();
        $this->addCommand('dropColumn', compact('columns'));
        return $this;
    }
    
    /**
     * Drop an index
     */
    public function dropIndex($index)
    {
        $this->addCommand('dropIndex', compact('index'));
        return $this;
    }
    
    /**
     * Drop a unique constraint
     */
    public function dropUnique($index)
    {
        $this->addCommand('dropUnique', compact('index'));
        return $this;
    }
    
    /**
     * Drop a primary key
     */
    public function dropPrimary($index = null)
    {
        $this->addCommand('dropPrimary', compact('index'));
        return $this;
    }
    
    /**
     * Drop a foreign key constraint
     */
    public function dropForeign($index)
    {
        $this->addCommand('dropForeign', compact('index'));
        return $this;
    }
    
    /**
     * Rename the table
     */
    public function rename($to)
    {
        $this->addCommand('rename', compact('to'));
        return $this;
    }
    
    /**
     * Set the storage engine for the table (MySQL)
     */
    public function engine($engine)
    {
        $this->engine = $engine;
        return $this;
    }
    
    /**
     * Set the character set for the table (MySQL)
     */
    public function charset($charset)
    {
        $this->charset = $charset;
        return $this;
    }
    
    /**
     * Set the collation for the table (MySQL)
     */
    public function collation($collation)
    {
        $this->collation = $collation;
        return $this;
    }
    
    // =============================================================================
    // INTERNAL METHODS
    // =============================================================================
    
    /**
     * Add a column to the blueprint
     */
    protected function addColumn($type, $name, $parameters = [])
    {
        $column = new ColumnDefinition([
            'type' => $type,
            'name' => $name
        ] + $parameters);
        
        $this->columns[] = $column;
        
        return $column;
    }
    
    /**
     * Add a command to the blueprint
     */
    protected function addCommand($name, $parameters = [])
    {
        $this->commands[] = [
            'name' => $name,
            'parameters' => $parameters
        ];
        
        return $this;
    }
}

/**
 * Column definition class for fluent column configuration
 */
class ColumnDefinition
{
    protected $attributes;
    
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }
    
    /**
     * Get all attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * Get a specific attribute
     */
    public function get($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }
    
    /**
     * Set an attribute
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }
    
    /**
     * Allow NULL values
     */
    public function nullable($value = true)
    {
        return $this->set('nullable', $value);
    }
    
    /**
     * Set default value
     */
    public function default($value)
    {
        return $this->set('default', $value);
    }
    
    /**
     * Make column unsigned (integers)
     */
    public function unsigned()
    {
        return $this->set('unsigned', true);
    }
    
    /**
     * Add auto increment
     */
    public function autoIncrement()
    {
        return $this->set('autoIncrement', true);
    }
    
    /**
     * Set as primary key
     */
    public function primary()
    {
        return $this->set('primary', true);
    }
    
    /**
     * Add unique constraint
     */
    public function unique()
    {
        return $this->set('unique', true);
    }
    
    /**
     * Add index
     */
    public function index()
    {
        return $this->set('index', true);
    }
    
    /**
     * Add comment
     */
    public function comment($comment)
    {
        return $this->set('comment', $comment);
    }
    
    /**
     * Set column after another column (MySQL)
     */
    public function after($column)
    {
        return $this->set('after', $column);
    }
    
    /**
     * Set column as first (MySQL)
     */
    public function first()
    {
        return $this->set('first', true);
    }
    
    /**
     * Set charset for column (MySQL)
     */
    public function charset($charset)
    {
        return $this->set('charset', $charset);
    }
    
    /**
     * Set collation for column (MySQL)
     */
    public function collation($collation)
    {
        return $this->set('collation', $collation);
    }
}

/**
 * Foreign key definition class
 */
class ForeignKeyDefinition
{
    protected $blueprint;
    protected $columns;
    protected $on;
    protected $references;
    protected $onUpdate;
    protected $onDelete;
    
    public function __construct(Blueprint $blueprint, $columns)
    {
        $this->blueprint = $blueprint;
        $this->columns = is_array($columns) ? $columns : [$columns];
    }
    
    /**
     * Set the referenced table
     */
    public function references($columns)
    {
        $this->references = is_array($columns) ? $columns : [$columns];
        return $this;
    }
    
    /**
     * Set the referenced table
     */
    public function on($table)
    {
        $this->on = $table;
        
        // Add the foreign key command to the blueprint
        $this->blueprint->getCommands()[] = [
            'name' => 'foreign',
            'parameters' => [
                'columns' => $this->columns,
                'references' => $this->references,
                'on' => $this->on,
                'onUpdate' => $this->onUpdate,
                'onDelete' => $this->onDelete
            ]
        ];
        
        return $this;
    }
    
    /**
     * Set the ON UPDATE action
     */
    public function onUpdate($action)
    {
        $this->onUpdate = $action;
        return $this;
    }
    
    /**
     * Set the ON DELETE action
     */
    public function onDelete($action)
    {
        $this->onDelete = $action;
        return $this;
    }
    
    /**
     * Set cascade on update
     */
    public function cascadeOnUpdate()
    {
        return $this->onUpdate('cascade');
    }
    
    /**
     * Set cascade on delete
     */
    public function cascadeOnDelete()
    {
        return $this->onDelete('cascade');
    }
    
    /**
     * Set restrict on update
     */
    public function restrictOnUpdate()
    {
        return $this->onUpdate('restrict');
    }
    
    /**
     * Set restrict on delete
     */
    public function restrictOnDelete()
    {
        return $this->onDelete('restrict');
    }
    
    /**
     * Set null on delete
     */
    public function nullOnDelete()
    {
        return $this->onDelete('set null');
    }
} 