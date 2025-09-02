<?php
session_start();

// Fungsi untuk memvalidasi session admin
function checkAdminAuth() {
    // Cek apakah user sudah login
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php?error=not_logged_in');
        exit;
    }
    
    // Cek apakah user memiliki role admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../login.php?error=not_admin');
        exit;
    }
    
    // Cek apakah session masih valid (optional: tambahkan timeout)
    $session_timeout = 3600; // 1 jam
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
        session_unset();
        session_destroy();
        header('Location: ../login.php?error=session_expired');
        exit;
    }
    
    // Update last activity
    $_SESSION['last_activity'] = time();
    
    return true;
}

// Fungsi untuk memvalidasi CSRF token
function validateCSRFToken() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header('Location: ../login.php?error=invalid_token');
            exit;
        }
    }
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fungsi untuk log aktivitas admin
function logAdminActivity($action, $details = '') {
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        $log_entry = date('Y-m-d H:i:s') . " - User: " . $_SESSION['username'] . 
                    " (ID: " . $_SESSION['user_id'] . ") - Action: " . $action;
        if ($details) {
            $log_entry .= " - Details: " . $details;
        }
        error_log("ADMIN_ACTIVITY: " . $log_entry);
    }
}

// Fungsi untuk memvalidasi IP address (optional)
function validateIP() {
    $allowed_ips = ['127.0.0.1', '::1']; // localhost
    $client_ip = $_SERVER['REMOTE_ADDR'];
    
    // Jika bukan localhost, redirect ke login
    if (!in_array($client_ip, $allowed_ips)) {
        header('Location: ../login.php?error=invalid_ip');
        exit;
    }
}

// Fungsi untuk memvalidasi user agent (optional)
function validateUserAgent() {
    if (!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT'])) {
        header('Location: ../login.php?error=invalid_user_agent');
        exit;
    }
}

// Fungsi utama untuk mengamankan halaman admin
function secureAdminPage() {
    // Validasi IP (uncomment jika ingin membatasi akses hanya dari localhost)
    // validateIP();
    
    // Validasi user agent
    validateUserAgent();
    
    // Validasi session admin
    checkAdminAuth();
    
    // Validasi CSRF token untuk POST requests
    validateCSRFToken();
    
    // Log akses halaman
    $current_page = basename($_SERVER['PHP_SELF']);
    logAdminActivity("Accessed page: " . $current_page);
    
    return true;
}

// Auto-execute security check jika file ini di-include
if (basename($_SERVER['PHP_SELF']) !== 'auth_check.php') {
    secureAdminPage();
}
?> 