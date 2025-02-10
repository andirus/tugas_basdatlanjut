<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Ambil data mahasiswa dengan status pendaftaran, pembayaran, dan dokumen
$query_mahasiswa = "SELECT m.id_mahasiswa, m.nama_mahasiswa, m.email, m.status_pendaftaran, 
                    COALESCE(p.status_pembayaran, 'Belum Bayar') AS status_pembayaran, 
                    COALESCE(d.path_dokumen, 'Tidak Ada Dokumen') AS path_dokumen 
                    FROM Mahasiswa m 
                    LEFT JOIN Pembayaran p ON m.id_mahasiswa = p.id_mahasiswa
                    LEFT JOIN Dokumen d ON m.id_mahasiswa = d.id_mahasiswa";
$stmt_mahasiswa = $conn->prepare($query_mahasiswa);
$stmt_mahasiswa->execute();
$result_mahasiswa = $stmt_mahasiswa->get_result();
$stmt_mahasiswa->close();

// Tambah Mahasiswa Baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_mahasiswa'])) {
    $nama = $_POST['nama_mahasiswa'];
    $email = $_POST['email'];
    $status_pendaftaran = "Menunggu"; // Default status

    $insert_query = "INSERT INTO Mahasiswa (nama_mahasiswa, email, status_pendaftaran) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param('sss', $nama, $email, $status_pendaftaran);
    $stmt_insert->execute();
    $stmt_insert->close();

    header("Location: admin_dashboard.php");
    exit;
}

// Hapus Mahasiswa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_mahasiswa'])) {
    $id_mahasiswa = $_POST['id_mahasiswa'];

    $delete_query = "DELETE FROM Mahasiswa WHERE id_mahasiswa = ?";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bind_param('i', $id_mahasiswa);
    $stmt_delete->execute();
    $stmt_delete->close();

    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #eef2f3; margin: 0; padding: 0; }
        .container { max-width: 1000px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f1f1f1; }
        button, select, input { padding: 10px; border-radius: 5px; font-size: 14px; border: 1px solid #ccc; }
        button { background: #28a745; color: white; border: none; cursor: pointer; transition: 0.3s; }
        button:hover { background: #218838; }
        .logout-btn { padding: 10px 20px; background: #dc3545; color: white; border-radius: 8px; font-size: 14px; text-decoration: none; display: inline-block; }
        .logout-btn:hover { background: #c82333; }
        .action-buttons { display: flex; gap: 5px; }
        form input { margin-bottom: 10px; display: block; width: 100%; }
        .paid { color: green; font-weight: bold; }
        .unpaid { color: red; font-weight: bold; }
    </style>
    <script>
        function editMahasiswa(id) {
            window.location.href = 'edit_admin.php?id_mahasiswa=' + id;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Admin - Kelola Mahasiswa</h1>
        
        <h2>Tambah Mahasiswa</h2>
        <form method="POST">
            <input type="text" name="nama_mahasiswa" placeholder="Nama Mahasiswa" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit" name="add_mahasiswa">Tambah</button>
        </form>

        <h2>Daftar Mahasiswa</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status Pendaftaran</th>
                    <th>Status Pembayaran</th>
                    <th>Bukti Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_mahasiswa->num_rows > 0): ?>
                    <?php $no = 1; while ($row = $result_mahasiswa->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['status_pendaftaran']); ?></td>
                            <td class="<?php echo ($row['status_pembayaran'] === 'Lunas') ? 'paid' : 'unpaid'; ?>">
                                <?php echo htmlspecialchars($row['status_pembayaran']); ?>
                            </td>
                            <td>
                                <?php if ($row['path_dokumen'] !== 'Tidak Ada Dokumen'): ?>
                                    <a href="<?php echo htmlspecialchars($row['path_dokumen']); ?>" target="_blank">Lihat</a>
                                <?php else: ?>
                                    Tidak Ada Dokumen
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <button onclick="editMahasiswa(<?php echo $row['id_mahasiswa']; ?>)">Edit</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_mahasiswa" value="<?php echo $row['id_mahasiswa']; ?>">
                                    <button type="submit" name="delete_mahasiswa" onclick="return confirm('Yakin ingin menghapus?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Tidak ada data mahasiswa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 20px;">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>
