<?php
session_start();
require 'koneksi.php';

// Proteksi halaman: jika bukan admin, tendang ke login
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Aspirasi Sekolah</title>
    <link rel="stylesheet" href="css/admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="sidebar">
        <h2>Aspirasi Sekolah</h2>
        <a href="admin.php" class="nav-link active">Dashboard</a>
        <a href="data-siswa.php" class="nav-link">Data Siswa</a>
    </div>

    <div class="main-content">
        <nav class="top-nav">
            <span style="font-weight: 600; color: #7f8c8d;">Panel Admin Pengaduan</span>
            <div class="admin-info">
                <span style="font-size: 14px; color: #2c3e50;">Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    Daftar Pengaduan Siswa
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>NIS</th>
                            <th>Kategori</th>
                            <th>Pesan</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$query = "SELECT Input_Aspirasi.*, Siswa.nama, Kategori.ket_kategori
          FROM Input_Aspirasi
          JOIN Siswa ON Input_Aspirasi.nis = Siswa.nis
          JOIN Kategori ON Input_Aspirasi.id_kategori = Kategori.id_kategori
          ORDER BY id_pelaporan DESC";

$result = mysqli_query($conn, $query);
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $tanggal_format = date('d M Y, H:i', strtotime($row['tanggal']));

    echo "<tr>";
    echo "<td>".$tanggal_format."</td>";
    echo "<td>".$row['nis']."</td>";
    echo "<td>".$row['ket_kategori']."</td>";
    echo "<td>".nl2br(htmlspecialchars($row['pesan']))."</td>";
    echo "</tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>