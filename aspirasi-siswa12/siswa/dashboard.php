<?php
session_start();
if (!isset($_SESSION['siswa'])) {
    header("Location: ../index.php");
    exit();
}

include '../koneksi.php'; 
$nis = $_SESSION['siswa'];

if (isset($_POST['kirim'])) {
    $id_kategori = $_POST['id_kategori'];
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    
    $foto = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $folder = "../uploads/" . $foto; 

    if (move_uploaded_file($tmp_name, $folder)) {
        $query = "INSERT INTO Input_Aspirasi (nis, id_kategori, lokasi, pesan, foto) 
                  VALUES ('$nis', '$id_kategori', '$lokasi', '$pesan', '$foto')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Aspirasi berhasil dikirim!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim aspirasi.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Aspirasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans">

    <nav class="bg-white shadow-md border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-10">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Portal Aspirasi</h1>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-tighter">Siswa Dashboard</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold text-slate-700"><?php echo $_SESSION['nama_siswa']; ?></p>
                <p class="text-xs text-slate-500">NIS: <?php echo $nis; ?></p>
            </div>
            <a href="../logout.php" class="bg-red-50 text-red-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-100 transition-colors">Logout</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <section class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="p-2 bg-blue-100 text-blue-600 rounded-lg text-sm">✍️</span>
                    Buat Laporan Baru
                </h3>
                
                <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Kategori</label>
                        <select name="id_kategori" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $kategori_query = mysqli_query($conn, "SELECT * FROM Kategori");
                            while ($k = mysqli_fetch_assoc($kategori_query)) {
                                echo "<option value='".$k['id_kategori']."'>".$k['ket_kategori']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Lokasi Kejadian</label>
                        <input type="text" name="lokasi" required placeholder="Contoh: Kantin atau Kelas" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Pesan / Laporan</label>
                        <textarea name="pesan" rows="4" required placeholder="Tuliskan keluhan atau saran Anda..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Bukti Foto</label>
                        <input type="file" name="foto" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <button type="submit" name="kirim" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-100 transition-all active:scale-95">
                        Kirim Aspirasi
                    </button>
                </form>
            </div>
        </section>

        <section class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="p-2 bg-orange-100 text-orange-600 rounded-lg text-sm">📋</span>
                    Riwayat Pengaduan
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="py-4 px-2 text-xs font-bold text-slate-400 uppercase tracking-widest">No</th>
                                <th class="py-4 px-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori</th>
                                <th class="py-4 px-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Pesan</th>
                                <th class="py-4 px-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="py-4 px-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Balasan Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php
                            $query_riwayat = "SELECT ia.*, k.ket_kategori, a.status, a.feedback 
                                              FROM Input_Aspirasi ia 
                                              JOIN Kategori k ON ia.id_kategori = k.id_kategori 
                                              LEFT JOIN Aspirasi a ON ia.id_pelaporan = a.id_pelaporan 
                                              WHERE ia.nis = '$nis' 
                                              ORDER BY ia.id_pelaporan DESC";
                            
                            $result_riwayat = mysqli_query($conn, $query_riwayat);
                            $no = 1;

                            if (mysqli_num_rows($result_riwayat) > 0) {
                                while ($row = mysqli_fetch_assoc($result_riwayat)) {
                                    $status = !empty($row['status']) ? $row['status'] : 'Menunggu';
                                    
                                    // Warna Badge Status
                                    $badge_class = "bg-orange-100 text-orange-600";
                                    if ($status == 'Proses') $badge_class = "bg-blue-100 text-blue-600";
                                    if ($status == 'Selesai') $badge_class = "bg-green-100 text-green-600";

                                    echo "<tr>";
                                    echo "<td class='py-4 px-2 text-sm text-slate-500'>".$no++."</td>";
                                    echo "<td class='py-4 px-2 text-sm font-bold text-slate-700'>".$row['ket_kategori']."</td>";
                                    echo "<td class='py-4 px-2 text-sm text-slate-600 max-w-xs truncate'>".$row['pesan']."</td>";
                                    echo "<td class='py-4 px-2'>
                                            <span class='px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider $badge_class'>$status</span>
                                          </td>";
                                    echo "<td class='py-4 px-2 text-sm text-slate-500 italic'>".(!empty($row['feedback']) ? $row['feedback'] : 'Menunggu balasan...')."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='py-10 text-center text-slate-400 text-sm'>Belum ada data pengaduan.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

</body>
</html>