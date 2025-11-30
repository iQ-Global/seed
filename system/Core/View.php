<?php
/**
 * View - View rendering with fallback support
 */

namespace Seed\Core;

class View {
    // Render view with data
    public static function render($view, $data = []) {
        // Dispatch view rendering event (can modify data)
        $eventData = Event::dispatch('view.rendering', [
            'view' => $view,
            'data' => $data
        ]);
        
        // If event listener returns data, use it
        if (!empty($eventData) && is_array($eventData[0])) {
            $data = array_merge($data, $eventData[0]);
        }
        
        // Extract data to variables
        extract($data);
        
        // Determine view path (app views override system views)
        $viewFile = self::findView($view);
        
        if (!$viewFile) {
            throw new \Exception("View not found: {$view}");
        }
        
        // Start output buffering
        ob_start();
        
        // Include view file
        include $viewFile;
        
        // Get contents and clean buffer
        $content = ob_get_clean();
        
        // Dispatch view rendered event
        Event::dispatch('view.rendered', [
            'view' => $view,
            'content' => $content
        ]);
        
        return $content;
    }
    
    // Find view file with fallback
    private static function findView($view) {
        // Normalize view path
        $view = str_replace('.', '/', $view);
        $view = trim($view, '/');
        
        // Try app views first
        $appView = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($appView)) {
            return $appView;
        }
        
        // Fall back to system views
        $systemView = SYSTEM_PATH . '/views/' . $view . '.php';
        if (file_exists($systemView)) {
            return $systemView;
        }
        
        return false;
    }
}

