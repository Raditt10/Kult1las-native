<?php
session_start();
include 'koneksi.php';

$error_msg = '';
$koneksi = mysqli_connect("localhost", "root", "", "db_eskul");
$success_msg = $_SESSION['success_msg'] ?? null;

// Ambil dari cookie untuk prefill form
$saved_user = isset($_COOKIE['admin_username']) ? $_COOKIE['admin_username'] : '';
$saved_pass = isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM pengguna WHERE username = '$username'";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            // Set session login
            $_SESSION['id_pengguna'] = $row['id_pengguna'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['login_success'] = true; 
            $_SESSION['success_msg'] = "Anda berhasil login!";

            // Simpan cookie username dan password asli (untuk prefill, bukan login)
           setcookie("admin_username", $username, time() + (2 * 60 * 60), "/");
           setcookie("admin_password", $password, time() + (2 * 60 * 60), "/");
           header("Location: panel_admin.php");
           exit;
        } else {
             $error_msg = 'Username atau password salah!';
        }
    } else {
        $error_msg = 'Username tidak ditemukan!';
    }
}
$saved_user = isset($_COOKIE['admin_username']) ? $_COOKIE['admin_username'] : '';
$saved_pass = isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
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
        label {
            font-weight: 600;
            color: #2c3e50;
        }
        input[type="text"], input[type="password"], select {
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
        input[type="text"]:hover, input[type="password"]:hover, select:hover,
        input[type="text"]:focus, input[type="password"]:focus, select:focus {
            border-color: #43e97b;
            box-shadow: 0 0 0 3px #43e97b88;
            background: #fff;
            color: #222;
        }
        input[type="text"]::placeholder, input[type="password"]::placeholder {
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
        .input-group {
            position: relative;
            width: 100%;
        }
        .input-group input[type="password"] {
            width: 100%;
            padding-right: 40px;
            height: 44px;
            line-height: 44px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            color: #222;
            box-shadow: 0 2px 8px rgba(44,202,107,0.07);
        }
        .input-group input[type="password"]:hover,
        .input-group input[type="password"]:focus {
            border-color: #43e97b;
            box-shadow: 0 0 0 3px #43e97b88;
        }
        .input-group .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            display: flex;
            align-items: center;
            height: 44px;
        }
        .input-group .toggle-password i {
            font-size: 1em;
            color: #43e97b;
            height: 12px;
            transition: color 0.2s;
        }
        .input-group .toggle-password:hover i {
            color: #388e3c;
        }
    </style>
</head>
<body>
    <h1><i class="fa-solid fa-user-shield"></i> Login Admin</h1>
    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
            <a href="index_admin.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </nav>
    <div class="container">
    <?php if ($error_msg): ?>
<div id="notif-error" class="notif-error">
  <span><?= htmlspecialchars($error_msg) ?></span>
  <button onclick="closeNotif()" aria-label="Close notification">&times;</button>
</div>
<?php endif; ?>
    <form method="post" action="">
        <table>
            <tr>
                <td><label for="username">Username:</label></td>
            </tr>
            <tr>
                <td><input type="text" id="username" name="username" placeholder="Masukkan username" autocomplete="off" value="<?= htmlspecialchars($saved_user); ?>" required></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
            </tr>
            <tr>
                <td style="position:relative; min-width:180px;">
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="off" value="<?= htmlspecialchars($saved_pass); ?>" required>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="Login"></td>
            </tr>
        </table>
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
            <p class="footer-copyright">&copy; <?php echo date('Y');?> RAFADITYA SYAHPUTRA. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const burger = document.querySelector('.burger');
        sidebar.classList.toggle('open');
        burger.classList.toggle('move', sidebar.classList.contains('open'));
    }
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const burger = document.querySelector('.burger');
        if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !burger.contains(e.target)) {
            sidebar.classList.remove('open');
            burger.classList.remove('move');
        }
    });
    function closeNotif() {
        const notif = document.getElementById('notif-error');
        if (notif) {
            notif.classList.remove('show');
        }
    }
    window.addEventListener('DOMContentLoaded', () => {
        const notif = document.getElementById('notif-error');
        if (notif) {
            setTimeout(() => notif.classList.add('show'), 100);
            setTimeout(() => {
                notif.classList.remove('show');
            }, 4000);
        }
    });
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
    </script>
</body>
</html>