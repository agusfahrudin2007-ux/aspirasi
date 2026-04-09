<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
include '../koneksi.php'; 

$id_pelaporan = $_GET['id'];

// Query Detail Laporan
$query_laporan = "SELECT Input_Aspirasi.*, Siswa.nama, Kategori.ket_kategori 
                  FROM Input_Aspirasi 
                  JOIN Siswa ON Input_Aspirasi.nis = Siswa.nis
                  JOIN Kategori ON Input_Aspirasi.id_kategori = Kategori.id_kategori
                  WHERE id_pelaporan = '$id_pelaporan'";
$result_laporan = mysqli_query($conn, $query_laporan);
$data = mysqli_fetch_assoc($result_laporan);

// Query Tanggapan
$query_tanggapan = "SELECT * FROM Aspirasi WHERE id_pelaporan = '$id_pelaporan'";
$result_tanggapan = mysqli_query($conn, $query_tanggapan);
$data_tanggapan = mysqli_fetch_assoc($result_tanggapan);

if (isset($_POST['simpan'])) {
    $status = $_POST['status'];
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $id_kategori = $data['id_kategori'];

    if (mysqli_num_rows($result_tanggapan) > 0) {
        $query_save = "UPDATE Aspirasi SET status='$status', feedback='$feedback' WHERE id_pelaporan='$id_pelaporan'";
    } else {
        $query_save = "INSERT INTO Aspirasi (id_pelaporan, status, id_kategori, feedback) VALUES ('$id_pelaporan', '$status', '$id_kategori', '$feedback')";
    }
    mysqli_query($conn, $query_save);
    echo "<script>alert('Berhasil diperbarui!'); window.location='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanggapan Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6">

    <div class="max-w-5xl mx-auto">
        <a href="dashboard.php" class="text-sm font-bold text-blue-600 hover:underline inline-flex items-center mb-6">← Kembali ke Dashboard</a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">Detail Laporan</h3>
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <p class="text-slate-400 font-medium uppercase tracking-widest text-[10px]">Nama Siswa</p>
                            <p class="text-slate-700 font-bold"><?= htmlspecialchars($data['nama']); ?></p>
                        </div>
                        <div>
                            <p class="text-slate-400 font-medium uppercase tracking-widest text-[10px]">Kategori</p>
                            <p class="text-slate-700 font-bold"><?= htmlspecialchars($data['ket_kategori']); ?></p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <p class="text-slate-400 font-medium uppercase tracking-widest text-[10px] mb-2">Isi Aspirasi</p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-slate-600 italic">
                            "<?= nl2br(htmlspecialchars($data['pesan'])); ?>"
                        </div>
                    </div>
                    <?php if (!empty($data['foto'])): ?>
                        <p class="text-slate-400 font-medium uppercase tracking-widest text-[10px] mb-2">Foto Bukti</p>
                        <img src="../uploads/<?= $data['foto']; ?>" class="rounded-xl border w-full h-64 object-cover shadow-sm">
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-1">
                <form method="POST" class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 space-y-4 sticky top-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Tindakan Admin</h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ubah Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $curr_status = $data_tanggapan['status'] ?? 'Menunggu'; ?>
                            <option value="Menunggu" <?= $curr_status == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="Proses" <?= $curr_status == 'Proses' ? 'selected' : ''; ?>>Proses</option>
                            <option value="Selesai" <?= $curr_status == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Balasan / Feedback</label>
                        <textarea name="feedback" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Tulis solusi atau pesan..." required><?= $data_tanggapan['feedback'] ?? ''; ?></textarea>
                    </div>

                    <button type="submit" name="simpan" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-100 transition">
                        Simpan Tanggapan
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>