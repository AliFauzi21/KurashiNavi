<?php
try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    // Coba koneksi ke MySQL tanpa memilih database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "MySQL connection successful.<br>";
    
    // Cek apakah database kurashinavi ada
    $stmt = $pdo->query("SHOW DATABASES LIKE 'kurashinavi'");
    if ($stmt->rowCount() > 0) {
        echo "Database 'kurashinavi' exists.<br>";
        
        // Pilih database kurashinavi
        $pdo->exec("USE kurashinavi");
        
        // Cek tabel users
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($stmt->rowCount() > 0) {
            echo "Table 'users' exists.<br>";
            
            // Tampilkan struktur tabel
            $stmt = $pdo->query("DESCRIBE users");
            echo "<br>Table structure:<br>";
            while ($row = $stmt->fetch()) {
                echo $row['Field'] . " - " . $row['Type'] . "<br>";
            }
        } else {
            echo "Table 'users' does not exist.<br>";
        }
    } else {
        echo "Database 'kurashinavi' does not exist.<br>";
        
        // Buat database
        $pdo->exec("CREATE DATABASE kurashinavi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Database 'kurashinavi' created.<br>";
        
        // Pilih database
        $pdo->exec("USE kurashinavi");
        
        // Buat tabel users
        $pdo->exec("CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        echo "Table 'users' created.<br>";
    }
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?> 