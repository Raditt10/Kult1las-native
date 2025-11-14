<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_pengguna']) || !isset($_SESSION['role'])) {
    echo "Akses ditolak.";
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];
$role = $_SESSION['role'];

if (!isset($_GET['id'])) {
    $_SESSION['error_msg'] = "ID tidak ditemukan.";
    header("Location: admin_eskul.php");
    exit();
}

$id_eskul = intval($_GET['id']);

// Cek role dan hak akses
if ($role === 'admin') {
    $query = "DELETE FROM eskul WHERE id_eskul = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_eskul);

    // Eksekusi query
    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Data ekstrakurikuler berhasil dihapus.";
    } else {
        $_SESSION['error_msg'] = "Gagal menghapus data: " . $conn->error;
    }
    header("Location: admin_eskul.php");
    exit();

} elseif ($role === 'pembina') {
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

} else {
    echo "Akses ditolak.";
    exit();
}
?>
