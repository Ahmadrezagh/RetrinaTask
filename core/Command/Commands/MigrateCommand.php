<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;
use Core\MigrationRunner;

class MigrateCommand extends BaseCommand
{
    protected $signature = 'migrate';
    protected $description = 'Run database migrations';
    protected $help = 'This command runs all pending database migrations.

Usage:
  migrate [options]

Options:
  --rollback=steps     Rollback the last N migrations
  --rollback-all       Rollback all migrations
  --status             Show migration status
  --force              Force run migrations in production

Examples:
  php retrina.php migrate
  php retrina.php migrate --status
  php retrina.php migrate --rollback=3
  php retrina.php migrate --rollback-all';

    public function handle(array $arguments = [], array $options = []): int
    {
        require_once dirname(__DIR__, 2) . '/MigrationRunner.php';
        
        $runner = new MigrationRunner();

        if (isset($options['status'])) {
            return $this->showStatus($runner);
        }

        if (isset($options['rollback-all'])) {
            return $this->rollbackAll($runner);
        }

        if (isset($options['rollback'])) {
            $steps = is_numeric($options['rollback']) ? (int)$options['rollback'] : 1;
            return $this->rollback($runner, $steps);
        }

        return $this->runMigrations($runner);
    }

    private function showStatus(MigrationRunner $runner): int
    {
        $this->info('Checking migration status...');
        $runner->status();
        return 0;
    }

    private function runMigrations(MigrationRunner $runner): int
    {
        $this->info('Running database migrations...');
        
        if ($runner->migrate()) {
            $this->success('All migrations completed successfully!');
            return 0;
        } else {
            $this->error('Migration failed!');
            return 1;
        }
    }

    private function rollback(MigrationRunner $runner, int $steps): int
    {
        $this->warning("Rolling back last {$steps} migration(s)...");
        
        if ($runner->rollback($steps)) {
            $this->success('Rollback completed successfully!');
            return 0;
        } else {
            $this->error('Rollback failed!');
            return 1;
        }
    }

    private function rollbackAll(MigrationRunner $runner): int
    {
        if (!$this->confirm('This will rollback ALL migrations. Are you sure?', false)) {
            $this->info('Rollback cancelled.');
            return 0;
        }

        $this->warning('Rolling back all migrations...');
        
        if ($runner->rollbackAll()) {
            $this->success('All migrations rolled back successfully!');
            return 0;
        } else {
            $this->error('Rollback failed!');
            return 1;
        }
    }
} 