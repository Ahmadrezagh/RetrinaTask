<?php

namespace Core\Database;

use PDO;
use PDOException;

class Connection
{
    protected static $instance;
    protected $pdo;
    
    private function __construct()
    {
        $this->connect();
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
    
    /**
     * Get PDO connection
     */
    public function getPdo()
    {
        return $this->pdo;
    }
    
    /**
     * Establish database connection
     */
    protected function connect()
    {
        if (!class_exists('Core\Environment')) {
            require_once __DIR__ . '/../Environment.php';
            \Core\Environment::load();
        }
        
        $config = require __DIR__ . '/../../config/database.php';
        
        try {
            if ($config['driver'] === 'sqlite') {
                $dsn = "sqlite:" . $config['database'];
                $this->pdo = new PDO($dsn);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } elseif ($config['driver'] === 'mysql') {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
                $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } elseif ($config['driver'] === 'postgres' || $config['driver'] === 'postgresql') {
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } else {
                throw new \Exception("Unsupported database driver: " . $config['driver']);
            }
            
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get a new query builder instance
     */
    public function table($table)
    {
        return new QueryBuilder($this->pdo, $table);
    }
    
    /**
     * Execute a raw query
     */
    public function query($sql, array $bindings = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Execute a raw statement
     */
    public function statement($sql, array $bindings = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bindings);
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->pdo->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }
    
    /**
     * Execute callback in transaction
     */
    public function transaction(callable $callback)
    {
        $this->beginTransaction();
        
        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
} 