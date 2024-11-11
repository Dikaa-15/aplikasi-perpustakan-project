<?php

include_once __DIR__ . '/../core/Database.php';

class User
{
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
    public $profil_user;

    public function __construct($db)
    {
        $this->conn = $db;

        // Periksa apakah koneksi berhasil
        if (!$this->conn) {
            die("Koneksi ke database gagal: " . print_r($this->conn->errorInfo(), true));
        }
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
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

    public function update()
    {
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

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_user = :id_user";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_user", $this->id_user);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function register()
    {
        $queryCheck = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE no_kartu = :no_kartu OR password = :password";
        $stmtCheck = $this->conn->prepare($queryCheck);

        $stmtCheck->bindParam(":no_kartu", $this->no_kartu);
        $stmtCheck->bindParam(":password", $this->password);

        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            return "Nomor kartu perpustakaan atau password sudah digunakan. Silakan gunakan yang lain.";
        }

        $query = "INSERT INTO " . $this->table_name . " 
            SET nama_lengkap=:nama_lengkap, nis=:nis, nisn=:nisn, no_kartu=:no_kartu, kelas=:kelas, no_whatsapp=:no_whatsapp, 
                password=:password, roles=:roles";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->nama_lengkap = htmlspecialchars(strip_tags($this->nama_lengkap));
        $this->nis = htmlspecialchars(strip_tags($this->nis));
        $this->nisn = htmlspecialchars(strip_tags($this->nisn));
        $this->no_kartu = htmlspecialchars(strip_tags($this->no_kartu));
        $this->kelas = htmlspecialchars(strip_tags($this->kelas));
        $this->no_whatsapp = htmlspecialchars(strip_tags($this->no_whatsapp));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->roles = htmlspecialchars(strip_tags($this->roles));

        $stmt->bindParam(":nama_lengkap", $this->nama_lengkap);
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":nisn", $this->nisn);
        $stmt->bindParam(":no_kartu", $this->no_kartu);
        $stmt->bindParam(":kelas", $this->kelas);
        $stmt->bindParam(":no_whatsapp", $this->no_whatsapp);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":roles", $this->roles);

        if ($stmt->execute()) {
            // Simpan data pengguna ke dalam session setelah registrasi berhasil
            $this->id_user = $this->conn->lastInsertId();

            // Menyimpan data pengguna ke dalam session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['id_user'] = $this->id_user;
            $_SESSION['no_kartu'] = $this->no_kartu;
            $_SESSION['roles'] = $this->roles;
            $_SESSION['nama_lengkap'] = $this->nama_lengkap;
            // Anda bisa menambahkan gambar profil jika ada, misalnya:
            $_SESSION['profil_user'] = ""; // Atur path gambar profil jika ada

            return true; // Registrasi berhasil
        }

        return false; // Jika tidak berhasil
    }


    public function login()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE no_kartu = :no_kartu LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':no_kartu', $this->no_kartu);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->password, $row['password'])) {
                // Simpan data user ke dalam properti objek
                $this->id_user = $row['id_user'];
                $this->roles = $row['roles'];
                $this->no_kartu = $row['no_kartu'];
                $this->nama_lengkap = $row['nama_lengkap'];
                $this->profil_user = $row['profil_user']; // Misalnya untuk path gambar profil

                // Simpan data user ke sesi
                $_SESSION['user_logged_in'] = true;
                $_SESSION['id_user'] = $this->id_user;
                $_SESSION['no_kartu'] = $this->no_kartu;
                $_SESSION['roles'] = $this->roles;
                $_SESSION['nama_lengkap'] = $this->nama_lengkap;
                $_SESSION['profil_user'] = $this->profil_user;

                return true; // Login berhasil
            }
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

    public function getUserByNoKartu($no_kartu)
    {
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

    public function absenPerpustakaan()
    {
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



    // Fungsi untuk memeriksa apakah user sudah absen hari ini
    public function cekAbsensiHariIni()
    {
        // Periksa apakah ada entri kunjungan untuk id_user dan tanggal hari ini
        $query = "SELECT * FROM kunjungan WHERE id_user = :id_user AND tanggal_kunjungan = CURDATE()";

        // Persiapkan query
        $stmt = $this->conn->prepare($query);

        // Bind parameter id_user
        $stmt->bindParam(':id_user', $this->id_user);

        // Eksekusi query
        $stmt->execute();

        // Debugging jumlah baris
        error_log('Jumlah baris ditemukan: ' . $stmt->rowCount());

        // Jika ada hasil (rowCount > 0), maka user sudah absen
        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    // Fungsi untuk mencatat kunjungan (absensi) baru untuk user
    public function recordVisit()
    {
        $query = "INSERT INTO kunjungan (id_user, tanggal_kunjungan, waktu_kunjungan, keperluan) 
                  VALUES (:id_user, CURDATE(), CURTIME(), 'Absensi Perpustakaan')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $this->id_user);
        return $stmt->execute();
    }

    public function getUserProfile($id_user)
    {
        // Query untuk mendapatkan data profil pengguna berdasarkan id_user
        $query = "SELECT nama_lengkap, profil_user FROM user WHERE id_user = :id_user";

        // Persiapkan query
        $stmt = $this->conn->prepare($query);

        // Bind parameter id_user
        $stmt->bindParam(':id_user', $id_user);

        // Eksekusi query
        $stmt->execute();

        // Ambil hasil query sebagai array asosiatif
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika data ditemukan, kembalikan data tersebut; jika tidak, kembalikan nilai default
        if ($result) {
            return $result;
        } else {
            return [
                'nama_lengkap' => 'Nama Tidak Ditemukan',
                'profil_user' => 'default_profile.png' // Nama file gambar default jika profil tidak ada
            ];
        }
    }


    public function getUserData($id_user)
    {
        $query = "SELECT nama_lengkap, nis, nisn, kelas, no_whatsapp, no_kartu, password, profil_user FROM " . $this->table_name . " WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateProfilePhoto($id_user, $photo_path)
    {
        $query = "UPDATE " . $this->table_name . " SET profil_user = :profile_photo WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':profile_photo', $photo_path); // Perbaiki parameter ini
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function verifyPassword($id_user, $input_password)
    {
        // Ambil password hash dari database berdasarkan id_user
        $query = "SELECT password FROM " . $this->table_name . " WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        $hashed_password = $stmt->fetchColumn();

        // Jika password hash ditemukan, verifikasi dengan password_verify
        if ($hashed_password) {
            return password_verify($input_password, $hashed_password);
        }

        // Jika tidak ditemukan password hash, return false
        return false;
    }


    function encryptPassword($password, $encryption_key)
    {
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted_password = openssl_encrypt($password, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($iv . $encrypted_password);
    }


    function decryptPassword($encrypted_password, $encryption_key)
    {
        $data = base64_decode($encrypted_password);
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $iv_length);
        $encrypted_password = substr($data, $iv_length);
        return openssl_decrypt($encrypted_password, 'aes-256-cbc', $encryption_key, 0, $iv);
    }


    public function updatePassword($id_user, $new_password)
    {
        // Hash password baru menggunakan password_hash
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Query untuk memperbarui password di database
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);

        // Bind parameter untuk keamanan
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Menjalankan query dan mengembalikan hasilnya (true jika berhasil)
        return $stmt->execute();
    }

    public function updateUserData($id_user, $nama_lengkap, $kelas, $no_whatsapp, $no_kartu)
    {
        $query = "UPDATE " . $this->table_name . " SET nama_lengkap = :nama_lengkap, kelas = :kelas, no_whatsapp = :no_whatsapp, no_kartu = :no_kartu WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':nama_lengkap', $nama_lengkap, PDO::PARAM_STR);
        $stmt->bindParam(':kelas', $kelas, PDO::PARAM_STR);
        $stmt->bindParam(':no_whatsapp', $no_whatsapp, PDO::PARAM_STR);
        $stmt->bindParam(':no_kartu', $no_kartu, PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        return $stmt->execute();
    }




    public function updateNISN($id_user, $nisn)
    {
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
