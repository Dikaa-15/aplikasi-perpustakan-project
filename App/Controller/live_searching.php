<?php
session_start();  // Memulai sesi untuk memastikan user login

// Sertakan file koneksi dan model Buku
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat instance dari kelas Buku
$buku = new Buku($db);

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo json_encode([]);
    exit();
}

$id_user = $_SESSION['id_user'];

// Mengambil data pencarian dari permintaan POST
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Memanggil method search untuk mengambil hasil pencarian berdasarkan id_user
$stmt = $buku->search($query, $id_user);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengembalikan hasil pencarian dalam bentuk JSON
echo json_encode($books);
?>
