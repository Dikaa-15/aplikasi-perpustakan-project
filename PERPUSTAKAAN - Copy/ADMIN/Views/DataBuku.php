<?php
include_once '../core/Database.php';
include_once '../Models/Buku.php';

$database = new Database();
$db = $database->getConnection();

$buku = new Buku($db);
$stmt = $buku->read();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$i = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body class="bg-gray-100">

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Daftar Buku</h1>
        <div class="text-left mb-4">
            <a href="view.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Halaman Siswa</a>
        </div>
        <div class="text-right mb-4">
            <a href="createbuku.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tambah Buku Baru</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">No</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Kode Buku</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Judul</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Kategori</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Penerbit</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Stok</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Cover</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap"><?= $i++; ?></td>
                            <td class="py-3 px-6 text-left"><?= $row['kode_buku']; ?></td>
                            <td class="py-3 px-6 text-left"><?= $row['judul_buku']; ?></td>
                            <td class="py-3 px-6 text-left"><?= $row['kategori']; ?></td>
                            <td class="py-3 px-6 text-left"><?= $row['penerbit']; ?></td>
                            <td class="py-3 px-6 text-left"><?= $row['stok_buku']; ?></td>
                            <td class="py-3 px-6 text-left">
                                <img src="../uploads//"<?= $row['cover']; ?>" alt="Cover Buku" width="100">
                            </td>
                            <td class="py-3 px-6 text-left">
                            <a href="Updatebuku.php?id=<?= $row['id_buku']; ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button onclick="confirmDelete(<?= $row['id_buku']; ?>)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script>
        function confirmDelete(bukuId) {
            Swal.fire({
                title: 'Hapus Buku',
                text: "Buku yang dipilih akan dihapus secara permanen, yakin ingin menghapus buku tersebut?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Tidak, Terima Kasih'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Controller/deleteBuku.php?id=' + bukuId;
                }
            });
        }
    </script>

</body>
</html>
