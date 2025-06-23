<?php
session_start();

// Hapus semua data session admin
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_full_name']);

// Redirect ke halaman login user
header('Location: ../login.php');
exit; 