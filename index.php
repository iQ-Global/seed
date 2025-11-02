<?php
/**
 * Seed Framework - Entry Point
 */

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the framework
$app = new Seed\Core\Seed();

// Run the application
$app->run();

