<?php
session_start();
include 'koneksi.php';

// 1. LOGIKA UNTUK PENGECEKAN AJAX (Dibutuhkan oleh index.php)
if (isset($_POST['action']) && $_POST['action'] == 'check_user') {
    $id = mysqli_real_escape_string($conn, $_POST['identifier']);
    
    // Cek di tabel Admin
    $cekAdmin = mysqli_query($conn, "SELECT username FROM Admin WHERE username = '$id'");
    if (mysqli_num_rows($cekAdmin) > 0) {
        echo json_encode(['status' => 'ada', 'role' => 'admin']);
        exit;
    }

    // Cek di tabel Siswa
    $cekSiswa = mysqli_query($conn, "SELECT nis FROM Siswa WHERE nis = '$id'");
    if (mysqli_num_rows($cekSiswa) > 0) {
        echo json_encode(['status' => 'ada', 'role' => 'siswa']);
        exit;
    }

    echo json_encode(['status' => 'tidak_ada']);
    exit;
}

// 2. LOGIKA PROSES LOGIN SETELAH TOMBOL DIKLIK
if (isset($_POST['login'])) {
    $role = $_POST['role'];
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']); // Menggunakan identifier

    if ($role == 'admin') {
        $password = $_POST['password'];
        $query = "SELECT * FROM Admin WHERE username='$identifier'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            if (password_verify($password, $data['password'])) {
                $_SESSION['admin'] = $data['username'];
                header("Location: admin/dashboard.php");
                exit();
            } else {
                echo "<script>alert('Password Admin salah!'); window.location='index.php';</script>";
            }
        } else {
            echo "<script>alert('Username Admin tidak ditemukan!'); window.location='index.php';</script>";
        }

    } else if ($role == 'siswa') {
        // Login siswa cukup menggunakan NIS (identifier) tanpa password
        $query = "SELECT * FROM Siswa WHERE nis='$identifier'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            $_SESSION['siswa'] = $data['nis'];
            $_SESSION['nama_siswa'] = $data['nama'];
            header("Location: siswa/dashboard.php");
            exit();
        } else {
            echo "<script>alert('NIS tidak ditemukan!'); window.location='index.php';</script>";
        }
    }
}
?>