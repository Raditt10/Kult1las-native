<?php
include 'koneksi.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo '
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }
        .akses-ditolak {
            display: inline-block;
            background-color: #ff4d4d;
            color: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-size: 1.5em;
        }
    </style>
    <div class="akses-ditolak">🚫 Akses Ditolak. Hanya admin yang dapat menghapus data ini.</div>';
    exit();
}
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    $query = "DELETE FROM presensi WHERE id_presensi = '$id'";
    if ($conn->query($query)) {
       $_SESSION['success_msg'] = "Data presensi berhasil dihapus.";
        header("Location: admin_presensi.php");
        exit;
    } else {
        $_SESSION['error_msg'] = "Gagal menghapus data: " . $conn->error;
        header("Location: admin_presensi.php");
        exit;
    }
} else {
   $_SESSION['error_msg'] = "ID tidak ditemukan.";
}
?>
