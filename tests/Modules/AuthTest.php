<?php
/**
 * Authentication Tests
 */

TestRunner::suite('AUTHENTICATION');

use Seed\Modules\Auth\Auth;

TestRunner::test("Auth class exists", function() {
    return class_exists('Seed\Modules\Auth\Auth');
});

TestRunner::test("Auth: hashPassword creates hash", function() {
    $hash = Auth::hashPassword('password123');
    return strlen($hash) > 50;
});

TestRunner::test("Auth: verifyPassword validates correct password", function() {
    $hash = Auth::hashPassword('password123');
    return Auth::verifyPassword('password123', $hash);
});

TestRunner::test("Auth: verifyPassword rejects incorrect password", function() {
    $hash = Auth::hashPassword('password123');
    return !Auth::verifyPassword('wrongpassword', $hash);
});

TestRunner::test("Auth has login() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'login');
});

TestRunner::test("Auth has logout() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'logout');
});

TestRunner::test("Auth has check() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'check');
});

// Enhanced Auth (v1.5.0)
TestRunner::test("Auth has sendPasswordReset() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'sendPasswordReset');
});

TestRunner::test("Auth has resetPassword() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'resetPassword');
});

TestRunner::test("Auth has sendVerificationEmail() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'sendVerificationEmail');
});

TestRunner::test("Auth has verifyEmail() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'verifyEmail');
});

TestRunner::test("Auth has isLocked() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'isLocked');
});

TestRunner::test("Auth has unlock() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'unlock');
});

TestRunner::test("Auth has recordLoginAttempt() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'recordLoginAttempt');
});

TestRunner::test("Auth has checkRememberToken() method", function() {
    return method_exists('Seed\Modules\Auth\Auth', 'checkRememberToken');
});

