<?php

namespace Core\Command\Commands;

use Core\Command\CommandInterface;
use Core\Testing\TestRunner;

class TestCommand implements CommandInterface
{
    public function getSignature(): string
    {
        return 'test';
    }
    
    public function getDescription(): string
    {
        return 'Run the test suite';
    }
    
    public function handle(array $arguments = [], array $options = []): int
    {
        echo $this->colorize("🧪 Retrina Test Runner\n", 'cyan');
        echo str_repeat("=", 50) . "\n";
        
        // Parse options
        $verbose = in_array('--verbose', $options) || in_array('-v', $options);
        $colors = !in_array('--no-colors', $options);
        $filter = $this->getOption($options, '--filter') ?: null;
        $directory = $this->getOption($options, '--directory') ?: null;
        $report = in_array('--report', $options);
        
        // Load required files
        $this->loadTestingFramework();
        
        // Create test runner
        $runner = new TestRunner();
        $runner->setVerbose($verbose);
        $runner->setColors($colors);
        
        // Add specific directory if provided
        if ($directory && is_dir($directory)) {
            $runner = new TestRunner();
            $runner->addDirectory($directory);
        }
        
        try {
            // Run tests
            $stats = $runner->run();
            
            // Generate HTML report if requested
            if ($report) {
                $reportFile = 'test-report-' . date('Y-m-d-H-i-s') . '.html';
                $runner->generateHtmlReport($reportFile);
            }
            
            // Return error code if tests failed
            if ($stats['failed'] > 0) {
                return 1;
            }
            
        } catch (\Exception $e) {
            echo $this->colorize("Error running tests: " . $e->getMessage() . "\n", 'red');
            return 1;
        }
        
        return 0;
    }
    
    public function getHelp(): string
    {
        return "Usage: php retrina test [options]

Run the application test suite.

Options:
  -v, --verbose      Show detailed test output
  --no-colors        Disable colored output
  --filter=PATTERN   Run only tests matching the pattern
  --directory=DIR    Run tests from specific directory
  --report           Generate HTML test report

Examples:
  php retrina test                    # Run all tests
  php retrina test --verbose          # Run with detailed output
  php retrina test --filter=Auth      # Run only authentication tests
  php retrina test --directory=tests/Unit  # Run only unit tests
  php retrina test --report           # Generate HTML report
";
    }
    
    /**
     * Load testing framework files
     */
    protected function loadTestingFramework(): void
    {
        $files = [
            'core/Testing/TestCase.php',
            'core/Testing/AssertionException.php',
            'core/Testing/SkippedException.php',
            'core/Testing/ApiTestCase.php',
            'core/Testing/WebTestCase.php',
            'core/Testing/TestRunner.php'
        ];
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
    
    /**
     * Get option value
     */
    protected function getOption(array $options, string $option): ?string
    {
        foreach ($options as $opt) {
            if (strpos($opt, $option . '=') === 0) {
                return substr($opt, strlen($option) + 1);
            }
        }
        return null;
    }
    
    /**
     * Colorize text for terminal output
     */
    protected function colorize(string $text, string $color): string
    {
        $colors = [
            'red' => "\033[31m",
            'green' => "\033[32m",
            'yellow' => "\033[33m",
            'blue' => "\033[34m",
            'cyan' => "\033[36m",
            'white' => "\033[37m",
            'reset' => "\033[0m"
        ];
        
        $colorCode = $colors[$color] ?? $colors['white'];
        return $colorCode . $text . $colors['reset'];
    }
} 