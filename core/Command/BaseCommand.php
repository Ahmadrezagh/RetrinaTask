<?php

namespace Core\Command;

abstract class BaseCommand implements CommandInterface
{
    protected $signature = '';
    protected $description = '';
    protected $help = '';
    
    // Color constants for console output
    const COLOR_GREEN = "\033[32m";
    const COLOR_RED = "\033[31m";
    const COLOR_YELLOW = "\033[33m";
    const COLOR_BLUE = "\033[34m";
    const COLOR_CYAN = "\033[36m";
    const COLOR_MAGENTA = "\033[35m";
    const COLOR_WHITE = "\033[37m";
    const COLOR_RESET = "\033[0m";
    const BOLD = "\033[1m";
    
    public function getSignature(): string
    {
        return $this->signature;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function getHelp(): string
    {
        return $this->help ?: $this->description;
    }
    
    /**
     * Output success message
     */
    protected function success(string $message): void
    {
        echo self::COLOR_GREEN . "✅ " . $message . self::COLOR_RESET . "\n";
    }
    
    /**
     * Output error message
     */
    protected function error(string $message): void
    {
        echo self::COLOR_RED . "❌ " . $message . self::COLOR_RESET . "\n";
    }
    
    /**
     * Output warning message
     */
    protected function warning(string $message): void
    {
        echo self::COLOR_YELLOW . "⚠️  " . $message . self::COLOR_RESET . "\n";
    }
    
    /**
     * Output info message
     */
    protected function info(string $message): void
    {
        echo self::COLOR_BLUE . "ℹ️  " . $message . self::COLOR_RESET . "\n";
    }
    
    /**
     * Output plain message
     */
    protected function line(string $message = ''): void
    {
        echo $message . "\n";
    }
    
    /**
     * Output colored message
     */
    protected function colored(string $message, string $color = self::COLOR_WHITE): void
    {
        echo $color . $message . self::COLOR_RESET . "\n";
    }
    
    /**
     * Ask user for input
     */
    protected function ask(string $question, string $default = ''): string
    {
        echo self::COLOR_CYAN . $question . self::COLOR_RESET;
        if ($default) {
            echo self::COLOR_YELLOW . " [" . $default . "]" . self::COLOR_RESET;
        }
        echo ": ";
        
        $handle = fopen("php://stdin", "r");
        $input = trim(fgets($handle));
        fclose($handle);
        
        return $input ?: $default;
    }
    
    /**
     * Ask user for confirmation
     */
    protected function confirm(string $question, bool $default = true): bool
    {
        $defaultText = $default ? 'Y/n' : 'y/N';
        $answer = $this->ask($question . " (" . $defaultText . ")", $default ? 'y' : 'n');
        
        if (strtolower($answer) === 'y' || strtolower($answer) === 'yes') {
            return true;
        }
        if (strtolower($answer) === 'n' || strtolower($answer) === 'no') {
            return false;
        }
        
        return $default;
    }
    
    /**
     * Create directory if it doesn't exist
     */
    protected function ensureDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
            $this->info("Created directory: " . $path);
        }
    }
    
    /**
     * Check if file exists and ask for overwrite confirmation
     */
    protected function checkFileExists(string $path): bool
    {
        if (file_exists($path)) {
            return $this->confirm("File already exists: " . $path . ". Overwrite?", false);
        }
        return true;
    }
    
    /**
     * Convert string to StudlyCase (PascalCase)
     */
    protected function studly(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }
    
    /**
     * Convert string to camelCase
     */
    protected function camel(string $string): string
    {
        return lcfirst($this->studly($string));
    }
    
    /**
     * Convert string to snake_case
     */
    protected function snake(string $string): string
    {
        return strtolower(preg_replace('/([A-Z])/', '_$1', lcfirst($string)));
    }
    
    /**
     * Convert string to kebab-case
     */
    protected function kebab(string $string): string
    {
        return str_replace('_', '-', $this->snake($string));
    }
    
    /**
     * Get plural form of a word (simple pluralization)
     */
    protected function plural(string $word): string
    {
        $word = strtolower($word);
        
        // Special cases
        $irregulars = [
            'child' => 'children',
            'person' => 'people',
            'man' => 'men',
            'woman' => 'women',
            'foot' => 'feet',
            'tooth' => 'teeth',
            'mouse' => 'mice',
        ];
        
        if (isset($irregulars[$word])) {
            return $irregulars[$word];
        }
        
        // Rules
        if (substr($word, -1) === 'y' && !in_array(substr($word, -2, 1), ['a', 'e', 'i', 'o', 'u'])) {
            return substr($word, 0, -1) . 'ies';
        }
        
        if (in_array(substr($word, -1), ['s', 'x', 'z']) || in_array(substr($word, -2), ['ch', 'sh'])) {
            return $word . 'es';
        }
        
        if (substr($word, -1) === 'f') {
            return substr($word, 0, -1) . 'ves';
        }
        
        if (substr($word, -2) === 'fe') {
            return substr($word, 0, -2) . 'ves';
        }
        
        return $word . 's';
    }
    
    /**
     * Get singular form of a word (simple singularization)
     */
    protected function singular(string $word): string
    {
        $word = strtolower($word);
        
        // Special cases
        $irregulars = [
            'children' => 'child',
            'people' => 'person',
            'men' => 'man',
            'women' => 'woman',
            'feet' => 'foot',
            'teeth' => 'tooth',
            'mice' => 'mouse',
        ];
        
        if (isset($irregulars[$word])) {
            return $irregulars[$word];
        }
        
        // Rules
        if (substr($word, -3) === 'ies') {
            return substr($word, 0, -3) . 'y';
        }
        
        if (substr($word, -3) === 'ves') {
            return substr($word, 0, -3) . 'f';
        }
        
        if (substr($word, -2) === 'es' && in_array(substr($word, -4, 2), ['ch', 'sh'])) {
            return substr($word, 0, -2);
        }
        
        if (substr($word, -1) === 's' && !in_array(substr($word, -2), ['ss'])) {
            return substr($word, 0, -1);
        }
        
        return $word;
    }
    
    /**
     * Generate timestamp for migration files
     */
    protected function getMigrationTimestamp(): string
    {
        return date('Y_m_d_His');
    }
} 