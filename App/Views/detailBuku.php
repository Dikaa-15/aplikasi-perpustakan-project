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
$buku->getDetailBuku($id_buku);

// Ambil data pengguna dari session
$no_kartu = $_SESSION['no_kartu'];
$user_query = "SELECT * FROM user WHERE no_kartu = :no_kartu";
$user_stmt = $db->prepare($user_query);
$user_stmt->bindParam(':no_kartu', $no_kartu);
$user_stmt->execute();
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

// Cek jika pengguna tidak ditemukan
if (!$user) {
    die('ERROR: Pengguna tidak ditemukan.');
}

// Jika form disubmit
if ($_POST) {
    // Validasi input
    $jumlah_buku = isset($_POST['jumlah_buku']) ? intval($_POST['jumlah_buku']) : 0;
    $tanggal_peminjaman = isset($_POST['tanggal_peminjaman']) ? $_POST['tanggal_peminjaman'] : '';
    $tanggal_kembalian = isset($_POST['tanggal_kembalian']) ? $_POST['tanggal_kembalian'] : '';

    // Validasi input tanggal
    if (empty($tanggal_peminjaman)) {
        echo json_encode(["status" => "error", "message" => "Tanggal peminjaman harus diisi."]);
        exit();
    }

    if (empty($tanggal_kembalian)) {
        echo json_encode(["status" => "error", "message" => "Tanggal pengembalian harus diisi."]);
        exit();
    }

    // Validasi format tanggal
    $date_format = 'Y-m-d';
    $valid_peminjaman = DateTime::createFromFormat($date_format, $tanggal_peminjaman);
    $valid_kembalian = DateTime::createFromFormat($date_format, $tanggal_kembalian);

    if (!$valid_peminjaman || !$valid_kembalian) {
        echo json_encode(["status" => "error", "message" => "Format tanggal tidak valid."]);
        exit();
    }

    if ($jumlah_buku > $buku->stok_buku) {
        echo json_encode(["status" => "error", "message" => "Jumlah buku yang diminta melebihi stok yang tersedia."]);
        exit();
    } else {
        // Assign waktu peminjaman dan waktu pengembalian default
        $waktu_peminjaman = date('H:i:s');  // Waktu sekarang
        $waktu_kembalian = $waktu_peminjaman;  // Menggunakan waktu peminjaman sebagai default untuk waktu_kembalian

        // Cek jika nama_petugas ada di $user, jika tidak ada beri nilai default
        $nama_petugas = !empty($user['nama_petugas']) ? $user['nama_petugas'] : 'Tidak Diketahui';


        $query = "INSERT INTO peminjaman SET
    id_user = :id_user,
    id_buku = :id_buku,
    kuantitas_buku = :kuantitas_buku,
    tanggal_peminjaman = :tanggal_peminjaman,
    waktu_peminjaman = :waktu_peminjaman,
    tanggal_kembalian = :tanggal_kembalian,
    waktu_kembalian = :waktu_kembalian,
    status_peminjaman = 'sedang dipinjam',
    nama_petugas = :nama_petugas";  // Masukkan nama_petugas yang tidak NULL

        $stmt = $db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id_user', $user['id_user']);
        $stmt->bindParam(':id_buku', $buku->id_buku);
        $stmt->bindParam(':kuantitas_buku', $jumlah_buku);
        $stmt->bindParam(':tanggal_peminjaman', $tanggal_peminjaman);
        $stmt->bindParam(':waktu_peminjaman', $waktu_peminjaman);
        $stmt->bindParam(':tanggal_kembalian', $tanggal_kembalian);
        $stmt->bindParam(':waktu_kembalian', $waktu_kembalian);
        $stmt->bindParam(':nama_petugas', $nama_petugas);  // Pastikan ini tidak NULL


        // Eksekusi query
        if ($stmt->execute()) {
            // Kurangi stok buku
            $update_stock_query = "UPDATE buku SET stok_buku = stok_buku - :kuantitas_buku WHERE id_buku = :id_buku";
            $update_stock_stmt = $db->prepare($update_stock_query);
            $update_stock_stmt->bindParam(':kuantitas_buku', $jumlah_buku);
            $update_stock_stmt->bindParam(':id_buku', $buku->id_buku);
            $update_stock_stmt->execute();

            header('Location: ./successPage.php');
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat meminjam buku."]);
        }
    }
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="../../output.css" rel="stylesheet" />
</head>

<body>

    <?= require_once '../Template/header.php' ?>
    <div class="container mx-auto mt-5">


        <!-- Main Content Detail Buku Section Start -->
        <section class="pt-28 pb-36">
            <div class="w-full px-4">
                <div class="container mx-auto">
                    <div class="grid lg:grid-cols-3 justify-center">
                        <!-- Buku Start -->
                        <div class="mb-2 md:mb-6 lg:mb-0">
                            <div
                                class="w-full mx-auto md:w-[90%] lg:mx-0 xl:w-[380px] xl:h-[500px] rounded-md">
                                <img
                                    src="../../public//assets//Books/<?= htmlspecialchars($buku->cover); ?>" alt="cover buku"

                                    class="w-96 object-cover rounded-lg" />
                            </div>
                        </div>
                        <!-- Buku End -->

                        <!-- Title Start -->
                        <div class="lg:col-span-2 lg:ml-20 xl:ml-0">
                            <!-- Judul Buku Start -->
                            <div class="pt-2">
                                <h2 class="text-2xl md:text-[28px] font-bold mb-1 md:mb-2">
                                    <?= htmlspecialchars($buku->judul_buku) ?>
                                </h2>
                                <p class="font-normal text-sm md:text-lg text-grey mb-6">Penerbit : 
                                    <?= htmlspecialchars($buku->penerbit) ?>
                                </p>

                                <p
                                    class="font-normal text-sm md:text-lg text-slate-500 w-full mb-5 md:mb-8">
                                    <?= htmlspecialchars($buku->sinopsis) ?>
                                </p>

                                <p
                                    class="font-normal text-sm md:text-lg text-slate-500 mb-4 md:mb-5">Buku Tersedia : 
                                    <?= htmlspecialchars($buku->stok_buku) ?>
                                </p>

                                <?php if ($buku->stok_buku > 0): ?>
                                <button
                                    id="openModal"
                                    class="block w-full md:w-[30%] lg:w-[40%] xl:w-[30%]">
                                    <a
                                        href="#"
                                        class="px-8 py-3 block w-full rounded-full bg-main text-white text-sm hover:bg-white hover:text-main border hover:border-main transition-all duration-300">Pinjam Buku</a>
                                </button>
                                <?php else : ?>
                                <button
                                    
                                    class="block w-full md:w-[30%] lg:w-[40%] xl:w-[30%]">
                                    <a
                                        href="#"
                                        class="px-8 py-3 block w-full rounded-full bg-gray-500 text-white text-sm cursor-not-allowed">Pinjam Buku</a>
                                </button>
                                <?php endif; ?>
                            </div>
                            <!-- Judul Buku End -->
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
            </div>
        </section>
        <!-- Main Content Detail Buku Section End -->
        <!-- Form Peminjaman Buku -->
        <?php if ($buku->stok_buku > 0): ?>
            <!-- <form id="form-peminjaman" action="" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-5">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="<?= htmlspecialchars($user['nama_lengkap']); ?>" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                    <input type="text" name="kelas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="<?= htmlspecialchars($user['kelas']); ?>" readonly>
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
            </form> -->
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div
        id="myModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-y-scroll pt-32 md:pt-96">
        <div
            id="modalContent"
            class="bg-white rounded-3xl shadow-lg px-6 py-4 w-[90%] md:w-[70%] modal-enter">
            <!-- Header Modal Start -->
            <div class="relative mb-8 md:mb-12">
                <h2 class="text-lg md:text-2xl font-bold md:text-center">
                    Atur Waktu Peminjaman
                </h2>
                <button
                    id="closeModal"
                    class="text-gray-500 hover:text-gray-700 text-2xl md:text-4xl absolute top-0 right-0">
                    &times;
                </button>
            </div>
            <!-- Header Modal End -->

            <!-- Form Modal Start -->
            <?php if ($buku->stok_buku > 0): ?>
                <form method="post">
                    <div class="mb-3 md:mb-6">
                        <label
                            for="name"
                            class="block text-lg font-normal text-gray-700 mb-2">Nama Lengkap</label>
                        <input
                            type="text"
                            id="name"
                            placeholder="Masukkan Nama Lengkap"
                            class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full"
                            value="<?= htmlspecialchars($user['nama_lengkap']); ?>" readonly />
                    </div>

                    <div class="mb-3 md:mb-6">
                        <label
                            for="name"
                            class="block text-lg font-normal text-gray-700 mb-2">Kelas</label>
                        <input
                            type="text"
                            id="name"
                            placeholder="Kelas..."
                            class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-smmd:text-[16px] sm:leading-6 rounded-full"
                            value="<?= htmlspecialchars($user['kelas']); ?>" readonly />
                    </div>

                    <div class="mb-3 md:mb-6">
                        <label
                            for="name"
                            class="block text-lg font-normal text-gray-700 mb-2">No.Kartu</label>
                        <input
                            type="text"
                            id="name"
                            placeholder="Masukkan No Kartu Anda"
                            class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full"
                            value="<?= htmlspecialchars($user['no_kartu']); ?>" readonly />
                    </div>

                    <div class="mb-3 md:mb-6">
                        <label
                            for="name"
                            class="block text-lg font-normal text-gray-700 mb-2">Judul Buku</label>
                        <input
                            type="text"
                            id="name"
                            placeholder="Masukkan Judul Buku"
                            class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full"
                            value="<?= htmlspecialchars($buku->judul_buku); ?>" readonly />
                    </div>

                    <div class="mb-3 md:mb-6">
                        <label
                            for="jumlah_buku"
                            class="block text-lg font-normal text-gray-700 mb-2">Jumlah Buku</label>
                        <input
                            type="text"
                            id="jumlah_buku"
                            name="jumlah_buku"
                            placeholder="Masukkan jumlah buku yang ingin dipinjam"
                            required
                            class="w-full block flex-1 border border-slate-400 bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
                    </div>

                    <div class="mb-3 md:mb-6">
                        <label
                            for="tanggal_peminjaman"
                            class="block text-lg font-normal text-gray-700 mb-2">Tanggal Peminjaman</label>
                        <input
                            name="tanggal_peminjaman"
                            type="date"
                            id="tanggal_peminjaman"
                            placeholder="mm/dd/yy"
                            required
                            class="w-full block flex-1 border border-slate-400 bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
                    </div>

                    <div class="mb-3 md:mb-6">
                        <label
                            for="tanggal_kembalian"
                            class="block text-lg font-normal text-gray-700 mb-2">Tanggal Kembalikan</label>
                        <input
                            name="tanggal_kembalian"
                            type="date"
                            id="tanggal_kembalian"
                            placeholder="mm/dd/yy"
                            required
                            class="w-full block flex-1 border border-slate-400 bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
                    </div>

                    <div class="flex gap-2 items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            required
                            class="w-5 h-5" />
                        <p class="text-[12px] font-normal md:text-sm">
                            Saya telah membaca dan menyetujui Syarat dan Ketentuan
                        </p>
                    </div>

                    <!-- Buttom Form Submit Start -->
                    <div class="flex justify-center mt-6">
                        <button
                            type="submit"
                            class="bg-main text-white px-4 py-2 block w-full rounded-full">
                            Pinjam Buku
                        </button>
                    </div>

                    <!-- Buttom Form Submit End -->
                </form>
            <?php endif; ?>
            <!-- Form Modal End -->
        </div>
    </div>
    <?= require_once '../Template/footer.php' ?>

    <!-- Scripts JS Start -->
    <script>
        // Navbar Fixed
        const menu = document.getElementById("menu");
        const faBars = document.querySelector(".fa-bars");
        const navMobile = document.getElementById("navMobile");

        menu.addEventListener("click", function() {
            faBars.classList.toggle("fa-x");
            navMobile.classList.toggle("hidden");
        });

        // Modal Start
        document.addEventListener("DOMContentLoaded", () => {
            const openModalButton = document.getElementById("openModal");
            const closeModalButton = document.getElementById("closeModal");
            const modal = document.getElementById("myModal");
            const modalContent = document.querySelector(".modal-enter");

            openModalButton.addEventListener("click", () => {
                modal.classList.remove("hidden");
                setTimeout(() => {
                    modalContent.classList.add("modal-enter-active");
                }, 10);
            });

            closeModalButton.addEventListener("click", () => {
                modalContent.classList.remove("modal-enter-active");
                modalContent.classList.add("modal-leave-active");
                setTimeout(() => {
                    modal.classList.add("hidden");
                    modalContent.classList.remove("modal-leave-active");
                }, 300);
            });

            window.addEventListener("click", (event) => {
                if (event.target === modal) {
                    modalContent.classList.remove("modal-enter-active");
                    modalContent.classList.add("modal-leave-active");
                    setTimeout(() => {
                        modal.classList.add("hidden");
                        modalContent.classList.remove("modal-leave-active");
                    }, 300);
                }
            });
        });
    </script>
    <!-- Scripts JS End -->
</body>

</html>