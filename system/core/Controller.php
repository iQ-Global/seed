<?php
/**
 * Controller - Base controller class
 */

namespace Seed\Core;

class Controller {
    protected $request;
    protected $response;
    
    public function __construct() {
        // Controllers can access request and response helpers
        $this->request = new Request();
        $this->response = new Response();
    }
}

