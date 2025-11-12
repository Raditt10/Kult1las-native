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
$success_msg = '';
$error_msg = '';

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_siswa']);
    $kelas = trim($_POST['kelas']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = trim($_POST['no_telp']);
    $alamat = trim($_POST['alamat']);

    $stmt = $conn->prepare("UPDATE siswa SET nama_siswa=?, kelas=?, jenis_kelamin=?, no_telp=?, alamat=? WHERE id_siswa=?");
    $stmt->bind_param("sssssi", $nama, $kelas, $jenis_kelamin, $no_telp, $alamat, $id_siswa);

    if ($stmt->execute()) {
        $success_msg = "Kamu berhasil Mengupdate Profile Kamu!";
        // Refresh data
        $stmt = $conn->prepare("SELECT * FROM siswa WHERE id_siswa = ?");
        $stmt->bind_param("i", $id_siswa);
        $stmt->execute();
        $result = $stmt->get_result();
        $siswa = $result->fetch_assoc();
    } else {
        $error_msg = "Gagal update profil!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil Siswa</title>
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
        .burger.move {
            left: 10px;
            background: rgba(67,233,123,0.18);
        }
        .burger i {
            color: #fff;
            font-size: 1.6em;
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
            margin-top: 24px;
            padding: 12px 24px;
            font-size: 1.1em;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
            transition: background 0.2s, transform 0.2s;
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
    </style>
</head>
<body>
    <div class="burger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
     <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
            <a href="profile_siswa.php"><i class="fa-solid fa-arrow-left"></i>Kembali</a>
            <a href="logout_siswa.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            <a href="daftar.php"><i class="fa-solid fa-list"></i> Daftar Ekskul</a>
            <a href="presensi_siswa.php"><i class="fa-solid fa-calendar-check"></i> Presensi</a>
    </nav>

    <?php if ($success_msg): ?>
<div id="notif-success" class="notif-success show">
  <span><?= htmlspecialchars($success_msg) ?></span>
  <button onclick="closeNotif()" aria-label="Close notification">&times;</button>
</div>
<?php endif; ?>

<?php if ($error_msg): ?>
        <div class="notif-error show" id="notif-error">
            <button onclick="closeNotif()"><i class="fa-solid fa-xmark"></i></button>
            <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <div class="profile-card">
        <h2>Edit Profil Siswa</h2>

        <div class="profile-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="profile-title"><?= htmlspecialchars($siswa['nama_siswa']); ?></div>

        <form method="POST" enctype="multipart/form-data">
            <label for="nama_siswa">Nama Lengkap:</label>
            <input type="text" name="nama_siswa" id="nama_siswa" value="<?= htmlspecialchars($siswa['nama_siswa']) ?>" required>

            <label for="kelas">Kelas:</label>
            <input type="text" name="kelas" id="kelas" value="<?= htmlspecialchars($siswa['kelas']) ?>" required>

            <label for="jenis_kelamin">Jenis Kelamin:</label>
           <select name="jenis_kelamin" id="jenis_kelamin" required>
             <option value="L" <?= $siswa['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
             <option value="P" <?= $siswa['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
           </select>

            <label for="no_telp">No. HP:</label>
            <input type="text" name="no_telp" id="no_telp" value="<?= htmlspecialchars($siswa['no_telp']) ?>" required>

            <label for="alamat">Alamat:</label>
            <textarea name="alamat" id="alamat" rows="3" required><?= htmlspecialchars($siswa['alamat']) ?></textarea>

            <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
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
        }
    });

      // Ripple effect for .btn-auth buttons
  document.querySelectorAll('.btn-auth').forEach(btn => {
    btn.addEventListener('click', function(e) {
      const circle = document.createElement('span');
      circle.className = 'ripple';
      const rect = btn.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      circle.style.width = circle.style.height = size + 'px';
      circle.style.left = (e.clientX - rect.left - size/2) + 'px';
      circle.style.top = (e.clientY - rect.top - size/2) + 'px';
      btn.appendChild(circle);
      setTimeout(() => circle.remove(), 600);
    });
  });

function closeNotif() {
  const notif = document.getElementById("notif-success");
  if (notif) {
    notif.classList.remove("show");
    notif.style.display = 'none';
    notif.innerHTML = '';
    localStorage.setItem('notifSuccessClosed', '1');
  }
}

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

// Auto-close dalam 4 detik
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
</body>
</html>