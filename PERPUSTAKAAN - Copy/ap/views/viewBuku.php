<?php
session_start(); // Mulai session jika diperlukan
include_once '../core/Database.php'; // Sertakan koneksi database
include_once '../models/Buku.php'; // Sertakan kelas Buku

// Buat instance dari Database
$database = new Database();
$db = $database->getConnection();

// Buat instance dari Buku
$buku = new Buku($db);

// Ambil data buku
$stmt = $buku->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-5">
        <h1 class="text-3xl font-bold mb-5">Daftar Buku</h1>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Judul</th>
                    <th class="py-2 px-4 border-b">Pengarang</th>
                    <th class="py-2 px-4 border-b">Tahun Terbit</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= $row['id_buku']; ?></td>
                    <td class="py-2 px-4 border-b"><?= $row['judul_buku']; ?></td>
                    <td class="py-2 px-4 border-b"><?= $row['penerbit']; ?></td>
                    <td class="py-2 px-4 border-b"><?= $row['sinopsis']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
