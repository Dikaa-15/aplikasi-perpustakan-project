<?php
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Ambil koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Buku
$buku = new Buku($db);

// Ambil data pencarian dari permintaan POST
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Cek apakah ada query pencarian
if ($query != '') {
    // Panggil fungsi pencarian berdasarkan judul buku
    $stmt = $buku->searchByTitle($query);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Jika query kosong, kembalikan semua data buku
    $stmt = $buku->read();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Kembalikan hasil dalam format JSON
echo json_encode($rows);
?>
