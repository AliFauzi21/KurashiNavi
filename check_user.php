<?php
session_start();
require_once 'models/db.php';

echo "<h2>Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['username'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch();
        
        echo "<h2>Database Data:</h2>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "User belum login";
}
?> 