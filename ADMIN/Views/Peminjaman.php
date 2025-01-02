<?php

require_once '../Controller/Peminjaman.php';


session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ");
    exit();
}


$peminjamanController = new PeminjamanController();
$peminjamans = $peminjamanController->getAllPeminjaman();


// Mendapatkan data hari ini atau semua
$today = isset($_GET['filter']) && $_GET['filter'] == 'today';
$peminjamans = $today ? $peminjamanController->getPeminjamanToday() : $peminjamanController->getAllPeminjaman();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto py-8">
        <h2 class="text-3xl font-bold text-center mb-8">Data Peminjaman Buku</h2>

        <!-- Navtabs -->
        <div class="flex justify-center mb-4">
            <a href="Peminjaman.php?filter=today" class="px-4 py-2 <?= $today ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-700'; ?> rounded-lg">Hari Ini</a>
            <a href="Peminjaman.php" class="px-4 py-2 <?= !$today ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-700'; ?> rounded-lg ml-4">Semua Hari</a>
        </div>

                        <div class="overflow-x-auto">
                    <table class="min-w-full bg-white shadow-md rounded-lg text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">No</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Nama Pengunjung</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Kelas</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">No Kartu</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Judul Buku</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Kuantitas</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Tanggal Peminjaman</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Jam Peminjaman</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Tanggal Pengembalian</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Jam Pengembalian</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Status</th>
                                <th class="py-2 px-4 bg-gray-200 font-bold text-gray-600 uppercase text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($peminjamans as $peminjaman) : ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-2 px-4 text-left whitespace-nowrap"><?= $no++; ?></td>
                                    <td class="py-2 px-4 text-left"><?= htmlspecialchars($peminjaman['nama_lengkap'] ?? ''); ?></td>
                                    <td class="py-2 px-4 text-left"><?= htmlspecialchars($peminjaman['kelas'] ?? ''); ?></td>
                                    <td class="py-2 px-4 text-left"><?= htmlspecialchars($peminjaman['no_kartu'] ?? ''); ?></td>
                                    <td class="py-2 px-4 text-left"><?= htmlspecialchars($peminjaman['judul_buku'] ?? ''); ?></td>
                                    <td class="py-2 px-4 text-left"><?= htmlspecialchars($peminjaman['kuantitas_buku'] ?? ''); ?></td>
                                    <td class="py-2 px-4 text-left"><?= isset($peminjaman['tanggal_peminjaman']) ? date('Y-m-d', strtotime($peminjaman['tanggal_peminjaman'])) : '-'; ?></td>
                                    <td class="py-2 px-4 text-left"><?= isset($peminjaman['tanggal_peminjaman']) ? date('H:i:s', strtotime($peminjaman['waktu_peminjaman'])) : '-'; ?></td>
                                    <td class="py-2 px-4 text-left"><?= isset($peminjaman['tanggal_kembalian']) ? date('Y-m-d', strtotime($peminjaman['tanggal_kembalian'])) : '-'; ?></td>
                                    <td class="py-2 px-4 text-left"><?= isset($peminjaman['tanggal_kembalian']) ? date('H:i:s', strtotime($peminjaman['waktu_kembalian'])) : '-'; ?></td>

                                    <td class="py-2 px-4 text-left"><?= htmlspecialchars($peminjaman['status_peminjaman'] ?? ''); ?></td>
                                    <td class="py-2 px-4 text-left">
                                        <button onclick="updateStatus(<?= $peminjaman['id_peminjaman']; ?>, 'sudah dikembalikan')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Verifikasi</button>
                                        <button onclick="confirmDelete(<?= $peminjaman['id_peminjaman']; ?>)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded ml-2">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

    </div>
    <script>
        function updateStatus(id, status) {
    Swal.fire({
        title: 'Update Status Peminjaman',
        text: 'Apakah Anda yakin ingin mengubah status peminjaman ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update Status',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Update status dan tanggal kembalian otomatis
            window.location.href = '../Controller/editPeminjaman.php?id=' + id + '&status=' + status;
        }
    });
}


        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Peminjaman',
                text: 'Apakah Anda yakin ingin menghapus peminjaman ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Controller/delete_peminjaman.php?id=' + id;
                }
            });
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
