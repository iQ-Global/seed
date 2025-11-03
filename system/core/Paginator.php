<?php
/**
 * Paginator - Pagination support
 */

namespace Seed\Core;

class Paginator {
    private $items;
    private $total;
    private $perPage;
    private $currentPage;
    private $lastPage;
    private $path;
    
    public function __construct($items, $total, $perPage, $currentPage, $path = null) {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = max(1, $currentPage);
        $this->lastPage = max(1, (int) ceil($total / $perPage));
        $this->path = $path ?? current_url();
    }
    
    // Get items for current page
    public function items() {
        return $this->items;
    }
    
    // Get total items
    public function total() {
        return $this->total;
    }
    
    // Get per page count
    public function perPage() {
        return $this->perPage;
    }
    
    // Get current page
    public function currentPage() {
        return $this->currentPage;
    }
    
    // Get last page number
    public function lastPage() {
        return $this->lastPage;
    }
    
    // Check if on first page
    public function onFirstPage() {
        return $this->currentPage <= 1;
    }
    
    // Check if on last page
    public function onLastPage() {
        return $this->currentPage >= $this->lastPage;
    }
    
    // Check if there are more pages
    public function hasMorePages() {
        return $this->currentPage < $this->lastPage;
    }
    
    // Get URL for a page number
    public function url($page) {
        $page = max(1, $page);
        $query = $_GET;
        $query['page'] = $page;
        
        $path = parse_url($this->path, PHP_URL_PATH);
        return $path . '?' . http_build_query($query);
    }
    
    // Get previous page URL
    public function previousPageUrl() {
        if ($this->currentPage > 1) {
            return $this->url($this->currentPage - 1);
        }
        return null;
    }
    
    // Get next page URL
    public function nextPageUrl() {
        if ($this->currentPage < $this->lastPage) {
            return $this->url($this->currentPage + 1);
        }
        return null;
    }
    
    // Get first item number on current page
    public function firstItem() {
        return ($this->currentPage - 1) * $this->perPage + 1;
    }
    
    // Get last item number on current page
    public function lastItem() {
        return min($this->firstItem() + count($this->items) - 1, $this->total);
    }
    
    // Generate pagination links HTML
    public function links($onEachSide = 3) {
        if ($this->lastPage <= 1) {
            return '';
        }
        
        $html = '<nav class="pagination">';
        $html .= '<ul class="pagination-list">';
        
        // Previous link
        if ($this->onFirstPage()) {
            $html .= '<li class="pagination-item disabled"><span>« Previous</span></li>';
        } else {
            $html .= '<li class="pagination-item"><a href="' . $this->previousPageUrl() . '">« Previous</a></li>';
        }
        
        // Page numbers
        $start = max(1, $this->currentPage - $onEachSide);
        $end = min($this->lastPage, $this->currentPage + $onEachSide);
        
        // First page
        if ($start > 1) {
            $html .= '<li class="pagination-item"><a href="' . $this->url(1) . '">1</a></li>';
            if ($start > 2) {
                $html .= '<li class="pagination-item disabled"><span>...</span></li>';
            }
        }
        
        // Page numbers
        for ($page = $start; $page <= $end; $page++) {
            if ($page == $this->currentPage) {
                $html .= '<li class="pagination-item active"><span>' . $page . '</span></li>';
            } else {
                $html .= '<li class="pagination-item"><a href="' . $this->url($page) . '">' . $page . '</a></li>';
            }
        }
        
        // Last page
        if ($end < $this->lastPage) {
            if ($end < $this->lastPage - 1) {
                $html .= '<li class="pagination-item disabled"><span>...</span></li>';
            }
            $html .= '<li class="pagination-item"><a href="' . $this->url($this->lastPage) . '">' . $this->lastPage . '</a></li>';
        }
        
        // Next link
        if ($this->hasMorePages()) {
            $html .= '<li class="pagination-item"><a href="' . $this->nextPageUrl() . '">Next »</a></li>';
        } else {
            $html .= '<li class="pagination-item disabled"><span>Next »</span></li>';
        }
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
    
    // Simple pagination info text
    public function info() {
        return "Showing {$this->firstItem()} to {$this->lastItem()} of {$this->total} results";
    }
}

