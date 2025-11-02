<?php
/**
 * Database helper functions
 */

use Seed\Modules\Database\DatabaseManager;

// Get database instance
function db($driver = null) {
    return DatabaseManager::getInstance($driver);
}

