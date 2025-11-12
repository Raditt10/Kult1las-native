<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Ekstrakurikuler</title>
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
        h1 {
            margin-top: 10px;
            text-align: center;
            font-size: 2.7em;
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
        main.text {
            max-width: 980px;
            margin: auto;
            background: rgba(255,255,255,0.97);
            padding: 44px 36px 36px 36px;
            border-radius: 22px;
            box-shadow: 0 12px 40px rgba(44,202,107,0.09);
            display: flex;
            flex-direction: column;
            gap: 32px;
            animation: fadeIn 1s;
        }
        .card {
            background: linear-gradient(90deg, #e0f7fa 0%, #f8f9fa 100%);
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(44,202,107,0.08);
            padding: 32px 28px;
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            animation: fadeIn 1s;
        }
        .card h3 {
            color: #388e3c;
            font-size: 1.45em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 1s;
        }
        .card p, .card ul {
            font-size: 1.08em;
            color: #222;
            animation: fadeIn 1s;
        }
        .card ul {
            margin-top: 10px;
            padding-left: 25px;
        }
        .card li {
            margin-bottom: 12px;
            padding-left: 20px;
            position: relative;
            animation: fadeIn 1s;
        }
        .card li::before {
            content: "\f058";
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 0;
            color: #43e97b;
            font-size: 1em;
        }
        .about {
            margin-top: 18px;
            display: flex;
            justify-content: center;
            gap: 22px;
            align-items: center;
            animation: fadeIn 1s;
        }
        .about a {
            text-decoration: none;
            color: #222;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.08em;
            transition: color 0.2s;
            animation: fadeIn 1s;
        }
        .about a:hover {
            color: #388e3c;
        }
        .about img {
            vertical-align: middle;
            margin-right: 6px;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 0.98em;
            padding: 10px 0;
            animation: fadeIn 1s;
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
            width: calc(100% - 20px);
            color: #222;
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
    </style>
</head>
<body>
    <h1><i class="fa-solid fa-school"></i> Beranda Ekstrakurikuler Admin</h1>
    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
            <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <a href="login_admin.php"><i class="fa-solid fa-right-to-bracket"></i>Login sebagai Admin</a>
            <a href="login_pembina.php"><i class="fa-solid fa-right-to-bracket"></i>Login sebagai Pembina</a>
            <a href="login_pelatih.php"><i class="fa-solid fa-right-to-bracket"></i>Login sebagai Pelatih</a>
        </div>
    </nav>
    <main class="text">
        <div class="card">
            <h3><i class="fa-solid fa-hand-wave"></i> Hai, Warga SMKN 13 Bandung!</h3>
            <p>Selamat datang di sistem pendaftaran ekstrakurikuler. Anda dapat mengatur/mengedit data ekstrakurikuler dan siswa yang tersedia di sekolah ini.</p>
        </div>
        <div class="card">
            <h3><i class="fa-solid fa-bullseye"></i> Fitur Admin:</h3>
            <ul>
                <li><strong>Melihat dan Mengatur data Ekskul:</strong> Mengedit, Menghapus, dan Menambahkan data Ekskul.</li>
                <li><strong>Melihat dan mengatur data Akun Siswa:</strong> Mengedit, Menghapus, dan Menambahkan Akun Siswa.</li>
                <li><strong>Melihat dan mengatur data Pendaftaran:</strong> Mengedit, Menghapus, dan Menambahkan data Pendaftaran.</li>
                <li><strong>Melihat dan mengatur data Presensi:</strong> Mengedit, Menghapus, data Presensi Ekskul Siswa.</li>
                <li><strong>Melihat dan mengatur data Akun Admin, Pembina/Pelatih Ekskul:</strong> Mengedit, Menghapus, dan Menambahkan Akun Admin, Pembina/Pelatih Ekskul.</li>
            </ul>
        </div>
        <div class="card">
            <h3><i class="fa-solid fa-list"></i> Fitur Pembina dan Pelatih:</h3>
            <ul>
                <li><strong>Melihat dan Mengatur data Ekskul:</strong> Mengedit, Menghapus, dan Menambahkan data Ekskul.</li>
                <li><strong>Melihat data Presensi:</strong> Melihat data Presensi Ekstrakurikuler Siswa.</li>
                <li><strong>Sebelum itu Kamu diharuskan Login terlebih dulu ya :)</strong></li>
            </ul>
        </div>
        <footer>
        <hr>
        <p>&copy;  <?php echo date('Y');?>  SMKN 13 BDG. Hak Cipta Dilindungi.</p>
    </footer>
    </main>
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
    </script>
</body>
</html>
