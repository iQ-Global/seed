<?php
/**
 * Request Tests
 */

TestRunner::suite('REQUEST');

use Seed\Core\Request;

TestRunner::test("Request class exists", function() {
    return class_exists('Seed\Core\Request');
});

TestRunner::test("Request can be instantiated", function() {
    $request = new Request();
    return $request instanceof Request;
});

TestRunner::test("Request has method() function", function() {
    $request = new Request();
    return method_exists($request, 'method');
});

TestRunner::test("Request has uri() function", function() {
    $request = new Request();
    return method_exists($request, 'uri');
});

TestRunner::test("Request has get() function", function() {
    $request = new Request();
    return method_exists($request, 'get');
});

TestRunner::test("Request has post() function", function() {
    $request = new Request();
    return method_exists($request, 'post');
});

TestRunner::test("Request has file() function", function() {
    $request = new Request();
    return method_exists($request, 'file');
});

TestRunner::test("Request has header() function", function() {
    $request = new Request();
    return method_exists($request, 'header');
});

