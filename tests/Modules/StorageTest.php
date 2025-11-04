<?php
/**
 * Storage Tests
 */

TestRunner::suite('STORAGE');

use Seed\Modules\Storage\Storage;

TestRunner::test("Storage class exists", function() {
    return class_exists('Seed\Modules\Storage\Storage');
});

TestRunner::test("Storage has put() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'put');
});

TestRunner::test("Storage has get() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'get');
});

TestRunner::test("Storage has delete() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'delete');
});

TestRunner::test("Storage has exists() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'exists');
});

TestRunner::test("Storage has url() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'url');
});

TestRunner::test("Storage has makeDirectory() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'makeDirectory');
});

TestRunner::test("Storage has deleteDirectory() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'deleteDirectory');
});

TestRunner::test("Storage has files() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'files');
});

TestRunner::test("Storage has disk() method", function() {
    return method_exists('Seed\Modules\Storage\Storage', 'disk');
});

