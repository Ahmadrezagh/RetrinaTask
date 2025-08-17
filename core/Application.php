<?php

namespace Core;

class Application
{
    private $router;
    private $basePath;
    
    public function __construct($basePath = null)
    {
        $this->basePath = $basePath ?: dirname(__DIR__);
        $this->router = new Router();
        
        $this->registerAutoloader();
        $this->registerErrorHandler();
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
            echo "<h1>Error</h1>";
            echo "<p><strong>Message:</strong> " . $exception->getMessage() . "</p>";
            echo "<p><strong>File:</strong> " . $exception->getFile() . "</p>";
            echo "<p><strong>Line:</strong> " . $exception->getLine() . "</p>";
            echo "<h3>Stack Trace:</h3>";
            echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        } else {
            echo "500 - Internal Server Error";
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
} 