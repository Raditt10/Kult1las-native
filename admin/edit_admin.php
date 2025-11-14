<?php
include 'koneksi.php';
session_start();

// Cek login dan role
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

// Cek ID pengguna dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak ditemukan atau tidak valid!";
    exit();
}

$id = (int) $_GET['id'];

// Ambil data pengguna
$stmt = $conn->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Data tidak ditemukan!";
    exit();
}

$row = $result->fetch_assoc();

// Tangani jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Validasi role
    $allowed_roles = ['admin', 'pembina', 'pelatih'];
    if (!in_array($role, $allowed_roles)) {
        echo "Role tidak valid!";
        exit();
    }

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE pengguna SET username = ?, password = ?, role = ? WHERE id_pengguna = ?");
        $stmt->bind_param("sssi", $username, $hashed, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE pengguna SET username = ?, role = ? WHERE id_pengguna = ?");
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Data berhasil diperbarui.";
        header("Location: kelola_admin.php");
    } else {
        $_SESSION['error_msg'] = "Gagal memperbarui data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin</title>
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
            background: #ffffff;
            padding: 40px 50px;
            max-width: 480px;
            margin: 80px auto 32px auto;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(44,202,107,0.13);
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
        form label {
            display: block;
            margin-top: 20px;
            font-size: 1.08em;
            font-weight: 500;
            color: #388e3c;
        }
        form input, select {
            width: 100%;
            padding: 14px 16px;
            font-size: 1.08em;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #43e97b55;
            transition: 0.3s;
            background: #f8f9fa;
            color: #222;
        }
        form input:focus, select:focus {
            border-color: #43e97b;
            outline: none;
            background: #e0f7fa;
        }
        .btn {
            margin-top: 30px;
            padding: 16px;
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
            position: relative;
            overflow: hidden;
        }
        .btn:hover {
            background: linear-gradient(90deg, #388e3c 0%, #2ecc71 100%);
            transform: scale(1.04) translateY(-2px);
        }
        .btn:active {
            transform: scale(0.97);
        }
        .btn::after {
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
        .btn:active::after {
            width: 180%;
            height: 180%;
            transition: width 0.2s, height 0.2s;
        }
        @media (max-width: 700px) {
            .form-container {
                padding: 18px 12px;
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
        <div class="sidebar-logo"><i class="fa-solid fa-school"></i> Ekskul 13</div>
        <div class="sidebar-nav">
            <a href="panel_admin.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <a href="logout_admin.php" onclick="return confirm('Yakin ingin logout?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>

    <div class="form-container">
        <h2>Edit Akun</h2>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($row['username']) ?>" required>

            <label for="password">Password (biarkan kosong jika tidak ingin diubah):</label>
            <input type="password" name="password" id="password" placeholder="******">

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="pembina" <?= $row['role'] === 'pembina' ? 'selected' : '' ?>>Pembina</option>
                <option value="pelatih" <?= $row['role'] === 'pelatih' ? 'selected' : '' ?>>Pelatih</option>
            </select>

            <button type="submit" class="btn">Simpan Perubahan</button>
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