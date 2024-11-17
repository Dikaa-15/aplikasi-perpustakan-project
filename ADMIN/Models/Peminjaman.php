<?php
date_default_timezone_set('Asia/Jakarta');
class Peminjaman {
    private $conn;
    private $table = 'peminjaman';
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Fetch all borrowings
    public function getPeminjamanToday() {
        $query = "SELECT p.id_peminjaman, u.nama_lengkap, u.kelas, u.no_kartu, b.judul_buku, p.kuantitas_buku, 
                  p.tanggal_peminjaman, p.tanggal_kembalian, p.status_peminjaman
                  FROM " . $this->table . " p
                  JOIN user u ON p.id_user = u.id_user
                  JOIN buku b ON p.id_buku = b.id_buku
                  WHERE DATE(p.tanggal_peminjaman) = CURDATE()";  // Filter peminjaman hari ini
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllPeminjaman() {
        $query = "SELECT p.id_peminjaman, u.nama_lengkap, u.kelas, u.no_kartu, b.judul_buku, p.kuantitas_buku, 
                  p.tanggal_peminjaman, p.tanggal_kembalian, p.status_peminjaman
                  FROM " . $this->table . " p
                  JOIN user u ON p.id_user = u.id_user
                  JOIN buku b ON p.id_buku = b.id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    


    // Delete borrowing
    public function deletePeminjaman($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function searchPeminjaman($query) {
        $query = '%' . $query . '%';
        $sql = "SELECT p.id_peminjaman, u.nama_lengkap, u.kelas, u.no_kartu, b.judul_buku, p.kuantitas_buku,
                p.tanggal_peminjaman, p.tanggal_kembalian, p.status_peminjaman
                FROM " . $this->table . " p
                JOIN user u ON p.id_user = u.id_user
                JOIN buku b ON p.id_buku = b.id_buku
                WHERE u.nama_lengkap LIKE :query OR b.judul_buku LIKE :query";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status_peminjaman =:status WHERE id_peminjaman =:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function getPeminjamanById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
}
?>