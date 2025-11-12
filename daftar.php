<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_siswa'])) {
    header("Location: login_siswa.php");
    exit;
}

$id_siswa = $_SESSION['id_siswa'];
$nama_siswa = $_SESSION['nama_siswa'];

$success_msg = $_SESSION['success_msg'] ?? '';
$error_msg = $_SESSION['error_msg'] ?? '';
unset($_SESSION['success_msg'], $_SESSION['error_msg']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pendaftaran = uniqid('id_');
    $id_eskul = $conn->real_escape_string($_POST['id_eskul']);
    $tanggal_daftar = $conn->real_escape_string($_POST['tanggal_daftar']);
    $status = 'tunda';
    $keterangan = $conn->real_escape_string($_POST['keterangan']);

    $cek_eskul = $conn->query("SELECT * FROM eskul WHERE id_eskul = '$id_eskul'");
    if ($cek_eskul->num_rows == 0) {
        $_SESSION['error_msg'] = "Ekstrakurikuler tidak ditemukan!";
    } else {
        $insert_query = "INSERT INTO pendaftaran_eskul (id_pendaftaran, id_siswa, id_eskul, tanggal_daftar, status, keterangan)
                         VALUES ('$id_pendaftaran', '$id_siswa', '$id_eskul', '$tanggal_daftar', '$status', '$keterangan')";

        if ($conn->query($insert_query)) {
            $_SESSION['success_msg'] = "Pendaftaran berhasil. Lihat status pendaftaranmu di halaman Status Pendaftaran.";
        } else {
            $_SESSION['error_msg'] = "Gagal mendaftar: " . $conn->error;
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pendaftaran Ekstrakurikuler</title>
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
        .container {
            max-width: 480px;
            margin: 60px auto;
            background: rgba(255,255,255,0.97);
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(44,202,107,0.08);
            padding: 38px 32px;
        }
        h1 {
            text-align: center;
            font-size: 2.1em;
            background: linear-gradient(90deg, #388e3c 60%, #2ecc71 100%);
            color: #fff;
            padding: 18px 0 14px 0;
            border-radius: 14px;
            margin-bottom: 28px;
            box-shadow: 0 4px 16px rgba(44,202,107,0.13);
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
        .burger i {
            color: #fff;
            font-size: 1.6em;
        }
        .burger.move {
            left: 10px;
            background: rgba(67,233,123,0.18);
        }
        @media (max-width: 700px) {
            .sidebar {
                width: 70vw;
                min-width: 180px;
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
        .form-group {
            margin-bottom: 22px;
            position: relative;
        }
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            bottom: 50%;
            transform: translateY(-50%);
            color: #43e97b;
            font-size: 1.1em;
            opacity: 0.7;
            pointer-events: none;
        }
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 10px 10px 38px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background: #fff;
            color: #222;
            box-shadow: 0 2px 8px rgba(44,202,107,0.07);
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 0 3px #43e97b44;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
            max-height: 200px;
        }

        .notif-success, .notif-error {
            position: fixed;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            padding: 16px 32px;
            border-radius: 12px;
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
            box-shadow: 0 8px 24px rgba(44,202,107,0.18);
            animation: notifFadeIn 0.7s ease forwards;
        }
        .notif-success {
            background: #2ecc71;
            color: white;
        }
        .notif-error {
            background: #e74c3c;
            color: white;
        }
        @keyframes notifFadeIn {
            0% { opacity: 0; transform: translateX(-50%) translateY(-40px); }
            100% { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
   /* Ubah bagian .btn-submit pada style daftar.php */
.btn-submit {
    width: 100%;
    padding: 12px;
    background: linear-gradient(90deg, #388e3c 0%, #43e97b 100%);
    border: none;
    color: white;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 8px;
    margin-top: 10px;
    box-shadow: 0 2px 8px rgba(44,202,107,0.10);
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    position: relative;
    overflow: hidden;
}
.btn-submit:hover {
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    transform: scale(1.04) translateY(-2px);
    box-shadow: 0 6px 24px rgba(44,202,107,0.18);
}
.btn-submit:active {
    transform: scale(0.97);
}
.btn-submit::after {
    content: "";
    position: absolute;
    left: 50%;
    top: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.4s cubic-bezier(.4,2,.6,1), height 0.4s cubic-bezier(.4,2,.6,1);
    z-index: 1;
}
.btn-submit:active::after {
    width: 180%;
    height: 180%;
    transition: width 0.2s, height 0.2s;
}

.greeting {
    text-align: center;
    margin-bottom: 30px;
    background: linear-gradient(90deg, #e0f7fa, #f1f8e9);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(44, 202, 107, 0.1);
    font-size: 1.05em;
    color: #2c3e50;
}

.greeting strong {
    color: #2ecc71;
    font-size: 1.15em;
    background: linear-gradient(90deg, #388e3c, #43e97b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.greeting-sub {
    display: block;
    margin-top: 8px;
    color: #555;
    font-size: 0.95em;
    font-weight: 500;
}


        .notif-success.show, .notif-error.show {
            opacity: 1;
            pointer-events: auto;
        }
        .notif-success button, .notif-error button {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
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
    <?php if ($success_msg): ?>
        <div class="notif-success show" id="notif-success">
            <i class="fa-solid fa-circle-check"></i>
            <span><?php echo $success_msg; ?></span>
            <button onclick="closeNotif('notif-success')">&times;</button>
        </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="notif-error show" id="notif-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><?php echo $error_msg; ?></span>
            <button onclick="closeNotif('notif-error')">&times;</button>
        </div>
    <?php endif; ?>

 <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
           <a href="panel_siswa.php"><i class="fa-solid fa-house"></i>Kembali</a>
           <a href="hasil_daftar.php"><i class="fa-solid fa-list-check"></i> Status Pendaftaran saya</a>
           <a href="logout_siswa.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
           <a href="profile_siswa.php"><i class="fa-solid fa-id-card"></i></i> Profil Saya</a>
        </div>
    </nav>

<div class="container">
    <h1><i class="fa-solid fa-user-plus"></i> Daftar Ekskul</h1>
   <div class="greeting">
    <p>
        👋 Halo, <strong><?php echo htmlspecialchars($nama_siswa); ?></strong>!<br>
        <span class="greeting-sub">Pilih ekstrakurikuler yang paling menarik buat kamu 🚀</span>
    </p>
</div>

    <form method="post">
        <div class="form-group">
            <label for="id_eskul">Pilih Ekstrakurikuler</label>
            <span class="input-icon"><i class="fa-solid fa-list"></i></span>
            <select id="id_eskul" name="id_eskul" required>
                <option value="">Pilih Ekskul</option>
                <?php
                $eskul_result = $conn->query("SELECT id_eskul, nama_eskul FROM eskul");
                while ($eskul = $eskul_result->fetch_assoc()) {
                    echo '<option value="' . $eskul['id_eskul'] . '">' . $eskul['nama_eskul'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tanggal_daftar">Tanggal Daftar</label>
            <span class="input-icon"><i class="fa-solid fa-calendar-days"></i></span>
            <input type="date" name="tanggal_daftar" required>
        </div>
        <div class="form-group">
            <label for="keterangan">Alasan Mengikuti</label>
            <span class="input-icon"><i class="fa-solid fa-comment-dots"></i></span>
            <textarea id="keterangan" name="keterangan" required placeholder="Tulis alasan kamu ikut ekskul..."></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Daftar" class="btn-submit">
        </div>
    </form>
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
    function closeNotif(id) {
        const notif = document.getElementById(id);
        if (notif) {
            notif.classList.remove("show");
            notif.style.display = 'none';
        }
    }
    // Auto close notification
    setTimeout(() => {
        closeNotif('notif-success');
        closeNotif('notif-error');
    }, 4000);

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const burger = document.querySelector('.burger');
        sidebar.classList.toggle('open');
        burger.classList.toggle('move', sidebar.classList.contains('open'));
    }
    // Optional: close sidebar when clicking outside
    document.addEventListener('click', function(e) {
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
