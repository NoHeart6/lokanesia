<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus semua data session
    session_unset();
    
    // Hapus cookie session jika ada
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Hancurkan session
    session_destroy();
    
    // Redirect ke halaman home dengan path yang benar
    header('Location: /');
    exit();
} else {
    // Jika bukan POST, redirect ke dashboard
    header('Location: /dashboard');
    exit();
}
?> 