<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $host = 'localhost';
    $dbname = 'kurashinavi';
    $username = 'root';
    $password = '';
    
    echo "Mencoba koneksi ke database...<br>";
    
    // Coba koneksi ke MySQL tanpa memilih database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Koneksi ke MySQL berhasil<br>";
    
    // Cek database
    $stmt = $pdo->query("SHOW DATABASES LIKE 'kurashinavi'");
    if ($stmt->rowCount() > 0) {
        echo "Database 'kurashinavi' ditemukan<br>";
        
        // Pilih database
        $pdo->exec("USE kurashinavi");
        echo "Database 'kurashinavi' dipilih<br>";
        
        // Cek tabel
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($stmt->rowCount() > 0) {
            echo "Tabel 'users' ditemukan<br>";
            
            // Cek struktur tabel
            $stmt = $pdo->query("DESCRIBE users");
            echo "<br>Struktur tabel users:<br>";
            while ($row = $stmt->fetch()) {
                echo $row['Field'] . " - " . $row['Type'] . "<br>";
            }
            
            // Coba insert test data
            echo "<br>Mencoba insert data test...<br>";
            $test_username = 'test_' . time();
            $test_email = $test_username . '@test.com';
            $test_password = password_hash('test123', PASSWORD_DEFAULT);
            $test_fullname = 'Test User';
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$test_username, $test_email, $test_password, $test_fullname])) {
                echo "Insert test data berhasil<br>";
                
                // Verifikasi data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$test_username]);
                $user = $stmt->fetch();
                echo "<br>Data yang diinsert:<br>";
                echo "Username: " . $user['username'] . "<br>";
                echo "Email: " . $user['email'] . "<br>";
                echo "Full Name: " . $user['full_name'] . "<br>";
                echo "Status: " . $user['status'] . "<br>";
                echo "Created: " . $user['created_at'] . "<br>";
                
                // Hapus test data
                $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
                $stmt->execute([$test_username]);
                echo "<br>Test data dihapus<br>";
            } else {
                echo "Gagal insert test data<br>";
                print_r($stmt->errorInfo());
            }
        } else {
            echo "Tabel 'users' tidak ditemukan<br>";
            
            // Buat tabel
            echo "Membuat tabel users...<br>";
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
            echo "Tabel users berhasil dibuat<br>";
        }
    } else {
        echo "Database 'kurashinavi' tidak ditemukan<br>";
        
        // Buat database
        echo "Membuat database kurashinavi...<br>";
        $pdo->exec("CREATE DATABASE kurashinavi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Database kurashinavi berhasil dibuat<br>";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Error code: " . $e->getCode() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?> 