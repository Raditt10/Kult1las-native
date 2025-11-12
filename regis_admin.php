<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username     = $conn->real_escape_string($_POST['username']);
    $password     = $conn->real_escape_string($_POST['password']);
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    $role         = $conn->real_escape_string($_POST['role']);
    
    if (!preg_match('/^\d{1,11}$/', $password)) {
        $error = "Password hanya terdapat angka!";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $username)) {
        $error = "Username hanya boleh huruf dan spasi!";
    } else {
    $check_query = "SELECT * FROM pengguna WHERE username = '$username'";
    $check_result = $conn->query($check_query);
   
    if ($check_result->num_rows > 0) {
        $error = "Mohon maaf, Username anda telah digunakan!";
    } else {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO pengguna(username, password, nama_lengkap, role)
                         VALUES ('$username', '$hash_password', '$nama_lengkap', '$role')";

        if ($conn->query($insert_query)) {
            $success = "Berhasil masuk!";
        } else {
            $error = "Gagal masuk: " . $conn->error;
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            padding: 20px;
            position: relative;
            background-image: url('smkn13.jpg'); /* Ganti sesuai nama file gambarmu */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('smkn13.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(15px) brightness(0.6);
            z-index: -2;
            pointer-events: none;
        }

        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 50, 0, 0.5);
            z-index: -1;
            pointer-events: none;
        }

        h1, .nav, form, footer {
            animation: fadeIn 1s ease forwards;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            color: rgb(44, 202, 107);
            margin-bottom: 30px;
            z-index: 1;
        }

        .nav {
            text-align: center;
            margin-bottom: 20px;
        }

        .nav a {
            text-decoration: none;
            color: rgb(44, 202, 107);
            font-weight: bold;
            margin: 0 10px;
        }

        .nav a:hover {
            text-decoration: underline;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
            z-index: 1;
        }

        table {
            width: 100%;
        }

        table td {
            padding: 12px;
            vertical-align: top;
        }

        label {
            font-weight: 600;
            color: #2c3e50;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: green;
            border: none;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #2e7d32;
        }

        .error, .success {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error {
            color: red;
        }

        .success {
            color: rgb(44, 202, 107);
        }

        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
            color: rgb(44, 202, 107);
            z-index: 1;
        }
    </style>
</head>
<body>

    <h1>Form Registrasi Akun Admin</h1>

    <p class="nav">
        <a href="login_admin.php">Login</a>
    </p>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="post" action="">
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
                <td><label for="nama_lengkap">Nama Lengkap:</label></td>
                <td><input type="text" id="nama_lengkap" name="nama_lengkap" required></td>
            </tr>
            <tr>
                <td><label for="role">Role:</label></td>
                <td>
                    <select id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Pembina">Pembina</option>
                        <option value="Pembina">Pelatih</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Kirim"></td>
            </tr>
        </table>
    </form>
        <script>
    document.querySelector("form").addEventListener("submit", function(e) {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    let error = "";

    if (!/^\d{1,11}$/.test(nis)) {
        error = "NIS hanya boleh angka dan maksimal 11 digit!";
    } else if (!/^[A-Za-z\s]+$/.test(nama)) {
        error = "Nama hanya boleh huruf dan spasi!";
    }

    if (error) {
        e.preventDefault(); // Cegah submit ke server
        alert(error); // Tampilkan pesan
    }
});
</script>

    <footer>
        <hr>
        <p>&copy; <?php echo date('Y'); ?> RAFADITYA SYAHPUTRA. Hak Cipta Dilindungi.</p>
    </footer>

</body>
</html>
