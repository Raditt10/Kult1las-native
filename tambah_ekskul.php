<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_eskul   = $conn->real_escape_string($_POST['nama_eskul']);
    $deskripsi    = $conn->real_escape_string($_POST['deskripsi']);
    $pembina      = $conn->real_escape_string($_POST['pembina']);
    $hari_kegiatan = $conn->real_escape_string($_POST['hari_kegiatan']);
    $jam_mulai    = $conn->real_escape_string($_POST['jam_mulai']);
    $jam_selesai  = $conn->real_escape_string($_POST['jam_selesai']); 
    $lokasi       = $conn->real_escape_string($_POST['lokasi']);
    $kuota        = $conn->real_escape_string($_POST['kuota']);

    $check_query = "SELECT * FROM eskul WHERE nama_eskul = '$nama_eskul'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error = "Mohon maaf, ekskul tersebut sudah ada!";
    } else {
        $sql = "INSERT INTO eskul(nama_eskul, deskripsi, pembina, hari_kegiatan, jam_mulai, jam_selesai, lokasi, kuota) 
                VALUES ('$nama_eskul', '$deskripsi', '$pembina', '$hari_kegiatan', '$jam_mulai', '$jam_selesai', '$lokasi', '$kuota')";

        if ($conn->query($sql) === TRUE) {
            $success = "Ekskul berhasil ditambahkan.";
        } else {
            $error = "Terjadi kesalahan: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Ekstrakurikuler</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            padding: 30px;
            color: #2c3e50;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            background-color: green;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: 0.3s;
        }

        a:hover {
            background-color: #2e7d32;
        }

        form table {
            width: 100%;
        }

        form table td {
            padding: 10px;
            vertical-align: top;
        }

        input[type="text"], input[type="time"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            padding: 10px 25px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #1b5e20;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #2e7d32;
        }

        .error {
            background-color: #f8d7da;
            color: #c0392b;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>

    <h1>Form Tambah Ekstrakurikuler</h1>

    <div class="container">
        <a href="admin_eskul.php">← Kembali</a>

        <?php if (isset($error)): ?>
            <div class="message error"><?= $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="message success"><?= $success; ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <table>
                <tr>
                    <td><label for="nama_eskul">Nama Ekstrakurikuler:</label></td>
                    <td><input type="text" id="nama_eskul" name="nama_eskul" required></td>
                </tr>
                <tr>
                    <td><label for="deskripsi">Deskripsi:</label></td>
                    <td><textarea id="deskripsi" name="deskripsi" required></textarea></td>
                </tr>
                <tr>
                    <td><label for="pembina">Pembina:</label></td>
                    <td><input type="text" id="pembina" name="pembina" required></td>
                </tr>
                <tr>
                    <td><label for="hari_kegiatan">Hari Kegiatan:</label></td>
                    <td><input type="text" id="hari_kegiatan" name="hari_kegiatan" required></td>
                </tr>
                <tr>
                    <td><label for="jam_mulai">Jam Mulai:</label></td>
                    <td><input type="time" id="jam_mulai" name="jam_mulai" required></td>
                </tr>
                <tr>
                    <td><label for="jam_selesai">Jam Selesai:</label></td>
                    <td><input type="time" id="jam_selesai" name="jam_selesai" required></td>
                </tr>
                <tr>
                    <td><label for="lokasi">Lokasi Kegiatan:</label></td>
                    <td><textarea id="lokasi" name="lokasi" required></textarea></td>
                </tr>
                <tr>
                    <td><label for="kuota">Kuota:</label></td>
                    <td><input type="number" id="kuota" name="kuota" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Kirim"></td>
                </tr>
            </table>
        </form>
    </div>

    <footer>
        <hr>
        <p>&copy;  <?php echo date('Y');?>  SMKN 13 BDG. Hak Cipta Dilindungi.</p>
    </footer>

</body>
</html>
