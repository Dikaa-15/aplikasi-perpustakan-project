<?php

require_once '../Controller/Kunjungan.php';

// Create a database connection
$database = new Database();
$db = $database->getConnection();

// Initialize the Kunjungan class
$kunjungan = new Kunjungan($db);

// Check if the delete button is clicked
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    if ($kunjungan->deleteVisit($delete_id)) {
        echo "<script>Swal.fire('Berhasil', 'Data berhasil dihapus!', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Gagal', 'Gagal menghapus data!', 'error');</script>";
    }
}

// Get all visit data
$visits = $kunjungan->getAllVisits();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kunjungan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body class="bg-gray-100">

    <div class="container mx-auto py-8">
        <h2 class="text-3xl font-bold text-center mb-8">Data Kunjungan</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">No</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Nama Pengunjung</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Kelas</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">No Kartu</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Tanggal Kunjungan</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Keperluan</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($visits as $visit) : ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap"><?= $no++; ?></td>
                            <td class="py-3 px-6 text-left"><?= htmlspecialchars($visit['nama_lengkap']); ?></td>
                            <td class="py-3 px-6 text-left"><?= htmlspecialchars($visit['kelas']); ?></td>
                            <td class="py-3 px-6 text-left"><?= htmlspecialchars($visit['no_kartu']); ?></td>
                            <td class="py-3 px-6 text-left"><?= htmlspecialchars($visit['tanggal_kunjungan']); ?></td>
                            <td class="py-3 px-6 text-left"><?= htmlspecialchars($visit['keperluan']); ?></td>
                            <td class="py-3 px-6 text-left">
                                <button onclick="confirmDelete(<?= $visit['id_kunjungan'] ?>)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script>
        function confirmDelete(kunjunganId) {
            Swal.fire({
                title: 'Hapus Data Kunjungan',
                text: "Data yang dipilih akan dihapus secara permanen, yakin ingin menghapus data tersebut?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Tidak, Terima Kasih'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?delete_id=' + kunjunganId;
                }
            });
        }
    </script>

</body>
</html>
