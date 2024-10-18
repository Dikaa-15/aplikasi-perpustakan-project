<?php

require_once '../Controller/UserController.php';

$user = new UserController();
$users = $user->getAllUsers();
$i = 1; // Inisialisasi nomor urut
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Tambahkan SweetAlert -->
</head>
<body class="bg-gray-100">

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-8"> Data Siswa</h1>
        
        <!-- table -->
        <div class="text-left mb-4">
            <a href="DataBuku.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Daftar Buku</a>
        </div>
        <div class="text-right mb-4">
            <a href="create.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tambah Data</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">No</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Nama Lengkap</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">NIS</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">NISN</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Kelas</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">No Whatsapp</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">No Kartu</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Roles</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold text-gray-600 uppercase text-sm text-left">Edit Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap"><?= $i++; ?></td> 
                            <td class="py-3 px-6 text-left"><?= $user['nama_lengkap'] ?></td>
                            <td class="py-3 px-6 text-left"><?= $user['nis'] ?></td>
                            <td class="py-3 px-6 text-left"><?= $user['nisn'] ?></td>
                            <td class="py-3 px-6 text-left"><?= $user['kelas'] ?></td>
                            <td class="py-3 px-6 text-left"><?= $user['no_whatsapp'] ?></td>
                            <td class="py-3 px-6 text-left"><?= $user['no_kartu'] ?></td>
                            <td class="py-3 px-6 text-left"><?= $user['roles'] ?></td>
                            <td class="py-3 px-6 text-left">
                                <a href="edit.php?id=<?= $user['id_user'] ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                <button onclick="confirmDelete(<?= $user['id_user'] ?>)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Hapus Data Siswa',
                text: "Data yang dipilih akan dihapus secara permanen, yakin ingin menghapus data tersebut?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Tidak, Terima Kasih'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Controller/delete.php?id=' + userId;
                }
            });
        }
    </script>

</body>
</html>
