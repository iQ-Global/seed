<?php
/**
 * HTTP client helper functions
 */

use Seed\Modules\Http\HttpClient;

// Create new HTTP client instance
function http($baseUrl = '') {
    return new HttpClient($baseUrl);
}

