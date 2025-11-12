<?php
include 'koneksi.php';
session_start();

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = "SELECT * FROM eskul WHERE id_eskul = '$id'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $eskul = $result->fetch_assoc();
    } else {
        die("Eskul tidak ditemukan.");
    }
} else {
    die("ID tidak valid.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_eskul    = $conn->real_escape_string($_POST['nama_eskul']);
    $deskripsi     = $conn->real_escape_string($_POST['deskripsi']);
    $pembina       = $conn->real_escape_string($_POST['pembina']);
    $hari_kegiatan = $conn->real_escape_string($_POST['hari_kegiatan']);
    $jam_mulai     = $conn->real_escape_string($_POST['jam_mulai']);
    $jam_selesai   = $conn->real_escape_string($_POST['jam_selesai']);
    $lokasi        = $conn->real_escape_string($_POST['lokasi']);
    $kuota         = $conn->real_escape_string($_POST['kuota']);

    $update = "UPDATE eskul SET 
        nama_eskul='$nama_eskul',
        deskripsi='$deskripsi',
        pembina='$pembina',
        hari_kegiatan='$hari_kegiatan',
        jam_mulai='$jam_mulai',
        jam_selesai='$jam_selesai',
        lokasi='$lokasi',
        kuota='$kuota'
        WHERE id_eskul='$id'";

    if ($conn->query($update)) {
        $_SESSION['success_msg'] = "Data berhasil diperbarui.";
            header("Location: admin_eskul.php")
        ;    exit;
    } else {
        $_SESSION['error_msg'] = "Gagal memperbarui data: " . $conn->error;
       header("Location: admin_eskul.php");
            exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Ekstrakurikuler</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px grey;

        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="time"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #2e7d32;
        }

        .message {
            margin-bottom: 15px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
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
    <div class="container">
        <h2>Edit Data Ekstrakurikuler</h2>

        <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>

        <form method="POST">
            <label for="nama_eskul">Nama Ekstrakurikuler:</label>
            <input type="text" name="nama_eskul" id="nama_eskul" value="<?= htmlspecialchars($eskul['nama_eskul']); ?>" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" rows="3"><?= htmlspecialchars($eskul['deskripsi']); ?></textarea>

            <label for="pembina">Pembina:</label>
            <input type="text" name="pembina" id="pembina" value="<?= htmlspecialchars($eskul['pembina']); ?>" required>

            <label for="hari_kegiatan">Hari Kegiatan:</label>
            <input type="text" name="hari_kegiatan" id="hari_kegiatan" value="<?= htmlspecialchars($eskul['hari_kegiatan']); ?>" required>

            <label for="jam_mulai">Jam Mulai:</label>
            <input type="time" name="jam_mulai" id="jam_mulai" value="<?= htmlspecialchars($eskul['jam_mulai']); ?>">

            <label for="jam_selesai">Jam Selesai:</label>
            <input type="time" name="jam_selesai" id="jam_selesai" value="<?= htmlspecialchars($eskul['jam_selesai']); ?>">

            <label for="lokasi">Lokasi:</label>
            <textarea name="lokasi" id="lokasi" rows="2"><?= htmlspecialchars($eskul['lokasi']); ?></textarea>

            <label for="kuota">Kuota:</label>
            <input type="number" name="kuota" id="kuota" value="<?= htmlspecialchars($eskul['kuota']); ?>" required>

            <input type="submit" value="Update">
        </form>

        <a href="admin_eskul.php">← Kembali ke Daftar Ekskul</a>
    </div>

    <footer>
        <hr>
        <p>&copy;  <?php echo date('Y');?>  SMKN 13 BDG. Hak Cipta Dilindungi.</p>
    </footer>
</body>
</html>
