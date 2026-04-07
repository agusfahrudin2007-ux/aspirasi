<?php
session_start();
include 'koneksi.php';

$input = $_POST['user_input'];
$pass  = $_POST['password'];

// Cek sebagai siswa
$cek_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$input'");
if (mysqli_num_rows($cek_siswa) > 0) {
    $s = mysqli_fetch_assoc($cek_siswa);

    if ($s && password_verify($pass, $s['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['role']  = 'siswa';
        $_SESSION['nis']   = $s['nis'];

        header("Location: halaman_siswa.php");
    } else {
        echo "Login Anda GAGAL!";
    }

} else {

    // Cek sebagai admin
    $cek_admin = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$input'");
    $a = mysqli_fetch_assoc($cek_admin);

    if ($a && password_verify($pass, $a['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['role']  = 'admin';

        header("Location: halaman_admin.php");
    } else {
        echo "Login Anda GAGAL!";
    }
}
?>
