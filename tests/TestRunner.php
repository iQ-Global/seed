<?php
/**
 * Seed Framework Test Runner
 * 
 * Simple test runner - no dependencies required
 * Run: php tests/TestRunner.php
 */

class TestRunner {
    private static $tests = 0;
    private static $passed = 0;
    private static $failed = 0;
    private static $suites = [];
    
    public static function suite($name) {
        self::$suites[] = $name;
        echo "\n{$name}\n";
        echo str_repeat('-', strlen($name)) . "\n";
    }
    
    public static function test($name, $callback) {
        self::$tests++;
        
        try {
            $result = $callback();
            if ($result) {
                self::$passed++;
                echo "✓ {$name}\n";
            } else {
                self::$failed++;
                echo "✗ {$name}\n";
            }
        } catch (Exception $e) {
            self::$failed++;
            echo "✗ {$name} - Exception: {$e->getMessage()}\n";
        }
    }
    
    public static function summary() {
        echo "\n";
        echo str_repeat('=', 80) . "\n";
        echo "TEST SUMMARY\n";
        echo str_repeat('=', 80) . "\n\n";
        
        echo "Total Tests:  " . self::$tests . "\n";
        echo "Passed:       " . self::$passed . " (" . round((self::$passed/self::$tests)*100, 1) . "%)\n";
        echo "Failed:       " . self::$failed . "\n\n";
        
        if (self::$failed === 0) {
            echo "✓ ALL TESTS PASSED!\n\n";
            return 0;
        } else {
            echo "✗ SOME TESTS FAILED\n\n";
            return 1;
        }
    }
}

// Autoload
require_once __DIR__ . '/../vendor/autoload.php';

echo "\n";
echo str_repeat('=', 80) . "\n";
echo "SEED FRAMEWORK TEST SUITE\n";
echo str_repeat('=', 80) . "\n";

// Load all test files
require_once __DIR__ . '/Core/RouterTest.php';
require_once __DIR__ . '/Core/RequestTest.php';
require_once __DIR__ . '/Core/ResponseTest.php';
require_once __DIR__ . '/Core/ValidatorTest.php';
require_once __DIR__ . '/Core/SessionTest.php';
require_once __DIR__ . '/Modules/DatabaseTest.php';
require_once __DIR__ . '/Modules/AuthTest.php';
require_once __DIR__ . '/Modules/EmailTest.php';
require_once __DIR__ . '/Modules/StorageTest.php';
require_once __DIR__ . '/Modules/AITest.php';
require_once __DIR__ . '/Helpers/HelpersTest.php';

// Print summary and exit
exit(TestRunner::summary());

