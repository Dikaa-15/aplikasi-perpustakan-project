<?php
require_once '../Controller/peminjamancontroller.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $peminjamanController = new PeminjamanController();
    if ($status == 'sudah dikembalikan') {
        $tanggal_kembalian = date('Y-m-d H:i:s'); // Mengambil tanggal dan waktu saat ini
        $success = $peminjamanController->updateStatus($id, $status, $tanggal_kembalian);
    } else {
        $success = $peminjamanController->updateStatus($id, $status);
    }

    if ($success) {
        header("Location: viewpeminjaman.php?message=success_update");
    } else {
        header("Location: viewpeminjaman.php?message=error_update");
    }
} else {
    header("Location: viewpeminjaman.php");
}
exit();
