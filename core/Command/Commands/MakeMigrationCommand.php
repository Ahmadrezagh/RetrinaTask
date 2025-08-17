<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;

class MakeMigrationCommand extends BaseCommand
{
    protected $signature = 'make:migration';
    protected $description = 'Create a new migration file';
    protected $help = 'This command creates a new migration file in the database/migrations directory.

Usage:
  make:migration migration_name [options]

Arguments:
  name                 The name of the migration

Options:
  --create=table       Create a new table
  --table=table        Modify an existing table
  -f, --force          Overwrite the migration if it already exists

Examples:
  php retrina.php make:migration create_users_table
  php retrina.php make:migration add_email_to_users_table --table=users
  php retrina.php make:migration create_posts_table --create=posts';

    public function handle(array $arguments = [], array $options = []): int
    {
        if (empty($arguments)) {
            $this->error('Migration name is required.');
            $this->line('Usage: make:migration migration_name [options]');
            return 1;
        }

        $migrationName = $this->snake($arguments[0]);
        $createTable = $options['create'] ?? null;
        $modifyTable = $options['table'] ?? null;
        $force = isset($options['f']) || isset($options['force']);

        return $this->createMigration($migrationName, $createTable, $modifyTable, $force);
    }

    private function createMigration(string $name, ?string $createTable, ?string $modifyTable, bool $force): int
    {
        $className = $this->studly($name);
        $timestamp = $this->getMigrationTimestamp();
        $filename = "{$timestamp}_{$name}.php";
        $migrationPath = dirname(__DIR__, 3) . "/database/migrations/{$filename}";

        if (!$force && !$this->checkFileExists($migrationPath)) {
            $this->warning('Migration creation cancelled.');
            return 1;
        }

        $this->ensureDirectory(dirname($migrationPath));

        $content = $this->generateMigrationContent($className, $createTable, $modifyTable);

        if (file_put_contents($migrationPath, $content) === false) {
            $this->error('Failed to create migration file.');
            return 1;
        }

        $this->success("Migration created successfully: {$migrationPath}");
        return 0;
    }

    private function generateMigrationContent(string $className, ?string $createTable, ?string $modifyTable): string
    {
        if ($createTable) {
            return $this->getCreateTableTemplate($className, $createTable);
        }

        if ($modifyTable) {
            return $this->getModifyTableTemplate($className, $modifyTable);
        }

        return $this->getBasicMigrationTemplate($className);
    }

    private function getCreateTableTemplate(string $className, string $tableName): string
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

    private function getModifyTableTemplate(string $className, string $tableName): string
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
        // Add columns
        // \$this->addColumn('{$tableName}', '`new_column` VARCHAR(255) NULL');
        
        // Add indexes
        // \$this->addIndex('{$tableName}', 'column_name');
        
        // Execute custom SQL
        // \$this->executeSQL(\"ALTER TABLE {$tableName} ...\");
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        // Drop columns
        // \$this->dropColumn('{$tableName}', 'new_column');
        
        // Drop indexes
        // \$this->dropIndex('{$tableName}', 'idx_column_name');
    }
}
";
    }

    private function getBasicMigrationTemplate(string $className): string
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
        // Add your migration logic here
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        // Add your rollback logic here
    }
}
";
    }
} 