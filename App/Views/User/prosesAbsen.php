    <?php

    session_start();

    // Memeriksa apakah pengguna sudah login
    if (!isset($_SESSION['no_kartu'])) {
        header("Location: ..//Views/auth/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }

    require_once '../../core/Database.php';
    require_once '../../Models/user.php';

    // Inisiasi database
    $database = new Database();
    $db = $database->getConnection();

    // Inisiasi objek user
    $user = new User($db);

    // Cek apakah form di-submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $user->nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '';
        $user->kelas = isset($_POST['kelas']) ? $_POST['kelas'] : '';
        $user->no_kartu = isset($_POST['no_kartu']) ? $_POST['no_kartu'] : '';

        // Cari user berdasarkan nomor kartu (no_kartu) di tabel user
        $userInfo = $user->getUserByNoKartu($user->no_kartu);

        if ($userInfo) {
            // Jika user ditemukan, lakukan proses absensi
            $user->id_user = $userInfo['id_user'];

            // Panggil fungsi absenPerpustakaan dari class User
            if ($user->absenPerpustakaan()) {
                // Jika absensi berhasil, alihkan user ke halaman successPage.php
                header("Location: ../dataPeminjaman.php");
                exit(); // Pastikan script berhenti setelah redirect
            } else {
                // Jika absensi gagal, alihkan ke userabsen.php dengan pesan error
                header("Location: userabsen.php?error=Gagal+melakukan+absensi");
                exit();
            }
        } else {
            // Jika no_kartu tidak ditemukan, alihkan ke userabsen.php dengan pesan error
            header("Location: userabsen.php?error=Nomor+kartu+belum+terdaftar");
            exit();
        }
    }
    ?>
