<?php
/**
 * Application Routes
 * 
 * Simple routes work as always. For multi-domain support, see examples below.
 */

// =============================================================================
// SIMPLE ROUTES (work on all domains)
// =============================================================================

// Home page
$router->get('/', 'homeController/index');

// User profile with route parameter
$router->get('/user/{id}', 'homeController/user');

// API endpoint example
$router->get('/api', 'homeController/api');

// =============================================================================
// MULTI-DOMAIN ROUTING (uncomment to use)
// =============================================================================

// Set a global default route (used if no domain-specific default)
// $router->setDefault('homeController/index');

// Domain-specific routes
// $router->domain('example.com', function($router) {
//     $router->setDefault('exampleController/index');  // Default for this domain
//     $router->get('/about', 'exampleController/about');
//     $router->get('/contact', 'exampleController/contact');
// });

// $router->domain('example2.com', function($router) {
//     $router->setDefault('example2Controller/index');
//     $router->get('/about', 'example2Controller/about');  // Different handler!
// });

// Subdomain with parameter extraction
// $router->domain('{tenant}.app.example.com', function($router) {
//     $router->setDefault('dashboardController/index');
//     $router->get('/settings', 'dashboardController/settings');
//     // In controller: domain_param('tenant') returns the subdomain
// });

// Wildcard subdomain
// $router->domain('*.example.com', function($router) {
//     $router->get('/', 'subdomainController/index');
//     // In controller: domain_param('subdomain') returns the subdomain
// });
