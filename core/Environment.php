<?php

namespace Core;

class Environment
{
    private static $loaded = false;
    private static $variables = [];
    
    /**
     * Load environment variables from .env file
     */
    public static function load($path = null)
    {
        if (self::$loaded) {
            return;
        }
        
        $envPath = $path ?: dirname(__DIR__) . '/.env';
        
        if (!file_exists($envPath)) {
            // Try to use .env.example as fallback
            $examplePath = dirname($envPath) . '/.env.example';
            if (file_exists($examplePath)) {
                $envPath = $examplePath;
            } else {
                // No env file found, continue with system environment variables only
                self::$loaded = true;
                return;
            }
        }
        
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }
                
                // Set in $_ENV if not already set
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("{$key}={$value}");
                }
                
                // Store in our local cache
                self::$variables[$key] = $value;
            }
        }
        
        self::$loaded = true;
    }
    
    /**
     * Get environment variable with optional default
     */
    public static function get($key, $default = null)
    {
        // Check $_ENV first
        if (array_key_exists($key, $_ENV)) {
            return self::parseValue($_ENV[$key]);
        }
        
        // Check getenv
        $value = getenv($key);
        if ($value !== false) {
            return self::parseValue($value);
        }
        
        // Check our local cache
        if (array_key_exists($key, self::$variables)) {
            return self::parseValue(self::$variables[$key]);
        }
        
        return $default;
    }
    
    /**
     * Set environment variable
     */
    public static function set($key, $value)
    {
        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
        self::$variables[$key] = $value;
    }
    
    /**
     * Check if environment variable exists
     */
    public static function has($key)
    {
        return array_key_exists($key, $_ENV) || 
               getenv($key) !== false || 
               array_key_exists($key, self::$variables);
    }
    
    /**
     * Get all environment variables
     */
    public static function all()
    {
        return array_merge(self::$variables, $_ENV);
    }
    
    /**
     * Parse environment value (convert strings to appropriate types)
     */
    private static function parseValue($value)
    {
        if ($value === '') {
            return '';
        }
        
        // Convert boolean-like strings
        $lower = strtolower($value);
        if (in_array($lower, ['true', 'yes', 'on', '1'])) {
            return true;
        }
        if (in_array($lower, ['false', 'no', 'off', '0'])) {
            return false;
        }
        
        // Convert null-like strings
        if (in_array($lower, ['null', 'nil', ''])) {
            return null;
        }
        
        // Try to convert to number
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float) $value : (int) $value;
        }
        
        return $value;
    }
    
    /**
     * Get environment with type casting
     */
    public static function getString($key, $default = '')
    {
        return (string) self::get($key, $default);
    }
    
    public static function getInt($key, $default = 0)
    {
        return (int) self::get($key, $default);
    }
    
    public static function getFloat($key, $default = 0.0)
    {
        return (float) self::get($key, $default);
    }
    
    public static function getBool($key, $default = false)
    {
        $value = self::get($key, $default);
        if (is_bool($value)) {
            return $value;
        }
        
        $lower = strtolower((string) $value);
        return in_array($lower, ['true', 'yes', 'on', '1']);
    }
    
    public static function getArray($key, $default = [])
    {
        $value = self::get($key, $default);
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value) && !empty($value)) {
            return array_map('trim', explode(',', $value));
        }
        
        return $default;
    }
} 