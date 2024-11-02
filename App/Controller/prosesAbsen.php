<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['no_kartu'])) {
    header("Location: ../auth/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

require_once '../core/Database.php';
require_once '../Models/user.php';

// Inisiasi database
$database = new Database();
$db = $database->getConnection();

// Inisiasi objek user
$user = new User($db);

header('Content-Type: application/json'); // Set header JSON untuk response

// Cek apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $user->nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '';
    $user->kelas = isset($_POST['kelas']) ? $_POST['kelas'] : '';
    $user->no_kartu = isset($_POST['no_kartu']) ? $_POST['no_kartu'] : '';

    // Cari user berdasarkan nomor kartu (no_kartu) di tabel user
    $userInfo = $user->getUserByNoKartu($user->no_kartu);

    if ($userInfo) {
        // Jika user ditemukan, cek apakah user sudah absen hari ini
        $user->id_user = $userInfo['id_user'];

        if ($user->cekAbsensiHariIni()) {
            // Jika sudah absen hari ini, kembalikan pesan error
            echo json_encode(['status' => 'error', 'message' => 'Anda sudah absen hari ini']);
            exit();
        }

        // Jika belum absen hari ini, lakukan proses absensi
        if ($user->absenPerpustakaan()) {
            // Jika absensi berhasil
            echo json_encode(['status' => 'success', 'message' => 'Absensi berhasil']);
            exit();
        } else {
            // Jika absensi gagal
            echo json_encode(['status' => 'error', 'message' => 'Gagal melakukan absensi']);
            exit();
        }
    } else {
        // Jika no_kartu tidak ditemukan
        echo json_encode(['status' => 'error', 'message' => 'Nomor kartu belum terdaftar']);
        exit();
    }
}
?>
