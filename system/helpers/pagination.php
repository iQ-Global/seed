<?php
/**
 * Pagination helper functions
 */

use Seed\Core\Paginator;

// Paginate array or database results
function paginate($items, $perPage = 15, $page = null) {
    $page = $page ?? (int) ($_GET['page'] ?? 1);
    
    // Handle array pagination
    if (is_array($items)) {
        $total = count($items);
        $offset = ($page - 1) * $perPage;
        $pageItems = array_slice($items, $offset, $perPage);
        
        return new Paginator($pageItems, $total, $perPage, $page);
    }
    
    // Items are already paginated from database
    return new Paginator($items, count($items), $perPage, $page);
}

// Database pagination helper
function db_paginate($query, $params = [], $perPage = 15, $page = null) {
    $page = $page ?? (int) ($_GET['page'] ?? 1);
    $offset = ($page - 1) * $perPage;
    
    // Get total count
    $countQuery = preg_replace('/SELECT .+ FROM/i', 'SELECT COUNT(*) as count FROM', $query);
    $countResult = db()->queryOne($countQuery, $params);
    $total = $countResult->count ?? 0;
    
    // Get paginated results
    $paginatedQuery = $query . " LIMIT $perPage OFFSET $offset";
    $items = db()->query($paginatedQuery, $params);
    
    return new Paginator($items, $total, $perPage, $page);
}

