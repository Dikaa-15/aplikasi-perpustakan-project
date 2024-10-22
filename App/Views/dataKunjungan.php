<?php
session_start();

// Sertakan file koneksi dan class Buku
require_once './../core/Database.php';
require_once '../Models/Buku.php';

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data peminjaman milik pengguna yang sedang login
$query2 = "SELECT p.*, b.cover, b.judul_buku, b.penerbit, p.status_peminjaman
          FROM peminjaman p
          JOIN buku b ON p.id_buku = b.id_buku 
          WHERE p.id_user = :id_user";

$stmt = $db->prepare($query2);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();

// Ambil semua data untuk pencarian
$allBooksStmt = $db->prepare("SELECT p.*, b.cover, b.judul_buku, b.penerbit, p.status_peminjaman
                               FROM peminjaman p
                               JOIN buku b ON p.id_buku = b.id_buku 
                               WHERE p.id_user = :id_user");
$allBooksStmt->bindParam(':id_user', $id_user);
$allBooksStmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-5">
        <h1 class="text-3xl font-bold mb-5">Data Peminjaman Buku</h1>

        <input type="text" id="search" placeholder="Cari judul buku..." class="focus:outline outline-gray-500 mb-5">

        <div class="space-y-4" id="book-table">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-purple-600 text-white">
                        <th class="py-2">No</th>
                        <th class="py-2">Foto Buku</th>
                        <th class="py-2">Judul Buku</th>
                        <th class="py-2">Batas Waktu</th>
                        <th class="py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td class="border border-gray-300 text-center"><?= $no++; ?></td>
                        <td class="border border-gray-300 text-center">
                            <img src="<?= htmlspecialchars($row['cover']); ?>" alt="Foto Buku" class="w-20 h-30 object-cover">
                        </td>
                        <td class="border border-gray-300 text-center"><?= htmlspecialchars($row['judul_buku']); ?></td>
                        <td class="border border-gray-300 text-center">-</td> <!-- Ganti dengan tanggal batas waktu jika ada -->
                        <td class="border border-gray-300 text-center">
                            <span class="
                                <?= $row['status_peminjaman'] == 'sedang dipinjam' ? 'bg-red-500' : ($row['status_peminjaman'] == 'sudah dikembalikan' ? 'bg-green-500' : 'bg-gray-500') ?> 
                                text-white py-1 px-2 rounded">
                                <?= htmlspecialchars($row['status_peminjaman']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: '../Controller/live_searching.php',  // Pastikan URL ini sesuai
                    method: 'POST',
                    data: {query: query},
                    dataType: 'json',
                    success: function(data) {
                        var rows = '';
                        var no = 1; // Inisialisasi nomor
                        data.forEach(function(book) {
                            var statusClass = '';
                            if (book.status_peminjaman === 'proses') {
                                statusClass = 'bg-gray-500';
                            } else if (book.status_peminjaman === 'sedang dipinjam') {
                                statusClass = 'bg-red-500';
                            } else if (book.status_peminjaman === 'sudah selesai') {
                                statusClass = 'bg-green-500';
                            }

                            rows += `<tr>
                                        <td class="border border-gray-300 text-center">${no++}</td>
                                        <td class="border border-gray-300 text-center">
                                            <img src="${book.cover}" alt="Foto Buku" class="w-20 h-30 object-cover">
                                        </td>
                                        <td class="border border-gray-300 text-center">${book.judul_buku}</td>
                                        <td class="border border-gray-300 text-center">-</td> <!-- Ganti dengan tanggal batas waktu jika ada -->
                                        <td class="border border-gray-300 text-center">
                                            <span class="${statusClass} text-white py-1 px-2 rounded">${book.status_peminjaman}</span>
                                        </td>
                                    </tr>`;
                        });
                        $('#book-table tbody').html(rows); // Update isi tbody dengan hasil pencarian
                    }
                });
            });
        });
    </script>
</body>
</html>
