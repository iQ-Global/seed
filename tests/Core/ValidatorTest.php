<?php
/**
 * Validator Tests
 */

TestRunner::suite('VALIDATOR');

use Seed\Core\Validator;

TestRunner::test("Validator class exists", function() {
    return class_exists('Seed\Core\Validator');
});

// Basic Rules
TestRunner::test("Validation: required rule", function() {
    $v = new Validator(['name' => 'John'], ['name' => 'required']);
    return $v->validate();
});

TestRunner::test("Validation: email rule", function() {
    $v = new Validator(['email' => 'test@example.com'], ['email' => 'email']);
    return $v->validate();
});

TestRunner::test("Validation: numeric rule", function() {
    $v = new Validator(['age' => 25], ['age' => 'numeric']);
    return $v->validate();
});

TestRunner::test("Validation: min rule", function() {
    $v = new Validator(['password' => 'secret123'], ['password' => 'min:8']);
    return $v->validate();
});

TestRunner::test("Validation: max rule", function() {
    $v = new Validator(['name' => 'John'], ['name' => 'max:50']);
    return $v->validate();
});

// v1.5.0 Rules
TestRunner::test("Validation: confirmed rule", function() {
    $v = new Validator([
        'password' => 'secret123',
        'password_confirmation' => 'secret123'
    ], ['password' => 'confirmed']);
    return $v->validate();
});

TestRunner::test("Validation: matches rule", function() {
    $v = new Validator([
        'password' => 'secret',
        'password_verify' => 'secret'
    ], ['password_verify' => 'matches:password']);
    return $v->validate();
});

TestRunner::test("Validation: different rule", function() {
    $v = new Validator([
        'new_password' => 'new',
        'old_password' => 'old'
    ], ['new_password' => 'different:old_password']);
    return $v->validate();
});

TestRunner::test("Validation: date rule", function() {
    $v = new Validator(['birthdate' => '1990-01-15'], ['birthdate' => 'date']);
    return $v->validate();
});

TestRunner::test("Validation: between rule", function() {
    $v = new Validator(['age' => 25], ['age' => 'between:18,65']);
    return $v->validate();
});

TestRunner::test("Validation: in rule", function() {
    $v = new Validator(['status' => 'active'], ['status' => 'in:active,pending,inactive']);
    return $v->validate();
});

TestRunner::test("Validation: not_in rule", function() {
    $v = new Validator(['username' => 'john'], ['username' => 'not_in:admin,root,system']);
    return $v->validate();
});

TestRunner::test("Validation: regex rule", function() {
    $v = new Validator(['phone' => '+12345678901'], ['phone' => 'regex:/^\+?[1-9]\d{1,14}$/']);
    return $v->validate();
});

TestRunner::test("Validation: integer rule", function() {
    $v = new Validator(['age' => 25], ['age' => 'integer']);
    return $v->validate();
});

TestRunner::test("Validation: boolean rule", function() {
    $v = new Validator(['terms' => true], ['terms' => 'boolean']);
    return $v->validate();
});

