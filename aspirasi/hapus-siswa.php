<?php
session_start();
require 'koneksi.php';

// Proteksi halaman: pastikan hanya admin yang bisa menghapus
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Mengambil parameter NIS dari URL
if (isset($_GET['nis'])) {
    $nis = mysqli_real_escape_string($conn, $_GET['nis']);

    // Proses hapus data berdasarkan NIS
    $query = mysqli_query($conn, "DELETE FROM siswa WHERE nis='$nis'");

    if ($query) {
        // Jika berhasil, tampilkan pesan dan kembali ke halaman data siswa
        echo "<script>
                alert('Data siswa berhasil dihapus!');
                window.location='data-siswa.php';
              </script>";
    } else {
        // Jika gagal karena masalah database
        echo "<script>
                alert('Gagal menghapus data!');
                window.location='data-siswa.php';
              </script>";
    }
} else {
    // Jika diakses tanpa parameter NIS, langsung kembalikan ke daftar siswa
    header("Location: data-siswa.php");
    exit();
}
?>