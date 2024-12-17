<?php

if (!function_exists('view')) {
    /**
     * Render view dengan data
     * 
     * @param string $view Path ke file view (tanpa ekstensi .php)
     * @param array $data Data yang akan dipass ke view
     * @return string
     */
    function view($view, $data = [])
    {
        // Extract data ke variabel
        extract($data);
        
        // Convert view path
        $view = str_replace('.', '/', $view);
        
        // Path lengkap ke file view
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        
        // Cek apakah file view ada
        if (!file_exists($viewPath)) {
            throw new Exception("View tidak ditemukan: {$viewPath}");
        }
        
        // Start output buffering
        ob_start();
        
        // Include view
        include $viewPath;
        
        // Return the output buffer content
        return ob_get_clean();
    }
}

if (!function_exists('asset')) {
    /**
     * Generate URL untuk asset
     * 
     * @param string $path Path ke asset
     * @return string
     */
    function asset($path)
    {
        return '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generate URL lengkap
     * 
     * @param string $path Path relatif
     * @return string
     */
    function url($path = '')
    {
        $base = rtrim(getenv('APP_URL') ?: 'http://localhost:8000', '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect ke URL lain
     * 
     * @param string $path Path tujuan
     * @return void
     */
    function redirect($path)
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Ambil nilai input lama dari session
     * 
     * @param string $key Key dari input
     * @param mixed $default Nilai default jika tidak ada
     * @return mixed
     */
    function old($key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('session')) {
    /**
     * Ambil atau set nilai session
     * 
     * @param string $key Key dari session
     * @param mixed $default Nilai default jika tidak ada
     * @return mixed
     */
    function session($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
} 