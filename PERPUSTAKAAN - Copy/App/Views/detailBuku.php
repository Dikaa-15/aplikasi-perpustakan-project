<?php
session_start();

// Sertakan file koneksi ke database dan model Buku
include_once '../core/Database.php'; 
include_once '../Models/Buku.php'; 

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['no_kartu'])) {
    header("Location: ./auth/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// Mendapatkan id_buku dari URL
$id_buku = isset($_GET['id_buku']) ? $_GET['id_buku'] : die('ERROR: Buku tidak ditemukan.');

// Membuat instance dari kelas Buku
$buku = new Buku($db);
$buku->getDetailBuku($id_buku); // Pastikan ada fungsi ini di kelas Buku

// Ambil data pengguna
$no_kartu = $_SESSION['no_kartu'];
$user_query = "SELECT * FROM user WHERE no_kartu = :no_kartu"; // Ubah ke no_kartu
$user_stmt = $db->prepare($user_query);
$user_stmt->bindParam(':no_kartu', $no_kartu);
$user_stmt->execute();
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

// Cek jika pengguna tidak ditemukan
if (!$user) {
    die('ERROR: Pengguna tidak ditemukan.');
}

if ($_POST) {
    // Validasi jumlah buku
    if ($_POST['jumlah_buku'] > $buku->stok_buku) {
        echo "<div>Jumlah buku yang diminta melebihi stok yang tersedia.</div>";
    } else {
        // Menyimpan data ke tabel peminjaman
        $query = "INSERT INTO peminjaman SET
            id_user = :id_user,
            id_buku = :id_buku,
            kuantitas_buku = :kuantitas_buku,
            tanggal_peminjaman = :tanggal_peminjaman,
            waktu_peminjaman = :waktu_peminjaman,
            tanggal_kembalian = :tanggal_kembalian,
            waktu_kembalian = :waktu_kembalian,
            status_peminjaman = 'sedang dipinjam',
            nama_petugas = :nama_petugas"; // Pastikan ini ada di query

        $stmt = $db->prepare($query);

        // Assign variables
        $kuantitas_buku = $_POST['jumlah_buku'];
        $tanggal_peminjaman = $_POST['tanggal_peminjaman']; // Format: YYYY-MM-DD
        $tanggal_kembalian = $_POST['tanggal_pengembalian']; // Format: YYYY-MM-DD
        $waktu_peminjaman = date('H:i:s'); // Format: HH:MM:SS
        $waktu_kembalian = date('H:i:s'); // Diisi NULL karena buku belum dikembalikan

        // Bind parameters
        $stmt->bindParam(':id_user', $user['id_user']);
        $stmt->bindParam(':id_buku', $buku->id_buku);
        $stmt->bindParam(':kuantitas_buku', $kuantitas_buku);
        $stmt->bindParam(':tanggal_peminjaman', $tanggal_peminjaman);
        $stmt->bindParam(':waktu_peminjaman', $waktu_peminjaman);
        $stmt->bindParam(':tanggal_kembalian', $tanggal_kembalian);
        $stmt->bindValue(':waktu_kembalian', $waktu_kembalian);

        // Jika nama petugas tidak ada, gunakan nilai default
        $nama_petugas = !empty($user['nama_petugas']) ? $user['nama_petugas'] : 'Tidak Diketahui';
        $stmt->bindParam(':nama_petugas', $nama_petugas);

        // Eksekusi query
        if ($stmt->execute()) {
            // Kurangi stok buku
            $update_stock_query = "UPDATE buku SET stok_buku = stok_buku - :kuantitas_buku WHERE id_buku = :id_buku";
            $update_stock_stmt = $db->prepare($update_stock_query);
            $update_stock_stmt->bindParam(':kuantitas_buku', $kuantitas_buku);
            $update_stock_stmt->bindParam(':id_buku', $buku->id_buku);
            $update_stock_stmt->execute();

            // Setelah berhasil, arahkan ke successPage.php dengan pesan sukses
            header("Location: successPage.php");
            exit();
        } else {
            echo "<div>Terjadi kesalahan saat meminjam buku.</div>";
        }
    }
}



// Tampilkan detail buku
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-5">
        <div class="flex">
            <div class="w-1/3">
                <!-- Cover Buku -->
                <img src="../../public//assets//Books// <? htmlspecialchars($buku->cover); ?>" alt="Cover Buku" class="w-full h-auto">
            </div>
            <div class="w-2/3 ml-5">
                <!-- Detail Buku -->
                <h1 class="text-3xl font-bold"><?= htmlspecialchars($buku->judul_buku); ?></h1>
                <p><strong>Penerbit:</strong> <?= htmlspecialchars($buku->penerbit); ?></p>
                <p><strong>Sinopsis:</strong> <?= htmlspecialchars($buku->sinopsis); ?></p>
                <p><strong>Stok Buku:</strong> <?= htmlspecialchars($buku->stok_buku); ?></p>

                <!-- Tombol Pinjam -->
                <?php if ($buku->stok_buku > 0): ?>
                    <a href="#form-peminjaman" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-5 inline-block">
                        Pinjam Buku
                    </a>
                <?php else: ?>
                    <button style="background-color: #ECE6FF;" class="text-white font-bold py-2 px-4 mt-5 cursor-not-allowed" disabled>
                        Pinjam Buku
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Form Peminjaman Buku -->
        <?php if ($buku->stok_buku > 0): ?>
        <form id="form-peminjaman" action="" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-5">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="<?= htmlspecialchars($user['nama_lengkap']); ?>" readonly>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                <input type="text" name="kelas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">No. Kartu</label>
                <input type="text" name="no_kartu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="<?= htmlspecialchars($user['no_kartu']); ?>" readonly>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Judul Buku</label>
                <input type="text" name="judul_buku" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="<?= htmlspecialchars($buku->judul_buku); ?>" readonly>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Buku</label>
                <input type="number" name="jumlah_buku" placeholder="Masukkan jumlah buku yang ingin dipinjam..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Peminjaman</label>
                <input type="date" name="tanggal_peminjaman" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengembalian</label>
                <input type="date" name="tanggal_pengembalian" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-6">
                <input type="checkbox" name="syarat" class="mr-2 leading-tight" required>
                <label class="text-sm">Saya telah membaca dan menyetujui Syarat dan Ketentuan</label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Pinjam Buku</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
