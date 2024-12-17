<?php

namespace App\Core;

class View
{
    /**
     * Render view file dengan data
     */
    public function render($view, $data = [])
    {
        // Extract data ke variabel
        extract($data);

        // Start output buffering
        ob_start();

        // Include view file
        $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new \Exception("View file not found: $viewPath");
        }

        // Get contents and clean buffer
        $content = ob_get_clean();

        return $content;
    }
} 