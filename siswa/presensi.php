<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_siswa'])) {
    header("Location: login_siswa.php");
    exit;
}

$id_siswa = $_SESSION['id_siswa'];
$nama_siswa = $_SESSION['nama_siswa'];
$status = 'tunda'; // Status default
$tanggal = date('Y-m-d');
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_eskul = $_POST['id_eskul'] ?? '';
    $status_hadir = $_POST['status_hadir'] ?? '';
    $catatan = $_POST['catatan'] ?? '';

    if (!empty($id_eskul) && !empty($status_hadir)) {
        $stmt = $conn->prepare("INSERT INTO presensi (id_siswa, id_eskul, tanggal, status_hadir, status, catatan) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Error dalam prepare statement: ' . $conn->error);
        }
        $stmt->bind_param("iissss", $id_siswa, $id_eskul, $tanggal, $status_hadir, $status, $catatan);

        if ($stmt->execute()) {
            $success = "Sip! Presensi Kamu Sudah Dicatat.";
        } else {
            $error = "Yah, Data Presensi gagal Dicatat.";
        }
        $stmt->close();
    } else {
        $error = "Mohon lengkapi semua data presensi.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Presensi Siswa</title>
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
            font-size: 2em;
            color: #fff;
            margin-left: 24px;
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
            font-size: 1.08em;
            font-weight: 600;
            padding: 12px 32px 12px 24px;
            border-radius: 0 24px 24px 0;
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .sidebar-nav a:hover {
            background: rgba(255,255,255,0.13);
            width: calc(100% - 20px);
            color: #222;
        }
        .form-container {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,202,107,0.13);
            padding: 38px 38px 28px 38px;
            margin: 60px 0 32px 0;
            max-width: 480px;
            width: 100%;
            position: relative;
            z-index: 2;
        }
        h2 {
            text-align: center;
            color: #43e97b;
            font-size: 2em;
            margin-bottom: 30px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        label {
            display: block;
            margin-top: 18px;
            font-size: 1.08em;
            font-weight: 500;
            color: #388e3c;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px 14px;
            font-size: 1.08em;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #43e97b55;
            background: #f8f9fa;
            color: #222;
            transition: 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #43e97b;
            outline: none;
            background: #e0f7fa;
        }
        .btn {
            margin-top: 28px;
            padding: 14px;
            font-size: 1.15em;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
            transition: background 0.2s, transform 0.2s;
        }
        .btn:hover {
            background: linear-gradient(90deg, #388e3c 0%, #2ecc71 100%);
            transform: scale(1.04) translateY(-2px);
        }
        .notif {
            background: #e0f7fa;
            color: #388e3c;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 18px;
            text-align: center;
            font-weight: 600;
        }
        @media (max-width: 700px) {
            .form-container {
                padding: 18px 8px 18px 8px;
                max-width: 98vw;
            }
        }
    </style>
</head>
<body>
    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-user-graduate"></i> Siswa</div>
         <div class="sidebar-nav">
            <a href="panel_siswa.php"><i class="fa-solid fa-home"></i> Kembali</a>
            <a href="logout_siswa.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            <a href="daftar.php"><i class="fa-solid fa-list"></i> Daftar Ekskul</a>
            <a href="profile_siswa.php"><i class="fa-solid fa-id-card"></i> Profil Saya</a>
        </div>
    </nav>
    <div class="form-container">
        <h2>Presensi Ekskul</h2>
        <?php if ($error): ?>
            <div class="notif" style="color:red"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="notif" style="color:green"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="id_eskul">Pilih Ekskul</label>
            <select name="id_eskul" id="id_eskul" required>
                <option value="">Pilih Ekskul</option>
                <?php
                $result = $conn->query("SELECT id_eskul, nama_eskul FROM eskul");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id_eskul'] . "'>" . htmlspecialchars($row['nama_eskul']) . "</option>";
                }
                ?>
            </select>

            <label for="status_hadir">Status Kehadiran</label>
            <select name="status_hadir" id="status_hadir" required>
                <option value="">Pilih Status</option>
                <option value="Hadir">Hadir</option>
                <option value="Sakit">Sakit</option>
                <option value="Izin">Izin</option>
                <option value="Alpha">Alpha</option>
            </select>

            <label for="catatan">Catatan (Opsional)</label>
            <textarea name="catatan" id="catatan" rows="4"></textarea>

            <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Catat Kehadiran</button>
        </form>
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
    </script>
</body>
</html>