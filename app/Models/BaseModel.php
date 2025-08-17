<?php

namespace App\Models;

use PDO;
use PDOException;

abstract class BaseModel
{
    protected $table;
    protected $fillable = [];
    protected $hidden = [];
    protected $casts = [];
    protected $attributes = [];
    
    protected static $db;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        static::$db = static::getConnection();
    }
    
    /**
     * Get database connection
     */
    protected static function getConnection()
    {
        if (static::$db) {
            return static::$db;
        }
        
        $config = require __DIR__ . '/../../config/database.php';
        
        try {
            if ($config['driver'] === 'sqlite') {
                $dsn = "sqlite:" . $config['database'];
                $pdo = new PDO($dsn);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } elseif ($config['driver'] === 'mysql') {
                $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
                $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } else {
                throw new \Exception("Unsupported database driver: " . $config['driver']);
            }
            
            static::$db = $pdo;
            return $pdo;
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get all records
     */
    public static function all()
    {
        $instance = new static();
        $stmt = static::getConnection()->query("SELECT * FROM " . $instance->table);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($row) {
            return new static($row);
        }, $results);
    }
    
    /**
     * Find record by ID
     */
    public static function find($id)
    {
        $instance = new static();
        $stmt = static::getConnection()->prepare("SELECT * FROM " . $instance->table . " WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? new static($result) : null;
    }
    
    /**
     * Create new record
     */
    public static function create(array $data)
    {
        $instance = new static();
        
        // Filter fillable fields
        $filtered = array_intersect_key($data, array_flip($instance->fillable));
        
        // Add timestamps
        $now = date('Y-m-d H:i:s');
        $filtered['created_at'] = $now;
        $filtered['updated_at'] = $now;
        
        $columns = implode(',', array_keys($filtered));
        $placeholders = ':' . implode(', :', array_keys($filtered));
        
        $sql = "INSERT INTO " . $instance->table . " ($columns) VALUES ($placeholders)";
        $stmt = static::getConnection()->prepare($sql);
        
        // Handle password hashing
        if (isset($filtered['password'])) {
            $filtered['password'] = password_hash($filtered['password'], PASSWORD_DEFAULT);
        }
        
        $stmt->execute($filtered);
        
        // Get the created record
        $id = static::getConnection()->lastInsertId();
        return static::find($id);
    }
    
    /**
     * Simple where clause
     */
    public static function where($column, $value)
    {
        return new static(['where' => [$column, $value]]);
    }
    
    /**
     * Get query results  
     */
    public function get()
    {
        $sql = "SELECT * FROM " . $this->table;
        $params = [];
        
        if (isset($this->attributes['where'])) {
            $sql .= " WHERE " . $this->attributes['where'][0] . " = ?";
            $params[] = $this->attributes['where'][1];
        }
        
        $stmt = static::getConnection()->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($row) {
            return new static($row);
        }, $results);
    }
    
    /**
     * Get first result
     */
    public function first()
    {
        $results = $this->get();
        return count($results) > 0 ? $results[0] : null;
    }
    
    /**
     * Get attribute value
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
    
    /**
     * Set attribute value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Check if attribute exists
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }
} 