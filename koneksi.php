<?php
// Aktifkan error reporting saat debugging (matikan jika sudah di production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Konfigurasi database
$host = "localhost";
$user = "root";      // ganti sesuai hosting
$pass = "";          // ganti sesuai hosting
$db   = "db_eskul";  // ganti sesuai nama database

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8 (supaya aman untuk karakter unik)
$conn->set_charset("utf8mb4");
?>
