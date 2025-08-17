<?php

use Core\Session;
use Core\Environment;

/**
 * Global Helper Functions for Retrina Framework
 * These functions are available throughout the application
 */

if (!function_exists('view')) {
    /**
     * Render a view
     */
    function view($template, $data = [], $layout = null) {
        $viewEngine = new \Core\ViewEngine();
        return $viewEngine->render($template, $data, $layout);
    }
}

if (!function_exists('escape')) {
    /**
     * Escape HTML output
     */
    function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url')) {
    /**
     * Generate URL
     */
    function url($path = '')
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $baseUrl = $protocol . '://' . $host . dirname($script);
        
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * Generate asset URL
     */
    function asset($path)
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to URL
     */
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     */
    function old($key, $default = '') {
        return Session::getFlash('old_' . $key, $default);
    }
}

if (!function_exists('session')) {
    /**
     * Get/Set session values
     */
    function session($key = null, $default = null) {
        if ($key === null) {
            return new Session();
        }
        return Session::get($key, $default);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generate CSRF token
     */
    function csrf_token() {
        return Session::getCsrfToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF hidden field
     */
    function csrf_field() {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate method field for forms
     */
    function method_field($method)
    {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     */
    function config($key, $default = null) {
        // Simple config helper - can be expanded later
        static $configs = [];
        
        if (strpos($key, '.') !== false) {
            [$file, $configKey] = explode('.', $key, 2);
            
            if (!isset($configs[$file])) {
                $configFile = __DIR__ . '/../config/' . $file . '.php';
                if (file_exists($configFile)) {
                    $configs[$file] = require $configFile;
                } else {
                    $configs[$file] = [];
                }
            }
            
            return $configs[$file][$configKey] ?? $default;
        }
        
        return $default;
    }
}

if (!function_exists('env')) {
    /**
     * Get environment variable
     */
    function env($key, $default = null) {
        return Environment::get($key, $default);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die (for debugging)
     */
    function dd(...$vars)
    {
        echo '<pre style="background: #f8f9fa; padding: 1rem; border: 1px solid #ddd; border-radius: 4px; margin: 1rem 0;">';
        
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n" . str_repeat('-', 50) . "\n";
        }
        
        echo '</pre>';
        exit;
    }
}

if (!function_exists('dump')) {
    /**
     * Dump variable (for debugging)
     */
    function dump(...$vars)
    {
        echo '<pre style="background: #f8f9fa; padding: 1rem; border: 1px solid #ddd; border-radius: 4px; margin: 1rem 0;">';
        
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n" . str_repeat('-', 50) . "\n";
        }
        
        echo '</pre>';
    }
} 

if (!function_exists('flash')) {
    function flash($key, $message) {
        Session::setFlash($key, $message);
    }
}

if (!function_exists('db')) {
    /**
     * Get database connection or query builder
     */
    function db($table = null) {
        if ($table === null) {
            return \Core\Database\Connection::getInstance();
        }
        return \Core\Database\DB::table($table);
    }
} 