<?php
// Konfigurasi Database
$host = 'localhost'; // Atau alamat server database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$dbname = 'sistem_pendaftaran'; // Nama database yang akan digunakan

// Membuat koneksi ke MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset untuk mencegah masalah encoding
$conn->set_charset('utf8');

// Mengembalikan koneksi jika diperlukan untuk file lain
return $conn;
?>
