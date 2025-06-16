<?php
session_start();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (in_array($lang, ['ja', 'en', 'zh'])) {
        $_SESSION['lang'] = $lang;
    }
}

// Redirect kembali ke halaman sebelumnya
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $redirect);
exit();
?> 