<?php
/**
 * Home Controller - Example controller
 */

namespace App\Controllers;

use Seed\Core\Controller;

class homeController extends Controller {
    // Show homepage
    public function index() {
        view('home', [
            'title' => 'Welcome to Seed',
            'message' => 'A minimal PHP framework that helps you grow without taking over.'
        ]);
    }
    
    // Example: Route parameters
    public function user($id) {
        view('user', [
            'title' => 'User Profile',
            'userId' => $id
        ]);
    }
    
    // Example: JSON API response
    public function api() {
        json([
            'status' => 'success',
            'message' => 'Seed Framework API',
            'version' => '1.0.0',
            'data' => [
                'framework' => 'Seed',
                'php_version' => PHP_VERSION
            ]
        ]);
    }
}

