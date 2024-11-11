<?php

require_once '../core/Database.php';
// require_once '../Models/user.php';


class Kunjungan {
    private $conn;
    private $table_name = 'kunjungan';
    public $id_user;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Function to get all visit data
    public function getAllVisits($id_user) {
        $query = "SELECT k.id_kunjungan, u.nama_lengkap, u.kelas, u.no_kartu, k.tanggal_kunjungan, k.keperluan 
                  FROM " . $this->table_name . " k 
                  JOIN user u ON k.id_user = u.id_user
                  WHERE k.id_user =:id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}