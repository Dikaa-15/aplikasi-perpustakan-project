<?php
class Buku
{
    private $conn;
    private $table_name = "buku";

    public $id_buku;
    public $kode_buku;
    public $judul_buku;
    public $kategori;
    public $cover;
    public $penerbit;
    public $sinopsis;
    public $stok_buku;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create buku
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                        SET kode_buku=:kode_buku, judul_buku=:judul_buku, kategori=:kategori, 
                            cover=:cover, penerbit=:penerbit, sinopsis=:sinopsis, stok_buku=:stok_buku";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->kode_buku = htmlspecialchars(strip_tags($this->kode_buku));
        $this->judul_buku = htmlspecialchars(strip_tags($this->judul_buku));
        $this->cover = basename($_FILES["cover"]["name"]); // Simpan hanya nama file
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->penerbit = htmlspecialchars(strip_tags($this->penerbit));
        $this->sinopsis = htmlspecialchars(strip_tags($this->sinopsis));
        $this->stok_buku = htmlspecialchars(strip_tags($this->stok_buku));

        // Batasi panjang data kategori jika diperlukan
        $this->kategori = substr($this->kategori, 0, 100);  // Misalkan kolom di database maksimal 100 karakter



        // Proses upload dan validasi file
        $target_dir = "uploads/";
        $target_file = $target_dir . $_FILES["cover"]["name"];

        // Validasi ukuran file (contoh: maksimal 2MB)
        if ($_FILES["cover"]["size"] > 2000000) {
            echo "Ukuran file terlalu besar.";
            return false;
        }

        // Validasi jenis file (hanya JPG, JPEG, PNG)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
            return false;
        }

        // Cek apakah file berhasil diupload
        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
            $this->cover = basename($target_file); // Simpan hanya nama file
        }


        // Bind values
        $stmt->bindParam(":kode_buku", $this->kode_buku);
        $stmt->bindParam(":judul_buku", $this->judul_buku);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":cover", $this->cover);
        $stmt->bindParam(":penerbit", $this->penerbit);
        $stmt->bindParam(":sinopsis", $this->sinopsis);
        $stmt->bindParam(":stok_buku", $this->stok_buku);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }


    // Read all buku
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read one buku
    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_buku = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_buku);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->kode_buku = $row['kode_buku'];
            $this->judul_buku = $row['judul_buku'];
            $this->kategori = $row['kategori'];
            $this->cover = $row['cover'];
            $this->penerbit = $row['penerbit'];
            $this->sinopsis = $row['sinopsis'];
            $this->stok_buku = $row['stok_buku'];
        } else {
            // Jika tidak ada data, Anda bisa mengatur nilai default atau menampilkan pesan
            $this->kode_buku = null;
            $this->judul_buku = null;
            $this->kategori = null;
            $this->cover = null;
            $this->penerbit = null;
            $this->sinopsis = null;
            $this->stok_buku = null;

            echo "Buku tidak ditemukan.";
        }
    }

    // Update buku
    public function update($id)
    {
        $query = "UPDATE " . $this->table_name . " 
                          SET kode_buku=:kode_buku, judul_buku=:judul_buku, kategori=:kategori, 
                              cover=:cover, penerbit=:penerbit, sinopsis=:sinopsis, stok_buku=:stok_buku
                          WHERE id_buku = :id_buku";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->kode_buku = htmlspecialchars(strip_tags($this->kode_buku));
        $this->judul_buku = htmlspecialchars(strip_tags($this->judul_buku));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->sinopsis = htmlspecialchars(strip_tags($this->sinopsis));
        $this->stok_buku = htmlspecialchars(strip_tags($this->stok_buku));

        // Handle cover file update (new cover)
        if (!empty($_FILES["cover"]["name"])) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["cover"]["name"]);
            
            // Validate file size
            if ($_FILES["cover"]["size"] > 2000000) {
                echo "Ukuran file terlalu besar.";
                return false;
            }
        
            // Validate file format
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
                return false;
            }
        
            // Try uploading the file
            if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
                // Set the new cover filename
                $this->cover = basename($target_file);
        
                // Optionally, you can remove the old cover if you want to delete it
                // For example:
                $oldCover = $this->getOldCover($id); // Retrieve the old cover name
                unlink("uploads/" . $oldCover); // Delete the old cover file
            } 
        }
        
        // Display the cover being used (new file)
        // echo "Cover yang digunakan: " . $this->cover;

        // Bind parameters with the new cover
        $stmt->bindParam(":kode_buku", $this->kode_buku);
        $stmt->bindParam(":judul_buku", $this->judul_buku);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":cover", $this->cover);
        $stmt->bindParam(":penerbit", $this->penerbit);
        $stmt->bindParam(":sinopsis", $this->sinopsis);
        $stmt->bindParam(":stok_buku", $this->stok_buku);
        $stmt->bindParam(":id_buku", $id);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }




    // Delete buku
    public function delete()
    {
        // Ambil cover lama dari database sebelum menghapus
        // $this->deleteOldCover();

        $query = "DELETE FROM " . $this->table_name . " WHERE id_buku = ?";
        $stmt = $this->conn->prepare($query);
        $this->id_buku = htmlspecialchars(strip_tags($this->id_buku));
        $stmt->bindParam(1, $this->id_buku);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tambahkan deleteOldCover() disini
    // private function deleteOldCover() {
    //     // Ambil data buku berdasarkan id_buku
    //     $query = "SELECT cover FROM " . $this->table_name . " WHERE id_buku = ? LIMIT 0,1";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(1, $this->id_buku);
    //     $stmt->execute();

    //     // Ambil cover buku yang lama
    //     $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //     $oldCover = $row['cover'];

    //     // Cek jika file cover lama ada dan hapus
    //     if ($oldCover && file_exists("uploads/" . $oldCover)) {
    //         unlink("uploads/" . $oldCover);
    //     }
    // }

    public function getOldCover($id)
    {
        $query = "SELECT cover FROM " . $this->table_name . " WHERE id_buku = :id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_buku", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cover']; // Mengembalikan nama file cover lama
    }
    public function searchByTitle($keyword) {
        $query = "SELECT * FROM buku WHERE judul_buku LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    
}
