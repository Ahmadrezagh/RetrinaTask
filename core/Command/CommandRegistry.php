<?php

namespace Core\Command;

class CommandRegistry
{
    private array $commands = [];
    
    public function __construct()
    {
        $this->registerDefaultCommands();
    }
    
    /**
     * Register a command
     */
    public function register(CommandInterface $command): void
    {
        $this->commands[$command->getSignature()] = $command;
    }
    
    /**
     * Get a command by signature
     */
    public function get(string $signature): ?CommandInterface
    {
        return $this->commands[$signature] ?? null;
    }
    
    /**
     * Get all registered commands
     */
    public function all(): array
    {
        return $this->commands;
    }
    
    /**
     * Check if command exists
     */
    public function has(string $signature): bool
    {
        return isset($this->commands[$signature]);
    }
    
    /**
     * Register default commands
     */
    private function registerDefaultCommands(): void
    {
        // Load command files
        $commandPath = __DIR__ . '/Commands';
        if (!is_dir($commandPath)) {
            return;
        }
        
        $files = glob($commandPath . '/*.php');
        foreach ($files as $file) {
            // Require the file first
            require_once $file;
            
            $className = basename($file, '.php');
            $fullClassName = "Core\\Command\\Commands\\{$className}";
            
            if (class_exists($fullClassName)) {
                $command = new $fullClassName();
                if ($command instanceof CommandInterface) {
                    $this->register($command);
                }
            }
        }
    }
    
    /**
     * Find command by partial name
     */
    public function findCommand(string $input): ?CommandInterface
    {
        // Exact match first
        if ($this->has($input)) {
            return $this->get($input);
        }
        
        // Partial match
        $matches = [];
        foreach ($this->commands as $signature => $command) {
            if (strpos($signature, $input) === 0) {
                $matches[] = $command;
            }
        }
        
        // Return single match, or null if ambiguous
        return count($matches) === 1 ? $matches[0] : null;
    }
    
    /**
     * Get commands grouped by category
     */
    public function getGroupedCommands(): array
    {
        $grouped = [];
        
        foreach ($this->commands as $signature => $command) {
            $parts = explode(':', $signature);
            $category = count($parts) > 1 ? $parts[0] : 'General';
            
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            
            $grouped[$category][$signature] = $command;
        }
        
        return $grouped;
    }
} 