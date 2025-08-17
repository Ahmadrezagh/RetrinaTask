<?php

namespace Core;

class Application
{
    private $router;
    private $basePath;
    
    public function __construct($basePath = null)
    {
        $this->basePath = $basePath ?: dirname(__DIR__);
        
        $this->startSession();
        $this->loadHelpers();
        $this->registerAutoloader();
        $this->registerErrorHandler();
        
        // Router must be instantiated after autoloader is registered
        $this->router = new Router();
    }
    
    /**
     * Start session if not already started
     */
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Load global helper functions
     */
    private function loadHelpers()
    {
        $helpersFile = $this->basePath . '/core/helpers.php';
        if (file_exists($helpersFile)) {
            require_once $helpersFile;
        }
    }
    
    /**
     * Register PSR-4 autoloader
     */
    private function registerAutoloader()
    {
        spl_autoload_register(function ($class) {
            // Convert namespace to file path
            $prefix = '';
            $baseDir = $this->basePath . '/';
            
            // Check if class starts with App namespace
            if (strpos($class, 'App\\') === 0) {
                $prefix = 'App\\';
                $baseDir .= 'app/';
            }
            // Check if class starts with Core namespace
            elseif (strpos($class, 'Core\\') === 0) {
                $prefix = 'Core\\';
                $baseDir .= 'core/';
            }
            
            // Get the relative class name
            $relativeClass = substr($class, strlen($prefix));
            
            // Replace namespace separators with directory separators
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            
            // If the file exists, require it
            if (file_exists($file)) {
                require $file;
            }
        });
    }
    
    /**
     * Register error and exception handlers
     */
    private function registerErrorHandler()
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }
    
    /**
     * Handle PHP errors
     */
    public function handleError($severity, $message, $filename, $lineno)
    {
        throw new \ErrorException($message, 0, $severity, $filename, $lineno);
    }
    
    /**
     * Handle uncaught exceptions
     */
    public function handleException($exception)
    {
        http_response_code(500);
        
        if ($this->isDebugMode()) {
            echo "<div style='font-family: monospace; background: #f8f9fa; padding: 2rem; border: 1px solid #ddd; border-radius: 8px; margin: 1rem;'>";
            echo "<h1 style='color: #dc3545; margin-bottom: 1rem;'>🚨 Error</h1>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "</p>";
            echo "<p><strong>Line:</strong> " . $exception->getLine() . "</p>";
            echo "<h3 style='margin-top: 2rem; color: #333;'>Stack Trace:</h3>";
            echo "<pre style='background: white; padding: 1rem; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto;'>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
            echo "</div>";
        } else {
            echo "<div style='text-align: center; padding: 3rem; font-family: sans-serif;'>";
            echo "<h1 style='color: #dc3545;'>500 - Internal Server Error</h1>";
            echo "<p style='color: #666;'>Something went wrong. Please try again later.</p>";
            echo "</div>";
        }
        
        exit;
    }
    
    /**
     * Check if debug mode is enabled
     */
    private function isDebugMode()
    {
        return defined('DEBUG') && DEBUG === true;
    }
    
    /**
     * Get the router instance
     */
    public function router()
    {
        return $this->router;
    }
    
    /**
     * Run the application
     */
    public function run()
    {
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Get base path
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    /**
     * Set global view data
     */
    public function shareViewData($key, $value = null)
    {
        \Core\View::share($key, $value);
    }
} 