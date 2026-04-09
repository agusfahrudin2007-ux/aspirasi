<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $nis   = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $query = "INSERT INTO Siswa (nis, nama) VALUES ('$nis', '$nama')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Berhasil ditambah!'); window.location='dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white w-full max-w-md p-8 rounded-3xl shadow-xl shadow-slate-200/60 border border-white">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Siswa</h2>
            <p class="text-slate-400 text-sm">Masukkan data siswa baru ke dalam sistem.</p>
        </div>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" required placeholder="Contoh: 10223"
                       class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Nama lengkap siswa"
                       class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all">
            </div>

            <button type="submit" name="simpan" 
                    class="w-full py-4 bg-slate-900 hover:bg-black text-white font-bold rounded-2xl shadow-lg transition-all active:scale-[0.98]">
                Simpan Data
            </button>
            
            <a href="dashboard.php" class="block text-center text-sm font-bold text-slate-400 hover:text-slate-600 transition">
                Batal & Kembali
            </a>
        </form>
    </div>

</body>
</html>