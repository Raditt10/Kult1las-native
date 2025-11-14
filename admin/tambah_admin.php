<?php
include 'koneksi.php';
session_start();

// Hanya admin yang bisa mengakses
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

// Proses tambah admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Validasi role
    $allowed_roles = ['admin', 'pembina', 'pelatih'];
    if (!in_array($role, $allowed_roles)) {
        $error = "Role tidak valid.";
    } elseif (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong.";
    } else {
        // Cek apakah username sudah digunakan ??
        $stmt = $conn->prepare("SELECT id_pengguna FROM pengguna WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO pengguna (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed, $role);
            if ($stmt->execute()) {
                $success = "Akun admin berhasil ditambahkan.";
            } else {
                $error = "Gagal menambahkan admin: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
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

<h1>Form Tambah Akun</h1>

<div class="container">
    <a href="kelola_admin.php">← Kembali</a>

    <?php if (isset($error)): ?>
        <div class="message error"><?= $error; ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="message success"><?= $success; ?></div>
    <?php endif; ?>

    <form method="POST">
        <table>
            <tr>
                <td><label for="username">Username:</label></td>
                <td><input type="text" id="username" name="username" required></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" id="password" name="password" required></td>
            </tr>
            <tr>
                <td><label for="role">Role:</label></td>
                <td>
                    <select id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="pembina">Pembina</option>
                        <option value="pelatih">Pelatih</option>
                    </select>
                </td>
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
    <p>&copy; <?= date('Y'); ?> SMKN 13 BDG. Hak Cipta Dilindungi.</p>
</footer>

</body>
</html>
