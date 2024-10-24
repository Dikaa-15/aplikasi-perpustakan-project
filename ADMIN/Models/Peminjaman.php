<?php
class Peminjaman {
    private $conn;
    private $table = 'peminjaman';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all borrowings
    public function getPeminjamanToday() {
        $query = "SELECT p.id_peminjaman, u.nama_lengkap, u.kelas, u.no_kartu, b.judul_buku, p.kuantitas_buku, 
                  p.tanggal_peminjaman, p.waktu_peminjaman, p.tanggal_kembalian, p.waktu_kembalian, p.status_peminjaman
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
                  p.tanggal_peminjaman, p.waktu_peminjaman, p.tanggal_kembalian, p.waktu_kembalian, p.status_peminjaman
                  FROM " . $this->table . " p
                  JOIN user u ON p.id_user = u.id_user
                  JOIN buku b ON p.id_buku = b.id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update borrowing status
    // Update borrowing status and set return date
    public function updateStatus($id, $status, $tanggal_kembalian) {
        $query = "UPDATE " . $this->table . " 
                SET status_peminjaman = :status, tanggal_kembalian = :tanggal_kembalian 
                WHERE id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':tanggal_kembalian', $tanggal_kembalian);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


    // Delete borrowing
    public function deletePeminjaman($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_peminjaman = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>