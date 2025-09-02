<?php
require_once 'auth_check.php';

// Jika sudah login sebagai admin, redirect ke dashboard
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    header('Location: dashboard.php');
    exit;
} else {
    // Jika tidak login atau bukan admin, redirect ke login
    header('Location: ../login.php');
    exit;
}
?> 