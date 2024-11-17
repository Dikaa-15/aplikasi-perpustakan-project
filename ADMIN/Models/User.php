<?php

class User {
    private $conn;
    private $table_name = "user";

    public $id_user;
    public $nama_lengkap;
    public $nis;
    public $nisn;
    public $kelas;
    public $no_whatsapp;
    public $password;
    public $no_kartu;
    public $roles;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama_lengkap=:nama_lengkap, nis=:nis, nisn=:nisn, kelas=:kelas, no_whatsapp=:no_whatsapp, password=:password, no_kartu=:no_kartu, roles=:roles";

        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->nis = htmlspecialchars(strip_tags($this->nis));
        $this->nisn = htmlspecialchars(strip_tags($this->nisn));
        $this->kelas = htmlspecialchars(strip_tags($this->kelas));
        $this->no_whatsapp = htmlspecialchars(strip_tags($this->no_whatsapp));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->no_kartu = htmlspecialchars(strip_tags($this->no_kartu));
        $this->roles = htmlspecialchars(strip_tags($this->roles));

        // Bind data
        $stmt->bindParam(":nama_lengkap", $this->nama_lengkap);
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":nisn", $this->nisn);
        $stmt->bindParam(":kelas", $this->kelas);
        $stmt->bindParam(":no_whatsapp", $this->no_whatsapp);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":no_kartu", $this->no_kartu);
        $stmt->bindParam(":roles", $this->roles);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_lengkap=:nama_lengkap, nis=:nis, nisn=:nisn, kelas=:kelas, no_whatsapp=:no_whatsapp, password=:password, no_kartu=:no_kartu, roles=:roles WHERE id_user=:id_user";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_user", $this->id_user);
        $stmt->bindParam(":nama_lengkap", $this->nama_lengkap);
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":nisn", $this->nisn);
        $stmt->bindParam(":kelas", $this->kelas);
        $stmt->bindParam(":no_whatsapp", $this->no_whatsapp);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":no_kartu", $this->no_kartu);
        $stmt->bindParam(":roles", $this->roles);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_user = :id_user";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_user", $this->id_user);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function search($query) {
        $query = "%" . htmlspecialchars(strip_tags($query)) . "%";
        $sql = "SELECT * FROM " . $this->table_name . " WHERE nama_lengkap LIKE :query OR nis LIKE :query OR kelas LIKE :query";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":query", $query);
        $stmt->execute();
        return $stmt;
    }
}


?>