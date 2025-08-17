<?php

namespace Core\Database\Schema;

/**
 * Schema facade for easy access to schema operations
 * Provides Laravel-like Schema::create() syntax
 */
class Schema
{
    protected static $builder;
    
    /**
     * Get the schema builder instance
     */
    protected static function getBuilder()
    {
        if (!static::$builder) {
            static::$builder = new Builder();
        }
        
        return static::$builder;
    }
    
    /**
     * Create a new table
     */
    public static function create($table, $callback)
    {
        return static::getBuilder()->create($table, $callback);
    }
    
    /**
     * Modify an existing table
     */
    public static function table($table, $callback)
    {
        return static::getBuilder()->table($table, $callback);
    }
    
    /**
     * Drop a table
     */
    public static function drop($table)
    {
        return static::getBuilder()->drop($table);
    }
    
    /**
     * Drop a table if it exists
     */
    public static function dropIfExists($table)
    {
        return static::getBuilder()->dropIfExists($table);
    }
    
    /**
     * Check if a table exists
     */
    public static function hasTable($table)
    {
        return static::getBuilder()->hasTable($table);
    }
    
    /**
     * Check if a column exists
     */
    public static function hasColumn($table, $column)
    {
        return static::getBuilder()->hasColumn($table, $column);
    }
    
    /**
     * Get column listing for a table
     */
    public static function getColumnListing($table)
    {
        return static::getBuilder()->getColumnListing($table);
    }
    
    /**
     * Rename a table
     */
    public static function rename($from, $to)
    {
        return static::getBuilder()->rename($from, $to);
    }
    
    /**
     * Set a custom schema builder
     */
    public static function setBuilder(Builder $builder)
    {
        static::$builder = $builder;
    }
} 