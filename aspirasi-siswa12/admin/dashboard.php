<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Aspirasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">

    <nav class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center sticky top-0 z-10">
        <h1 class="text-xl font-bold text-slate-800">Admin Panel</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm text-slate-600 font-medium">👋 Halo, <?php echo $_SESSION['admin']; ?></span>
            <a href="../logout.php" class="text-sm bg-red-50 text-red-600 px-4 py-2 rounded-lg font-bold hover:bg-red-100 transition">Logout</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Daftar Laporan Aspirasi</h2>
                <p class="text-slate-500 text-sm">Kelola dan tanggapi masukan dari siswa.</p>
            </div>
            <a href="tambah_siswa.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-200 transition">
                + Tambah Siswa
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">No</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">Tanggal</th> 
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">Siswa</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">Kategori</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">Pesan</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center">Status</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $query = "SELECT ia.*, s.nama, k.ket_kategori, asp.status AS status_tanggapan
                                  FROM Input_Aspirasi ia
                                  JOIN Siswa s ON ia.nis = s.nis
                                  JOIN Kategori k ON ia.id_kategori = k.id_kategori
                                  LEFT JOIN Aspirasi asp ON ia.id_pelaporan = asp.id_pelaporan
                                  ORDER BY ia.id_pelaporan DESC";
                        
                        $result = mysqli_query($conn, $query);
                        $no = 1;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $status_db = $row['status_tanggapan'] ?? 'Menunggu';
                            $badge_class = "bg-slate-100 text-slate-600";
                            if ($status_db == "Proses") $badge_class = "bg-blue-100 text-blue-600";
                            if ($status_db == "Selesai") $badge_class = "bg-green-100 text-green-600";
                            if ($status_db == "Menunggu") $badge_class = "bg-orange-100 text-orange-600";
                        ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-sm text-slate-500"><?= $no++; ?></td>
                            <td class="p-4 text-sm text-slate-600 font-medium"><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                            <td class="p-4 text-sm font-bold text-slate-800"><?= $row['nama']; ?></td>
                            <td class="p-4 text-sm text-slate-600"><?= $row['ket_kategori']; ?></td>
                            <td class="p-4 text-sm text-slate-600 max-w-xs truncate"><?= htmlspecialchars($row['pesan']); ?></td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $badge_class; ?>">
                                    <?= $status_db; ?>
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <a href="proses_tanggapan.php?id=<?= $row['id_pelaporan']; ?>" class="text-blue-600 hover:text-blue-800 text-sm font-bold underline">Tanggapi</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>