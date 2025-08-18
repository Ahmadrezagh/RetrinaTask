<?php

namespace Core\Testing;

class TestRunner
{
    protected $testDirectories = [];
    protected $results = [];
    protected $verbose = false;
    protected $colors = true;
    
    public function __construct()
    {
        $this->testDirectories = [
            'tests/Unit',
            'tests/Feature', 
            'tests/Api',
            'tests/Web'
        ];
    }
    
    /**
     * Add test directory
     */
    public function addDirectory(string $directory): void
    {
        $this->testDirectories[] = $directory;
    }
    
    /**
     * Set verbose output
     */
    public function setVerbose(bool $verbose): void
    {
        $this->verbose = $verbose;
    }
    
    /**
     * Set colored output
     */
    public function setColors(bool $colors): void
    {
        $this->colors = $colors;
    }
    
    /**
     * Run all tests
     */
    public function run(): array
    {
        $this->output($this->colorize("Retrina Testing Framework\n", 'cyan'));
        $this->output(str_repeat("=", 50) . "\n");
        
        TestCase::resetStats();
        $startTime = microtime(true);
        
        foreach ($this->testDirectories as $directory) {
            if (is_dir($directory)) {
                $this->runTestsInDirectory($directory);
            }
        }
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        $stats = TestCase::getStats();
        $this->displaySummary($stats, $duration);
        
        return $stats;
    }
    
    /**
     * Run tests in a specific directory
     */
    protected function runTestsInDirectory(string $directory): void
    {
        $this->output("\nRunning tests in: $directory\n");
        
        $files = glob($directory . '/*Test.php');
        
        foreach ($files as $file) {
            $this->runTestFile($file);
        }
    }
    
    /**
     * Run tests in a specific file
     */
    protected function runTestFile(string $file): void
    {
        require_once $file;
        
        $className = basename($file, '.php');
        
        if (class_exists($className)) {
            $this->output("  " . $this->colorize("• $className", 'yellow') . "\n");
            
            $testInstance = new $className();
            $results = $testInstance->run();
            
            foreach ($results['results'] as $result) {
                $this->displayTestResult($result);
            }
        }
    }
    
    /**
     * Display individual test result
     */
    protected function displayTestResult(array $result): void
    {
        $status = $result['status'];
        $test = $result['test'];
        $message = $result['message'];
        
        $symbol = $this->getStatusSymbol($status);
        $color = $this->getStatusColor($status);
        
        $output = "    $symbol " . $this->colorize($test, $color);
        
        if ($this->verbose && $message) {
            $output .= "\n      " . $this->colorize($message, 'red');
        }
        
        $this->output($output . "\n");
    }
    
    /**
     * Get status symbol
     */
    protected function getStatusSymbol(string $status): string
    {
        switch ($status) {
            case 'PASSED':
                return $this->colorize('✓', 'green');
            case 'FAILED':
                return $this->colorize('✗', 'red');
            case 'SKIPPED':
                return $this->colorize('⚠', 'yellow');
            case 'ERROR':
                return $this->colorize('⚠', 'red');
            default:
                return '?';
        }
    }
    
    /**
     * Get status color
     */
    protected function getStatusColor(string $status): string
    {
        switch ($status) {
            case 'PASSED':
                return 'green';
            case 'FAILED':
            case 'ERROR':
                return 'red';
            case 'SKIPPED':
                return 'yellow';
            default:
                return 'white';
        }
    }
    
    /**
     * Display test summary
     */
    protected function displaySummary(array $stats, float $duration): void
    {
        $this->output("\n" . str_repeat("=", 50) . "\n");
        $this->output($this->colorize("Test Results\n", 'cyan'));
        
        $total = $stats['total'];
        $passed = $stats['passed'];
        $failed = $stats['failed'];
        $skipped = $stats['skipped'];
        
        $this->output("Total Tests: $total\n");
        $this->output($this->colorize("Passed: $passed", 'green') . "\n");
        
        if ($failed > 0) {
            $this->output($this->colorize("Failed: $failed", 'red') . "\n");
        }
        
        if ($skipped > 0) {
            $this->output($this->colorize("Skipped: $skipped", 'yellow') . "\n");
        }
        
        $this->output("Duration: {$duration}s\n");
        
        if ($failed === 0) {
            $this->output("\n" . $this->colorize("All tests passed! 🎉", 'green') . "\n");
        } else {
            $this->output("\n" . $this->colorize("Some tests failed! 😞", 'red') . "\n");
        }
    }
    
    /**
     * Colorize text for terminal output
     */
    protected function colorize(string $text, string $color): string
    {
        if (!$this->colors) {
            return $text;
        }
        
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
    
    /**
     * Output text
     */
    protected function output(string $text): void
    {
        echo $text;
    }
    
    /**
     * Generate HTML report
     */
    public function generateHtmlReport(string $filename = 'test-report.html'): void
    {
        $stats = TestCase::getStats();
        $results = TestCase::$results ?? [];
        
        $html = $this->generateHtmlContent($stats, $results);
        
        file_put_contents($filename, $html);
        $this->output("HTML report generated: $filename\n");
    }
    
    /**
     * Generate HTML content for report
     */
    protected function generateHtmlContent(array $stats, array $results): string
    {
        $total = $stats['total'];
        $passed = $stats['passed'];
        $failed = $stats['failed'];
        $skipped = $stats['skipped'];
        
        $passRate = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Test Report - Retrina Framework</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
                .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { text-align: center; margin-bottom: 30px; }
                .stats { display: flex; justify-content: space-around; margin-bottom: 30px; }
                .stat { text-align: center; padding: 20px; border-radius: 8px; }
                .stat-passed { background: #d4edda; color: #155724; }
                .stat-failed { background: #f8d7da; color: #721c24; }
                .stat-skipped { background: #fff3cd; color: #856404; }
                .stat-total { background: #e2e3e5; color: #383d41; }
                .results { margin-top: 20px; }
                .test-result { padding: 10px; margin: 5px 0; border-radius: 4px; }
                .test-passed { background: #d4edda; }
                .test-failed { background: #f8d7da; }
                .test-skipped { background: #fff3cd; }
                .test-error { background: #f8d7da; }
                .test-name { font-weight: bold; }
                .test-message { margin-top: 5px; font-size: 0.9em; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Test Report</h1>
                    <p>Retrina Framework - <?= date('Y-m-d H:i:s') ?></p>
                    <p>Pass Rate: <?= $passRate ?>%</p>
                </div>
                
                <div class="stats">
                    <div class="stat stat-total">
                        <h3><?= $total ?></h3>
                        <p>Total Tests</p>
                    </div>
                    <div class="stat stat-passed">
                        <h3><?= $passed ?></h3>
                        <p>Passed</p>
                    </div>
                    <div class="stat stat-failed">
                        <h3><?= $failed ?></h3>
                        <p>Failed</p>
                    </div>
                    <div class="stat stat-skipped">
                        <h3><?= $skipped ?></h3>
                        <p>Skipped</p>
                    </div>
                </div>
                
                <div class="results">
                    <h2>Test Results</h2>
                    <?php foreach ($results as $result): ?>
                        <div class="test-result test-<?= strtolower($result['status']) ?>">
                            <div class="test-name"><?= htmlspecialchars($result['test']) ?></div>
                            <div class="test-status">Status: <?= $result['status'] ?></div>
                            <?php if ($result['message']): ?>
                                <div class="test-message"><?= htmlspecialchars($result['message']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
} 