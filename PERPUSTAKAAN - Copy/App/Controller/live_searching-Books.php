<?php
// Mulai sesi jika dibutuhkan untuk memeriksa login (jika ada)
session_start();

// Sertakan file koneksi dan model Buku
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat instance dari kelas Buku
$buku = new Buku($db);

// Ambil query pencarian dari permintaan POST
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Memanggil method search tanpa id_user, hanya berdasarkan judul buku
$stmt = $buku->searchByTitle($query);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kembalikan hasil pencarian dalam bentuk JSON
echo json_encode($books);
?>
