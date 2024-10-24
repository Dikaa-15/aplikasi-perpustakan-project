<?php
require_once '../core/Database.php';
require_once '../Models/Peminjaman.php';

class PeminjamanController {
    private $peminjaman;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->peminjaman = new Peminjaman($db);
    }

    // Fetch all borrowings
    public function getAllPeminjaman() {
        return $this->peminjaman->getAllPeminjaman();
    }

    public function getPeminjamanToday() {
        return $this->peminjaman->getPeminjamanToday();
    }
    

    // Update borrowing status
    public function updateStatus($id, $status, $tanggal_kembalian) {
        // Gunakan instance $this->peminjaman yang sudah ada
        return $this->peminjaman->updateStatus($id, $status, $tanggal_kembalian);
    }

    // Delete borrowing
    public function deletePeminjaman($id) {
        return $this->peminjaman->deletePeminjaman($id);
    }
}
?>