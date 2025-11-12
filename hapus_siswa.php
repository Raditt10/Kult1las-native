<?php
include 'koneksi.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error_msg'] = "Akses ditolak.";
    exit();
}
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Cek apakah siswa memiliki relasi di tabel pendaftaran
    $cek = $conn->query("SELECT * FROM pendaftaran_eskul WHERE id_siswa = '$id'");

    if ($cek->num_rows > 0) {
        // Jika masih ada relasi, tampilkan pesan
        echo "<script>
            alert('Tidak dapat menghapus siswa karena masih memiliki data pendaftaran!');
            window.location.href = 'admin_siswa.php';
        </script>";
    } else {
        // Jika tidak ada relasi, lanjutkan hapus siswa
        $hapus = $conn->query("DELETE FROM siswa WHERE id_siswa = '$id'");
        if ($hapus) {
           $_SESSION['success_msg'] = "Akun siswa berhasil dihapus.";
        header("Location: admin_siswa.php");
        exit;
        } else {
              $_SESSION['error_msg'] = "Gagal menghapus Akun: " . $conn->error;
        }
    }
} else {
  $_SESSION['error_msg'] = "ID tidak ditemukan.";
}
?>
