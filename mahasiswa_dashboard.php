<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Mahasiswa') {
    header("Location: login.php");
    exit;
}

include 'db.php';

$user_id = $_SESSION['id_user'];
$nama_user = $_SESSION['nama_user'] ?? 'Mahasiswa';

$query_status = "SELECT status_pendaftaran, nama_mahasiswa FROM Mahasiswa WHERE id_user = ?";
$stmt_status = $conn->prepare($query_status);
$stmt_status->bind_param('i', $user_id);
$stmt_status->execute();
$result_status = $stmt_status->get_result();
$mahasiswa_data = $result_status->fetch_assoc();
$stmt_status->close();
$status_pendaftaran = $mahasiswa_data['status_pendaftaran'] ?? 'Menunggu';
$nama_mahasiswa = $mahasiswa_data['nama_mahasiswa'] ?? $nama_user;

$query_pembayaran = "SELECT status_pembayaran, bukti_pembayaran FROM Pembayaran WHERE id_user = ?";
$stmt_pembayaran = $conn->prepare($query_pembayaran);
$stmt_pembayaran->bind_param('i', $user_id);
$stmt_pembayaran->execute();
$result_pembayaran = $stmt_pembayaran->get_result();
$pembayaran_data = $result_pembayaran->fetch_assoc();
$stmt_pembayaran->close();
$status_pembayaran = $pembayaran_data['status_pembayaran'] ?? 'Belum Membayar';
$bukti_pembayaran = $pembayaran_data['bukti_pembayaran'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['bukti_pembayaran'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["bukti_pembayaran"]["name"]);
    if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
        $update_query = "UPDATE Pembayaran SET bukti_pembayaran = ?, status_pembayaran = 'Lunas' WHERE id_user = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param('si', $target_file, $user_id);
        $stmt_update->execute();
        $stmt_update->close();
        $bukti_pembayaran = $target_file;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_pendaftaran'])) {
    // Ambil data dari formulir
    $nama_mahasiswa = $_POST['nama_mahasiswa'];
    $program_studi = $_POST['program_studi'];
    $no_telepon = $_POST['no_telepon'];
    $email = $_POST['email'];

    // Query untuk memasukkan data ke dalam tabel Mahasiswa
    $query_insert = "INSERT INTO Mahasiswa (id_user, nama_mahasiswa, program_studi, no_telepon, email, status_pendaftaran) 
                     VALUES (?, ?, ?, ?, ?, 'Menunggu')";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param('issss', $user_id, $nama_mahasiswa, $program_studi, $no_telepon, $email);
    $stmt_insert->execute();
    $stmt_insert->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $status = $_POST['status_pendaftaran'];
    $query_update_status = "UPDATE Mahasiswa SET status_pendaftaran = ? WHERE id_user = ?";
    $stmt_update_status = $conn->prepare($query_update_status);
    $stmt_update_status->bind_param('si', $status, $user_id);
    $stmt_update_status->execute();
    $stmt_update_status->close();
    
    // Refresh halaman setelah update
    header("Location: mahasiswa_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 15px #ccc; text-align: center; }
        .button-container { display: flex; justify-content: center; gap: 10px; margin-bottom: 20px; }
        button { padding: 10px 20px; background: #007BFF; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; }
        button:hover { background: #0056b3; }
        .form-container, .status-container { display: none; padding: 15px; border: 1px solid #ccc; border-radius: 8px; background: #fff; font-size: 16px; text-align: left; }
        .logout-container { margin-top: 20px; }
        .logout-btn { text-decoration: none; padding: 10px 20px; background: #dc3545; color: white; border-radius: 8px; font-size: 14px; }
        .logout-btn:hover { background: #c82333; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; }
        h1, h2 { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mahasiswa Dashboard</h1>
        <h2>Selamat Datang, <?php echo htmlspecialchars($nama_mahasiswa); ?>!</h2>
        <div class="button-container">
            <button onclick="showSection('formulirContainer')">Formulir Pendaftaran</button>
            <button onclick="showSection('statusContainer')">Status Pendaftaran</button>
            <button onclick="showSection('pembayaranContainer')">Status Pembayaran</button>
        </div>

        <div class="form-container" id="formulirContainer">
            <form method="POST" action="" enctype="multipart/form-data">
                <label>Nama Mahasiswa</label>
                <input type="text" name="nama_mahasiswa" required>
                <label>Program Studi</label>
                <select name="program_studi" required>
                    <option value="">Pilih Program Studi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Elektro">Teknik Elektro</option>
                </select>
                <label>No Telepon</label>
                <input type="text" name="no_telepon" required>
                <label>Email</label>
                <input type="email" name="email" required>
                <label>Unggah Dokumen (PDF)</label>
                <input type="file" name="dokumen" accept=".pdf" required>
                <button type="submit" name="submit_pendaftaran">Ajukan Pendaftaran</button>
            </form>
        </div>

        <div class="status-container" id="statusContainer">
            <p>Status Pendaftaran: <?php echo htmlspecialchars($status_pendaftaran); ?></p>
            <?php if ($status_pendaftaran == 'Menunggu'): ?>
                <p>Menunggu verifikasi dari petugas.</p>
            <?php elseif ($status_pendaftaran == 'Diterima'): ?>
                <p>Selamat, pendaftaran Anda diterima! Silahkan Lakukan Pembayaran Registrasi</p>
            <?php elseif ($status_pendaftaran == 'Ditolak'): ?>
                <p>Maaf, pendaftaran Anda ditolak.</p>
            <?php endif; ?>
            
            <!-- Hanya untuk petugas -->
            <?php if ($_SESSION['role'] == 'Petugas'): ?>
                <form method="POST">
                    <label>Pilih Status Pendaftaran</label>
                    <select name="status_pendaftaran" required>
                        <option value="Menunggu" <?php echo ($status_pendaftaran == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                        <option value="Diterima" <?php echo ($status_pendaftaran == 'Diterima') ? 'selected' : ''; ?>>Diterima</option>
                        <option value="Ditolak" <?php echo ($status_pendaftaran == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                    <button type="submit" name="update_status">Update Status</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="status-container" id="pembayaranContainer">
            <p>Status Pembayaran: <?php echo htmlspecialchars($status_pembayaran); ?></p>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_user" value="<?php echo $user_id; ?>">
                <label>Unggah Bukti Pembayaran (JPG/PNG)</label>
                <input type="file" name="bukti_pembayaran" accept="image/*" required>
                <button type="submit">Unggah Bukti</button>
            </form>
            <?php if ($bukti_pembayaran): ?>
                <p>Bukti Pembayaran: <a href="<?php echo $bukti_pembayaran; ?>" target="_blank">Lihat Bukti</a></p>
            <?php endif; ?>
        </div>

        <div class="logout-container">
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.form-container, .status-container').forEach(el => el.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</body>
</html>
