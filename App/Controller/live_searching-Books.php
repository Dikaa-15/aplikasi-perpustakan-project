<?php
session_start();
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi model Buku
$buku = new Buku($db);

// Ambil query pencarian dari permintaan POST
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Memanggil metode pencarian
$stmt = $buku->searchByTitle($query);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kembalikan hasil pencarian dalam bentuk JSON
echo json_encode($books);
exit;
