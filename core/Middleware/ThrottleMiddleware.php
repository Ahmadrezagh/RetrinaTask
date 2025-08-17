<?php

namespace Core\Middleware;

/**
 * Throttle Middleware
 * 
 * Rate limiting middleware to prevent abuse
 */
class ThrottleMiddleware implements MiddlewareInterface
{
    protected $maxAttempts;
    protected $decayMinutes;
    protected $cachePrefix = 'throttle:';
    
    public function __construct(int $maxAttempts = 60, int $decayMinutes = 1)
    {
        $this->maxAttempts = $maxAttempts;
        $this->decayMinutes = $decayMinutes;
    }
    
    public function handle(array $request, callable $next)
    {
        $key = $this->resolveRequestSignature($request);
        
        if ($this->tooManyAttempts($key)) {
            return $this->buildResponse($key);
        }
        
        $this->hit($key);
        
        $response = $next($request);
        
        return $this->addHeaders(
            $response,
            $this->maxAttempts,
            $this->calculateRemainingAttempts($key)
        );
    }
    
    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(array $request): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $uri = $request['uri'] ?? $_SERVER['REQUEST_URI'] ?? '';
        
        return sha1($ip . '|' . $uri);
    }
    
    /**
     * Check if too many attempts have been made
     */
    protected function tooManyAttempts(string $key): bool
    {
        return $this->attempts($key) >= $this->maxAttempts;
    }
    
    /**
     * Get number of attempts for given key
     */
    protected function attempts(string $key): int
    {
        return (int) $this->getCache($key, 0);
    }
    
    /**
     * Increment attempts counter
     */
    protected function hit(string $key): void
    {
        $attempts = $this->attempts($key) + 1;
        $ttl = $this->decayMinutes * 60;
        
        $this->setCache($key, $attempts, $ttl);
    }
    
    /**
     * Calculate remaining attempts
     */
    protected function calculateRemainingAttempts(string $key): int
    {
        return max(0, $this->maxAttempts - $this->attempts($key));
    }
    
    /**
     * Get time until attempts reset
     */
    protected function getTimeUntilReset(string $key): int
    {
        $cacheKey = $this->cachePrefix . $key;
        
        // Simple file-based cache TTL calculation
        $filename = $this->getCacheFilename($cacheKey);
        if (file_exists($filename)) {
            $data = json_decode(file_get_contents($filename), true);
            if ($data && isset($data['expires'])) {
                return max(0, $data['expires'] - time());
            }
        }
        
        return $this->decayMinutes * 60;
    }
    
    /**
     * Build rate limit exceeded response
     */
    protected function buildResponse(string $key)
    {
        $retryAfter = $this->getTimeUntilReset($key);
        
        http_response_code(429);
        header("Retry-After: {$retryAfter}");
        header('Content-Type: application/json');
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Too many requests',
            'retry_after' => $retryAfter,
            'code' => 429
        ]);
        
        exit;
    }
    
    /**
     * Add rate limit headers to response
     */
    protected function addHeaders($response, int $maxAttempts, int $remainingAttempts)
    {
        header("X-RateLimit-Limit: {$maxAttempts}");
        header("X-RateLimit-Remaining: {$remainingAttempts}");
        
        return $response;
    }
    
    /**
     * Get cache value
     */
    protected function getCache(string $key, $default = null)
    {
        $cacheKey = $this->cachePrefix . $key;
        $filename = $this->getCacheFilename($cacheKey);
        
        if (!file_exists($filename)) {
            return $default;
        }
        
        $data = json_decode(file_get_contents($filename), true);
        
        if (!$data || $data['expires'] < time()) {
            unlink($filename);
            return $default;
        }
        
        return $data['value'];
    }
    
    /**
     * Set cache value
     */
    protected function setCache(string $key, $value, int $ttl): void
    {
        $cacheKey = $this->cachePrefix . $key;
        $filename = $this->getCacheFilename($cacheKey);
        
        // Ensure cache directory exists
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        file_put_contents($filename, json_encode($data), LOCK_EX);
    }
    
    /**
     * Get cache filename for key
     */
    protected function getCacheFilename(string $key): string
    {
        $hash = sha1($key);
        return __DIR__ . '/../../storage/cache/throttle/' . substr($hash, 0, 2) . '/' . $hash . '.json';
    }
} 