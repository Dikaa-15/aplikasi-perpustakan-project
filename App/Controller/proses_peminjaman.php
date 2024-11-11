<?php
require_once './App/core/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = $_POST['id_buku'];
    $kuantitas_buku = $_POST['kuantitas_buku'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $lama_peminjaman = $_POST['lama_peminjaman']; // Dapatkan lama peminjaman dari form
    // Ambil nama_petugas dari sesi atau sumber lain jika diperlukan
    session_start();
    $nama_petugas = isset($_SESSION['nama_petugas']) ? $_SESSION['nama_petugas'] : 'Tidak Diketahui';

    // Inisialisasi koneksi database
    $database = new Database();
    $db = $database->getConnection();

    // Menghitung tanggal_kembalian berdasarkan tanggal_peminjaman + lama_peminjaman
    $start_date = DateTime::createFromFormat('Y-m-d', $tanggal_peminjaman);
    $end_date = clone $start_date;
    $end_date->modify("+$lama_peminjaman days");
    $tanggal_kembalian = $end_date->format('Y-m-d');

    // Insert data peminjaman
    $query = "INSERT INTO peminjaman (id_buku, kuantitas_buku, tanggal_peminjaman, tanggal_kembalian, status_peminjaman, nama_petugas) 
              VALUES (:id_buku, :kuantitas_buku, :tanggal_peminjaman, :tanggal_kembalian, 'proses', :nama_petugas)";
    
    $stmt = $db->prepare($query);

    // Bind parameter
    $stmt->bindParam(':id_buku', $id_buku);
    $stmt->bindParam(':kuantitas_buku', $kuantitas_buku);
    $stmt->bindParam(':tanggal_peminjaman', $tanggal_peminjaman);
    $stmt->bindParam(':tanggal_kembalian', $tanggal_kembalian);
    $stmt->bindParam(':nama_petugas', $nama_petugas);

    // Eksekusi query
    if ($stmt->execute()) {
        echo "Peminjaman berhasil ditambahkan.";
    } else {
        echo "Gagal menambahkan peminjaman.";
    }
}
?>
