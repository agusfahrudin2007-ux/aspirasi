<?php
session_start();
require 'koneksi.php';

$error = ""; // Variabel untuk menampung pesan kesalahan

if (isset($_POST['login'])) { // Memeriksa jika tombol login ditekan
    $role = $_POST['role'];
    
    if ($role == 'admin') {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password']; 
        
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            // Cek password hash atau bypass admin123
            if (password_verify($password, $data['password']) || $password == 'admin123') { 
                $_SESSION['role'] = 'admin';
                $_SESSION['username'] = $username;
                header("Location: admin.php");
                exit();
            } else {
                $error = "Password admin salah!";
            }
        } else {
            $error = "Username admin tidak ditemukan!";
        }
    } else if ($role == 'siswa') {
        // Untuk siswa, codingan Anda mencari 'nis'
        $nis = mysqli_real_escape_string($conn, $_POST['username']); 
        
        $query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $_SESSION['role'] = 'siswa';
            $_SESSION['nis'] = $data['nis'];
            $_SESSION['nama'] = $data['nama'];
            header("Location: siswa.php");
            exit();
        } else {
            $error = "NIS tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aspirasi Sekolah</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="login-card">
        <h2>Login Aspirasi Sekolah</h2>
        
        <?php if($error): ?>
            <p style="color: red; text-align: center; font-size: 14px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="role">Login Sebagai</label>
                <select id="role" name="role" onchange="updateLabel()">
                    <option value="admin">Admin</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>

            <div class="form-group">
                <label id="user-label" for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            </div>

            <div id="pass-group" class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password">
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
    </div>

<script>
// Fungsi untuk mengubah label Username menjadi NIS jika memilih Siswa
function updateLabel() {
    var role = document.getElementById('role').value;
    var label = document.getElementById('user-label');
    var passGroup = document.getElementById('pass-group');
    var passInput = document.getElementById('password');

    if (role == 'siswa') {
        label.innerText = 'NIS';
        document.getElementById('username').placeholder = 'Masukkan NIS Anda';
        passGroup.style.display = 'none'; // Siswa di codingan Anda tidak pakai password
        passInput.removeAttribute('required');
    } else {
        label.innerText = 'Username';
        document.getElementById('username').placeholder = 'Masukkan username';
        passGroup.style.display = 'block';
        passInput.setAttribute('required', '');
    }
}
// Jalankan saat halaman pertama kali dimuat
updateLabel();
</script>
</body>
</html>