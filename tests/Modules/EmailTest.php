<?php
/**
 * Email Tests
 */

TestRunner::suite('EMAIL');

use Seed\Modules\Email\Email;

TestRunner::test("Email class exists", function() {
    return class_exists('Seed\Modules\Email\Email');
});

TestRunner::test("PHPMailer is available", function() {
    return class_exists('PHPMailer\PHPMailer\PHPMailer');
});

TestRunner::test("Email can be instantiated", function() {
    $email = new Email();
    return $email instanceof Email;
});

TestRunner::test("Email has fluent API", function() {
    $email = new Email();
    $result = $email->to('test@example.com')->subject('Test');
    return $result instanceof Email;
});

TestRunner::test("Email has to() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'to');
});

TestRunner::test("Email has from() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'from');
});

TestRunner::test("Email has subject() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'subject');
});

TestRunner::test("Email has body() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'body');
});

TestRunner::test("Email has html() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'html');
});

TestRunner::test("Email has attach() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'attach');
});

// Enhanced Email (v1.5.0)
TestRunner::test("Email has cc() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'cc');
});

TestRunner::test("Email has bcc() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'bcc');
});

TestRunner::test("Email has replyTo() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'replyTo');
});

TestRunner::test("Email has altBody() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'altBody');
});

TestRunner::test("Email has getError() method", function() {
    return method_exists('Seed\Modules\Email\Email', 'getError');
});

