<?php
session_start();
require 'koneksi.php';

// Cek login admin
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Menangkap 'id' dan 'status' dari URL admin.php
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    // Pastikan nama tabel adalah 'input_aspirasi' dan kolomnya 'id_pelaporan'
    $sql = "UPDATE input_aspirasi SET status='$status' WHERE id_pelaporan='$id'";
    $update = mysqli_query($conn, $sql);

    if ($update) {
        echo "<script>
                alert('Status Berhasil Diperbarui!');
                window.location='admin.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: admin.php");
}
?>