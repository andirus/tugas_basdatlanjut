<?php
session_start();
include 'db.php';

// Pastikan pengguna adalah petugas
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Petugas') {
    header("Location: login.php");
    exit;
}

// Ambil status filter (jika ada)
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'Menunggu';

// Query berdasarkan filter
$query = "SELECT * FROM Mahasiswa WHERE status_pendaftaran = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $status_filter);
$stmt->execute();
$result = $stmt->get_result();

// Proses verifikasi pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $status_pendaftaran = $_POST['status_pendaftaran'];

    $update_query = "UPDATE Mahasiswa SET status_pendaftaran = ? WHERE id_mahasiswa = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $status_pendaftaran, $id_mahasiswa);

    if ($stmt->execute()) {
        $success_message = "✅ Status pendaftaran berhasil diperbarui.";
        header("Location: petugas_dashboard.php?status=$status_filter"); // Refresh halaman dengan filter tetap
        exit;
    } else {
        $error_message = "❌ Gagal memperbarui status.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 30px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .message {
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .filter {
            margin-bottom: 15px;
        }
        .filter select {
            padding: 8px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        select, button {
            padding: 8px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
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
    <script>
        function applyFilter() {
            let status = document.getElementById("statusFilter").value;
            window.location.href = "petugas_dashboard.php?status=" + status;
        }
    </script>
</head>
<body>

    <div class="container">
        <h2>Verifikasi Pendaftaran Mahasiswa</h2>

        <!-- Filter Mahasiswa -->
        <div class="filter">
            <label for="statusFilter">Filter Status: </label>
            <select id="statusFilter" onchange="applyFilter()">
                <option value="Menunggu" <?php echo ($status_filter == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                <option value="Diterima" <?php echo ($status_filter == 'Diterima') ? 'selected' : ''; ?>>Diterima</option>
                <option value="Ditolak" <?php echo ($status_filter == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
            </select>
        </div>

        <?php if (isset($success_message)) echo "<p class='message success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='message error'>$error_message</p>"; ?>

        <table>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Program Studi</th>
                <th>Dokumen</th>
                <th>Verifikasi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                <td><?php echo htmlspecialchars($row['program_studi']); ?></td>
                <td><a href="uploads/<?php echo $row['dokumen']; ?>" target="_blank">📄 Lihat Dokumen</a></td>
                <td>
                    <?php if ($status_filter == 'Menunggu') { ?>
                        <form method="POST">
                            <input type="hidden" name="id_mahasiswa" value="<?php echo $row['id_mahasiswa']; ?>">
                            <select name="status_pendaftaran">
                                <option value="Diterima">Diterima</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                            <button type="submit">✔️ Verifikasi</button>
                        </form>
                    <?php } else { ?>
                        <span><?php echo $row['status_pendaftaran']; ?></span>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>

        <!-- Tombol Logout -->
        <div class="logout-container">
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>

    </div>

</body>
</html>
