<?php
// Koneksi ke database
require 'db.php';

// Inisialisasi pesan error dan success
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_user = $_POST['nama_user'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($nama_user) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            // Insert data ke tabel Users
            $stmt = $conn->prepare("INSERT INTO Users (nama_user, email, password, role) VALUES (?, ?, ?, 'Mahasiswa')");
            $stmt->bind_param('sss', $nama_user, $email, $password);

            if ($stmt->execute()) {
                $success = "Akun berhasil dibuat. Silakan login!";
            } else {
                $error = "Terjadi kesalahan saat membuat akun.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 10px;
            color: red;
            text-align: center;
        }
        .success {
            color: green;
        }
        .logout-container {
            margin-top: 20px;
            text-align: center;
        }
        .logout-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrasi Akun Baru</h2>
        <?php if ($error): ?>
            <div class="message"> <?= $error ?> </div>
        <?php elseif ($success): ?>
            <div class="message success"> <?= $success ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_user">Nama Lengkap</label>
                <input type="text" id="nama_user" name="nama_user" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Daftar</button>
        </form>
         <!-- Tombol Logout -->
         <div class="logout-container">
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>
    </div>
</body>
</html>
