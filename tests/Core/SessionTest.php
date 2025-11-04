<?php
/**
 * Session Tests
 */

TestRunner::suite('SESSION');

use Seed\Core\Session;

TestRunner::test("Session class exists", function() {
    return class_exists('Seed\Core\Session');
});

TestRunner::test("Session has set() method", function() {
    return method_exists('Seed\Core\Session', 'set');
});

TestRunner::test("Session has get() method", function() {
    return method_exists('Seed\Core\Session', 'get');
});

TestRunner::test("Session has has() method", function() {
    return method_exists('Seed\Core\Session', 'has');
});

TestRunner::test("Session has remove() method", function() {
    return method_exists('Seed\Core\Session', 'remove');
});

TestRunner::test("Session has flash() method", function() {
    return method_exists('Seed\Core\Session', 'flash');
});

TestRunner::test("Session has getFlash() method", function() {
    return method_exists('Seed\Core\Session', 'getFlash');
});

TestRunner::test("DatabaseSessionDriver exists", function() {
    return class_exists('Seed\Modules\Session\DatabaseSessionDriver');
});

