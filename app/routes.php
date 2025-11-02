<?php
/**
 * Application Routes
 */

// Home page
$router->get('/', 'homeController/index');

// User profile with route parameter
$router->get('/user/{id}', 'homeController/user');

// API endpoint example
$router->get('/api', 'homeController/api');
