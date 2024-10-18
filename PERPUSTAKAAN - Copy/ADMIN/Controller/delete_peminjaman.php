<?php
require_once '../Controller/Peminjaman.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $peminjamanController = new PeminjamanController();
    $result = $peminjamanController->deletePeminjaman($id);

    if ($result) {
        // Redirect ke halaman viewpeminjaman.php dengan pesan sukses
        header("Location: ../Views/Peminjaman.php?message=success_delete");
    } else {
        // Redirect ke halaman viewpeminjaman.php dengan pesan error
        header("Location: ../Views/Peminjaman.php?message=error_delete");
    }
} else {
    // Jika tidak ada ID yang diberikan, redirect ke halaman viewpeminjaman.php
    header("Location: ../Views/Peminjaman.php");
}
exit();
