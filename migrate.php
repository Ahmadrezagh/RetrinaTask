#!/usr/bin/env php
<?php

/**
 * Retrina Framework - Migration Command
 * 
 * Usage:
 *   php migrate.php               - Run all pending migrations
 *   php migrate.php status        - Show migration status
 *   php migrate.php rollback      - Rollback last migration
 *   php migrate.php rollback:all  - Rollback all migrations
 *   php migrate.php rollback:3    - Rollback last 3 migrations
 *   php migrate.php list          - List all migrations
 *   php migrate.php run:MigrationName - Run specific migration
 */

require_once __DIR__ . '/core/MigrationRunner.php';

use Core\MigrationRunner;

// Colors for console output
const COLOR_GREEN = "\033[32m";
const COLOR_RED = "\033[31m";
const COLOR_YELLOW = "\033[33m";
const COLOR_BLUE = "\033[34m";
const COLOR_RESET = "\033[0m";

function printUsage()
{
    echo COLOR_BLUE . "🗃️  Retrina Framework - Database Migration Tool" . COLOR_RESET . "\n\n";
    echo "Usage:\n";
    echo "  php migrate.php               " . COLOR_GREEN . "Run all pending migrations" . COLOR_RESET . "\n";
    echo "  php migrate.php status        " . COLOR_BLUE . "Show migration status" . COLOR_RESET . "\n";
    echo "  php migrate.php rollback      " . COLOR_YELLOW . "Rollback last migration" . COLOR_RESET . "\n";
    echo "  php migrate.php rollback:all  " . COLOR_RED . "Rollback all migrations" . COLOR_RESET . "\n";
    echo "  php migrate.php rollback:3    " . COLOR_YELLOW . "Rollback last 3 migrations" . COLOR_RESET . "\n";
    echo "  php migrate.php list          " . COLOR_BLUE . "List all migrations" . COLOR_RESET . "\n";
    echo "  php migrate.php run:MigrationName " . COLOR_GREEN . "Run specific migration" . COLOR_RESET . "\n";
    echo "\n";
}

function printBanner()
{
    echo COLOR_BLUE . "
╔═══════════════════════════════════════╗
║        RETRINA FRAMEWORK              ║
║        Migration System               ║
╚═══════════════════════════════════════╝
" . COLOR_RESET . "\n";
}

// Check if script is being run from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Parse command line arguments
$command = $argv[1] ?? 'migrate';

try {
    printBanner();
    
    $runner = new MigrationRunner();
    
    switch ($command) {
        case 'migrate':
        case 'up':
            $success = $runner->migrate();
            exit($success ? 0 : 1);
            
        case 'status':
            $runner->status();
            break;
            
        case 'list':
            $runner->listMigrations();
            break;
            
        case 'rollback':
            $success = $runner->rollback(1);
            exit($success ? 0 : 1);
            
        case 'rollback:all':
            echo COLOR_RED . "⚠️  WARNING: This will rollback ALL migrations!" . COLOR_RESET . "\n";
            echo "Are you sure? (y/N): ";
            $handle = fopen("php://stdin", "r");
            $line = fgets($handle);
            fclose($handle);
            
            if (trim(strtolower($line)) === 'y') {
                $success = $runner->rollbackAll();
                exit($success ? 0 : 1);
            } else {
                echo "Operation cancelled.\n";
                exit(0);
            }
            break;
            
        default:
            // Check for rollback:number pattern
            if (preg_match('/^rollback:(\d+)$/', $command, $matches)) {
                $steps = (int) $matches[1];
                echo COLOR_YELLOW . "Rolling back last {$steps} migrations..." . COLOR_RESET . "\n\n";
                $success = $runner->rollback($steps);
                exit($success ? 0 : 1);
            }
            
            // Check for run:MigrationName pattern
            if (preg_match('/^run:(.+)$/', $command, $matches)) {
                $migrationName = $matches[1];
                echo COLOR_GREEN . "Running specific migration: {$migrationName}" . COLOR_RESET . "\n\n";
                $success = $runner->runMigration($migrationName);
                exit($success ? 0 : 1);
            }
            
            // Check for rollback:MigrationName pattern
            if (preg_match('/^rollback:(.+)$/', $command, $matches)) {
                $migrationName = $matches[1];
                if (is_numeric($migrationName)) {
                    // Already handled above
                    break;
                }
                echo COLOR_YELLOW . "Rolling back specific migration: {$migrationName}" . COLOR_RESET . "\n\n";
                $success = $runner->rollbackMigration($migrationName);
                exit($success ? 0 : 1);
            }
            
            echo COLOR_RED . "❌ Unknown command: {$command}" . COLOR_RESET . "\n\n";
            printUsage();
            exit(1);
    }
    
} catch (Exception $e) {
    echo COLOR_RED . "❌ Error: " . $e->getMessage() . COLOR_RESET . "\n";
    exit(1);
}

echo COLOR_GREEN . "✅ Command completed successfully!" . COLOR_RESET . "\n";
exit(0); 