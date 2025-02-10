<?php
session_start();
session_destroy(); // Hapus semua sesi

// Periksa apakah ada parameter redirect
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

// Redirect ke halaman yang diinginkan
header("Location: $redirect");
exit;
?>
