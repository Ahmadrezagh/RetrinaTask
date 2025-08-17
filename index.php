<?php

/**
 * Retrina Framework - Entry Point
 * 
 * A custom PHP MVC framework
 */

// Define debug mode (set to false in production)
define('DEBUG', true);

// Start output buffering
ob_start();

// Include the application bootstrapper
require_once __DIR__ . '/core/Application.php';

// Create application instance
$app = new \Core\Application(__DIR__);

// Load routes
require_once __DIR__ . '/routes/web.php';

// Run the application
$app->run();

// End output buffering and send content
ob_end_flush(); 