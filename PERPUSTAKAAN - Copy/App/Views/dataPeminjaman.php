<?php
// Mulai sesi
session_start();

// Sertakan file koneksi dan class Buku
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$buku = new Buku($db);
$stmt = $buku->getPeminjaman($id_user);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman Buku</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-5">
        <h1 class="text-3xl font-bold mb-5">Data Peminjaman Buku</h1>

        <input type="text" id="search" placeholder="Cari judul buku..." class="focus:outline outline-gray-500">

        <div class="space-y-4" id="book-table">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="flex items-center p-6 bg-white shadow rounded-lg">
                <!-- Kolom Kiri untuk Gambar dan Info Singkat -->
                <div class="w-1/3">
                    <img class="w-48 h-64 object-cover rounded" src="<?= isset($row['cover']) ? htmlspecialchars($row['cover']) : 'default-image.jpg'; ?>" alt="gambar-buku">
                    <h1 class="text-xl font-bold mt-4"><?= htmlspecialchars($row['judul_buku']); ?></h1>
                    <p class="text-gray-500 mt-2"><?= isset($row['sinopsis']) ? htmlspecialchars($row['sinopsis']) : 'Sinopsis tidak tersedia'; ?></p>
                </div>

                <!-- Kolom Kanan untuk Info Detail -->
                <div class="ml-5 w-2/3">
                    <h2 class="text-lg font-bold"><?= htmlspecialchars($row['judul_buku']); ?></h2>
                    <p class="text-sm text-gray-600">Penerbit: <?= isset($row['penerbit']) ? htmlspecialchars($row['penerbit']) : 'Penerbit tidak tersedia'; ?></p>

                    <!-- Status Peminjaman dari Database -->
                    <div class="mt-6">
                        <h3 class="font-semibold">Status Peminjaman</h3>
                        <div class="mt-2">
                            <span class="py-2 px-4 rounded text-white font-bold
                                <?php if ($row['status_peminjaman'] == 'sedang dipinjam'): ?>
                                    bg-red-500
                                <?php elseif ($row['status_peminjaman'] == 'sudah dikembalikan'): ?>
                                    bg-green-500
                                <?php elseif($row['status_peminjaman'] == 'proses') : ?>
                                    bg-gray-500
                                <?php endif; ?>">
                                <?= htmlspecialchars($row['status_peminjaman']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Kembalikan Buku -->
                    <!-- <?php if ($row['status_peminjaman'] == 'sedang dipinjam'): ?>
                    <form action="kembalikanBuku.php" method="POST">
                        <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($row['id_peminjaman']); ?>">
                        <input type="hidden" name="id_buku" value="<?= htmlspecialchars($row['id_buku']); ?>">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Kembalikan Buku</button>
                    </form>
                    <?php endif; ?> -->
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
   $(document).ready(function() {
    $('#search').on('keyup', function() {
        var query = $(this).val();
        $.ajax({
            url: '../Controller/live_searching.php',
            method: 'POST',
            data: {query: query},
            dataType: 'json',
            success: function(data) {
                var rows = '';
                data.forEach(function(book) {
                    var statusClass = '';
                    if (book.status_peminjaman === 'proses') {
                        statusClass = 'bg-gray-500'; // Abu-abu
                    } else if (book.status_peminjaman === 'sedang dipinjam') {
                        statusClass = 'bg-red-500'; // Merah
                    } else if (book.status_peminjaman === 'sudah dikembalikan') {
                        statusClass = 'bg-green-500'; // Hijau
                    }

                    rows += `<div class="flex gap-5 border-2 border-black p-3">
                               <div class="block">
                                   <img src="${book.cover}" width="100" alt="Cover Buku"></img>
                                   <h1>${book.judul_buku}</h1>
                                   <p>${book.penerbit}</p>
                               </div>
                               <div class="w-2/3">
                                   <h1 class="text-lg font-bold">${book.judul_buku}</h1>
                                   <p>${book.penerbit}</p>
                                   <p class="${statusClass} text-white py-1 px-2 rounded w-40">${book.status_peminjaman}</p>
                               </div>
                             </div>`;
                });
                $('#book-table').html(rows);
            }
        });
    });
});
    </script>
</body>
</html>
