<?php
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

$buku = new Buku($db);
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

// Tampilkan data awal atau data pencarian berdasarkan kondisi
if ($query === '') {
    $stmt = $buku->readLimited(4); // Data default (4 atau jumlah sesuai kebutuhan)
} else {
    $stmt = $buku->searchByTitle($query); // Data pencarian
}

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($books);
exit;
