<?php
include 'koneksi.php';
session_start();

// Cek login admin
if (!isset($_SESSION['id_pengguna']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login_admin.php");
    exit();
}

// Ambil id_prestasi dari parameter GET
$id_prestasi = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_prestasi <= 0) {
    $_SESSION['error_msg'] = "ID prestasi tidak valid.";
    header("Location: admin_prestasi.php");
    exit();
}

// Ambil nama file sertifikat (jika ada)
$stmt = $conn->prepare("SELECT sertifikat FROM prestasi WHERE id_prestasi = ?");
$stmt->bind_param("i", $id_prestasi);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    $_SESSION['error_msg'] = "Data prestasi tidak ditemukan.";
    header("Location: admin_prestasi.php");
    exit();
}
$row = $result->fetch_assoc();
$sertifikat = $row['sertifikat'];

// Hapus data prestasi
$stmt = $conn->prepare("DELETE FROM prestasi WHERE id_prestasi = ?");
$stmt->bind_param("i", $id_prestasi);
if ($stmt->execute()) {
    // Hapus file sertifikat jika ada
    if (!empty($sertifikat) && file_exists("uploads/sertifikat/" . $sertifikat)) {
        unlink("uploads/sertifikat/" . $sertifikat);
    }
    $_SESSION['success_msg'] = "Data prestasi berhasil dihapus.";
     header("Location: admin_prestasi.php");
    exit();
} else {
    $_SESSION['error_msg'] = "Gagal menghapus data prestasi.";
     header("Location: admin_prestasi.php");
    exit();
}
