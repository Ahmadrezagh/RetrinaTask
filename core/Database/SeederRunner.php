<?php

namespace Core\Database;

class SeederRunner
{
    private $seederPath;
    
    public function __construct($seederPath = null)
    {
        $this->seederPath = $seederPath ?: __DIR__ . '/../../database/seeders/';
    }
    
    /**
     * Run all seeders or a specific seeder
     */
    public function run($seederClass = null)
    {
        echo "🌱 " . str_repeat("=", 50) . "\n";
        echo "🌱 Starting Database Seeding\n";
        echo "🌱 " . str_repeat("=", 50) . "\n\n";
        
        if ($seederClass) {
            $this->runSeeder($seederClass);
        } else {
            $this->runDatabaseSeeder();
        }
        
        echo "\n🎉 " . str_repeat("=", 50) . "\n";
        echo "🎉 Database seeding completed successfully!\n";
        echo "🎉 " . str_repeat("=", 50) . "\n";
    }
    
    /**
     * Run a specific seeder
     */
    private function runSeeder($seederClass)
    {
        // Load seeder file if it's just a class name
        if (!class_exists($seederClass)) {
            $this->loadSeederFile($seederClass);
        }
        
        if (!class_exists($seederClass)) {
            throw new \Exception("Seeder class '{$seederClass}' not found");
        }
        
        $seeder = new $seederClass();
        
        if (!$seeder instanceof Seeder) {
            throw new \Exception("Seeder must extend Core\\Database\\Seeder");
        }
        
        echo "🌱 Seeding: {$seederClass}\n";
        $seeder->run();
        echo "✅ Seeded: {$seederClass}\n";
    }
    
    /**
     * Run the main DatabaseSeeder
     */
    private function runDatabaseSeeder()
    {
        $databaseSeederClass = 'DatabaseSeeder';
        
        // Load the DatabaseSeeder file
        $this->loadSeederFile($databaseSeederClass);
        
        if (!class_exists($databaseSeederClass)) {
            throw new \Exception("DatabaseSeeder not found. Please create database/seeders/DatabaseSeeder.php");
        }
        
        $this->runSeeder($databaseSeederClass);
    }
    
    /**
     * Load a seeder file
     */
    private function loadSeederFile($seederClass)
    {
        $seederFile = $this->seederPath . $seederClass . '.php';
        
        if (file_exists($seederFile)) {
            require_once $seederFile;
        }
    }
    
    /**
     * Get all seeder files
     */
    public function getAllSeeders()
    {
        $seeders = [];
        
        if (!is_dir($this->seederPath)) {
            return $seeders;
        }
        
        $files = scandir($this->seederPath);
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $seeders[] = $className;
            }
        }
        
        return $seeders;
    }
    
    /**
     * Check if a seeder exists
     */
    public function seederExists($seederClass)
    {
        $seederFile = $this->seederPath . $seederClass . '.php';
        return file_exists($seederFile);
    }
} 