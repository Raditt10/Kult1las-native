<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'admin') {
    header("Location: kelola_admin.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($id == $_SESSION['id_pengguna']) {
        echo "<script>alert('Anda tidak bisa menghapus akun Anda sendiri.'); window.location.href='kelola_admin.php';</script>";
        exit();
    }

    $cek = $conn->prepare("SELECT id_pengguna FROM pengguna WHERE id_pengguna = ? AND role IN ('admin','pembina','pelatih')");
    $cek->bind_param("i", $id);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $delete = $conn->prepare("DELETE FROM pengguna WHERE id_pengguna = ?");
        $delete->bind_param("i", $id);

        if ($delete->execute()) {
            echo "<script>alert('Akun berhasil dihapus.'); window.location.href='kelola_admin.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus akun.'); window.location.href='kelola_admin.php';</script>";
        }
    } else {
        echo "<script>alert('Data tidak ditemukan atau role tidak valid.'); window.location.href='kelola_admin.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan.'); window.location.href='kelola_admin.php';</script>";
}
?>
