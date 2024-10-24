<?php
require_once './Peminjaman.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $tanggal_kembalian = date('Y-m-d H:i:s'); // Tanggal saat ini

    // Panggil fungsi updateStatus dari controller yang sudah memiliki koneksi
    $peminjamanController = new PeminjamanController();
    $success = $peminjamanController->updateStatus($id, $status, $tanggal_kembalian);

//     if ($success) {
//         echo "<script>
//                 Swal.fire({
//                     title: 'Status Updated',
//                     text: 'Status peminjaman berhasil diupdate',
//                     icon: 'success'
//                 }).then(() => {
//                     window.location.href = 'viewpeminjaman.php'; // Redirect ke halaman viewpeminjaman.php
//                 });
//               </script>";
//     } else {
//         echo "<script>
//                 Swal.fire({
//                     title: 'Error',
//                     text: 'Gagal mengupdate status peminjaman',
//                     icon: 'error'
//                 }).then(() => {
//                     window.location.href = 'viewpeminjaman.php';
//                 });
//               </script>";
//     }
// } else {
//     echo "Invalid request!";
// }



if ($success) {
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

?>
