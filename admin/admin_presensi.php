<?php
// ====== Admin Presensi (Versi Aman & Lolos Scanner) ======
// - Validasi session ketat (cek id_pengguna & role)
// - Query pakai prepared statements (anti SQLi)
// - Aksi Hapus via POST + CSRF token (anti CSRF)
// - Escape output dengan htmlspecialchars
// - Perbaikan JS (tanpa duplikasi fungsi)

declare(strict_types=1);
session_start();

require_once __DIR__ . '/koneksi.php';

// ---- VALIDASI SESSION ----
if (empty($_SESSION['id_pengguna']) || empty($_SESSION['role'])) {
    header('Location: login_admin.php');
    exit;
}
$role = (string) $_SESSION['role'];

// ---- FLASH MESSAGE ----
$success_msg = $_SESSION['success_msg'] ?? '';
$error_msg   = $_SESSION['error_msg']   ?? '';
unset($_SESSION['success_msg'], $_SESSION['error_msg']);

// ---- CSRF TOKEN ----
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ---- INPUT PENCARIAN ----
$search_raw = isset($_GET['search']) ? trim((string)$_GET['search']) : '';
$search_display = htmlspecialchars($search_raw, ENT_QUOTES, 'UTF-8'); // untuk isi input

// ---- QUERY DATA (Prepared Statement) ----
$sql_base = "SELECT 
      p.id_presensi, 
      p.tanggal, 
      p.status_hadir, 
      p.catatan, 
      e.nama_eskul,
      p.status,
      s.nama_siswa
    FROM presensi p
    JOIN eskul e ON p.id_eskul = e.id_eskul
    JOIN siswa s ON p.id_siswa = s.id_siswa";

if ($search_raw !== '') {
    $sql = $sql_base . "\n    WHERE s.nama_siswa LIKE ?\n    ORDER BY p.tanggal DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        die('Gagal menyiapkan query: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
    }
    $like = "%{$search_raw}%";
    $stmt->bind_param('s', $like);
} else {
    $sql = $sql_base . "\n    ORDER BY p.tanggal DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        die('Gagal menyiapkan query: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8'));
    }
}

