<?php
include 'koneksi.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak.";
    exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idPendaftaran = (int)$_GET['id'];

    $sql = "SELECT p.*, s.nama_siswa, e.nama_eskul 
            FROM pendaftaran_eskul p
            JOIN siswa s ON p.id_siswa = s.id_siswa
            JOIN eskul e ON p.id_eskul = e.id_eskul
            WHERE p.id_pendaftaran = $idPendaftaran";
    
    $result = $conn->query($sql);
    $pendaftaran = $result->fetch_assoc();

    if (!$pendaftaran) {
        die("Data tidak ditemukan.");
    }
} else {
    die("ID tidak valid.");
}

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statusBaru = $conn->real_escape_string($_POST['status']);

    $update = "UPDATE pendaftaran_eskul SET status = '$statusBaru' WHERE id_pendaftaran = $idPendaftaran";
    if ($conn->query($update)) {
       $_SESSION['success_msg'] = "Status Pendaftaran berhasil diperbarui.";
       header("Location: admin_daftar.php");
        // Refresh data
          $pendaftaran['status'] = $statusBaru;
     exit;
    } else {
     $_SESSION['error_msg'] = "Gagal menghapus data: " . $conn->error;
     header("Location: admin_daftar.php");
     exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Status Pendaftaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 40px;
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            padding: 30px;
            color: #2c3e50;
        }

        h2 {
            color: #2c3e50;
            text-align:center;
        }

        form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 4px 12px grey;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        select, textarea {
            width: 100%;
            margin-top: 8px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid grey;
            font-size: 1em;
        }

        textarea[readonly] {
            background: #f0f0f0;
        }

        .message {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
        
        input[type="submit"] {
            margin-top: 15px;
            background-color: green;
            color:  white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #388e3c;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: green;
        }

        a:hover {
            text-decoration: underline;
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

    <h2>Edit Status Pendaftaran Ekskul</h2>

    <form method="POST">
        <label>Nama Siswa:</label>
        <p><?= htmlspecialchars($pendaftaran['nama_siswa']); ?></p>

        <label>Ekskul:</label>
        <p><?= htmlspecialchars($pendaftaran['nama_eskul']); ?></p>

        <label>Tanggal Daftar:</label>
        <p><?= htmlspecialchars($pendaftaran['tanggal_daftar']); ?></p>

        <label>Keterangan:</label>
        <textarea readonly style="width: 50%; max-width: 400px;"><?= htmlspecialchars($pendaftaran['keterangan']); ?></textarea>

        <label>Status:</label>
        <select name="status" required>
            <option value="tunda" <?= $pendaftaran['status'] === 'tunda' ? 'selected' : ''; ?>>Tunda</option>
            <option value="Diterima" <?= $pendaftaran['status'] === 'Diterima' ? 'selected' : ''; ?>>Diterima</option>
            <option value="Ditolak" <?= $pendaftaran['status'] === 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
        </select>

        <input type="submit" value="Update Status">
    </form>

    <div style="text-align: center;">
    <a href="admin_daftar.php">← Kembali ke Daftar Pendaftaran</a>
    </div>
    
    <footer>
        <hr>
        <p>&copy;  <?php echo date('Y');?>  SMKN 13 BDG. Hak Cipta Dilindungi.</p>
    </footer>
</body>
</html>
