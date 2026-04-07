<?php
session_start();
require 'koneksi.php';

// Proteksi halaman admin
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$pesan = "";

// Logika ketika tombol simpan ditekan
if (isset($_POST['simpan'])) {
    $nis    = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas  = mysqli_real_escape_string($conn, $_POST['kelas']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Cek apakah NIS sudah terdaftar
    $cek_nis = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
    if (mysqli_num_rows($cek_nis) > 0) {
        $pesan = "<div class='error-msg'>Gagal! NIS sudah terdaftar.</div>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO siswa (nis, nama, kelas, alamat) VALUES ('$nis', '$nama', '$kelas', '$alamat')");
        if ($query) {
            echo "<script>alert('Data siswa berhasil ditambahkan!'); window.location='data-siswa.php';</script>";
        } else {
            $pesan = "<div class='error-msg'>Gagal menyimpan ke database.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa | Admin</title>
    <link rel="stylesheet" href="css/admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            max-width: 600px;
        }
        .btn-save {
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-back {
            background: #95a5a6;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            margin-right: 10px;
            display: inline-block;
            font-size: 14px;
        }
        .error-msg {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Aspirasi Sekolah</h2>
        <a href="admin.php" class="nav-link">Dashboard</a>
        <a href="aspirasi.php" class="nav-link">Data Pengaduan</a>
        <a href="data-siswa.php" class="nav-link active">Data Siswa</a>
    </div>

    <div class="main-content">
        <nav class="top-nav">
            <span style="font-weight: 600; color: #7f8c8d;">Tambah Data Siswa Baru</span>
            <div class="admin-info">
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="form-container">
                <?php echo $pesan; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label>NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" placeholder="Masukkan NIS" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama siswa" required>
                    </div>
                    <div class="form-group">
                        <label>Kelas</label>
                        <input type="text" name="kelas" placeholder="Contoh: XI-RPL" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" style="width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #e1e5ee;" rows="4" required></textarea>
                    </div>
                    <div style="margin-top: 20px;">
                        <a href="data-siswa.php" class="btn-back">Kembali</a>
                        <button type="submit" name="simpan" class="btn-save">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>