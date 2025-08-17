<?php

namespace Core\Middleware;

/**
 * Log Middleware
 * 
 * Logs requests for monitoring and debugging
 */
class LogMiddleware implements MiddlewareInterface
{
    public function handle(array $request, callable $next)
    {
        $startTime = microtime(true);
        
        // Log request start
        $this->logRequest($request);
        
        // Execute next middleware/route
        $response = $next($request);
        
        // Log request completion
        $duration = microtime(true) - $startTime;
        $this->logResponse($request, $duration);
        
        return $response;
    }
    
    /**
     * Log incoming request
     */
    protected function logRequest(array $request)
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $request['method'] ?? $_SERVER['REQUEST_METHOD'],
            'uri' => $request['uri'] ?? $_SERVER['REQUEST_URI'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'type' => 'request'
        ];
        
        $this->writeLog($logData);
    }
    
    /**
     * Log request completion
     */
    protected function logResponse(array $request, float $duration)
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $request['method'] ?? $_SERVER['REQUEST_METHOD'],
            'uri' => $request['uri'] ?? $_SERVER['REQUEST_URI'],
            'duration' => round($duration * 1000, 2) . 'ms',
            'memory' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'MB',
            'type' => 'response'
        ];
        
        $this->writeLog($logData);
    }
    
    /**
     * Write log entry to file
     */
    protected function writeLog(array $data)
    {
        $logFile = $this->getLogFile();
        $logEntry = json_encode($data) . "\n";
        
        // Ensure log directory exists
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get log file path
     */
    protected function getLogFile()
    {
        $date = date('Y-m-d');
        return __DIR__ . '/../../storage/logs/requests-' . $date . '.log';
    }
} 