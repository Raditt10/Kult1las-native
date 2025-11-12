<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis = $_POST['nis'];
    $nama = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        // Prepare statement to prevent SQL injection
        $check_stmt = $conn->prepare("SELECT * FROM siswa WHERE nis = ?");
        $check_stmt->bind_param("s", $nis);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result && $check_result->num_rows > 0) {
            $error = "NIS sudah digunakan.";
        } else {
            $stmt = $conn->prepare("INSERT INTO siswa (nis, nama_siswa, kelas, tanggal_lahir, alamat, no_telp, email, jenis_kelamin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $nis, $nama, $kelas, $tanggal_lahir, $alamat, $no_telp, $email, $jenis_kelamin);

            if ($stmt->execute()) {
                $_SESSION['success_msg'] = "Siswa berhasil ditambahkan.";
                header("Location: admin_siswa.php");
                exit;
            } else {
                $error = "Terjadi kesalahan: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Tambah Siswa</title>
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

        input[type="text"], input[type="email"], input[type="date"], select, textarea {
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

<h1>Form Tambah Akun Siswa</h1>

<div class="container">
    <a href="admin_siswa.php">← Kembali</a>

    <?php if (isset($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="message success"><?php echo htmlspecialchars($_SESSION['success_msg']); ?></div>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <form method="post" action="">
        <table>
            <tr>
                <td><label for="nis">NIS:</label></td>
                <td><input type="text" id="nis" name="nis" required></td>
            </tr>
            <tr>
                <td><label for="nama_siswa">Nama Lengkap:</label></td>
                <td><input type="text" id="nama_siswa" name="nama_siswa" required></td>
            </tr>
            <tr>
                <td><label for="kelas">Kelas:</label></td>
                <td>
                    <select id="kelas" name="kelas" required>
                        <option value="">Pilih kelas kamu</option>
                        <option value="X RPL 1">X RPL 1</option>
                        <option value="X RPL 2">X RPL 2</option>
                        <option value="X TKJ 1">X TKJ 1</option>
                        <option value="X TKJ 2">X TKJ 2</option>
                        <option value="X KA 1">X KA 1</option>
                        <option value="X KA 2">X KA 2</option>
                        <option value="X KA 3">X KA 3</option>
                        <option value="X KA 4">X KA 4</option>
                        <option value="X KA 5">X KA 5</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="jenis_kelamin">Jenis Kelamin:</label></td>
                <td>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="tanggal_lahir">Tanggal Lahir:</label></td>
                <td><input type="date" id="tanggal_lahir" name="tanggal_lahir" required></td>
            </tr>
            <tr>
                <td><label for="alamat">Alamat:</label></td>
                <td><textarea id="alamat" name="alamat" rows="3" required></textarea></td>
            </tr>
            <tr>
                <td><label for="no_telp">Nomor HP:</label></td>
                <td><input type="text" id="no_telp" name="no_telp" required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" required></td>
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
    <p>&copy; <?php echo date('Y');?> SMKN 13 BDG. Hak Cipta Dilindungi.</p>
</footer>

</body>
</html>

