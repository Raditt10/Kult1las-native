<?php
include 'koneksi.php';
session_start();

// Pastikan siswa sudah login
if (!isset($_SESSION['id_siswa'])) {
    header("Location: login_siswa.php");
    exit();
}

$id_siswa = $_SESSION['id_siswa'];

// Ambil data siswa
$stmt = $conn->prepare("SELECT * FROM siswa WHERE id_siswa = ?");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Data siswa tidak ditemukan!";
    exit();
}

$siswa = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Siswa</title>
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
        .profile-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,202,107,0.13);
            padding: 38px 38px 28px 38px;
            margin: 60px 0 32px 0;
            max-width: 480px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px auto;
            box-shadow: 0 2px 16px #43e97b44;
        }
        .profile-avatar i {
            color: #fff;
            font-size: 3.5em;
        }
        .profile-title {
            font-size: 2em;
            font-weight: 600;
            color: #43e97b;
            margin-bottom: 18px;
            letter-spacing: 1px;
        }
        .profile-table {
            width: 100%;
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        .profile-table td {
            padding: 12px 10px;
            font-size: 1.08em;
            color: #388e3c;
            background: #f8f9fa;
            border-radius: 8px;
            border: none;
        }
        .profile-table td.label {
            font-weight: 600;
            color: #43e97b;
            width: 38%;
            text-align: left;
        }
        .profile-table td.value {
            text-align: left;
        }
        .btn-edit {
            display: inline-block;
            margin-top: 28px;
            padding: 12px 38px;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.08em;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
            transition: background 0.2s, transform 0.2s;
        }
        .btn-edit:hover {
            background: linear-gradient(90deg, #388e3c 0%, #2ecc71 100%);
            transform: scale(1.05);
        }
        @media (max-width: 700px) {
            .profile-card {
                padding: 18px 8px 18px 8px;
                max-width: 98vw;
            }
            .profile-table td {
                font-size: 1em;
                padding: 8px 6px;
            }
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
            <a href="panel_siswa.php"><i class="fa-solid fa-house"></i>Kembali</a>
            <a href="logout_siswa.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            <a href="daftar.php"><i class="fa-solid fa-list"></i> Daftar Ekskul</a>
            <a href="presensi_siswa.php"><i class="fa-solid fa-calendar-check"></i> Presensi</a>
    </nav>

    <div class="profile-card">
        <div class="profile-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="profile-title">Profil Siswa</div>
        <table class="profile-table">
            <tr>
                <td class="label">NIS</td>
                <td class="value"><?= htmlspecialchars($siswa['nis']); ?></td>
            </tr>
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="value"><?= htmlspecialchars($siswa['nama_siswa']); ?></td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td class="value"><?= htmlspecialchars($siswa['kelas']); ?></td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="value"><?= htmlspecialchars($siswa['jenis_kelamin']); ?></td>
            </tr>
            <tr>
                <td class="label">No. HP</td>
                <td class="value"><?= htmlspecialchars($siswa['no_telp']); ?></td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="value"><?= htmlspecialchars($siswa['alamat']); ?></td>
            </tr>
        </table>
        <a href="edit_profiles.php" class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Edit Profil</a>
    </div>
         <footer>
        <hr>
        <p>&copy;  <?php echo date('Y');?>  SMKN 13 BDG. Hak Cipta Dilindungi.</p>
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

// Jalankan setelah DOM siap
document.addEventListener('DOMContentLoaded', function () {
    const notif = document.getElementById("notif-success");
    if (notif) {
        notif.classList.add("show");
        setTimeout(() => {
            notif.classList.remove("show");
            notif.style.display = 'none';
        }, 4000); // Auto close dalam 4 detik
    }
});
</script>
</body>
</html>