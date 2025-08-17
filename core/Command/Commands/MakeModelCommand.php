<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;

class MakeModelCommand extends BaseCommand
{
    protected $signature = 'make:model';
    protected $description = 'Create a new model class';
    protected $help = 'This command creates a new model class in the app/Models directory.

Usage:
  make:model ModelName [options]

Arguments:
  name                 The name of the model

Options:
  -m, --migration      Create a migration file along with the model
  -f, --force          Overwrite the model if it already exists

Examples:
  php retrina.php make:model User
  php retrina.php make:model Post --migration
  php retrina.php make:model Category -m';

    public function handle(array $arguments = [], array $options = []): int
    {
        if (empty($arguments)) {
            $this->error('Model name is required.');
            $this->line('Usage: make:model ModelName [options]');
            return 1;
        }

        $modelName = $this->studly($arguments[0]);
        $withMigration = isset($options['m']) || isset($options['migration']);
        $force = isset($options['f']) || isset($options['force']);

        $result = $this->createModel($modelName, $force);
        
        if ($result === 0 && $withMigration) {
            $this->createMigration($modelName);
        }

        return $result;
    }

    private function createModel(string $name, bool $force): int
    {
        $modelPath = $this->getModelPath($name);

        if (!$force && !$this->checkFileExists($modelPath)) {
            $this->warning('Model creation cancelled.');
            return 1;
        }

        $this->ensureDirectory(dirname($modelPath));

        $content = $this->generateModelContent($name);

        if (file_put_contents($modelPath, $content) === false) {
            $this->error('Failed to create model file.');
            return 1;
        }

        $this->success("Model created successfully: {$modelPath}");
        return 0;
    }

    private function createMigration(string $modelName): void
    {
        $tableName = $this->plural($this->snake($modelName));
        $migrationName = "Create" . $this->plural($modelName) . "Table";
        $timestamp = $this->getMigrationTimestamp();
        $migrationFile = "{$timestamp}_{$this->snake($migrationName)}.php";
        $migrationPath = dirname(__DIR__, 3) . "/database/migrations/{$migrationFile}";

        $this->ensureDirectory(dirname($migrationPath));

        $content = $this->generateMigrationContent($migrationName, $tableName);

        if (file_put_contents($migrationPath, $content) !== false) {
            $this->success("Migration created successfully: {$migrationPath}");
        } else {
            $this->warning("Failed to create migration file.");
        }
    }

    private function getModelPath(string $name): string
    {
        return dirname(__DIR__, 3) . "/app/Models/{$name}.php";
    }

    private function generateModelContent(string $name): string
    {
        $tableName = $this->plural($this->snake($name));

        return "<?php

namespace App\Models;

class {$name} extends BaseModel
{
    protected \$table = '{$tableName}';
    
    protected \$fillable = [
        // Add your fillable fields here
    ];
    
    protected \$hidden = [
        // Add fields to hide from JSON output
    ];
    
    protected \$casts = [
        // Add field type casting here
        // 'created_at' => 'datetime',
        // 'is_active' => 'boolean',
    ];
    
    // Add your model methods here
}
";
    }

    private function generateMigrationContent(string $className, string $tableName): string
    {
        return "<?php

require_once __DIR__ . '/../../core/Migration.php';

use Core\Migration;

class {$className} extends Migration
{
    /**
     * Run the migration
     */
    public function up()
    {
        \$columns = [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            // Add your columns here
            ...\$this->timestamps(),
            '',
            '-- Indexes',
            // Add your indexes here
        ];
        
        \$this->createTable('{$tableName}', \$columns);
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        \$this->dropTable('{$tableName}');
    }
}
";
    }
} 