<?php
session_start(); // Memulai sesi

include('db.php'); // Menyertakan file koneksi database

// Mengecek apakah form login sudah disubmit
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menghindari SQL Injection dengan menggunakan prepared statements
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Mengecek apakah ada data yang ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Menyimpan informasi user ke session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];

        // Mengarahkan ke halaman dashboard sesuai role
        if ($user['role'] == 'Mahasiswa') {
            header("Location: mahasiswa_dashboard.php");
        } elseif ($user['role'] == 'Petugas') {
            header("Location: petugas_dashboard.php");
        } elseif ($user['role'] == 'Admin') {
            header("Location: admin_dashboard.php");
        }
        exit();
    } else {
        // Jika login gagal
        $error_message = "Email atau password salah!";
    }

    $stmt->close(); // Menutup prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('1.jpg'); /* Ganti dengan path gambar background */
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .form-button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Sistem Pendaftaran Kuliah Online</h2>

        <?php
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" class="form-input" placeholder="Email" required>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
            <button type="submit" name="login" class="form-button">Login</button>
        </form>
    </div>

</body>
</html>
