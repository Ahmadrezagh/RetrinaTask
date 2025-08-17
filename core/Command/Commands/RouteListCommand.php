<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;
use Core\Router;

class RouteListCommand extends BaseCommand
{
    protected $signature = 'route:list';
    protected $description = 'Display all registered routes';
    protected $help = 'This command displays all registered routes in the application.

Usage:
  route:list [options]

Options:
  --method=METHOD      Filter routes by HTTP method (GET, POST, PUT, DELETE)
  --uri=PATTERN        Filter routes by URI pattern

Examples:
  php retrina route:list                    # Show all routes
  php retrina route:list --method=GET       # Show only GET routes
  php retrina route:list --uri=api          # Show routes containing "api"';

    private $router;

    public function handle(array $arguments = [], array $options = []): int
    {
        $methodFilter = $options['method'] ?? null;
        $uriFilter = $options['uri'] ?? null;

        // Load routes to get them registered
        $this->loadRoutes();

        // Get routes from router
        $routes = $this->getRegisteredRoutes();

        // Apply filters
        if ($methodFilter) {
            $routes = $this->filterByMethod($routes, strtoupper($methodFilter));
        }

        if ($uriFilter) {
            $routes = $this->filterByUri($routes, $uriFilter);
        }

        // Display routes
        $this->displayRoutes($routes);

        return 0;
    }

    private function loadRoutes(): void
    {
        // Create a temporary application instance to load routes
        $basePath = dirname(__DIR__, 3);
        
        // Load Router class
        require_once $basePath . '/core/Router.php';
        
        // Create router instance
        $router = new Router();
        
        // Load web routes
        if (file_exists($basePath . '/routes/web.php')) {
            // Create app mock for web routes
            $app = new class($router) {
                private $router;
                private $viewData = [];
                
                public function __construct($router) {
                    $this->router = $router;
                }
                
                public function router() {
                    return $this->router;
                }
                
                public function shareViewData($data) {
                    $this->viewData = array_merge($this->viewData, $data);
                }
            };
            
            require $basePath . '/routes/web.php';
        }
        
        // Load API routes
        if (file_exists($basePath . '/routes/api.php')) {
            require $basePath . '/routes/api.php';
        }
        
        // Store router instance for later use
        $this->router = $router;
    }

    private function getRegisteredRoutes(): array
    {
        if (!isset($this->router)) {
            return [];
        }

        // Access private routes property using reflection
        $reflection = new \ReflectionClass($this->router);
        $routesProperty = $reflection->getProperty('routes');
        $routesProperty->setAccessible(true);
        $routes = $routesProperty->getValue($this->router);

        $formattedRoutes = [];
        
        // The router stores routes as an array of route objects
        foreach ($routes as $route) {
            $uri = $this->convertPatternToUri($route['uri'], $route['pattern']);
            
            $formattedRoutes[] = [
                'method' => strtoupper($route['method']),
                'uri' => $uri,
                'action' => $this->formatAction($route['controller'])
            ];
        }

        return $formattedRoutes;
    }

    private function convertPatternToUri(string $originalUri, string $pattern): string
    {
        // If original URI is available and meaningful, use it
        if (!empty($originalUri)) {
            $uri = '/' . $originalUri;
            
            // Convert common patterns
            $uri = str_replace('(?P<id>[^\/]+)', '{id}', $uri);
            $uri = str_replace('(?P<name>[^\/]+)', '{name}', $uri);
            $uri = str_replace('(?P<category>[^\/]+)', '{category}', $uri);
            $uri = str_replace('(\d+)', '{id}', $uri);
            
            return $uri;
        }
        
        // Fall back to converting pattern
        $uri = $pattern;
        
        // Remove regex anchors
        $uri = str_replace(['/^', '$/'], '', $uri);
        
        // Convert common patterns
        $uri = str_replace('(?P<id>[^\/]+)', '{id}', $uri);
        $uri = str_replace('(?P<name>[^\/]+)', '{name}', $uri);
        $uri = str_replace('(?P<category>[^\/]+)', '{category}', $uri);
        $uri = str_replace('(\d+)', '{id}', $uri);
        
        // Clean up escaped characters
        $uri = str_replace('\/', '/', $uri);
        
        // Handle root route
        if (empty($uri)) {
            $uri = '/';
        } elseif ($uri[0] !== '/') {
            $uri = '/' . $uri;
        }
        
        return $uri;
    }

    private function formatAction($action): string
    {
        if (is_string($action)) {
            return $action;
        }
        
        if ($action instanceof \Closure) {
            return 'Closure';
        }
        
        if (is_array($action) && count($action) === 2) {
            return $action[0] . '@' . $action[1];
        }
        
        return 'Unknown';
    }

    private function filterByMethod(array $routes, string $method): array
    {
        return array_filter($routes, function($route) use ($method) {
            return $route['method'] === $method;
        });
    }

    private function filterByUri(array $routes, string $uri): array
    {
        return array_filter($routes, function($route) use ($uri) {
            return strpos($route['uri'], $uri) !== false;
        });
    }

    private function displayRoutes(array $routes): void
    {
        if (empty($routes)) {
            $this->warning('No routes found matching the specified criteria.');
            return;
        }

        $this->line();
        $this->colored("📋 Route List", self::COLOR_BLUE . self::BOLD);
        $this->line();

        // Calculate column widths
        $methodWidth = max(6, max(array_map(function($r) { return strlen($r['method']); }, $routes)));
        $uriWidth = max(20, max(array_map(function($r) { return strlen($r['uri']); }, $routes)));
        $actionWidth = max(15, max(array_map(function($r) { return strlen($r['action']); }, $routes)));

        // Header
        $this->colored(
            sprintf(
                "%-{$methodWidth}s  %-{$uriWidth}s  %-{$actionWidth}s",
                'METHOD',
                'URI',
                'ACTION'
            ),
            self::COLOR_YELLOW . self::BOLD
        );

        $this->colored(str_repeat('-', $methodWidth + $uriWidth + $actionWidth + 4), self::COLOR_YELLOW);

        // Routes
        foreach ($routes as $route) {
            $methodColor = $this->getMethodColor($route['method']);
            
            $this->line(sprintf(
                "%s%-{$methodWidth}s%s  %-{$uriWidth}s  %-{$actionWidth}s",
                $methodColor,
                $route['method'],
                self::COLOR_RESET,
                $route['uri'],
                $route['action']
            ));
        }

        $this->line();
        $this->info("Total routes: " . count($routes));
        $this->line();
    }

    private function getMethodColor(string $method): string
    {
        switch ($method) {
            case 'GET':
                return self::COLOR_GREEN;
            case 'POST':
                return self::COLOR_BLUE;
            case 'PUT':
                return self::COLOR_YELLOW;
            case 'DELETE':
                return self::COLOR_RED;
            case 'PATCH':
                return self::COLOR_MAGENTA;
            default:
                return self::COLOR_WHITE;
        }
    }
} 