<?php
session_start();

// Simpan role sebelum session dihancurkan
$role = $_SESSION['role'] ?? null;

// Hapus session
session_unset();
session_destroy();

// Arahkan sesuai role
if ($role === 'admin') {
    header("Location: login_admin.php");
} elseif ($role === 'pembina') {
    header("Location: login_pembina.php");
} elseif ($role === 'pelatih') {
    header("Location: login_pelatih.php");
} else {
    // Jika role tidak diketahui, arahkan ke halaman utama atau login umum
    header("Location: index_admin.php");
}
exit;
?>
