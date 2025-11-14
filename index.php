<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/index.css">
</head>
<body>
    <h1><i class="fa-solid fa-school"></i> Sistem Ekstrakurikuler SMKN 13 Bandung</h1>


    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
            <a href="siswa/index.php"><i class="fa-solid fa-user-graduate"></i>Siswa</a>
            <a href="index_admin.php"><i class="fa-solid fa-user-tie"></i>Admin</a>
            <a href="index_admin.php"><i class="fa-solid fa-chalkboard-user"></i> Pembina</a>
            <a href="index_admin.php"><i class="fa-solid fa-dumbbell"></i> Pelatih</a>
            <a href="tentang_kami.html"><i class="fa-solid fa-circle-info"></i>Tentang Kami</a>
        </div>
    </nav>

    <main class="text">
        <div class="card">
            <h3><i class="fa-solid fa-hand-wave"></i> Hai, Warga SMKN 13 Bandung!</h3>
            <p>Selamat datang di sistem pendaftaran ekstrakurikuler <b>SMKN 13 BANDUNG.</b></p>
        </div>
        <div class="card">
            <h3><i class="fa-solid fa-bullseye"></i> Kegunaan :</h3>
            <p>Website ini dirancang sebagai sistem informasi manajemen kegiatan ekstrakurikuler di lingkungan sekolah. Melalui platform ini, siswa dapat melihat daftar ekstrakurikuler yang tersedia dan presensi Ekskul yang telah didaftari, mengetahui detail seperti jadwal kegiatan, pembina, dan kuota, serta melakukan pendaftaran secara langsung secara online. Admin sekolah memiliki akses untuk mengelola data siswa, ekstrakurikuler, dan pendaftaran yang masuk. Dengan adanya sistem ini, proses administrasi kegiatan ekstrakurikuler menjadi lebih terstruktur, efisien, dan transparan, serta mendorong partisipasi aktif siswa dalam pengembangan bakat dan minat mereka di luar kegiatan akademik.</p>
        </div>
        <div class="card">
            <h3><i class="fa-solid fa-rocket"></i> Misi :</h3>
            <ul>
                <li>Meningkatkan Aksesibilitas Informasi Menyediakan informasi lengkap dan mudah diakses mengenai kegiatan ekstrakurikuler bagi seluruh siswa dan warga sekolah.</li>
                <li>Mendukung Pengembangan Minat dan Bakat Siswa Memfasilitasi siswa dalam memilih dan mengikuti kegiatan yang sesuai dengan potensi dan minat mereka di luar bidang akademik.</li>
                <li>Meningkatkan Efisiensi Administrasi Menyederhanakan proses pendaftaran, pengelolaan data, dan pelaporan kegiatan ekstrakurikuler melalui sistem digital.</li>
                <li>Mendorong Partisipasi Aktif Membangun antusiasme dan keterlibatan siswa dalam kegiatan sekolah dengan menyediakan platform yang interaktif dan informatif.</li>
                <li>Menjaga Transparansi dan Akuntabilitas Memastikan semua proses pendaftaran dan pengelolaan kegiatan dilakukan secara transparan dan dapat dipantau oleh pihak yang berwenang.</li>
                <li>Memperkuat Komunikasi Antar Pihak Menjadi jembatan komunikasi antara siswa, pembina, pelatih, dan pihak sekolah dalam mendukung keberhasilan program ekstrakurikuler.</li>
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
