<?php

namespace Core\Database;

use Core\Database\DB;

abstract class Seeder
{
    /**
     * Run the database seeds
     */
    abstract public function run();
    
    /**
     * Get database connection
     */
    protected function db()
    {
        return DB::connection();
    }
    
    /**
     * Call another seeder
     */
    protected function call($seederClass)
    {
        if (is_string($seederClass)) {
            if (!class_exists($seederClass)) {
                throw new \Exception("Seeder class '{$seederClass}' not found");
            }
            $seeder = new $seederClass();
        } else {
            $seeder = $seederClass;
        }
        
        if (!$seeder instanceof Seeder) {
            throw new \Exception("Seeder must extend Core\\Database\\Seeder");
        }
        
        echo "🌱 Seeding: " . get_class($seeder) . "\n";
        $seeder->run();
        echo "✅ Seeded: " . get_class($seeder) . "\n";
    }
    
    /**
     * Call multiple seeders
     */
    protected function callSeeders(array $seeders)
    {
        foreach ($seeders as $seeder) {
            $this->call($seeder);
        }
    }
    
    /**
     * Helper method to hash passwords
     */
    protected function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Helper method to get current timestamp
     */
    protected function now()
    {
        return date('Y-m-d H:i:s');
    }
    
    /**
     * Helper method to insert data with timestamps
     */
    protected function insert($table, $data)
    {
        // Add timestamps if not present
        if (!isset($data['created_at'])) {
            $data['created_at'] = $this->now();
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = $this->now();
        }
        
        return DB::table($table)->insert($data);
    }
    
    /**
     * Helper method to truncate table
     */
    protected function truncate($table)
    {
        return DB::statement("TRUNCATE TABLE {$table}");
    }
    
    /**
     * Helper method to delete all records from table
     */
    protected function delete($table)
    {
        return DB::table($table)->delete();
    }
} 