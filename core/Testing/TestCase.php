<?php

namespace Core\Testing;

use Exception;

abstract class TestCase
{
    protected static $passed = 0;
    protected static $failed = 0;
    protected static $skipped = 0;
    protected static $results = [];
    protected static $currentTest = '';
    
    protected $assertions = 0;
    
    /**
     * Set up before each test
     */
    protected function setUp(): void
    {
        // Override in test classes
    }
    
    /**
     * Clean up after each test
     */
    protected function tearDown(): void
    {
        // Override in test classes
    }
    
    /**
     * Run all test methods in the class
     */
    public function run(): array
    {
        $reflection = new \ReflectionClass($this);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        foreach ($methods as $method) {
            if (strpos($method->getName(), 'test') === 0) {
                $this->runTest($method->getName());
            }
        }
        
        return [
            'passed' => self::$passed,
            'failed' => self::$failed,
            'skipped' => self::$skipped,
            'results' => self::$results
        ];
    }
    
    /**
     * Run a single test method
     */
    protected function runTest(string $methodName): void
    {
        self::$currentTest = get_class($this) . '::' . $methodName;
        $this->assertions = 0;
        
        try {
            $this->setUp();
            $this->$methodName();
            $this->tearDown();
            
            self::$passed++;
            self::$results[] = [
                'test' => self::$currentTest,
                'status' => 'PASSED',
                'assertions' => $this->assertions,
                'message' => null,
                'time' => microtime(true)
            ];
            
        } catch (AssertionException $e) {
            self::$failed++;
            self::$results[] = [
                'test' => self::$currentTest,
                'status' => 'FAILED',
                'assertions' => $this->assertions,
                'message' => $e->getMessage(),
                'time' => microtime(true)
            ];
            
        } catch (SkippedException $e) {
            self::$skipped++;
            self::$results[] = [
                'test' => self::$currentTest,
                'status' => 'SKIPPED',
                'assertions' => $this->assertions,
                'message' => $e->getMessage(),
                'time' => microtime(true)
            ];
            
        } catch (Exception $e) {
            self::$failed++;
            self::$results[] = [
                'test' => self::$currentTest,
                'status' => 'ERROR',
                'assertions' => $this->assertions,
                'message' => $e->getMessage(),
                'time' => microtime(true)
            ];
        }
    }
    
    /**
     * Assert that a condition is true
     */
    protected function assertTrue($condition, string $message = ''): void
    {
        $this->assertions++;
        
        if (!$condition) {
            throw new AssertionException($message ?: 'Failed asserting that condition is true');
        }
    }
    
    /**
     * Assert that a condition is false
     */
    protected function assertFalse($condition, string $message = ''): void
    {
        $this->assertions++;
        
        if ($condition) {
            throw new AssertionException($message ?: 'Failed asserting that condition is false');
        }
    }
    
    /**
     * Assert that two values are equal
     */
    protected function assertEquals($expected, $actual, string $message = ''): void
    {
        $this->assertions++;
        
        if ($expected !== $actual) {
            $message = $message ?: "Failed asserting that '$actual' equals '$expected'";
            throw new AssertionException($message);
        }
    }
    
    /**
     * Assert that two values are not equal
     */
    protected function assertNotEquals($expected, $actual, string $message = ''): void
    {
        $this->assertions++;
        
        if ($expected === $actual) {
            $message = $message ?: "Failed asserting that '$actual' does not equal '$expected'";
            throw new AssertionException($message);
        }
    }
    
    /**
     * Assert that a value is null
     */
    protected function assertNull($value, string $message = ''): void
    {
        $this->assertions++;
        
        if ($value !== null) {
            throw new AssertionException($message ?: 'Failed asserting that value is null');
        }
    }
    
    /**
     * Assert that a value is not null
     */
    protected function assertNotNull($value, string $message = ''): void
    {
        $this->assertions++;
        
        if ($value === null) {
            throw new AssertionException($message ?: 'Failed asserting that value is not null');
        }
    }
    
    /**
     * Assert that an array contains a value
     */
    protected function assertContains($needle, array $haystack, string $message = ''): void
    {
        $this->assertions++;
        
        if (!in_array($needle, $haystack)) {
            throw new AssertionException($message ?: "Failed asserting that array contains '$needle'");
        }
    }
    
    /**
     * Assert that a string contains a substring
     */
    protected function assertStringContains(string $needle, string $haystack, string $message = ''): void
    {
        $this->assertions++;
        
        if (strpos($haystack, $needle) === false) {
            throw new AssertionException($message ?: "Failed asserting that '$haystack' contains '$needle'");
        }
    }
    
    /**
     * Assert that an exception is thrown
     */
    protected function expectException(string $exceptionClass): void
    {
        $this->expectedExceptionClass = $exceptionClass;
    }
    
    /**
     * Skip the current test
     */
    protected function markTestSkipped(string $message = ''): void
    {
        throw new SkippedException($message ?: 'Test skipped');
    }
    
    /**
     * Get test statistics
     */
    public static function getStats(): array
    {
        return [
            'passed' => self::$passed,
            'failed' => self::$failed,
            'skipped' => self::$skipped,
            'total' => self::$passed + self::$failed + self::$skipped
        ];
    }
    
    /**
     * Reset test statistics
     */
    public static function resetStats(): void
    {
        self::$passed = 0;
        self::$failed = 0;
        self::$skipped = 0;
        self::$results = [];
    }
} 