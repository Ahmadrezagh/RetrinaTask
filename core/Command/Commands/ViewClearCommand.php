<?php

namespace Core\Command\Commands;

use Core\Command\BaseCommand;

class ViewClearCommand extends BaseCommand
{
    protected $signature = 'view:clear';
    protected $description = 'Clear all compiled view files from the cache';
    protected $help = 'Clear all compiled view files from the cache directory.

This command removes all .php files from the storage/cache/views/ directory,
forcing the template engine to recompile all views on the next request.

Usage:
  php retrina view:clear

This is useful when:
  - You\'ve made changes to the template engine
  - You\'re debugging view compilation issues
  - You want to ensure fresh template compilation';

    public function handle(array $arguments = [], array $options = []): int
    {
        $cacheDir = __DIR__ . '/../../../storage/cache/views';
        
        if (!is_dir($cacheDir)) {
            $this->warning('View cache directory does not exist.');
            return 0;
        }

        // Get all .php files in the cache directory
        $files = glob($cacheDir . '/*.php');
        
        if (empty($files)) {
            $this->warning('No cached view files found to clear.');
            return 0;
        }

        $deletedCount = 0;
        foreach ($files as $file) {
            if (unlink($file)) {
                $deletedCount++;
            }
        }

        $this->success("View cache cleared! Deleted {$deletedCount} cached view files.");
        return 0;
    }
} 