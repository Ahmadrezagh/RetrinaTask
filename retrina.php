#!/usr/bin/env php
<?php

/**
 * Retrina Framework - Command Line Tool
 * 
 * Usage:
 *   php retrina.php [command] [arguments] [options]
 * 
 * Available Commands:
 *   make:controller    - Create a new controller
 *   make:model         - Create a new model
 *   make:migration     - Create a new migration
 *   make:view          - Create a new view
 *   migrate            - Run database migrations
 *   help               - Show help information
 */

// Check if script is being run from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Load the application
require_once __DIR__ . '/core/Application.php';
require_once __DIR__ . '/core/Command/CommandInterface.php';
require_once __DIR__ . '/core/Command/BaseCommand.php';
require_once __DIR__ . '/core/Command/CommandRegistry.php';

use Core\Command\CommandRegistry;
use Core\Command\BaseCommand;

// Colors for console output
const COLOR_GREEN = "\033[32m";
const COLOR_RED = "\033[31m";
const COLOR_YELLOW = "\033[33m";
const COLOR_BLUE = "\033[34m";
const COLOR_CYAN = "\033[36m";
const COLOR_MAGENTA = "\033[35m";
const COLOR_WHITE = "\033[37m";
const COLOR_RESET = "\033[0m";
const BOLD = "\033[1m";

function printBanner()
{
    echo COLOR_MAGENTA . "
╔═══════════════════════════════════════╗
║          RETRINA FRAMEWORK            ║
║         Command Line Tool             ║
╚═══════════════════════════════════════╝
" . COLOR_RESET . "\n";
}

function printUsage()
{
    echo COLOR_BLUE . "Usage:" . COLOR_RESET . "\n";
    echo "  php retrina.php [command] [arguments] [options]\n\n";
    
    echo COLOR_BLUE . "Available Commands:" . COLOR_RESET . "\n";
    echo COLOR_GREEN . "  make:controller" . COLOR_RESET . "    Create a new controller\n";
    echo COLOR_GREEN . "  make:model" . COLOR_RESET . "         Create a new model\n";
    echo COLOR_GREEN . "  make:migration" . COLOR_RESET . "     Create a new migration\n";
    echo COLOR_GREEN . "  make:view" . COLOR_RESET . "          Create a new view\n";
    echo COLOR_GREEN . "  migrate" . COLOR_RESET . "            Run database migrations\n";
    echo COLOR_GREEN . "  test" . COLOR_RESET . "               Run the test suite\n";
    echo COLOR_GREEN . "  list" . COLOR_RESET . "               List all available commands\n";
    echo COLOR_GREEN . "  help" . COLOR_RESET . "               Show help information\n\n";
    
    echo COLOR_BLUE . "Options:" . COLOR_RESET . "\n";
    echo COLOR_YELLOW . "  -r, --resource" . COLOR_RESET . "     Create a resource controller (with make:controller)\n";
    echo COLOR_YELLOW . "  -m, --migration" . COLOR_RESET . "    Create migration with model (with make:model)\n";
    echo COLOR_YELLOW . "  -f, --force" . COLOR_RESET . "        Force overwrite existing files\n";
    echo COLOR_YELLOW . "  -h, --help" . COLOR_RESET . "         Show help for specific command\n\n";
    
    echo COLOR_BLUE . "Examples:" . COLOR_RESET . "\n";
    echo "  php retrina.php make:controller UserController\n";
    echo "  php retrina.php make:controller PostController --resource\n";
    echo "  php retrina.php make:model User --migration\n";
    echo "  php retrina.php make:migration create_posts_table\n";
    echo "  php retrina.php migrate\n\n";
}

function parseArguments(array $argv)
{
    $command = $argv[1] ?? 'help';
    $arguments = [];
    $options = [];
    
    for ($i = 2; $i < count($argv); $i++) {
        $arg = $argv[$i];
        
        if (substr($arg, 0, 2) === '--') {
            // Long option
            $option = substr($arg, 2);
            if (strpos($option, '=') !== false) {
                [$key, $value] = explode('=', $option, 2);
                $options[$key] = $value;
            } else {
                $options[$option] = true;
            }
        } elseif (substr($arg, 0, 1) === '-') {
            // Short option
            $option = substr($arg, 1);
            if (strlen($option) > 1) {
                // Multiple short options like -rf
                for ($j = 0; $j < strlen($option); $j++) {
                    $options[$option[$j]] = true;
                }
            } else {
                $options[$option] = true;
            }
        } else {
            // Argument
            $arguments[] = $arg;
        }
    }
    
    return [$command, $arguments, $options];
}

function showCommandHelp(string $commandName, CommandRegistry $registry)
{
    $command = $registry->findCommand($commandName);
    
    if (!$command) {
        echo COLOR_RED . "Command not found: " . $commandName . COLOR_RESET . "\n";
        return;
    }
    
    echo COLOR_BLUE . "Command: " . COLOR_RESET . COLOR_GREEN . $command->getSignature() . COLOR_RESET . "\n";
    echo COLOR_BLUE . "Description: " . COLOR_RESET . $command->getDescription() . "\n\n";
    echo $command->getHelp() . "\n";
}

function listCommands(CommandRegistry $registry)
{
    $grouped = $registry->getGroupedCommands();
    
    echo COLOR_BLUE . "Available Commands:" . COLOR_RESET . "\n\n";
    
    foreach ($grouped as $category => $commands) {
        echo COLOR_YELLOW . $category . ":" . COLOR_RESET . "\n";
        
        foreach ($commands as $signature => $command) {
            echo sprintf("  %-20s %s\n", 
                COLOR_GREEN . $signature . COLOR_RESET, 
                $command->getDescription()
            );
        }
        echo "\n";
    }
}

// Main execution
try {
    printBanner();
    
    [$commandName, $arguments, $options] = parseArguments($argv);
    
    // Handle built-in commands
    if ($commandName === 'help') {
        if (!empty($arguments)) {
            $registry = new CommandRegistry();
            showCommandHelp($arguments[0], $registry);
        } else {
            printUsage();
        }
        exit(0);
    }
    
    if ($commandName === 'list') {
        $registry = new CommandRegistry();
        listCommands($registry);
        exit(0);
    }
    
    // Load and execute command
    $registry = new CommandRegistry();
    $command = $registry->findCommand($commandName);
    
    if (!$command) {
        echo COLOR_RED . "Command not found: " . $commandName . COLOR_RESET . "\n\n";
        
        // Suggest similar commands
        $suggestions = [];
        foreach ($registry->all() as $signature => $cmd) {
            if (levenshtein($commandName, $signature) <= 3) {
                $suggestions[] = $signature;
            }
        }
        
        if (!empty($suggestions)) {
            echo COLOR_YELLOW . "Did you mean:" . COLOR_RESET . "\n";
            foreach ($suggestions as $suggestion) {
                echo "  " . COLOR_GREEN . $suggestion . COLOR_RESET . "\n";
            }
            echo "\n";
        }
        
        printUsage();
        exit(1);
    }
    
    // Show help for specific command
    if (isset($options['help']) || isset($options['h'])) {
        showCommandHelp($commandName, $registry);
        exit(0);
    }
    
    // Execute the command
    $exitCode = $command->handle($arguments, $options);
    exit($exitCode);
    
} catch (Exception $e) {
    echo COLOR_RED . "Error: " . $e->getMessage() . COLOR_RESET . "\n";
    exit(1);
} 