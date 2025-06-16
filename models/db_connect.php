<?php
// Konfigurasi database
$host = 'localhost';
$username = 'root';  // Username default XAMPP
$password = '';      // Password default XAMPP kosong
$database = 'kurashinavi';

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set karakter encoding
$conn->set_charset("utf8mb4");
?> 