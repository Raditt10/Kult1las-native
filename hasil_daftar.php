<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nama_siswa'])) {
    header("Location: login_siswa.php");
    exit;
}

$id_siswa = $_SESSION['id_siswa'];
$nama_siswa = $_SESSION['nama_siswa'];

$query = "SELECT p.id_pendaftaran, e.nama_eskul, p.tanggal_daftar, p.keterangan, p.status 
          FROM pendaftaran_eskul p
          JOIN eskul e ON p.id_eskul = e.id_eskul
          WHERE p.id_siswa = '$id_siswa'";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pendaftaran Ekstrakurikuler</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .main-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,202,107,0.13);
            padding: 38px 38px 28px 38px;
            margin: 60px 0 32px 0;
            max-width: 900px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        h1 {
            margin-top: 30px;
            text-align: center;
            font-size: 2.2em;
            color: #43e97b;
            margin-bottom: 18px;
            letter-spacing: 1px;
            font-weight: 700;
        }
        h2 {
            text-align: center;
            color: #388e3c;
            margin-bottom: 20px;
            font-size: 1.3em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
            font-size: 18px;
        }
        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        table th {
            background-color: #43e97b;
            color: white;
            font-weight: 600;
        }
        table tr:hover {
            background-color: #f1f8ff;
            transition: background-color 0.3s ease;
        }
        p.empty {
            text-align: center;
            font-size: 18px;
            margin-top: 30px;
            color: #555;
        }
        .back {
            text-align: center;
            margin-top: 18px;
        }
        .back a {
            color: #27ae60;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.1em;
        }
        .back a:hover {
            text-decoration: underline;
        }
        footer {
            text-align: center;
            color: white;
            font-size: 0.9em;
            padding: 12px 0;
            margin-top: 20px;
            width: 100%;
        }
        @media (max-width: 900px) {
            .main-card {
                padding: 18px 8px 18px 8px;
                max-width: 98vw;
            }
            table th, table td {
                font-size: 1em;
                padding: 8px 6px;
            }
        }
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            table thead {
                display: none;
            }
        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        table th {
            background-color: #43e97b;
            color: white;
            font-weight: 600;
        }
        /* Perbesar kolom Alasan */
        table th:nth-child(5),
        table td:nth-child(5) {
            width: 28%;
            max-width: 320px;
            min-width: 180px;
            text-align: left;
            word-break: break-word;
        } 
        table td::before {
                position: absolute;
                left: 10px;
                top: 15px;
                white-space: nowrap;
                font-weight: bold;
                color: #333;
            }
            table td:nth-child(1)::before { content: "ID Pendaftaran"; }
            table td:nth-child(2)::before { content: "Nama Pendaftar"; }
            table td:nth-child(3)::before { content: "Ekstrakurikuler"; }
            table td:nth-child(4)::before { content: "Tanggal Daftar"; }
            table td:nth-child(5)::before { content: "Alasan"; }
            table td:nth-child(6)::before { content: "Status"; }
        }
    </style>
</head>
<body>
    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
     <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
           <a href="daftar.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
           <a href="presensi_siswa.php"><i class="fa-solid fa-calendar-check"></i> Presensi</a>
           <a href="profile_siswa.php"><i class="fa-solid fa-id-card"></i></i> Profil Saya</a>
           <a href="logout_siswa.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>

    <div class="main-card">
        <h1>Daftar Pendaftaran Ekstrakurikuler</h1>
        <h2>Data Pendaftaran Anda</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pendaftaran</th>
                        <th>Nama Pendaftar</th>
                        <th>Ekstrakurikuler</th>
                        <th>Tanggal Daftar</th>
                        <th>Alasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_pendaftaran']); ?></td>
                            <td><?php echo htmlspecialchars($nama_siswa); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_eskul']); ?></td>
                            <td><?php echo htmlspecialchars($row['tanggal_daftar']); ?></td>
                            <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty">Anda belum mendaftar untuk ekstrakurikuler apapun.</p>
        <?php endif; ?>
        <div class="back">
            <a href="daftar.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <footer>
        <hr>
        <p>&copy; <?php echo date('Y');?> RAFADITYA SYAHPUTRA. Hak Cipta Dilindungi.</p>
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
    })
    </script>
</body>
</html>