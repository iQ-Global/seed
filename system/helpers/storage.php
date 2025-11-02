<?php
/**
 * Storage helper functions
 */

use Seed\Modules\Storage\Storage;

// Get storage instance
function storage($disk = 'private') {
    static $instances = [];
    
    if (!isset($instances[$disk])) {
        $instances[$disk] = new Storage($disk);
    }
    
    return $instances[$disk];
}

// Store file in public storage and get URL
function store_public($path, $contents) {
    $storage = storage('public');
    $storage->put($path, $contents);
    return $storage->url($path);
}

// Get uploaded file path
function upload_path($filename) {
    return storage('uploads')->path($filename);
}

