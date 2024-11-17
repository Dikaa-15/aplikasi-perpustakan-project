<?php
require_once '../core/Database.php';
require_once '../Models/peminjaman.php';

class PeminjamanController {
    private $peminjaman;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->peminjaman = new Peminjaman($db);
    }

    public function searchPeminjaman($query) {
        return $this->peminjaman->searchPeminjaman($query);
    }
    

    // Fetch all borrowings
    public function getAllPeminjaman() {
        return $this->peminjaman->getAllPeminjaman();
    }

    public function getPeminjamanToday() {
        return $this->peminjaman->getPeminjamanToday();
    }
    
    public function updateStatus($id, $status) {
        return $this->peminjaman->updateStatus($id, $status);
    }
    
    public function getPeminjamanById($id) {
        return $this->peminjaman->getPeminjamanById($id);
    }
    
    
    // Delete borrowing
    public function deletePeminjaman($id) {
        return $this->peminjaman->deletePeminjaman($id);
    }
    // Di dalam PeminjamanController.php
  
    

}
?>