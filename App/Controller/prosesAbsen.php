<?php
session_start();

include_once '../core/Database.php';
include_once '../Models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $noKartu = trim($_POST['no_kartu'] ?? '');

    if (empty($namaLengkap) || empty($kelas) || empty($noKartu)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi.']);
        exit();
    }

    $user->nama_lengkap = $namaLengkap;
    $user->kelas = $kelas;
    $user->no_kartu = $noKartu;

    $userInfo = $user->getUserByNoKartu($noKartu);

    if ($userInfo) {
        $user->id_user = $userInfo['id_user'];

        if ($user->cekAbsensiHariIni()) {
            echo json_encode(['status' => 'error', 'message' => 'Anda sudah absen hari ini.']);
            exit();
        }

        if ($user->absenPerpustakaan()) {
            echo json_encode(['status' => 'success', 'message' => 'Absen berhasil.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal melakukan absensi.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nomor kartu belum terdaftar.']);
    }
    exit();
}
?>
