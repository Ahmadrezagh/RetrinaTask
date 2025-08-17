<?php

namespace Core\Database;

use Core\Database\Connection;
use Core\Database\QueryBuilder;

abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['*'];
    protected $hidden = [];
    protected $visible = [];
    protected $casts = [];
    protected $dates = ['created_at', 'updated_at'];
    protected $attributes = [];
    protected $original = [];
    protected $changes = [];
    protected $exists = false;
    protected $timestamps = true;
    
    protected static $connection;
    protected static $globalScopes = [];
    
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->syncOriginal();
    }
    
    /**
     * Get database connection
     */
    public static function getConnection()
    {
        if (!static::$connection) {
            static::$connection = Connection::getInstance();
        }
        
        return static::$connection;
    }
    
    /**
     * Get a new query builder instance
     */
    public static function query()
    {
        $instance = new static();
        return new QueryBuilder(
            static::getConnection()->getPdo(),
            $instance->getTable(),
            get_called_class()
        );
    }
    
    /**
     * Get table name
     */
    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }
        
        // Generate table name from class name
        $className = class_basename(get_called_class());
        return strtolower($className) . 's';
    }
    
    /**
     * Get primary key
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }
    
    /**
     * Get the value of the primary key
     */
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }
    
    /**
     * Fill the model with attributes
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        
        return $this;
    }
    
    /**
     * Check if attribute is fillable
     */
    public function isFillable($key)
    {
        if (in_array($key, $this->fillable)) {
            return true;
        }
        
        if ($this->isGuarded($key)) {
            return false;
        }
        
        return empty($this->fillable) && !$this->isGuarded($key);
    }
    
    /**
     * Check if attribute is guarded
     */
    public function isGuarded($key)
    {
        return in_array($key, $this->guarded) || $this->guarded === ['*'];
    }
    
    /**
     * Set an attribute on the model
     */
    public function setAttribute($key, $value)
    {
        // Check for mutator
        $method = 'set' . studly($key) . 'Attribute';
        if (method_exists($this, $method)) {
            return $this->{$method}($value);
        }
        
        $this->attributes[$key] = $value;
        
        return $this;
    }
    
    /**
     * Get an attribute from the model
     */
    public function getAttribute($key)
    {
        if (!$key) {
            return null;
        }
        
        // Check for accessor
        $method = 'get' . studly($key) . 'Attribute';
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        
        $value = $this->attributes[$key] ?? null;
        
        // Apply casts
        if (isset($this->casts[$key])) {
            return $this->castAttribute($key, $value);
        }
        
        return $value;
    }
    
    /**
     * Cast an attribute to a specific type
     */
    protected function castAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }
        
        switch ($this->casts[$key]) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return json_decode($value);
            case 'array':
            case 'json':
                return json_decode($value, true);
            case 'datetime':
                return new \DateTime($value);
            default:
                return $value;
        }
    }
    
    /**
     * Get all attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * Sync the original attributes with current
     */
    public function syncOriginal()
    {
        $this->original = $this->attributes;
        return $this;
    }
    
    /**
     * Check if the model exists in database
     */
    public function exists()
    {
        return $this->exists;
    }
    
    /**
     * Save the model to database
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->performUpdate();
        }
        
        return $this->performInsert();
    }
    
    /**
     * Perform model insert
     */
    protected function performInsert()
    {
        if ($this->timestamps) {
            $this->updateTimestamps();
        }
        
        $attributes = $this->attributes;
        
        $id = static::query()->insertGetId($attributes);
        
        if ($id) {
            $this->setAttribute($this->getKeyName(), $id);
            $this->exists = true;
            $this->syncOriginal();
            return true;
        }
        
        return false;
    }
    
    /**
     * Perform model update
     */
    protected function performUpdate()
    {
        if ($this->timestamps) {
            $this->updateTimestamps();
        }
        
        $dirty = $this->getDirty();
        
        if (empty($dirty)) {
            return true;
        }
        
        $updated = static::query()->where($this->getKeyName(), $this->getKey())->update($dirty);
        
        if ($updated) {
            $this->syncOriginal();
        }
        
        return $updated;
    }
    
    /**
     * Update the creation and update timestamps
     */
    protected function updateTimestamps()
    {
        $time = date('Y-m-d H:i:s');
        
        if (!$this->exists && !is_null($this->getCreatedAtColumn())) {
            $this->setAttribute($this->getCreatedAtColumn(), $time);
        }
        
        if (!is_null($this->getUpdatedAtColumn())) {
            $this->setAttribute($this->getUpdatedAtColumn(), $time);
        }
    }
    
    /**
     * Get the name of the "created at" column
     */
    public function getCreatedAtColumn()
    {
        return 'created_at';
    }
    
    /**
     * Get the name of the "updated at" column
     */
    public function getUpdatedAtColumn()
    {
        return 'updated_at';
    }
    
    /**
     * Get the changed attributes
     */
    public function getDirty()
    {
        $dirty = [];
        
        foreach ($this->attributes as $key => $value) {
            if (!array_key_exists($key, $this->original) || $value !== $this->original[$key]) {
                $dirty[$key] = $value;
            }
        }
        
        return $dirty;
    }
    
    /**
     * Delete the model from database
     */
    public function delete()
    {
        if (!$this->exists()) {
            return false;
        }
        
        $deleted = static::query()->where($this->getKeyName(), $this->getKey())->delete();
        
        if ($deleted) {
            $this->exists = false;
        }
        
        return $deleted;
    }
    
    /**
     * Convert the model to an array
     */
    public function toArray()
    {
        $attributes = [];
        
        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, $this->hidden)) {
                if (empty($this->visible) || in_array($key, $this->visible)) {
                    $attributes[$key] = $this->getAttribute($key);
                }
            }
        }
        
        return $attributes;
    }
    
    /**
     * Convert the model to JSON
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
    
    // Static query methods
    
    /**
     * Find a model by its primary key
     */
    public static function find($id)
    {
        return static::query()->find($id);
    }
    
    /**
     * Find a model by its primary key or throw an exception
     */
    public static function findOrFail($id)
    {
        $result = static::find($id);
        
        if (!$result) {
            throw new \Exception("Model not found with ID: {$id}");
        }
        
        return $result;
    }
    
    /**
     * Get all models
     */
    public static function all()
    {
        return static::query()->get();
    }
    
    /**
     * Create a new model instance
     */
    public static function create(array $attributes = [])
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }
    
    /**
     * Add a where clause to the query
     */
    public static function where($column, $operator = null, $value = null)
    {
        return static::query()->where($column, $operator, $value);
    }
    
    /**
     * Dynamic method calls
     */
    public static function __callStatic($method, $parameters)
    {
        return static::query()->{$method}(...$parameters);
    }
    
    /**
     * Dynamic property access
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }
    
    /**
     * Dynamic property setting
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }
    
    /**
     * Check if attribute exists
     */
    public function __isset($key)
    {
        return !is_null($this->getAttribute($key));
    }
    
    /**
     * Convert to string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}

/**
 * Helper function to convert string to StudlyCase
 */
if (!function_exists('studly')) {
    function studly($value) {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
    }
}

/**
 * Helper function to get class basename
 */
if (!function_exists('class_basename')) {
    function class_basename($class) {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
} 