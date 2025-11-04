<?php
/**
 * Helper Functions Tests
 */

TestRunner::suite('HELPER FUNCTIONS');

// Core Helpers
TestRunner::test("Helper: env() exists", function() {
    return function_exists('env');
});

TestRunner::test("Helper: view() exists", function() {
    return function_exists('view');
});

TestRunner::test("Helper: json() exists", function() {
    return function_exists('json');
});

TestRunner::test("Helper: redirect() exists", function() {
    return function_exists('redirect');
});

TestRunner::test("Helper: dd() exists", function() {
    return function_exists('dd');
});

TestRunner::test("Helper: response() exists", function() {
    return function_exists('response');
});

// Security Helpers
TestRunner::test("Helper: esc() exists", function() {
    return function_exists('esc');
});

TestRunner::test("Helper: csrf_token() exists", function() {
    return function_exists('csrf_token');
});

TestRunner::test("Helper: csrf_field() exists", function() {
    return function_exists('csrf_field');
});

// Database Helpers
TestRunner::test("Helper: db() exists", function() {
    return function_exists('db');
});

// Auth Helpers
TestRunner::test("Helper: auth() exists", function() {
    return function_exists('auth');
});

TestRunner::test("Helper: is_logged_in() exists", function() {
    return function_exists('is_logged_in');
});

TestRunner::test("Helper: user_id() exists", function() {
    return function_exists('user_id');
});

// Enhanced Auth Helpers (v1.5.0)
TestRunner::test("Helper: send_password_reset() exists", function() {
    return function_exists('send_password_reset');
});

TestRunner::test("Helper: reset_password() exists", function() {
    return function_exists('reset_password');
});

TestRunner::test("Helper: send_verification_email() exists", function() {
    return function_exists('send_verification_email');
});

TestRunner::test("Helper: verify_email() exists", function() {
    return function_exists('verify_email');
});

TestRunner::test("Helper: is_email_verified() exists", function() {
    return function_exists('is_email_verified');
});

TestRunner::test("Helper: is_account_locked() exists", function() {
    return function_exists('is_account_locked');
});

TestRunner::test("Helper: check_remember_token() exists", function() {
    return function_exists('check_remember_token');
});

// Validation Helpers
TestRunner::test("Helper: validate() exists", function() {
    return function_exists('validate');
});

TestRunner::test("Helper: validator() exists", function() {
    return function_exists('validator');
});

// Form Helpers
TestRunner::test("Helper: input_value() exists", function() {
    return function_exists('input_value');
});

TestRunner::test("Helper: form_error() exists", function() {
    return function_exists('form_error');
});

// Session Helpers
TestRunner::test("Helper: session() exists", function() {
    return function_exists('session');
});

TestRunner::test("Helper: flash() exists", function() {
    return function_exists('flash');
});

// Storage Helpers
TestRunner::test("Helper: storage() exists", function() {
    return function_exists('storage');
});

// Config Helpers
TestRunner::test("Helper: config() exists", function() {
    return function_exists('config');
});

// Event Helpers
TestRunner::test("Helper: event() exists", function() {
    return function_exists('event');
});

TestRunner::test("Helper: listen() exists", function() {
    return function_exists('listen');
});

// URL Helpers
TestRunner::test("Helper: url() exists", function() {
    return function_exists('url');
});

TestRunner::test("Helper: asset() exists", function() {
    return function_exists('asset');
});

// Array Helpers
TestRunner::test("Helper: array_get() exists", function() {
    return function_exists('array_get');
});

TestRunner::test("Helper: array_pluck() exists", function() {
    return function_exists('array_pluck');
});

// Pagination Helpers
TestRunner::test("Helper: paginate() exists", function() {
    return function_exists('paginate');
});

TestRunner::test("Helper: db_paginate() exists", function() {
    return function_exists('db_paginate');
});

// String Helpers
TestRunner::test("Helper: str_limit() exists", function() {
    return function_exists('str_limit');
});

TestRunner::test("Helper: str_slug() exists", function() {
    return function_exists('str_slug');
});

TestRunner::test("Helper: str_random() exists", function() {
    return function_exists('str_random');
});

// AI Helpers (v1.5.0)
TestRunner::test("Helper: ai() exists", function() {
    return function_exists('ai');
});

TestRunner::test("Helper: ai_chat() exists", function() {
    return function_exists('ai_chat');
});

TestRunner::test("Helper: ai_openai() exists", function() {
    return function_exists('ai_openai');
});

TestRunner::test("Helper: ai_claude() exists", function() {
    return function_exists('ai_claude');
});

// HTTP Client Helper
TestRunner::test("Helper: http() exists", function() {
    return function_exists('http');
});

// Email Helper
TestRunner::test("Helper: email() exists", function() {
    return function_exists('email');
});

// Logging Helpers
TestRunner::test("Helper: log_info() exists", function() {
    return function_exists('log_info');
});

TestRunner::test("Helper: log_error() exists", function() {
    return function_exists('log_error');
});

TestRunner::test("Helper: log_debug() exists", function() {
    return function_exists('log_debug');
});

