<?php
/**
 * Database Tests
 */

TestRunner::suite('DATABASE');

use Seed\Modules\Database\Database;
use Seed\Modules\Database\MySQLDatabase;
use Seed\Modules\Database\PostgreSQLDatabase;
use Seed\Modules\Database\MongoDBDatabase;

TestRunner::test("Database classes exist", function() {
    return class_exists('Seed\Modules\Database\Database') &&
           class_exists('Seed\Modules\Database\MySQLDatabase') &&
           class_exists('Seed\Modules\Database\PostgreSQLDatabase');
});

TestRunner::test("MySQL driver has query() method", function() {
    return method_exists('Seed\Modules\Database\MySQLDatabase', 'query');
});

TestRunner::test("MySQL driver has insert() method", function() {
    return method_exists('Seed\Modules\Database\MySQLDatabase', 'insert');
});

TestRunner::test("MySQL driver has update() method", function() {
    return method_exists('Seed\Modules\Database\MySQLDatabase', 'update');
});

TestRunner::test("MySQL driver has delete() method", function() {
    return method_exists('Seed\Modules\Database\MySQLDatabase', 'delete');
});

TestRunner::test("MySQL driver has transaction methods", function() {
    return method_exists('Seed\Modules\Database\MySQLDatabase', 'beginTransaction') &&
           method_exists('Seed\Modules\Database\MySQLDatabase', 'commit') &&
           method_exists('Seed\Modules\Database\MySQLDatabase', 'rollback');
});

// MongoDB Tests
TestRunner::test("MongoDB driver exists", function() {
    return class_exists('Seed\Modules\Database\MongoDBDatabase');
});

TestRunner::test("MongoDB driver can be instantiated", function() {
    $mongo = new MongoDBDatabase(['host' => 'localhost', 'port' => '27017', 'database' => 'test']);
    return $mongo instanceof MongoDBDatabase;
});

TestRunner::test("MongoDB has CRUD methods", function() {
    return method_exists('Seed\Modules\Database\MongoDBDatabase', 'query') &&
           method_exists('Seed\Modules\Database\MongoDBDatabase', 'insert') &&
           method_exists('Seed\Modules\Database\MongoDBDatabase', 'update') &&
           method_exists('Seed\Modules\Database\MongoDBDatabase', 'delete');
});

TestRunner::test("MongoDB has aggregate() method", function() {
    return method_exists('Seed\Modules\Database\MongoDBDatabase', 'aggregate');
});

TestRunner::test("MongoDB has transaction methods", function() {
    return method_exists('Seed\Modules\Database\MongoDBDatabase', 'beginTransaction') &&
           method_exists('Seed\Modules\Database\MongoDBDatabase', 'commit') &&
           method_exists('Seed\Modules\Database\MongoDBDatabase', 'rollback');
});

