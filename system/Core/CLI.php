<?php
/**
 * CLI - Command line interface support
 */

namespace Seed\Core;

class CLI {
    private $commands = [];
    
    // Register a command
    public function register($name, $callback, $description = '') {
        $this->commands[$name] = [
            'callback' => $callback,
            'description' => $description
        ];
    }
    
    // Run CLI
    public function run($argv) {
        // Check if running from CLI
        if (PHP_SAPI !== 'cli') {
            die('This script can only be run from the command line.');
        }
        
        // Get command name (first argument after script name)
        $commandName = $argv[1] ?? 'help';
        
        // Get command arguments
        $args = array_slice($argv, 2);
        
        // Handle help command
        if ($commandName === 'help' || $commandName === '--help' || $commandName === '-h') {
            $this->showHelp();
            return;
        }
        
        // Check if command exists
        if (!isset($this->commands[$commandName])) {
            $this->error("Command '{$commandName}' not found.");
            $this->info("Run 'php seed help' to see available commands.");
            return;
        }
        
        // Execute command
        $command = $this->commands[$commandName];
        call_user_func($command['callback'], $args);
    }
    
    // Show help
    private function showHelp() {
        $this->line('');
        $this->success('Seed Framework CLI');
        $this->line('');
        $this->info('Usage:');
        $this->line('  php seed [command] [arguments]');
        $this->line('');
        $this->info('Available commands:');
        $this->line('');
        
        foreach ($this->commands as $name => $command) {
            $desc = $command['description'] ?: 'No description';
            $this->line("  {$name}");
            $this->line("    {$desc}");
            $this->line('');
        }
    }
    
    // Output success message
    public function success($message) {
        echo "\033[32m" . $message . "\033[0m\n";
    }
    
    // Output error message
    public function error($message) {
        echo "\033[31m" . $message . "\033[0m\n";
    }
    
    // Output info message
    public function info($message) {
        echo "\033[36m" . $message . "\033[0m\n";
    }
    
    // Output warning message
    public function warning($message) {
        echo "\033[33m" . $message . "\033[0m\n";
    }
    
    // Output line
    public function line($message = '') {
        echo $message . "\n";
    }
    
    // Prompt for input
    public function ask($question) {
        echo $question . ' ';
        return trim(fgets(STDIN));
    }
    
    // Confirm (yes/no)
    public function confirm($question) {
        $response = strtolower($this->ask($question . ' (y/n)'));
        return in_array($response, ['y', 'yes']);
    }
}

