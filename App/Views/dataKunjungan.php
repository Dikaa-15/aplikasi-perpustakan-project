<?php

session_start();

require_once '../Controller/Kunjungan.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$kunjungan = new Kunjungan($db);
// $kunjungan->id_user = $_SESSION['id_user'];

// get all visit user
$visits = $kunjungan->getAllVisits($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
</body>
</html>