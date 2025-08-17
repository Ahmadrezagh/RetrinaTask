<?php

namespace Core\Command\Commands;

use Core\Command\CommandInterface;

class MakeSeederCommand implements CommandInterface
{
    public function getSignature(): string
    {
        return 'make:seeder {name : The name of the seeder class}';
    }
    
    public function getDescription(): string
    {
        return 'Create a new seeder class';
    }
    
    public function getHelp(): string
    {
        return <<<HELP
USAGE:
  php retrina make:seeder <name>

DESCRIPTION:
  Create a new seeder class for database seeding.

ARGUMENTS:
  name            The name of the seeder class (e.g., UserSeeder)

EXAMPLES:
  php retrina make:seeder UserSeeder
  php retrina make:seeder ProductSeeder
  
HELP;
    }
    
    public function handle(array $arguments = [], array $options = []): int
    {
        try {
            // Get seeder name
            $seederName = $arguments[0] ?? null;
            
            if (!$seederName) {
                echo "❌ Error: Seeder name is required.\n";
                echo "Usage: php retrina make:seeder <name>\n";
                return 1;
            }
            
            // Ensure proper naming
            if (!str_ends_with($seederName, 'Seeder')) {
                $seederName .= 'Seeder';
            }
            
            // Create seeders directory if it doesn't exist
            $seedersDir = __DIR__ . '/../../../database/seeders';
            if (!is_dir($seedersDir)) {
                mkdir($seedersDir, 0755, true);
            }
            
            // Create seeder file
            $seederFile = $seedersDir . '/' . $seederName . '.php';
            
            if (file_exists($seederFile)) {
                echo "❌ Error: Seeder '{$seederName}' already exists.\n";
                return 1;
            }
            
            // Generate seeder content
            $content = $this->generateSeederContent($seederName);
            
            // Write file
            if (file_put_contents($seederFile, $content)) {
                echo "✅ Seeder created successfully!\n";
                echo "📁 File: database/seeders/{$seederName}.php\n";
                echo "\n💡 Next steps:\n";
                echo "   1. Edit the seeder file to add your seed data\n";
                echo "   2. Register it in DatabaseSeeder.php (if needed)\n";
                echo "   3. Run: php retrina db:seed --class={$seederName}\n";
                return 0;
            } else {
                echo "❌ Error: Could not create seeder file.\n";
                return 1;
            }
            
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            return 1;
        }
    }
    
    /**
     * Generate seeder class content
     */
    private function generateSeederContent(string $seederName): string
    {
        $className = $seederName;
        $tableName = $this->getTableNameFromSeeder($seederName);
        
        return <<<PHP
<?php

use Core\Database\Seeder;
use Core\Database\DB;

class {$className} extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run()
    {
        // Clear existing data (optional)
        // \$this->delete('{$tableName}');
        
        // Insert seed data
        \$data = [
            [
                'name' => 'Example Record 1',
                'description' => 'This is an example record',
                'created_at' => \$this->now(),
                'updated_at' => \$this->now(),
            ],
            [
                'name' => 'Example Record 2', 
                'description' => 'This is another example record',
                'created_at' => \$this->now(),
                'updated_at' => \$this->now(),
            ],
        ];
        
        foreach (\$data as \$record) {
            DB::table('{$tableName}')->insert(\$record);
        }
        
        echo "   Inserted " . count(\$data) . " {$tableName} records\\n";
    }
}
PHP;
    }
    
    /**
     * Convert seeder name to table name
     */
    private function getTableNameFromSeeder(string $seederName): string
    {
        // Remove 'Seeder' suffix
        $name = str_replace('Seeder', '', $seederName);
        
        // Convert PascalCase to snake_case and pluralize
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        
        // Simple pluralization
        if (str_ends_with($tableName, 'y')) {
            $tableName = substr($tableName, 0, -1) . 'ies';
        } elseif (str_ends_with($tableName, 's') || str_ends_with($tableName, 'sh') || str_ends_with($tableName, 'ch')) {
            $tableName .= 'es';
        } else {
            $tableName .= 's';
        }
        
        return $tableName;
    }
} 