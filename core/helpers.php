<?php

/**
 * Global Helper Functions for Retrina Framework
 * These functions are available throughout the application
 */

if (!function_exists('view')) {
    /**
     * Render a view
     */
    function view($viewName, $data = [], $layout = null)
    {
        return \Core\View::render($viewName, $data, $layout);
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
    function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     */
    function old($key, $default = '')
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
}

if (!function_exists('session')) {
    /**
     * Get/Set session values
     */
    function session($key = null, $value = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($key === null) {
            return $_SESSION;
        }
        
        if ($value !== null) {
            $_SESSION[$key] = $value;
            return $value;
        }
        
        return $_SESSION[$key] ?? null;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generate CSRF token
     */
    function csrf_token()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF hidden field
     */
    function csrf_field()
    {
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
    function config($key, $default = null)
    {
        static $config = [];
        
        if (empty($config)) {
            $configFiles = [
                'database' => __DIR__ . '/../config/database.php',
                'app' => __DIR__ . '/../config/app.php'
            ];
            
            foreach ($configFiles as $name => $file) {
                if (file_exists($file)) {
                    $config[$name] = require $file;
                }
            }
        }
        
        $keys = explode('.', $key);
        $value = $config;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
}

if (!function_exists('env')) {
    /**
     * Get environment variable
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        // Convert string representations of boolean to actual boolean
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        
        return $value;
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