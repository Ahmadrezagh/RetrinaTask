<?php

namespace Core\Command;

interface CommandInterface
{
    /**
     * Get the command signature
     */
    public function getSignature(): string;
    
    /**
     * Get the command description
     */
    public function getDescription(): string;
    
    /**
     * Execute the command
     */
    public function handle(array $arguments = [], array $options = []): int;
    
    /**
     * Get command help text
     */
    public function getHelp(): string;
} 