<?php
session_start();
include 'koneksi.php';

$error_msg = '';
$success_msg = $_SESSION['success_msg'] ?? null;
if ($success_msg) {
    unset($_SESSION['success_msg']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis            = $conn->real_escape_string($_POST['nis']);
    $nama_siswa     = $conn->real_escape_string($_POST['nama_siswa']);
    $kelas          = $conn->real_escape_string($_POST['kelas']);
    $jenis_kelamin  = $conn->real_escape_string($_POST['jenis_kelamin']);
    $tanggal_lahir  = $conn->real_escape_string($_POST['tanggal_lahir']);
    $alamat         = $conn->real_escape_string($_POST['alamat']);
    $no_telp        = $conn->real_escape_string($_POST['no_telp']);
    $email          = $conn->real_escape_string($_POST['email']);

    // Validasi
    if (!preg_match('/^\d{1,11}$/', $nis)) {
        $error_msg = "NIS hanya boleh angka dan maksimal 11 digit!";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $nama_siswa)) {
        $error_msg = "Nama hanya boleh huruf dan spasi!";
    } else {
        $check_query = "SELECT * FROM siswa WHERE nis = '$nis'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            $error_msg = "NIS sudah terdaftar!";
        } else {
            $insert_query = "INSERT INTO siswa(nis, nama_siswa, kelas, jenis_kelamin, tanggal_lahir, alamat, no_telp, email) 
                             VALUES('$nis', '$nama_siswa', '$kelas', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$no_telp', '$email')";
            if ($conn->query($insert_query)) {
                $_SESSION['success_msg'] = "Pendaftaran berhasil! Silakan login.";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error_msg = "Pendaftaran gagal: " . $conn->error;
            }
        }
    }
}
?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi Siswa</title>
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
            width: 30%;
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

     .notif-error {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100%);
            background: #e74c3c;
            color: white;
            padding: 14px 24px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.6);
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 280px;
            max-width: 90vw;
            opacity: 0;
            pointer-events: none;
            transition: transform 0.4s ease, opacity 0.4s ease;
            z-index: 9999;
        }
        .notif-error.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .notif-error button {
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
            width: 15%;
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
        form {
            background: rgba(255,255,255,0.97);
            padding: 32px 28px;
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(44,202,107,0.08);
            max-width: 400px;
            width: 100%;
            margin: 0 auto 32px auto;
            animation: fadeIn 1s;
        }
        table {
            width: 100%;
        }
        table td {
            padding: 12px;
            vertical-align: top;
        }
        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 8px;
        }
        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #43e97b;
            font-size: 1.1em;
            opacity: 0.7;
            pointer-events: none;
        }
        .input-group input,
        .input-group select,
        .input-group textarea {
            padding-left: 38px !important;
        }
        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 0 3px #43e97b44;
        }
        label {
            font-weight: 600;
            color: #2c3e50;
        }
        input[type="text"], input[type="date"], input[type="email"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            color: #222;
            box-shadow: 0 2px 8px rgba(44,202,107,0.07);
        }
        input[type="text"]:hover, input[type="date"], input[type="email"], textarea:hover, select:hover,
        input[type="text"]:hover, input[type="date"], input[type="email"], textarea:focus, select:focus {
            border-color: #43e97b;
            box-shadow: 0 0 0 3px #43e97b88;
            background: #fff;
            color: #222;
        }
        input[type="text"]:hover, input[type="date"], input[type="email"], textarea, select::placeholder {
            color: #43e97b;
            opacity: 0.7;
            font-style: italic;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #388e3c 0%, #2ecc71 100%);
        }
        .notif-error {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100%);
            background: #e74c3c;
            color: white;
            padding: 14px 24px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.6);
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 280px;
            max-width: 90vw;
            opacity: 0;
            pointer-events: none;
            transition: transform 0.4s ease, opacity 0.4s ease;
            z-index: 9999;
        }
        .notif-error.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .notif-error button {
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
        footer {
            margin-top: 10px;
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

    <h1><i class="fa-solid fa-user"></i>Buat Akun</h1>

    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
            <a href="index_siswa.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <a href="login_siswa.php"><i class="fa-solid fa-sign-in-alt"></i> Login</a>
        </div>
    </nav>
   
    <?php if ($error_msg): ?>
        <div class="notif-error show" id="notif-error">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <?php echo $error_msg; ?>
            <button type="button">&times;</button>
        </div>
    <?php endif; ?>

     <?php if ($success_msg): ?>
<div id="notif-success" class="notif-success show">
  <span><?= htmlspecialchars($success_msg) ?></span>
  <button onclick="closeNotif()" aria-label="Close notification">&times;</button>
</div>
<?php endif; ?>

    <form method="post" action="">
        <table>
            <tr>
                <td><label for="nis">NIS:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" id="nis" name="nis" placeholder="Masukkan NIS kamu" autocomplete="off" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="nama_siswa">Nama Lengkap:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="nama_siswa" name="nama_siswa" placeholder="Masukkan nama lengkap kamu" autocomplete="off" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="kelas">Kelas:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-school"></i>
                        <select id="kelas" name="kelas" required>
                            <option value="">Pilih kelas kamu</option>
                            <option value="X RPL 1">X RPL 1</option>
                            <option value="X RPL 2">X RPL 2</option>
                            <option value="X TKJ 1">X TKJ 1</option>
                            <option value="X TKJ 2">X TKJ 2</option>
                            <option value="X KA 1">X KA 1</option>
                            <option value="X KA 2">X KA 2</option>
                            <option value="X KA 3">X KA 3</option>
                            <option value="X KA 4">X KA 4</option>
                            <option value="X KA 5">X KA 5</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="jenis_kelamin">Jenis Kelamin:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-venus-mars"></i>
                        <select id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="tanggal_lahir">Tanggal Lahir:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-calendar-days"></i>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="alamat">Alamat:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-location-dot"></i>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="no_telp">No. HP:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" id="no_telp" name="no_telp" placeholder="08xxxxxxxxxx" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="input-group">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="email@example.com" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Daftar"></td>
            </tr>
        </table>
    </form>

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
                    <p><i class="fa-solid fa-phone"></i> (022) 1234567</p>
                </div>
                <div class="footer-col">
                    <h4><i class="fa-solid fa-share-nodes"></i> Social</h4>
                    <div class="footer-social">
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
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

    // Tutup sidebar jika klik di luar sidebar & tombol burger
    document.addEventListener('click', function (e) {
        const sidebar = document.getElementById('sidebar');
        const burger = document.querySelector('.burger');
        if (sidebar.classList.contains('open') &&
            !sidebar.contains(e.target) &&
            !burger.contains(e.target)) {
            sidebar.classList.remove('open');
            burger.classList.remove('move');
        }
    });

    // Fungsi untuk menutup notifikasi
    function closeNotif(notif) {
        if (notif) {
            notif.classList.remove('show');
            notif.style.display = 'none';
        }
    }

    // Jalankan saat DOM sudah siap
    document.addEventListener('DOMContentLoaded', function () {
        const notifSuccess = document.getElementById('notif-success');
        const notifError = document.getElementById('notif-error');

        // Notif sukses
        if (notifSuccess) {
            notifSuccess.classList.add('show');
            setTimeout(() => closeNotif(notifSuccess), 4000);

            const closeBtn = notifSuccess.querySelector('button');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => closeNotif(notifSuccess));
            }
        }

        // Notif error
        if (notifError) {
            notifError.classList.add('show');
            setTimeout(() => closeNotif(notifError), 4000);

            const closeBtn = notifError.querySelector('button');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => closeNotif(notifError));
            }
        }

        // Validasi NIS & nama siswa di sisi client
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const nis = document.getElementById('nis').value.trim();
                const nama = document.getElementById('nama_siswa').value.trim();

                if (!/^\d{1,11}$/.test(nis)) {
                    e.preventDefault();
                    alert("NIS hanya boleh angka dan maksimal 11 digit!");
                    return;
                }

                if (!/^[A-Za-z\s]+$/.test(nama)) {
                    e.preventDefault();
                    alert("Nama hanya boleh huruf dan spasi!");
                    return;
                }
            });
        }
    });
</script>

    
</body>
</html>
