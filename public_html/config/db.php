<?php 
$host = 'localhost'; // Sesuaikan dengan pengaturan XAMPP Anda
$db   = 'utslab'; // Ganti dengan nama database Anda
$user = 'root'; // Ganti dengan username database Anda
$pass = ''; // Ganti dengan password database Anda

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Mengatur mode error untuk PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Menangkap dan menampilkan error jika koneksi gagal
    echo "Connection failed: " . $e->getMessage();
}
