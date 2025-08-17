<?php

namespace Core\Middleware;

/**
 * Middleware Interface
 * 
 * All middleware classes must implement this interface
 */
interface MiddlewareInterface
{
    /**
     * Handle the request and proceed to the next middleware or route handler
     * 
     * @param array $request The request data
     * @param callable $next The next middleware or route handler
     * @return mixed
     */
    public function handle(array $request, callable $next);
} 