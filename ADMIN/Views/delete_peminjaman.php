<?php
require_once '../Models/Peminjaman.php';
require_once '../Controller/PeminjamanController.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $peminjamanController = new PeminjamanController();
    $result = $peminjamanController->deletePeminjaman($id);

    if ($result) {
        // Redirect ke halaman viewpeminjaman.php dengan pesan sukses
        header(header: "Location: ./viewpeminjaman.php?message=success_delete");
    } else {
        // Redirect ke halaman viewpeminjaman.php dengan pesan error
        header("Location: ./viewpeminjaman.php?message=error_delete");
    }
} else {
    // Jika tidak ada ID yang diberikan, redirect ke halaman viewpeminjaman.php
    header("Location: ./viewpeminjaman.php");
}
exit();
