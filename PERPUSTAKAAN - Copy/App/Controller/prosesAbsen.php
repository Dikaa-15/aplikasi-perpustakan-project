<?php
require_once '../core/Database.php';
require_once '../Models/user.php';

// Inisiasi database
$database = new Database();
$db = $database->getConnection();

// Inisiasi objek user
$user = new User($db);

if (!empty($user->nama_lengkap) && !empty($user->kelas) && !empty($user->no_kartu)) {
    // Lanjutkan ke proses absen
} else {
    echo "Data absensi tidak lengkap. Harap isi semua field.";
}


// Cek apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $user->nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '';
    $user->kelas = isset($_POST['kelas']) ? $_POST['kelas'] : '';
    $user->no_kartu = isset($_POST['no_kartu']) ? $_POST['no_kartu'] : '';

    // Set id_user ke null karena user tidak perlu login
    $user->id_user = null;  // Jika id_user tidak diperlukan, bisa diabaikan atau set ke null

    // Panggil fungsi absenPerpustakaan dari class User
    if ($user->absenPerpustakaan()) {
        // Jika absensi berhasil, alihkan user ke halaman successPage.php
        header("Location: ../Views/successPage.php");
        exit(); // Pastikan script berhenti setelah redirect
    } else {
        // Jika absensi gagal, tampilkan pesan error
        echo "Gagal melakukan absensi.";
    }
}
?>
