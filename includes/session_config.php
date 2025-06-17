<?php
// Pastikan tidak ada session yang aktif
if (session_status() === PHP_SESSION_NONE) {
    // Konfigurasi Session
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    
    // Mulai session
    session_start();
}
?> 