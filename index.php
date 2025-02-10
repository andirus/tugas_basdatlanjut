<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pendaftaran Mahasiswa Baru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('3.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            width: 90%;
            padding: 10px 15px;
            margin: 10px 0;
            text-align: center;
            color: #fff;
            background-color:rgb(7, 10, 219);
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .button:hover {
            background-color:rgb(2, 6, 63);
        }
        .info {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Portal Pendaftaran Mahasiswa Baru</h1>
        <a href="login.php" class="button">Login</a>
        <a href="register.php" class="button">Membuat Akun Baru</a>
        <div class="info">
            <p>Selamat datang di portal pendaftaran mahasiswa baru. Silakan login atau membuat akun baru untuk melanjutkan.</p>
        </div>
    </div>
</body>
</html>
