<?php

namespace Core\Middleware;

/**
 * Maintenance Middleware
 * 
 * Puts the application in maintenance mode
 */
class MaintenanceMiddleware implements MiddlewareInterface
{
    protected $allowedIPs = [
        '127.0.0.1',
        '::1'
    ];
    
    public function handle(array $request, callable $next)
    {
        if ($this->isInMaintenanceMode() && !$this->isAllowedIP()) {
            return $this->showMaintenancePage($request);
        }
        
        return $next($request);
    }
    
    /**
     * Check if application is in maintenance mode
     */
    protected function isInMaintenanceMode(): bool
    {
        $maintenanceFile = __DIR__ . '/../../storage/framework/down';
        return file_exists($maintenanceFile);
    }
    
    /**
     * Check if current IP is allowed during maintenance
     */
    protected function isAllowedIP(): bool
    {
        $clientIP = $_SERVER['REMOTE_ADDR'] ?? '';
        return in_array($clientIP, $this->allowedIPs);
    }
    
    /**
     * Show maintenance page
     */
    protected function showMaintenancePage(array $request)
    {
        http_response_code(503);
        
        if ($this->isApiRequest($request)) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Service temporarily unavailable',
                'code' => 503
            ]);
        } else {
            $this->renderMaintenanceHtml();
        }
        
        exit;
    }
    
    /**
     * Render maintenance mode HTML page
     */
    protected function renderMaintenanceHtml()
    {
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .icon {
            font-size: 5rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔧</div>
        <h1>Under Maintenance</h1>
        <p>We are currently performing scheduled maintenance. Please check back soon.</p>
        <p><small>If you are the administrator, you can access the site from allowed IP addresses.</small></p>
    </div>
</body>
</html>';
    }
    
    /**
     * Check if request is for API endpoint
     */
    protected function isApiRequest(array $request): bool
    {
        $uri = $request['uri'] ?? $_SERVER['REQUEST_URI'] ?? '';
        return strpos($uri, '/api/') === 0 || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
    
    /**
     * Enable maintenance mode
     */
    public static function enable(string $message = 'The application is down for maintenance.')
    {
        $downFile = __DIR__ . '/../../storage/framework/down';
        $downDir = dirname($downFile);
        
        if (!is_dir($downDir)) {
            mkdir($downDir, 0755, true);
        }
        
        $data = [
            'time' => time(),
            'message' => $message,
            'allowed' => ['127.0.0.1', '::1']
        ];
        
        file_put_contents($downFile, json_encode($data));
    }
    
    /**
     * Disable maintenance mode
     */
    public static function disable()
    {
        $downFile = __DIR__ . '/../../storage/framework/down';
        if (file_exists($downFile)) {
            unlink($downFile);
        }
    }
} 