<?php
session_start();
require 'koneksi.php';

if ($_SESSION['role'] != 'siswa') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['kirim'])) {
    $nis = $_SESSION['nis'];
    $id_kategori = $_POST['id_kategori'];
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    
    // Upload Foto sederhana
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $path = "uploads/".$foto;
    
    if (move_uploaded_file($tmp, $path)) {
        // Insert ke input_aspirasi
        $query1 = "INSERT INTO input_aspirasi (nis, id_kategori, lokasi, pesan, foto) VALUES ('$nis', '$id_kategori', '$lokasi', '$pesan', '$foto')";
        mysqli_query($conn, $query1);
        
        $id_pelaporan = mysqli_insert_id($conn); // Ambil ID yang baru masuk
        
        // Insert otomatis ke tabel aspirasi untuk tracking status
        $query2 = "INSERT INTO aspirasi (id_pelaporan, status, id_kategori) VALUES ('$id_pelaporan', 'Menunggu', '$id_kategori')";
        mysqli_query($conn, $query2);
        
        $sukses = "Aspirasi berhasil dikirim!";
    } else {
        $error = "Gagal mengupload foto.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Siswa - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand">Halo, <?= $_SESSION['nama'] ?> (<?= $_SESSION['nis'] ?>)</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">Buat Laporan / Aspirasi Baru</div>
        <div class="card-body">
            <?php if(isset($sukses)) echo "<div class='alert alert-success'>$sukses</div>"; ?>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <?php
                        $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                        while ($k = mysqli_fetch_assoc($kategori)) {
                            echo "<option value='".$k['id_kategori']."'>".$k['ket_kategori']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Pesan</label>
                    <textarea name="pesan" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Foto (Wajib)</label>
                    <input type="file" name="foto" class="form-control" required>
                </div>
                <button type="submit" name="kirim" class="btn btn-success">Kirim Aspirasi</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>