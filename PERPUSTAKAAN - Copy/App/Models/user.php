<?php

require_once '../../core/Database.php';

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

        // Periksa apakah koneksi berhasil
        if (!$this->conn) {
            die("Koneksi ke database gagal: " . print_r($this->conn->errorInfo(), true));
        }
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

     // Fungsi untuk registrasi pengguna
     public function register() {
        // Query untuk memasukkan data user
        $query = "INSERT INTO " . $this->table_name . " 
        SET nama_lengkap=:nama_lengkap, nis=:nis, no_kartu=:no_kartu, no_whatsapp=:no_whatsapp, 
            password=:password, roles=:roles";

        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->nis = htmlspecialchars(strip_tags($this->nis));
        $this->no_kartu = htmlspecialchars(strip_tags($this->no_kartu));
        $this->no_whatsapp = htmlspecialchars(strip_tags($this->no_whatsapp));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->roles = htmlspecialchars(strip_tags($this->roles));

        // Bind data
        $stmt->bindParam(":nama_lengkap", $this->nama_lengkap);
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":no_kartu", $this->no_kartu);
        $stmt->bindParam(":no_whatsapp", $this->no_whatsapp);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":roles", $this->roles);
        
        // Eksekusi query dan simpan session
        try {
            if ($stmt->execute()) {
                // Menyimpan id_user di session
                $this->id_user = $this->conn->lastInsertId();
                $_SESSION['id_user'] = $this->id_user;
                $_SESSION['nama_lengkap'] = $this->nama_lengkap;
                $_SESSION['roles'] = $this->roles;
        
                return true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        
        return false;
    }






    
    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE no_kartu = :no_kartu LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':no_kartu', $this->no_kartu);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Debugging data user yang ditemukan
            error_log('User found: ' . print_r($row, true));
            error_log('Password hash in DB: ' . $row['password']);
            error_log('Password entered by user: ' . $this->password);
    
            // Verifikasi password yang dimasukkan dengan yang ada di database
            if (password_verify($this->password, $row['password'])) {
                // Menyimpan data user di dalam properti objek
                $this->id_user = $row['id_user']; // Simpan id_user dari hasil query
                $this->roles = $row['roles'];
                $this->no_kartu = $row['no_kartu'];
    
                // Menyimpan data ke dalam sesi
                $_SESSION['id_user'] = $this->id_user;
                $_SESSION['no_kartu'] = $this->no_kartu;
                $_SESSION['roles'] = $this->roles;
    
                return true; // Login berhasil
            } else {
                error_log('Password verification failed');
            }
        } else {
            error_log('No user found with no_kartu: ' . $this->no_kartu);
        }
        return false;
    }
    
    
    

    // Fungsi untuk cek role
    public function checkRole()
    {
        if ($this->roles === 'admin') {
            // Redirect ke halaman admin
            header("Location: admin_dashboard.php");
        } elseif ($this->roles === 'petugas') {
            // Redirect ke halaman petugas
            header("Location: petugas_dashboard.php");
        } elseif ($this->roles === 'user') {
            // Redirect ke halaman user
            header("Location: user_dashboard.php");
        } else {
            // Jika tidak ada role yang cocok
            header("Location: login.php?error=Role tidak ditemukan");
        }
    }

    public function getUserByNoKartu($no_kartu) {
        // Query untuk mencari user berdasarkan no_kartu
        $query = "SELECT id_user, nama_lengkap, kelas, no_kartu 
                  FROM user 
                  WHERE no_kartu = :no_kartu 
                  LIMIT 1";
    
        // Persiapkan statement
        $stmt = $this->conn->prepare($query);
    
        // Bind parameter no_kartu ke query
        $stmt->bindParam(':no_kartu', $no_kartu);
    
        // Eksekusi query
        $stmt->execute();
    
        // Ambil hasil
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Jika ditemukan, kembalikan data user
        if ($user) {
            return $user;
        }
    
        // Jika tidak ditemukan, kembalikan false
        return false;
    }
    
// Fungsi untuk absensi perpustakaan
public function absenPerpustakaan() {
    $query = "INSERT INTO kunjungan (id_user, tanggal_kunjungan, waktu_kunjungan, keperluan) 
              VALUES (:id_user, CURDATE(), CURTIME(), 'Mengunjungi Perpustakaan')";

    // Persiapkan query
    $stmt = $this->conn->prepare($query);

    // Bind parameter
    $stmt->bindParam(':id_user', $this->id_user);

    // Eksekusi query
    if ($stmt->execute()) {
        return true;  // Absensi berhasil
    }

    return false;  // Absensi gagal
}
    public function getUserData($id_user) {
        // Query untuk mengambil data pengguna termasuk password dari database
        $query = "SELECT nama_lengkap, nis, nisn, kelas, no_whatsapp, no_kartu, password FROM " . $this->table_name . " WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT); // Bind parameter untuk keamanan
        $stmt->execute();
    
        // Mengembalikan hasil sebagai array asosiatif
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

 
    

    function encryptPassword($password, $encryption_key) {
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted_password = openssl_encrypt($password, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($iv . $encrypted_password);
    }
    
    

    function decryptPassword($encrypted_password, $encryption_key) {
        $data = base64_decode($encrypted_password);
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $iv_length);
        $encrypted_password = substr($data, $iv_length);
        return openssl_decrypt($encrypted_password, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
    
    
    

  
public function updatePassword($id_user, $new_password) {
    // Melakukan hashing terhadap password baru
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Query untuk memperbarui password di database
    $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id_user = :id_user";
    $stmt = $this->conn->prepare($query);

    // Bind parameter untuk keamanan
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

    // Menjalankan query dan mengembalikan hasilnya
    return $stmt->execute();
}

    public function updateNISN($id_user, $nisn) {
        // Query untuk memperbarui NISN di database
        $query = "UPDATE " . $this->table_name . " SET nisn = :nisn WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
    
        // Bind parameter untuk keamanan
        $stmt->bindParam(':nisn', $nisn, PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    
        // Menjalankan query dan mengembalikan hasilnya
        return $stmt->execute();
    }
    
        
}


?>
