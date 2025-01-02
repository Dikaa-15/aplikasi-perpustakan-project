    <?php

    include_once '../core/Database.php';

    class Buku
    {
        private $conn;
        private $table_name = "buku"; // Nama tabel di database

        public $id_buku;
        public $kode_buku;
        public $judul_buku;
        public $kategori;
        public $cover;
        public $penerbit;
        public $sinopsis;
        public $stok_buku;
        public $url_buku;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        // Fungsi untuk menampilkan semua data buku
        public function read()
        {
            // Query untuk mengambil semua data buku
            $query = "SELECT id_buku, kode_buku, judul_buku, cover, sinopsis, penerbit FROM " . $this->table_name;

            // Menyiapkan statement
            $stmt = $this->conn->prepare($query);

            // Eksekusi query
            $stmt->execute();

            return $stmt;
        }
        public function searchBooks($judul_buku)
        {
            $query = "SELECT b.id_buku, b.judul_buku, b.cover, b.penerbit 
                      FROM buku b 
                      WHERE b.judul_buku = :judul_buku";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':judul_buku', $judul_buku, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $stmt;
            } else {
                error_log("Query Error: " . implode(" | ", $stmt->errorInfo()));
                return false;
            }
        }

        public function readPaginated($offset, $limit)
        {
            $query = "SELECT id_buku, kode_buku, judul_buku, cover, sinopsis, penerbit 
                      FROM " . $this->table_name . " 
                      LIMIT :offset, :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        }


        public function readByKategori($kategori)
        {
            $query = "SELECT id_buku, kode_buku, judul_buku, cover, sinopsis, penerbit
            FROM " . $this->table_name . " WHERE kategori = :kategori";

            $stmt = $this->conn->prepare($query);

            // bind parameter
            $stmt->bindParam(':kategori', $kategori, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt;
        }

        public function search($query, $id_user)
        {
            // Query untuk mencari buku berdasarkan judul dengan LIKE dan id_user
            $search_query = "
                SELECT b.id_buku, b.judul_buku, b.sinopsis, b.cover, b.penerbit, p.status_peminjaman 
                FROM buku b
                LEFT JOIN peminjaman p ON b.id_buku = p.id_buku 
                WHERE p.id_user = :id_user
                AND b.judul_buku LIKE :search_term";

            // Menyiapkan statement
            $stmt = $this->conn->prepare($search_query);

            // Menambahkan wildcards untuk pencarian parsial
            $search_term = "%{$query}%";

            // Bind parameter pencarian dan id_user
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':search_term', $search_term);

            // Eksekusi query
            $stmt->execute();

            return $stmt; // Kembalikan statement untuk di-fetch
        }

        public function searchByTitle($query)
        {
            // Query untuk mencari buku berdasarkan judul dengan batas 1 hasil
            $search_query = "
                SELECT b.id_buku, b.judul_buku, b.cover, b.penerbit 
                FROM buku b
                WHERE b.judul_buku LIKE :search_term
                LIMIT 1"; // Menambahkan LIMIT 1

            // Menyiapkan statement
            $stmt = $this->conn->prepare($search_query);

            // Menambahkan wildcards untuk pencarian parsial
            $search_term = "%{$query}%";
            $stmt->bindParam(':search_term', $search_term);

            // Eksekusi query
            $stmt->execute();

            return $stmt; // Kembalikan statement untuk di-fetch
        }

        public function getDetailBuku($id_buku)
        {
            // Query untuk mendapatkan detail buku
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_buku = :id_buku LIMIT 1";

            // Menyiapkan statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(':id_buku', $id_buku, PDO::PARAM_INT);

            // Eksekusi query
            $stmt->execute();

            // Mengambil data buku
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Cek apakah data ditemukan
            if (!$row) {
                return false; // Data tidak ditemukan
            }

            // Set properti buku
            $this->id_buku = $row['id_buku'];
            $this->kode_buku = $row['kode_buku'];
            $this->judul_buku = $row['judul_buku'];
            $this->kategori = $row['kategori'];
            $this->cover = $row['cover'];
            $this->penerbit = $row['penerbit'];
            $this->sinopsis = $row['sinopsis'];
            $this->stok_buku = $row['stok_buku'];
            $this->url_buku = $row['url_buku'];

            return true; // Data ditemukan
        }

        public function getPeminjaman($id_user)
        {
            $query = "
            SELECT p.id_peminjaman, b.judul_buku, b.cover, p.tanggal_kembalian, p.status_peminjaman, b.sinopsis, b.penerbit 
            FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_user = :id_user
            ORDER BY p.tanggal_peminjaman DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            return $stmt;
        }


        // Fungsi lain sesuai kebutuhan
    }
    ?>