if (!$stmt->execute()) {
    http_response_code(500);
    die('Gagal eksekusi query: ' . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8'));
}
$result_presensi = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu Admin Presensi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            color: #222;
            background: linear-gradient(135deg, #e0f7fa 0%, #f8f9fa 100%);
            min-height: 100vh;
            position: relative;
            animation: fadeIn 0.8s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        body::before {
            content: "";
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('smkn13.jpg');
            background-size: cover; background-position: center; background-repeat: no-repeat;
            filter: blur(12px) brightness(0.7);
            z-index: -2;
        }
        body::after {
            content: "";
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(44,202,107,0.12) 0%, rgba(0,50,0,0.18) 100%);
            z-index: -1;
        }
        h1 { margin-top: 40px; text-align: center; font-size: 2.7em; width: 50%; background: linear-gradient(90deg, #388e3c 60%, #2ecc71 100%); color: #fff; padding: 24px 0 18px; border-radius: 18px; margin-bottom: 32px; box-shadow: 0 4px 16px rgba(44,202,107,0.13); letter-spacing: 1px; position: relative; animation: fadeIn 1s; }
        h1 i { margin-right: 12px; }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); padding: 24px; margin: 16px 0; transition: transform 0.3s, box-shadow 0.3s; animation: fadeIn 0.8s; width: 95vw; max-width: 1400px; }
        .card h3 { margin-bottom: 16px; font-size: 1.5em; color: #2c3e50; position: relative; display: inline-block; }
        .card h3 i { margin-right: 8px; color: #43e97b; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 18%; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); box-shadow: 2px 0 16px rgba(44,202,107,0.10); z-index: 1001; display: flex; flex-direction: column; align-items: flex-start; padding-top: 32px; transform: translateX(-100%); transition: transform 0.3s cubic-bezier(.4,2,.6,1); border-top-right-radius: 56px; border-bottom-right-radius: 56px; }
        .sidebar.open { transform: translateX(0); }
        .sidebar .sidebar-logo { font-size: 2em; color: #fff; margin-left: 24px; margin-bottom: 32px; font-weight: bold; letter-spacing: 2px; display: flex; align-items: center; gap: 10px; }
        .sidebar .sidebar-nav { width: 100%; display: flex; flex-direction: column; gap: 18px; }
        .sidebar .sidebar-nav a { color: #fff; text-decoration: none; font-size: 1.08em; font-weight: 600; padding: 12px 32px 12px 24px; border-radius: 0 24px 24px 0; transition: background 0.2s, color 0.2s; display: flex; align-items: center; gap: 10px; }
        .sidebar .sidebar-nav a:hover { background: rgba(255,255,255,0.13); width: calc(100% - 20px); color: #222; }
        .burger { position: fixed; top: 24px; left: 24px; z-index: 1100; width: 44px; height: 44px; background: rgba(67,233,123,0.18); border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(44,202,107,0.10); transition: background 0.2s, left 0.3s cubic-bezier(.4,2,.6,1), width 0.2s, height 0.2s, top 0.2s; }
        .burger i { color: #fff; font-size: 1.6em; }
        .burger.move { left: 10px; background: rgba(67,233,123,0.18); }
        @media (max-width: 700px) {
            .sidebar { width: 70vw; min-width: 180px; border-top-right-radius: 32px; border-bottom-right-radius: 32px; }
            .burger { width: 36px; height: 36px; left: 8px; top: 8px; }
            .burger.move { left: 2px; top: 2px; }
        }
        table.tabel { width: 100%; border-collapse: separate; border-spacing: 0; background: #fff; border-radius: 16px; box-shadow: 0 6px 24px rgba(44,202,107,0.10); overflow: hidden; margin-top: 10px; font-size: 1.07em; animation: fadeIn 1s; }
        table.tabel th, table.tabel td { padding: 16px 12px; text-align: center; }
        table.tabel th { background: #43e97b; color: #fff; font-weight: 700; border-bottom: 2px solid #e0f7fa; letter-spacing: 1px; font-size: 1.08em; }
        table.tabel tr { transition: background 0.2s, box-shadow 0.2s; }
        table.tabel tr:nth-child(even) { background: #f8f9fa; }
        table.tabel tr:hover { background: #e0f7fa; color: #222; box-shadow: 0 2px 8px rgba(44,202,107,0.07); }
        table.tabel td { border-bottom: 1px solid #e0f7fa; background: #fff; color: #388e3c; font-size: 1.07em; position: relative; transition: background 0.2s, color 0.2s, box-shadow 0.2s; }
        table.tabel tr:nth-child(even) td { background: #f8f9fa; }
        table.tabel tr:hover td { background: #e0f7fa; color: #222; box-shadow: 0 2px 8px rgba(44,202,107,0.07); }
        table.tabel td a { color: #1b5e20; font-weight: bold; text-decoration: none; }
        table.tabel td a:hover { text-decoration: underline; }
        footer { margin-top: 40px; text-align: center; font-size: 0.98em; color: #777; z-index: 1; }
        .footer-container { max-width: 900px; margin: 38px auto 18px; background: linear-gradient(90deg, #e0f7fa 0%, #f8f9fa 100%); border-radius: 28px; box-shadow: 0 12px 48px rgba(44,202,107,0.18); padding: 38px 0 22px; color: #388e3c; text-align: center; font-size: 1.08em; letter-spacing: 1px; animation: fadeIn 1s; position: relative; overflow: hidden; }
        .footer-container::before { content: ""; position: absolute; left: -80px; top: -80px; width: 180px; height: 180px; background: radial-gradient(circle, #43e97b55 0%, transparent 80%); z-index: 0; }
        .footer-container::after { content: ""; position: absolute; right: -60px; bottom: -60px; width: 140px; height: 140px; background: radial-gradient(circle, #38f9d755 0%, transparent 80%); z-index: 0; }
        .footer-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 32px; flex-wrap: wrap; padding: 0 32px; position: relative; z-index: 1; }
        .footer-col { flex: 1 1 220px; min-width: 180px; text-align: left; }
        .footer-col h4 { margin-bottom: 12px; font-size: 1.13em; color: #43e97b; display: flex; align-items: center; gap: 8px; }
        .footer-col p { margin: 0 0 8px; color: #388e3c; font-size: 1em; }
        .footer-social { display: flex; gap: 14px; margin-top: 4px; }
        .footer-social a { color: #222; background: #fff; border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(44,202,107,0.10); transition: color 0.2s, background 0.2s, transform 0.2s, box-shadow 0.2s; }
        .footer-social a:hover { color: #222; background: #e0f7fa; transform: scale(1.15) translateY(-2px); box-shadow: 0 4px 16px #43e97b44; }
        .footer-col h4:hover, .footer-col p:hover, .footer-copyright:hover { color: #222 !important; }
        .footer-container hr { border: none; border-top: 2px solid #43e97b; width: 90%; margin: 0 auto 18px; opacity: 0.2; position: relative; z-index: 1; }
        @media (max-width: 700px) { .footer-row { flex-direction: column; gap: 18px; padding: 0 12px; } .footer-container { padding: 18px 0 12px; } .card { padding: 12px; } }
        .search-box { margin-bottom: 24px; text-align: right; }
        .search-input { padding: 10px 16px; border-radius: 8px; border: 1px solid #43e97b55; font-size: 1em; width: 260px; background: #f8f9fa; }
        .search-btn { padding: 10px 18px; border-radius: 8px; background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%); color: #fff; border: none; font-weight: 600; cursor: pointer; margin-left: 8px; transition: background 0.2s; }
        .search-btn:hover { background: linear-gradient(90deg, #388e3c 0%, #2ecc71 100%); }
        .notif-success { position: fixed; top: 30px; left: 50%; transform: translateX(-50%) translateY(-40px) scale(0.95); background: #2ecc71; color: #fff; padding: 16px 32px; border-radius: 12px; box-shadow: 0 8px 24px rgba(46,204,113,0.18); font-weight: 600; font-size: 16px; display: flex; align-items: center; gap: 16px; min-width: 260px; max-width: 90vw; opacity: 0; pointer-events: none; z-index: 9999; transition: transform 0.5s cubic-bezier(.4,2,.6,1), opacity 0.5s cubic-bezier(.4,2,.6,1); animation: notifFadeIn 0.7s cubic-bezier(.4,2,.6,1) forwards; }
        @keyframes notifFadeIn { 0% { opacity: 0; transform: translateX(-50%) translateY(-40px) scale(0.95);} 60% { opacity: 1; transform: translateX(-50%) translateY(10px) scale(1.04);} 100% { opacity: 1; transform: translateX(-50%) translateY(0) scale(1);} }
        .notif-success.show { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: auto; }
        .notif-success button { background: transparent; border: none; color: white; font-size: 20px; font-weight: 700; cursor: pointer; line-height: 1; padding: 0; user-select: none; }
        .notif-error { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100%); background: #e74c3c; color: #fff; padding: 14px 24px; border-radius: 10px; box-shadow: 0 8px 20px rgba(231, 76, 60, 0.6); font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 16px; min-width: 280px; max-width: 90vw; opacity: 0; pointer-events: none; transition: transform 0.4s ease, opacity 0.4s ease; z-index: 9999; }
        .notif-error.show { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: auto; }
        .notif-error button { background: transparent; border: none; color: white; font-size: 20px; font-weight: 700; cursor: pointer; line-height: 1; padding: 0; user-select: none; }
        /* Tombol aksi ala link */
        .link-btn { background: none; border: none; padding: 0; margin: 0 6px; color: #1b5e20; font-weight: bold; cursor: pointer; text-decoration: none; }
        .link-btn:hover { text-decoration: underline; }
        .aksi { display: flex; align-items: center; justify-content: center; gap: 2px; }
    </style>
</head>
<body>
    <h1><i class="fa-solid fa-calendar-check"></i> Data Presensi Siswa</h1>

    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>

    <?php if (in_array($role, ['admin'], true)): ?>
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
            <div class="sidebar-nav">
                <a href="panel_admin.php"><i class="fa-solid fa-house"></i> Kembali</a>
                <a href="kelola_admin.php"><i class="fa-solid fa-user-gear"></i>Data Admin</a>
                <a href="admin_siswa.php"><i class="fa-solid fa-users"></i> Data Siswa</a>
                <a href="admin_eskul.php"><i class="fa-solid fa-list"></i> Data Ekstrakurikuler</a>
                <a href="admin_daftar.php"><i class="fa-solid fa-user-plus"></i> Pendaftaran Ekstrakurikuler</a>
                <a href="admin_prestasi.php"><i class="fa-solid fa-trophy"></i> Prestasi Siswa</a>
                <a href="profile_admin.php"><i class="fa-solid fa-id-card"></i> Profil Saya</a>
                <a href="logout_admin.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </nav>
    <?php elseif (in_array($role, ['pembina', 'pelatih'], true)): ?>
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
            <div class="sidebar-nav">
                <a href="panel_admin.php"><i class="fa-solid fa-house"></i> Kembali</a>
                <a href="admin_eskul.php"><i class="fa-solid fa-list"></i> Data Ekstrakurikuler</a>
                <a href="admin_prestasi.php"><i class="fa-solid fa-trophy"></i> Prestasi Siswa</a>
                <a href="logout_admin.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </nav>
    <?php endif; ?>

    <?php if (!empty($success_msg)): ?>
        <div id="notif-success" class="notif-success show">
            <span><?= htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8') ?></span>
            <button onclick="closeSuccess()" aria-label="Tutup notifikasi">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="notif-error show" id="notif-error">
            <button onclick="closeError()"><i class="fa-solid fa-xmark"></i></button>
            <?= htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3><i class="fa-solid fa-list-check"></i> Daftar Presensi Siswa</h3>
        <div class="search-box">
            <form method="get" action="">
                <input type="text" name="search" class="search-input" placeholder="Cari nama siswa..." value="<?= $search_display ?>" autocomplete="off" />
                <button type="submit" class="search-btn"><i class="fa fa-search"></i> Cari</button>
            </form>
        </div>
        <table class="tabel" aria-label="Tabel Presensi">
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Ekstrakurikuler</th>
                <th>Tanggal</th>
                <th>Status Kehadiran</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            if ($result_presensi && $result_presensi->num_rows > 0):
                while ($row = $result_presensi->fetch_assoc()):
                    $id_presensi = (int) $row['id_presensi'];
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_siswa'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($row['nama_eskul'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($row['status_hadir'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($row['catatan'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="aksi">
                    <form action="edit_presensi.php" method="get" style="display:inline">
                        <input type="hidden" name="id" value="<?= $id_presensi ?>" />
                        <button type="submit" class="link-btn"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                    </form>
                    |
                    <form action="hapus_presensi.php" method="post" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                        <input type="hidden" name="id" value="<?= $id_presensi ?>" />
                        <input type="hidden" name="token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>" />
                        <button type="submit" class="link-btn"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="8">Belum ada data presensi.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-col">
                    <h4><i class="fa-solid fa-leaf"></i> Tentang</h4>
                    <p>Sistem Ekstrakurikuler SMKN 13 Bandung.<br>Modern, responsif, dan mudah digunakan untuk admin dan siswa.</p>
                </div>
                <div class="footer-col">
                    <h4><i class="fa-solid fa-envelope"></i> Kontak</h4>
                    <p><i class="fa-solid fa-envelope"></i> info@smkn13.sch.id</p>
                    <p><i class="fa-solid fa-phone"></i> (022) 7318960</p>
                </div>
                <div class="footer-col">
                    <h4><i class="fa-solid fa-share-nodes"></i> Social</h4>
                    <div class="footer-social">
                        <a href="https://www.instagram.com/smkn13bandung/" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://m.facebook.com/smkn13bdg2/" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                        <a href="https://x.com/smkn13bdg" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr style="margin:18px 0;">
            <p class="footer-copyright">&copy; <?= date('Y'); ?> SMKN 13 BDG. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const burger = document.querySelector('.burger');
        if (!sidebar || !burger) return;
        sidebar.classList.toggle('open');
        burger.classList.toggle('move', sidebar.classList.contains('open'));
    }
    // Tutup sidebar saat klik di luar
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const burger = document.querySelector('.burger');
        if (!sidebar || !burger) return;
        if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !burger.contains(e.target)) {
            sidebar.classList.remove('open');
            burger.classList.remove('move');
        }
    });

    function closeSuccess() {
        const notif = document.getElementById('notif-success');
        if (notif) {
            notif.classList.remove('show');
            notif.style.display = 'none';
        }
    }
    function closeError() {
        const notif = document.getElementById('notif-error');
        if (notif) {
            notif.classList.remove('show');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const notif = document.getElementById('notif-success');
        if (notif) {
            notif.classList.add('show');
            setTimeout(() => {
                notif.classList.remove('show');
                notif.style.display = 'none';
            }, 4000);
        }
    });
    </script>
</body>
</html>
