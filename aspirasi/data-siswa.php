<?php
session_start();
require 'koneksi.php';

// Proteksi halaman
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Ambil data siswa dari database
$query = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa | Admin</title>
    <link rel="stylesheet" href="css/admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .btn-add {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Aspirasi Sekolah</h2>
        <a href="admin.php" class="nav-link">Dashboard</a>
        <a href="data-siswa.php" class="nav-link active">Data Siswa</a>
    </div>

    <div class="main-content">
        <nav class="top-nav">
            <span style="font-weight: 600; color: #7f8c8d;">Master Data Siswa</span>
            <div class="admin-info">
                <span style="font-size: 14px; color: #2c3e50;">Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </nav>

        <div class="content-wrapper">
            <a href="tambah-siswa.php" class="btn-add">+ Tambah Siswa Baru</a>
            
            <div class="card">
                <div class="card-header">Daftar Siswa Terdaftar</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Lengkap</th>
    
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($query)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $row['nis']; ?></strong></td>
                            <td><?php echo $row['nama']; ?></td>
                    
                            <td>
                                <a href="hapus-siswa.php?nis=<?php echo $row['nis']; ?>" style="color: #e74c3c;" onclick="return confirm('Hapus data siswa ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>