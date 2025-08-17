<?php

namespace Core\Middleware;

/**
 * Middleware Manager
 * 
 * Handles middleware registration, resolution, and execution
 */
class MiddlewareManager
{
    /**
     * Registered middleware aliases
     */
    protected static $middleware = [];
    
    /**
     * Global middleware (applied to all routes)
     */
    protected static $globalMiddleware = [];
    
    /**
     * Route-specific middleware groups
     */
    protected static $middlewareGroups = [
        'web' => [
            'csrf',
            'session'
        ],
        'api' => [
            'cors',
            'throttle:60,1'
        ]
    ];
    
    /**
     * Register a middleware alias
     */
    public static function register(string $alias, string $className)
    {
        static::$middleware[$alias] = $className;
    }
    
    /**
     * Register multiple middleware
     */
    public static function registerMultiple(array $middleware)
    {
        foreach ($middleware as $alias => $className) {
            static::register($alias, $className);
        }
    }
    
    /**
     * Add global middleware
     */
    public static function addGlobal(string $middleware)
    {
        static::$globalMiddleware[] = $middleware;
    }
    
    /**
     * Define a middleware group
     */
    public static function group(string $name, array $middleware)
    {
        static::$middlewareGroups[$name] = $middleware;
    }
    
    /**
     * Resolve middleware class from alias
     */
    public static function resolve(string $middleware)
    {
        // Check if it's a class name directly
        if (class_exists($middleware)) {
            return $middleware;
        }
        
        // Parse middleware with parameters (e.g., 'throttle:60,1')
        if (strpos($middleware, ':') !== false) {
            [$alias] = explode(':', $middleware, 2);
            return static::$middleware[$alias] ?? null;
        }
        
        // Return registered middleware
        return static::$middleware[$middleware] ?? null;
    }
    
    /**
     * Get middleware parameters
     */
    public static function getParameters(string $middleware)
    {
        if (strpos($middleware, ':') === false) {
            return [];
        }
        
        [, $params] = explode(':', $middleware, 2);
        return explode(',', $params);
    }
    
    /**
     * Expand middleware groups
     */
    public static function expandGroups(array $middleware)
    {
        $expanded = [];
        
        foreach ($middleware as $item) {
            if (isset(static::$middlewareGroups[$item])) {
                $expanded = array_merge($expanded, static::$middlewareGroups[$item]);
            } else {
                $expanded[] = $item;
            }
        }
        
        return $expanded;
    }
    
    /**
     * Execute middleware stack
     */
    public static function execute(array $middleware, array $request, callable $destination)
    {
        // Add global middleware first
        $middleware = array_merge(static::$globalMiddleware, $middleware);
        
        // Expand middleware groups
        $middleware = static::expandGroups($middleware);
        
        // Create the middleware stack
        $stack = array_reduce(
            array_reverse($middleware),
            function ($next, $middlewareItem) {
                return function ($request) use ($middlewareItem, $next) {
                    $className = static::resolve($middlewareItem);
                    
                    if (!$className) {
                        throw new \Exception("Middleware '{$middlewareItem}' not found");
                    }
                    
                    $parameters = static::getParameters($middlewareItem);
                    $instance = new $className(...$parameters);
                    
                    if (!$instance instanceof MiddlewareInterface) {
                        throw new \Exception("Middleware '{$className}' must implement MiddlewareInterface");
                    }
                    
                    return $instance->handle($request, $next);
                };
            },
            $destination
        );
        
        return $stack($request);
    }
    
    /**
     * Get all registered middleware
     */
    public static function getRegistered()
    {
        return static::$middleware;
    }
    
    /**
     * Get middleware groups
     */
    public static function getGroups()
    {
        return static::$middlewareGroups;
    }
    
    /**
     * Bootstrap default middleware
     */
    public static function bootstrap()
    {
        static::registerMultiple([
            'auth' => \Core\Middleware\AuthMiddleware::class,
            'guest' => \Core\Middleware\GuestMiddleware::class,
            'admin' => \Core\Middleware\AdminMiddleware::class,
            'csrf' => \Core\Middleware\CsrfMiddleware::class,
            'cors' => \Core\Middleware\CorsMiddleware::class,
            'throttle' => \Core\Middleware\ThrottleMiddleware::class,
            'session' => \Core\Middleware\SessionMiddleware::class,
            'json' => \Core\Middleware\JsonMiddleware::class,
            'log' => \Core\Middleware\LogMiddleware::class,
            'maintenance' => \Core\Middleware\MaintenanceMiddleware::class,
        ]);
    }
} 