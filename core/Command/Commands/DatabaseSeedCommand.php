<?php

namespace Core\Command\Commands;

use Core\Command\CommandInterface;
use Core\Database\SeederRunner;

class DatabaseSeedCommand implements CommandInterface
{
    public function getSignature(): string
    {
        return 'db:seed {--class= : Run a specific seeder class} {--force : Force the operation to run in production}';
    }
    
    public function getDescription(): string
    {
        return 'Seed the database with records';
    }
    
    public function getHelp(): string
    {
        return <<<HELP
USAGE:
  php retrina db:seed [options]

DESCRIPTION:
  Seed the database with records using seeders.

OPTIONS:
  --class=CLASS   Run a specific seeder class
  --force         Force the operation to run in production
  
EXAMPLES:
  php retrina db:seed                    # Run all seeders
  php retrina db:seed --class=UserSeeder # Run specific seeder
  
HELP;
    }
    
    public function handle(array $arguments = [], array $options = []): int
    {
        try {
            // Parse arguments and options
            $seederClass = $options['class'] ?? ($arguments[0] ?? null);
            $force = isset($options['force']) || in_array('--force', $arguments);
            
            // Check if we're in production and warn
            if (!$force && $this->isProduction()) {
                echo "⚠️  WARNING: You are running this command in production!\n";
                echo "This will modify your database and could affect live data.\n";
                echo "Use --force flag if you really want to proceed.\n";
                return 1;
            }
            
            // Load required files
            $this->loadRequiredFiles();
            
            // Create seeder runner
            $seederRunner = new SeederRunner();
            
            // Run seeders
            if ($seederClass) {
                if (!$seederRunner->seederExists($seederClass)) {
                    echo "❌ Error: Seeder '{$seederClass}' not found.\n";
                    $available = $seederRunner->getAllSeeders();
                    if (!empty($available)) {
                        echo "Available seeders: " . implode(', ', $available) . "\n";
                    } else {
                        echo "No seeders found. Create one with: php retrina make:seeder {$seederClass}\n";
                    }
                    return 1;
                }
                $seederRunner->run($seederClass);
            } else {
                $seederRunner->run();
            }
            
            return 0;
            
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            if (isset($options['verbose']) || in_array('-v', $arguments) || in_array('--verbose', $arguments)) {
                echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
            }
            return 1;
        }
    }
    
    /**
     * Load required files for seeding
     */
    private function loadRequiredFiles(): void
    {
        $files = [
            __DIR__ . '/../../Database/Connection.php',
            __DIR__ . '/../../Database/QueryBuilder.php',
            __DIR__ . '/../../Database/DB.php',
            __DIR__ . '/../../Database/Seeder.php',
            __DIR__ . '/../../Database/SeederRunner.php',
        ];
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
    
    /**
     * Check if we're in production environment
     */
    private function isProduction(): bool
    {
        // Load .env if exists
        $envFile = __DIR__ . '/../../../.env';
        if (file_exists($envFile)) {
            $env = parse_ini_file($envFile);
            return isset($env['APP_ENV']) && $env['APP_ENV'] === 'production';
        }
        
        return false;
    }
} 