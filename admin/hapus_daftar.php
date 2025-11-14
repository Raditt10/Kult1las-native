<?php
include 'koneksi.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak.";
    exit();
}
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    $query = "DELETE FROM pendaftaran_eskul WHERE id_pendaftaran = '$id'";
    if ($conn->query($query)) {
        $_SESSION['success_msg'] = "Data pendaftaran berhasil dihapus.";
        header("Location: admin_daftar.php");
        exit;
    } else {
        $_SESSION['error_msg'] = "Gagal menghapus data: " . $conn->error;
        header("Location: admin_daftar.php");
        exit;
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
