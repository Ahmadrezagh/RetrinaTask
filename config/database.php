<?php

// Database configuration using environment variables

// Make sure Environment class is available
if (!class_exists('Core\Environment')) {
    require_once __DIR__ . '/../core/Environment.php';
    \Core\Environment::load();
}

use Core\Environment;

$driver = Environment::get('DB_DRIVER', 'sqlite');

if ($driver === 'sqlite') {
    $sqlitePath = Environment::get('DB_SQLITE_PATH', 'storage/database.sqlite');
    
    // Convert relative path to absolute
    if (substr($sqlitePath, 0, 1) !== '/') {
        $sqlitePath = dirname(__DIR__) . '/' . $sqlitePath;
    }
    
    return [
        'driver' => 'sqlite',
        'database' => $sqlitePath,
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
} elseif ($driver === 'postgres' || $driver === 'postgresql') {
    return [
        'driver' => 'postgres',
        'host' => Environment::get('DB_HOST', 'localhost'),
        'port' => Environment::getInt('DB_PORT', 5432),
        'database' => Environment::get('DB_DATABASE', 'retrina_framework'),
        'username' => Environment::get('DB_USERNAME', 'postgres'),
        'password' => Environment::get('DB_PASSWORD', ''),
        'charset' => 'utf8',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
} else {
    // MySQL/MariaDB (default)
    return [
        'driver' => 'mysql',
        'host' => Environment::get('DB_HOST', 'localhost'),
        'port' => Environment::getInt('DB_PORT', 3306),
        'database' => Environment::get('DB_DATABASE', 'retrina_framework'),
        'username' => Environment::get('DB_USERNAME', 'root'),
        'password' => Environment::get('DB_PASSWORD', ''),
        'charset' => Environment::get('DB_CHARSET', 'utf8mb4'),
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
} 