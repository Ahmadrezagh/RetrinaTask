<?php

namespace Core;

class View
{
    private static $engine = null;
    
    /**
     * Get the view engine instance
     */
    private static function getEngine()
    {
        if (self::$engine === null) {
            self::$engine = new ViewEngine();
        }
        return self::$engine;
    }
    
    /**
     * Render a view
     */
    public static function render($view, $data = [], $layout = null)
    {
        return self::getEngine()->render($view, $data, $layout);
    }
    
    /**
     * Create a view instance with data
     */
    public static function make($view, $data = [])
    {
        $engine = new ViewEngine();
        return $engine->with($data)->render($view);
    }
    
    /**
     * Share data globally across all views
     */
    public static function share($key, $value = null)
    {
        self::getEngine()->share($key, $value);
    }
    
    /**
     * Start a section
     */
    public static function section($name)
    {
        self::getEngine()->section($name);
    }
    
    /**
     * End current section
     */
    public static function endSection()
    {
        self::getEngine()->endSection();
    }
    
    /**
     * Yield section content
     */
    public static function yield($section, $default = '')
    {
        return self::getEngine()->yield($section, $default);
    }
    
    /**
     * Check if section exists
     */
    public static function hasSection($section)
    {
        return self::getEngine()->hasSection($section);
    }
    
    /**
     * Extend a layout
     */
    public static function extends($layout)
    {
        self::getEngine()->extends($layout);
    }
    
    /**
     * Include a partial view
     */
    public static function include($view, $data = [])
    {
        self::getEngine()->include($view, $data);
    }
    
    /**
     * Escape HTML output
     */
    public static function e($value)
    {
        return self::getEngine()->escape($value);
    }
    
    /**
     * Generate URL
     */
    public static function url($path = '')
    {
        return self::getEngine()->url($path);
    }
    
    /**
     * Generate asset URL
     */
    public static function asset($path)
    {
        return self::getEngine()->asset($path);
    }
    
    /**
     * Get old input value
     */
    public static function old($key, $default = '')
    {
        return self::getEngine()->old($key, $default);
    }
    
    /**
     * Get CSRF token
     */
    public static function csrf()
    {
        return self::getEngine()->csrf();
    }
    
    /**
     * CSRF hidden field
     */
    public static function csrfField()
    {
        return self::getEngine()->csrfField();
    }
    
    /**
     * Method field for forms
     */
    public static function method($method)
    {
        return self::getEngine()->method($method);
    }
} 