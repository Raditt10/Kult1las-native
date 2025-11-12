<?php
include 'koneksi.php';
session_start();

// Cek login admin
if (!isset($_SESSION['id_pengguna']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login_admin.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];
$success = $error = "";

// Ambil data admin
$stmt = $conn->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    header("Location: panel_admin.php");
    exit();
}
$admin = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi
    if (empty($nama_lengkap) || empty($username)) {
        $error = "Nama dan username tidak boleh kosong.";
    } elseif (!empty($password) && $password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE pengguna SET nama_lengkap=?, username=?, password=? WHERE id_pengguna=?");
            $stmt->bind_param("sssi", $nama_lengkap, $username, $hash, $id_pengguna);
        } else {
            $stmt = $conn->prepare("UPDATE pengguna SET nama_lengkap=?, username=? WHERE id_pengguna=?");
            $stmt->bind_param("ssi", $nama_lengkap, $username, $id_pengguna);
        }
        if ($stmt->execute()) {
            header("Location: profile_admin.php");
            exit;
            $_SESSION['success_msg'] = "Profil berhasil diperbarui.";
        } else {
                header("profile_admin.php");
            exit;
            $_SESSION['error_msg'] = "Gagal memperbarui profil: " . $conn->error;
        }
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7fa 0%, #f8f9fa 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 50px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,202,107,0.13);
            padding: 38px 38px 28px 38px;
        }
        h2 {
            text-align: center;
            color: #43e97b;
            margin-bottom: 24px;
            font-size: 2em;
            letter-spacing: 1px;
            font-weight: 700;
        }
        form label {
            font-weight: 600;
            color: #388e3c;
            display: block;
            margin-bottom: 6px;
        }
        form input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 1px solid #b2dfdb;
            border-radius: 8px;
            font-size: 1em;
            background: #f8f9fa;
        }
        .btn {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 38px;
            font-weight: 600;
            font-size: 1.08em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(44,202,107,0.10);
            transition: background 0.2s, transform 0.2s;
            margin-top: 8px;
        }
        .btn:hover {
            background: linear-gradient(90deg, #388e3c 0%, #2ecc71 100%);
            transform: scale(1.05);
        }
        .msg-success {
            background: #e0f7fa;
            color: #2ecc71;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            text-align: center;
            font-weight: 600;
        }
        .msg-error {
            background: #ffebee;
            color: #e74c3c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            text-align: center;
            font-weight: 600;
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
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fa-solid fa-user-gear"></i> Edit Profil Admin</h2>
        <?php if ($success): ?>
            <div class="msg-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="msg-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($admin['nama_lengkap']) ?>" required>

            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($admin['username']) ?>" required>

            <label for="password">Password Baru (kosongkan jika tidak ingin ganti)</label>
            <input type="password" name="password" id="password" autocomplete="new-password">

            <label for="konfirmasi">Konfirmasi Password Baru</label>
            <input type="password" name="konfirmasi" id="konfirmasi" autocomplete="new-password">

            <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
        </form>
        <div class="back">
            <a href="panel_admin.php"><i class="fa-solid fa-arrow-left"></i> Kembali ke Panel Admin</a>
        </div>

        
    </div>

        <footer>
        <hr>
        <p>&copy;  <?php echo date('Y');?>  SMKN 13 BDG. Hak Cipta Dilindungi.</p>
    </footer>
        </body>
        </html>