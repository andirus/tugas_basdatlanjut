<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id_mahasiswa'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id_mahasiswa = $_GET['id_mahasiswa'];
$query = "SELECT * FROM Mahasiswa WHERE id_mahasiswa = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_mahasiswa);
$stmt->execute();
$result = $stmt->get_result();
$mahasiswa = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_mahasiswa'];
    $email = $_POST['email'];
    $status_pendaftaran = $_POST['status_pendaftaran'];

    $update_query = "UPDATE Mahasiswa SET nama_mahasiswa = ?, email = ?, status_pendaftaran = ? WHERE id_mahasiswa = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("sssi", $nama, $email, $status_pendaftaran, $id_mahasiswa);
    $stmt_update->execute();
    $stmt_update->close();

    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef2f3; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; }
        input, select, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Mahasiswa</h2>
        <form method="POST">
            <label>Nama:</label>
            <input type="text" name="nama_mahasiswa" value="<?php echo htmlspecialchars($mahasiswa['nama_mahasiswa']); ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($mahasiswa['email']); ?>" required>
            <label>Status Pendaftaran:</label>
            <select name="status_pendaftaran" required>
                <option value="Menunggu" <?php if ($mahasiswa['status_pendaftaran'] == 'Menunggu') echo 'selected'; ?>>Menunggu</option>
                <option value="Diterima" <?php if ($mahasiswa['status_pendaftaran'] == 'Diterima') echo 'selected'; ?>>Diterima</option>
                <option value="Ditolak" <?php if ($mahasiswa['status_pendaftaran'] == 'Ditolak') echo 'selected'; ?>>Ditolak</option>
            </select>
            <button type="submit">Simpan</button>
        </form>
        <a href="admin_dashboard.php" style="display: block; text-align: center; margin-top: 20px;">Batal</a>
    </div>
</body>
</html>
