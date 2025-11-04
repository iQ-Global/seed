<?php
/**
 * Response Tests
 */

TestRunner::suite('RESPONSE');

use Seed\Core\Response;

TestRunner::test("Response class exists", function() {
    return class_exists('Seed\Core\Response');
});

TestRunner::test("Response can be instantiated", function() {
    $response = new Response();
    return $response instanceof Response;
});

TestRunner::test("Response has json() method", function() {
    $response = new Response();
    return method_exists($response, 'json');
});

TestRunner::test("Response has redirect() method", function() {
    $response = new Response();
    return method_exists($response, 'redirect');
});

TestRunner::test("Response has status() method", function() {
    $response = new Response();
    return method_exists($response, 'status');
});

TestRunner::test("Response has download() method", function() {
    $response = new Response();
    return method_exists($response, 'download');
});

TestRunner::test("Response has file() method", function() {
    $response = new Response();
    return method_exists($response, 'file');
});

TestRunner::test("Response has stream() method", function() {
    $response = new Response();
    return method_exists($response, 'stream');
});

