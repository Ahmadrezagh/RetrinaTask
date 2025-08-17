<?php

namespace Core\Database;

use Core\Database\Connection;

/**
 * Database facade for easy access to query builder
 * Provides Laravel-like DB::table() syntax
 */
class DB
{
    protected static $connection;
    
    /**
     * Get database connection
     */
    protected static function getConnection()
    {
        if (!static::$connection) {
            static::$connection = Connection::getInstance();
        }
        
        return static::$connection;
    }
    
    /**
     * Get a query builder for the given table
     */
    public static function table($table)
    {
        return static::getConnection()->table($table);
    }
    
    /**
     * Execute a raw query
     */
    public static function query($sql, array $bindings = [])
    {
        return static::getConnection()->query($sql, $bindings);
    }
    
    /**
     * Execute a raw statement
     */
    public static function statement($sql, array $bindings = [])
    {
        return static::getConnection()->statement($sql, $bindings);
    }
    
    /**
     * Begin a database transaction
     */
    public static function beginTransaction()
    {
        return static::getConnection()->beginTransaction();
    }
    
    /**
     * Commit a database transaction
     */
    public static function commit()
    {
        return static::getConnection()->commit();
    }
    
    /**
     * Rollback a database transaction
     */
    public static function rollback()
    {
        return static::getConnection()->rollback();
    }
    
    /**
     * Execute a callback within a transaction
     */
    public static function transaction(callable $callback)
    {
        return static::getConnection()->transaction($callback);
    }
    
    /**
     * Get the raw PDO connection
     */
    public static function getPdo()
    {
        return static::getConnection()->getPdo();
    }
} 