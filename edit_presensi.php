<?php
include 'koneksi.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo '
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }
        .akses-ditolak {
            display: inline-block;
            background-color: #ff4d4d;
            color: white;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-size: 1.5em;
        }
    </style>
    <div class="akses-ditolak">🚫 Akses Ditolak. Hanya admin yang dapat mengedit data ini.</div>';
    exit();
}

// Ambil ID presensi dari parameter URL
$id_presensi = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data presensi
$query = "SELECT p.*, s.nama_siswa, e.nama_eskul 
          FROM presensi p
          JOIN siswa s ON p.id_siswa = s.id_siswa
          JOIN eskul e ON p.id_eskul = e.id_eskul
          WHERE p.id_presensi = $id_presensi";

$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    echo "Data tidak ditemukan.";
    exit;
}

$data = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $status_hadir = $_POST['status_hadir'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];

    $update = "UPDATE presensi 
               SET tanggal='$tanggal', status_hadir='$status_hadir', status='$status', catatan='$catatan'
               WHERE id_presensi=$id_presensi";

    if ($conn->query($update)) {
       $_SESSION['success_msg'] = "Data presensi berhasil diperbarui.";
            header("Location: admin_presensi.php");    
            exit;
    } else {
        $_SESSION['error_msg'] = "Gagal memperbarui data presensi: " . $conn->error;
       header("Location: admin_presensi.php");
            exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Presensi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 40px;
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            color: #2c3e50;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
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

        input[type="text"], input[type="date"], select, textarea {
            width: 100%;
            margin-top: 8px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid grey;
            font-size: 1em;
        }

        textarea {
            resize: vertical;
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
            color: white;
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

<h2>Edit Data Presensi Ekskul</h2>

<form method="post">
    <label>Nama Siswa:</label>
    <input type="text" value="<?= htmlspecialchars($data['nama_siswa']); ?>" readonly>

    <label>Ekstrakurikuler:</label>
    <input type="text" value="<?= htmlspecialchars($data['nama_eskul']); ?>" readonly>

    <label for="tanggal">Tanggal Presensi:</label>
    <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($data['tanggal']); ?>" required>

    <label for="status_hadir">Status Kehadiran:</label>
    <select name="status_hadir" id="status_hadir" required>
        <option value="Hadir" <?= $data['status_hadir'] === 'Hadir' ? 'selected' : ''; ?>>Hadir</option>
        <option value="Izin" <?= $data['status_hadir'] === 'Izin' ? 'selected' : ''; ?>>Izin</option>
        <option value="Sakit" <?= $data['status_hadir'] === 'Sakit' ? 'selected' : ''; ?>>Sakit</option>
        <option value="Alpha" <?= $data['status_hadir'] === 'Alpha' ? 'selected' : ''; ?>>Alpha</option>
    </select>

    <label for="status">Status Pendaftaran:</label>
    <select name="status" id="status" required>
        <option value="tunda" <?= $data['status'] === 'tunda' ? 'selected' : ''; ?>>Tunda</option>
        <option value="Diterima" <?= $data['status'] === 'Diterima' ? 'selected' : ''; ?>>Diterima</option>
        <option value="Ditolak" <?= $data['status'] === 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
    </select>

    <label for="catatan">Catatan:</label>
    <textarea name="catatan" id="catatan" rows="4"><?= htmlspecialchars($data['catatan']); ?></textarea>

    <input type="submit" value="Simpan Perubahan">
</form>

<div style="text-align: center;">
    <a href="admin_presensi.php">← Kembali ke Data Presensi</a>
</div>

    <footer>
        <hr>
        <p>&copy;  <?php echo date('Y');?>  SMKN 13 BDG. Hak Cipta Dilindungi.</p>
    </footer>

</body>
</html>
