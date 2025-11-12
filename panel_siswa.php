<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['id_siswa'])) {
    header("Location: login_siswa.php");
    exit;
}

$success_msg = $_SESSION['success_msg'] ?? null;
$id_siswa = $_SESSION['id_siswa'];
$nis = $_SESSION['nis'];
$nama_siswa = $_SESSION['nama_siswa'];

$query = "SELECT p.id_eskul, e.nama_eskul, p.hari_kegiatan, p.jam_mulai, p.jam_selesai FROM eskul p JOIN eskul e ON p.id_eskul = e.id_eskul";
$query_presensi = "SELECT p.id_eskul, e.nama_eskul, p.tanggal, p.status_hadir, p.status, p.catatan FROM presensi p JOIN eskul e ON p.id_eskul = e.id_eskul WHERE p.id_siswa = '$id_siswa' ORDER BY p.tanggal DESC";

$result = $conn->query($query);
$result_presensi = $conn->query($query_presensi);

?>
<html>
<head>
    <title>Panel Siswa</title>
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
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('smkn13.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(12px) brightness(0.7);
            z-index: -2;
        }
        body::after {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(44,202,107,0.12) 0%, rgba(0,50,0,0.18) 100%);
            z-index: -1;
        }
        h1 {
            margin-top: 40px;
            text-align: center;
            font-size: 2.7em;
            width: 50%;
            background: linear-gradient(90deg, #388e3c 60%, #2ecc71 100%);
            color: #fff;
            padding: 24px 0 18px 0;
            border-radius: 18px;
            margin-bottom: 32px;
            box-shadow: 0 4px 16px rgba(44,202,107,0.13);
            letter-spacing: 1px;
            position: relative;
            animation: fadeIn 1s;
        }
        h1 i {
            margin-right: 12px;
        }
         .notif-success {
      position: fixed;
      top: 30px;
      left: 50%;
      transform: translateX(-50%) translateY(-40px) scale(0.95);
      background: #2ecc71;
      color: white;
      padding: 16px 32px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(46,204,113,0.18);
      font-weight: 600;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 16px;
      min-width: 260px;
      max-width: 90vw;
      opacity: 0;
      pointer-events: none;
      z-index: 9999;
      transition: transform 0.5s cubic-bezier(.4,2,.6,1), opacity 0.5s cubic-bezier(.4,2,.6,1);
      animation: notifFadeIn 0.7s cubic-bezier(.4,2,.6,1) forwards;
    }
    @keyframes notifFadeIn {
      0% { opacity: 0; transform: translateX(-50%) translateY(-40px) scale(0.95); }
      60% { opacity: 1; transform: translateX(-50%) translateY(10px) scale(1.04); }
      100% { opacity: 1; transform: translateX(-50%) translateY(0) scale(1); }
    }
    
    .notif-success.show {
       transform: translateX(-50%) translateY(0);
       opacity: 1;
       pointer-events: auto;
    }
    .notif-success button {
      background: transparent;
      border: none;
      color: white;
      font-size: 20px;
      font-weight: 700;
      cursor: pointer;
      line-height: 1;
      padding: 0;
      user-select: none;
    }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 18%;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            box-shadow: 2px 0 16px rgba(44,202,107,0.10);
            z-index: 1001;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding-top: 32px;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(.4,2,.6,1);
            border-top-right-radius: 56px;
            border-bottom-right-radius: 56px;
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .sidebar .sidebar-logo {
            font-size: 1.7em;
            color: #fff;
            margin-left: 32px;
            margin-bottom: 32px;
            font-weight: bold;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .sidebar-nav {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .sidebar .sidebar-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 1.13em;
            font-weight: 600;
            padding: 14px 36px 14px 32px;
            border-radius: 0 24px 24px 0;
            transition: background 0.2s, color 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            box-shadow: 0 2px 8px rgba(44,202,107,0.07);
        }
        .sidebar .sidebar-nav a:hover {
            background: rgba(255,255,255,0.18);
            color: #222;
            width: calc(100% - 20px);
            transform: scale(1.04) translateX(4px);
        }
        .sidebar .sidebar-nav a::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #fff;
            margin-right: 8px;
            opacity: 0.5;
        }
        .burger {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 1100;
            width: 44px;
            height: 44px;
            background: rgba(67,233,123,0.18);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
            transition: background 0.2s, left 0.3s cubic-bezier(.4,2,.6,1), width 0.2s, height 0.2s, top 0.2s;
        }
        .burger.move {
            left: 10px;
            background: rgba(67,233,123,0.18);
        }
        .burger i {
            color: #fff;
            font-size: 1.6em;
        }
        @media (max-width: 700px) {
            .sidebar {
                width: 70vw;
                min-width: 140px;
                border-top-right-radius: 32px;
                border-bottom-right-radius: 32px;
            }
            .burger {
                width: 36px;
                height: 36px;
                left: 8px;
                top: 8px;
            }
            .burger.move {
                left: 2px;
                top: 2px;
            }
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin: 16px 0;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeIn 0.8s;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }
        .card h3 {
            margin-bottom: 16px;
            font-size: 1.5em;
            color: #2c3e50;
            position: relative;
            display: inline-block;
        }
        .card h3 i {
            margin-right: 8px;
            color: #43e97b;
        }
        .card table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(44,202,107,0.10);
            overflow: hidden;
            margin-top: 10px;
            font-size: 1.07em;
            animation: fadeIn 1s;
        }
        .card th, .card td {
            padding: 16px 12px;
            text-align: center;
        }
        .card th {
            background: #90ee90;
            color: #222;
            font-weight: 700;
            border-bottom: 2px solid #e0f7fa;
            letter-spacing: 1px;
            font-size: 1.08em;
        }
        .card tr {
            transition: background 0.2s, box-shadow 0.2s;
        }
        .card tr:nth-child(even) {
            background: #f8f9fa;
        }
        .card tr:hover {
            background: #e0f7fa;
            color: #222;
            box-shadow: 0 2px 8px rgba(44,202,107,0.07);
        }
        .card td {
            border-bottom: 1px solid #e0f7fa;
            background: #fff;
            color: #388e3c;
            font-size: 1.07em;
            position: relative;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .card tr:nth-child(even) td {
            background: #f8f9fa;
        }
        .card tr:hover td {
            background: #e0f7fa;
            color: #222;
            box-shadow: 0 2px 8px rgba(44,202,107,0.07);
        }
        .card td strong {
            color: #43e97b;
            font-weight: 700;
        }
        .card td .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.98em;
            font-weight: 600;
            background: #90ee90;
            color: #222;
            margin-left: 4px;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
        }
        .card td .badge.badge-success {
            background: #43e97b;
            color: #fff;
        }
        .card td .badge.badge-warning {
            background: #ffe066;
            color: #222;
        }
        .card td .badge.badge-danger {
            background: #e74c3c;
            color: #fff;
        }
        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.98em;
            color: #777;
            z-index: 1;
            animation: fadeIn 1s;
        }
        .footer-container {
            max-width: 900px;
            margin: 38px auto 18px auto;
            background: linear-gradient(90deg, #e0f7fa 0%, #f8f9fa 100%);
            border-radius: 28px;
            box-shadow: 0 12px 48px rgba(44,202,107,0.18);
            padding: 38px 0 22px 0;
            color: #388e3c;
            text-align: center;
            font-size: 1.08em;
            letter-spacing: 1px;
            animation: fadeIn 1s;
            position: relative;
            overflow: hidden;
        }
        .footer-container::before {
            content: "";
            position: absolute;
            left: -80px; top: -80px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, #43e97b55 0%, transparent 80%);
            z-index: 0;
        }
        .footer-container::after {
            content: "";
            position: absolute;
            right: -60px; bottom: -60px;
            width: 140px; height: 140px;
            background: radial-gradient(circle, #38f9d755 0%, transparent 80%);
            z-index: 0;
        }
        .footer-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 32px;
            flex-wrap: wrap;
            padding: 0 32px;
            position: relative;
            z-index: 1;
        }
        .footer-col {
            flex: 1 1 220px;
            min-width: 180px;
            text-align: left;
        }
        .footer-col h4 {
            margin-bottom: 12px;
            font-size: 1.13em;
            color: #43e97b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .footer-col p {
            margin: 0 0 8px 0;
            color: #388e3c;
            font-size: 1em;
        }
        .footer-social {
            display: flex;
            gap: 14px;
            margin-top: 4px;
        }
        .footer-social a {
            color: #222;
            background: #fff;
            border-radius: 50%;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
            transition: color 0.2s, background 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .footer-social a:hover {
            color: #222;
            background: #e0f7fa;
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 4px 16px #43e97b44;
        }
        .footer-col h4:hover,
        .footer-col p:hover,
        .footer-copyright:hover {
            color: #222 !important;
        }
        .footer-copyright {
            margin: 0;
            color: #388e3c;
            font-size: 1em;
            opacity: 0.7;
            position: relative;
            z-index: 1;
        }
        .footer-container hr {
            border: none;
            border-top: 2px solid #43e97b;
            width: 90%;
            margin: 0 auto 18px auto;
            opacity: 0.2;
            position: relative;
            z-index: 1;
        }
        @media (max-width: 700px) {
            .footer-row {
                flex-direction: column;
                gap: 18px;
                padding: 0 12px;
            }
            .footer-container {
                padding: 18px 0 12px 0;
            }
        }
    </style>
</head>
<body>
    <h1><i class="fa-solid fa-user-graduate"></i> Panel Siswa</h1>
    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
            <a href="logout_siswa.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            <a href="daftar.php"><i class="fa-solid fa-list"></i> Daftar Ekskul</a>
            <a href="presensi_siswa.php"><i class="fa-solid fa-calendar-check"></i> Presensi</a>
            <a href="profile_siswa.php"><i class="fa-solid fa-id-card"></i> Profil Saya</a>
        </div>
    </nav>

      <?php if ($success_msg): ?>
<div id="notif-success" class="notif-success show">
  <span><?= htmlspecialchars($success_msg) ?></span>
  <button onclick="closeNotif()" aria-label="Close notification">&times;</button>
</div>
<?php endif; ?>


    <div class="card">
        <h3><i class="fa-solid fa-user"></i> Hai, <?php echo htmlspecialchars($nama_siswa); ?>!</h3>
         <p>
            <?php
            date_default_timezone_set('Asia/Jakarta');
            $hour = (int)date('G');
            if ($hour >= 5 && $hour < 11) {
                $greeting = 'Selamat pagi';
            } elseif ($hour >= 11 && $hour < 15) {
                $greeting = 'Selamat siang';
            } elseif ($hour >= 15 && $hour < 18) {
                $greeting = 'Selamat sore';
            } else {
                $greeting = 'Selamat malam';
            }
            echo $greeting;
            ?>, selamat datang di panel siswa SMKN 13 Bandung. Di sini kamu bisa melihat jadwal ekskul dan riwayat presensi kamu.
        </p>
    </div>
    <div class="card">
        <h3><i class="fa-solid fa-calendar-days"></i> Jadwal Ekskul</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Ekstrakurikuler</th>
                <th>Hari Kegiatan</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_eskul']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hari_kegiatan']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jam_mulai']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jam_selesai']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Belum ada jadwal Ekskul terbaru.</td></tr>";
            }
            ?>
        </table>
    </div>
    <div class="card">
        <h3><i class="fa-solid fa-list-check"></i> Riwayat Presensi</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Ekstrakurikuler</th>
                <th>Status Kehadiran</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
            <?php
            if ($result_presensi && $result_presensi->num_rows > 0) {
                $no = 1;
                while ($row = $result_presensi->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                    echo  "<td>" . htmlspecialchars($row['nama_eskul']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status_hadir']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['catatan']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Belum ada data presensi.</td></tr>";
            }
            ?>
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
            <p class="footer-copyright">&copy; <?php echo date('Y');?> SMKN 13 BDG. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const burger = document.querySelector('.burger');
    sidebar.classList.toggle('open');
    burger.classList.toggle('move', sidebar.classList.contains('open'));
}

document.addEventListener('click', function (e) {
    const sidebar = document.getElementById('sidebar');
    const burger = document.querySelector('.burger');
    if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !burger.contains(e.target)) {
        sidebar.classList.remove('open');
        burger.classList.remove('move');
    }
});

// Fungsi untuk menutup notif secara manual
function closeNotif() {
    const notif = document.getElementById("notif-success");
    if (notif) {
        notif.classList.remove("show");
        notif.style.display = 'none';
    }
}

window.addEventListener('DOMContentLoaded', () => {
  const notif = document.getElementById("notif-success");
  if (notif) {
    if (!localStorage.getItem('notifSuccessClosed')) {
      notif.classList.add("show");
      setTimeout(() => {
        notif.classList.remove("show");
        notif.style.display = 'none';
        notif.innerHTML = '';
        localStorage.setItem('notifSuccessClosed', '1');
      }, 3000);
    } else {
      notif.classList.remove("show");
      notif.style.display = 'none';
      notif.innerHTML = '';
    }
  }
  // Reset flag saat reload
  window.addEventListener('beforeunload', () => {
    localStorage.removeItem('notifSuccessClosed');
  });
});
</script>
<?php unset($_SESSION['success_msg']); ?>
</body>
</html>
