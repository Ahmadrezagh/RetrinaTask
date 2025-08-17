<?php

use Core\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run()
    {
        echo "🌱 Running Database Seeders...\n\n";
        
        // Call individual seeders
        $this->callSeeders([
            UserSeeder::class,
            // Add more seeders here as needed
            // ProductSeeder::class,
            // CategorySeeder::class,
        ]);
        
        echo "\n✨ All seeders completed successfully!\n";
    }
} 