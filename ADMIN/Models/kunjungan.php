<?php
require_once '../core/database.php';

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

    // Function to get duplicate visit data
public function getDuplicateVisits() {
    $query = "SELECT k.id_kunjungan, u.nama_lengkap, u.kelas, u.no_kartu, k.tanggal_kunjungan, k.keperluan 
              FROM " . $this->table_name . " k 
              JOIN user u ON k.id_user = u.id_user
              WHERE u.nama_lengkap IN (
                  SELECT u2.nama_lengkap 
                  FROM " . $this->table_name . " k2
                  JOIN user u2 ON k2.id_user = u2.id_user
                  GROUP BY u2.nama_lengkap
                  HAVING COUNT(u2.nama_lengkap) > 1
              )";
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
