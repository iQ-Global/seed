<?php
/**
 * Router Tests
 */

TestRunner::suite('ROUTER');

use Seed\Core\Router;

TestRunner::test("Router class exists", function() {
    return class_exists('Seed\Core\Router');
});

TestRunner::test("Router can register GET route", function() {
    $router = new Router();
    $route = $router->get('/test', 'testController/index');
    return $route instanceof Seed\Core\Route;
});

TestRunner::test("Router can register POST route", function() {
    $router = new Router();
    $route = $router->post('/test', 'testController/store');
    return $route instanceof Seed\Core\Route;
});

TestRunner::test("Router can register PUT route", function() {
    $router = new Router();
    $route = $router->put('/test/{id}', 'testController/update');
    return $route instanceof Seed\Core\Route;
});

TestRunner::test("Router can register DELETE route", function() {
    $router = new Router();
    $route = $router->delete('/test/{id}', 'testController/destroy');
    return $route instanceof Seed\Core\Route;
});

TestRunner::test("Router can create route group", function() {
    $router = new Router();
    $router->group(['prefix' => 'api'], function($r) {
        $r->get('/users', 'userController/index');
    });
    return true;
});

TestRunner::test("Route can have middleware", function() {
    $router = new Router();
    $route = $router->get('/protected', 'testController/protected')
                    ->middleware('auth');
    return $route instanceof Seed\Core\Route;
});

