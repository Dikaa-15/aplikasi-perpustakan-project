<?php


require_once '../core/Database.php';

class Kunjungan {
    private $conn;
    private $table_name = 'kunjungan';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Function to get all visit data
    public function getAllVisits() {
        $query = "SELECT k.id_kunjungan, u.nama_lengkap, u.kelas, u.no_kartu, k.tanggal_kunjungan, k.keperluan 
                  FROM " . $this->table_name . " k 
                  JOIN user u ON k.id_user = u.id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Function to delete a visit by ID
    public function deleteVisit($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kunjungan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
