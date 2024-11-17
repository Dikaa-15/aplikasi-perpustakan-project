<?php
require_once '../Controller/peminjamancontroller.php';
$peminjamanController = new PeminjamanController();

$query = $_GET['query'] ?? '';
$peminjamans = $peminjamanController->searchPeminjaman($query);
$no = 1;

foreach ($peminjamans as $peminjaman) {
    echo '<tr class="border-b border-gray-200 hover:bg-gray-100">';
    echo '<td class="py-1 px-2">' . $no++ . '</td>';
    echo '<td class="py-1 px-2">' . htmlspecialchars($peminjaman['nama_lengkap'] ?? '') . '</td>';
    echo '<td class="py-1 px-2">' . htmlspecialchars($peminjaman['kelas'] ?? '') . '</td>';
    echo '<td class="py-1 px-2">' . htmlspecialchars($peminjaman['no_kartu'] ?? '') . '</td>';
    echo '<td class="py-1 px-2">' . htmlspecialchars($peminjaman['judul_buku'] ?? '') . '</td>';
    echo '<td class="py-1 px-2">' . htmlspecialchars($peminjaman['kuantitas_buku'] ?? '') . '</td>';
    echo '<td class="py-1 px-2">' . date('d-m-Y', strtotime($peminjaman['tanggal_peminjaman'] ?? '')) . '</td>';
    echo '<td class="py-1 px-2">' . date('d-m-Y', strtotime($peminjaman['tanggal_kembalian'] ?? '')) . '</td>';
    echo '<td class="py-1 px-2">' . htmlspecialchars($peminjaman['status_peminjaman'] ?? '') . '</td>';

    echo '<td>';
    if ($peminjaman['status_peminjaman'] === 'proses') {
        echo '<button onclick="confirmVerification(\'' . $peminjaman['id_peminjaman'] . '\', \'verif_peminjaman\')" class="text-blue-500">Verifikasi Peminjaman</button>';
        echo '<a href="hapus.php?id=' . $peminjaman['id_peminjaman'] . '" class="text-red-500 font-semibold">Hapus</a>';
    } elseif ($peminjaman['status_peminjaman'] === 'sedang dipinjam') {
        echo '<button onclick="confirmVerification(\'' . $peminjaman['id_peminjaman'] . '\', \'verif_pengembalian\')" class="text-green-500">Verifikasi Pengembalian</button>';
        echo '<a href="hapus.php?id=' . $peminjaman['id_peminjaman'] . '" class="text-red-500 font-semibold">Hapus</a>';
    } elseif ($peminjaman['status_peminjaman'] === 'sudah dikembalikan' || $peminjaman['status_peminjaman'] === 'Telat Mengembalikan') {
        echo '<button onclick="confirmDelete(' . $peminjaman['id_peminjaman'] . ')" class="text-red-500 font-semibold">Hapus</button>';
    }
    echo '</td>';

    echo '</tr>';
}
?>
