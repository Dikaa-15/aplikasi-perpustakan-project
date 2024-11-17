<?php

session_start();

// Sertakan file koneksi ke database dan model Buku
include_once '../core/Database.php';
include_once '../Models/Buku.php';
include_once '../Models/user.php';

// Mengatur zona waktu
date_default_timezone_set('Asia/Jakarta');

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
  $jumlah_buku = isset($_POST['kuantitas_buku']) ? intval($_POST['kuantitas_buku']) : 0;
  $tanggal_peminjaman = isset($_POST['tanggal_peminjaman']) ? $_POST['tanggal_peminjaman'] : '';
  $lama_peminjaman = isset($_POST['lama_peminjaman']) ? intval($_POST['lama_peminjaman']) : 0;

  // Validasi input tanggal
  if (empty($tanggal_peminjaman)) {
    echo json_encode(["status" => "error", "message" => "Tanggal peminjaman harus diisi."]);
    exit();
  }

  if ($lama_peminjaman <= 0) {
    echo json_encode(["status" => "error", "message" => "Lama peminjaman harus lebih dari 0 hari."]);
    exit();
  }

  // Validasi format tanggal dengan datetime format
  $date_format = 'Y-m-d H:i:s';
  $valid_peminjaman = DateTime::createFromFormat('Y-m-d', $tanggal_peminjaman);

  if (!$valid_peminjaman) {
    echo json_encode(["status" => "error", "message" => "Format tanggal tidak valid."]);
    exit();
  }

  // Konversi tanggal_peminjaman menjadi format datetime
  $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
  $start_date = DateTime::createFromFormat('Y-m-d', $tanggal_peminjaman);
  $start_date->setTime(0, 0); // Set ke jam 00:00:00
  $tanggal_peminjaman_datetime = $start_date->format('Y-m-d H:i:s');

  // Hitung tanggal_kembalian berdasarkan tanggal_peminjaman + lama_peminjaman dalam format datetime
  $end_date = clone $start_date;
  $end_date->modify("+$lama_peminjaman days");
  $tanggal_kembalian = $end_date->format($date_format);

  if ($jumlah_buku > $buku->stok_buku) {
    echo json_encode(["status" => "error", "message" => "Jumlah buku yang diminta melebihi stok yang tersedia."]);
    exit();
  } else {
    // Waktu peminjaman saat ini
    $waktu_peminjaman = date('H:i:s');  // Waktu sekarang

    // Cek jika nama_petugas ada di $user, jika tidak ada beri nilai default
    $nama_petugas = !empty($user['nama_petugas']) ? $user['nama_petugas'] : 'Tidak Diketahui';

    // Lakukan INSERT ke tabel peminjaman dengan format datetime
    $query = "INSERT INTO peminjaman SET
            id_user = :id_user,
            id_buku = :id_buku,
            kuantitas_buku = :kuantitas_buku,
            tanggal_peminjaman = :tanggal_peminjaman,
            waktu_peminjaman = :waktu_peminjaman,
            lama_peminjaman = :lama_peminjaman,
            tanggal_kembalian = :tanggal_kembalian,
            waktu_kembalian = NULL,
            status_peminjaman = 'sedang dipinjam',
            nama_petugas = :nama_petugas";

    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(':id_user', $user['id_user']);
    $stmt->bindParam(':id_buku', $buku->id_buku);
    $stmt->bindParam(':kuantitas_buku', $jumlah_buku);
    $stmt->bindParam(':tanggal_peminjaman', $tanggal_peminjaman_datetime);
    $stmt->bindParam(':waktu_peminjaman', $waktu_peminjaman);
    $stmt->bindParam(':lama_peminjaman', $lama_peminjaman);
    $stmt->bindParam(':tanggal_kembalian', $tanggal_kembalian);
    $stmt->bindParam(':nama_petugas', $nama_petugas);

    // Eksekusi query INSERT
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

// header section for profile

// Database connection initialization if needed
if (!isset($db)) {
  require_once '../core/Database.php';
  $database = new Database();
  $db = $database->getConnection();
}

// Get user profile if logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
  $user = new User($db);
  $profil = $user->getUserProfile($_SESSION['id_user']);
  $_SESSION['profil_user'] = $profil['profil_user']; // Update session to ensure consistency
  $_SESSION['nama_lengkap'] = $profil['nama_lengkap'];
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="../../output.css" rel="stylesheet" />
  <title>Detail Buku</title>
  <link
    rel="shortcut icon"
    href="../public/image/logo/logo 1.png"
    type="image/x-icon" />
  <!-- Font Family -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap"
    rel="stylesheet" />

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
</head>

<body class="scroll-smooth">
  <!-- Navbar Section Start -->
  <header class="w-full py-4 md:py-2 fixed top-0 left-0 bg-white shadow-md">
    <nav class="container mx-auto px-4 flex justify-between items-center">
      <!-- Content Left -->
      <div class="md:py-5 text-3xl font-bold capitalize">
        <a href="./Landing-page.php" class=""><img src="../../public/logo 1.png" alt="" /></a>
      </div>

      <!-- Content Center -->
      <div class="">
        <ul class="hidden md:flex items-center gap-2">
          <li class="group px-3 py-1">
            <a
              href="index.html"
              class="font-normal text-sm lg:text-[1.1rem] text-slate-800 group-hover:text-main">Beranda</a>
          </li>
          <li class="group px-3 py-1">
            <a
              href="index.html"
              class="font-normal text-sm lg:text-[1.1rem] text-slate-800 group-hover:text-main">Tentang Kami</a>
          </li>
          <li class="group px-3 py-1">
            <a
              href="daftarBuku.html"
              class="font-normal text-sm lg:text-[1.1rem] text-slate-800 group-hover:text-main">Kategori Buku</a>
          </li>
          <li class="group px-3 py-1">
            <a
              href="index.html"
              class="font-normal text-sm lg:text-[1.1rem] text-slate-800 group-hover:text-main">FAQ</a>
          </li>
        </ul>
      </div>

      <!-- Nav Content End Start -->
      <div class="hidden md:flex items-center gap-1">
        <div class="w-8 h-8">
          <img
            src="../../public/profile//<?php echo htmlspecialchars($_SESSION['profil_user'] ?? 'default_profile.png'); ?>"
            alt=""
            class="w-full h-full rounded-full object-cover" />
        </div>
        <p class="font-normal text-sm"><?php echo $_SESSION['nama_lengkap']; ?></p>
        <img src="../public/svg/arrow-down.svg" alt="" class="w-4 h-4" />
      </div>
      <!-- Nav Content End End -->

      <!-- Hamburger Menu -->

      <div id="navMobile" class="hidden transition-all duration-300">
        <ul
          class="p-5 absolute h-56 right-7 left-24 top-20 inset-0 bg-white capitalize z-50 shadow-lg space-y-2">
          <li>
            <a
              href=""
              class="hover:text-lime-500 text-[16px] transition-all duration-300">Beranda</a>
          </li>
          <li>
            <a
              href=""
              class="hover:text-lime-500 text-[16px] transition-all duration-300">Kelas</a>
          </li>
          <li>
            <a
              href=""
              class="hover:text-lime-500 text-[16px] transition-all duration-300">Alur Belajar</a>
          </li>
          <li>
            <a
              href=""
              class="hover:text-lime-500 text-[16px] transition-all duration-300">Event</a>
          </li>
          <!-- Nav Content End Start -->
          <div class="flex items-center gap-1 pt-3">
            <div class="w-8 h-8">
              <img
                src="../public/image/Image Profile.png"
                alt=""
                class="w-full h-full" />
            </div>
            <p class="font-normal text-sm">Reyhan Fadillah</p>
            <img src="../public/svg/arrow-down.svg" alt="" class="w-4 h-4" />
          </div>
          <!-- Nav Content End End -->
        </ul>
      </div>

      <div
        id="menu"
        class="md:hidden cursor-pointer transition-all duration-300 z-50">
        <i class="fa-solid fa-bars text-2xl"></i>
      </div>
    </nav>
  </header>
  <!-- Navbar Section End -->

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
                src="../../public//assets//Books/<?= htmlspecialchars($buku->cover); ?>"
                alt=""
                class="w-full h-full object-cover" />
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
              <p class="font-normal text-sm md:text-lg text-grey mb-6">
                <?= htmlspecialchars($buku->penerbit) ?>
              </p>

              <p
                class="font-normal text-sm md:text-lg text-slate-500 w-full mb-5 md:mb-8">
                <?= htmlspecialchars($buku->sinopsis) ?>
              </p>

              <p
                class="font-normal text-sm md:text-lg text-slate-500 mb-4 md:mb-5">
                Buku Tersedia : <?= htmlspecialchars($buku->stok_buku) ?>
              </p>

              <div class="flex items-center gap-4">
                <button
                  id="openModal"
                  class="block w-full md:w-[30%] lg:w-[40%] xl:w-[30%]">
                  <a
                    href="#"
                    class="px-8 py-3 block w-full rounded-full bg-main text-white text-sm hover:bg-white hover:text-main border hover:border-main transition-all duration-300">Pinjam Buku</a>
                </button>
                <a href="https://buku.kemdikbud.go.id/katalog/English-for-Nusantara-untuk-SMPMTs-Kelas-VII" class="px-6 py-3  rounded-full bg-main text-white hover:bg-white hover:text-main hover:border-main transition-all duration-300">
                  <button>Lihat buku</button>
                </a>
              </div>

            </div>
            <!-- Judul Buku End -->
          </div>
          <!-- Title End -->
        </div>
      </div>
    </div>
  </section>
  <!-- Main Content Detail Buku Section End -->



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
              name="nama_lengkap"
              placeholder="Masukkan Nama Lengkap"
              class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
          </div>

          <div class="mb-3 md:mb-6">
            <label
              for="name"
              class="block text-lg font-normal text-gray-700 mb-2">Kelas</label>
            <input
              type="text"
              id="name"
              name="kelas"
              placeholder="Kelas..."
              class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-smmd:text-[16px] sm:leading-6 rounded-full" />
          </div>

          <div class="mb-3 md:mb-6">
            <label
              for="name"

              class="block text-lg font-normal text-gray-700 mb-2">No.Kartu</label>
            <input
              type="text"
              id="name"
              name="no_kartu"
              placeholder="Masukkan No Kartu Anda"
              class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
          </div>

          <div class="mb-3 md:mb-6">
            <label
              for="name"
              class="block text-lg font-normal text-gray-700 mb-2">Judul Buku</label>
            <input
              type="text"
              id="name"
              name="judul_buku"
              placeholder="Masukkan Judul Buku"
              class="w-full block flex-1 border border-main bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
          </div>

          <div class="mb-3 md:mb-6">
            <label
              for="name"
              class="block text-lg font-normal text-gray-700 mb-2">Jumlah Buku</label>
            <input
              type="text"
              id="name"
              name="kuantitas_buku"
              required
              placeholder="Masukkan jumlah buku yang ingin dipinjam"
              class="w-full block flex-1 border border-slate-400 bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
          </div>

          <div class="mb-3 md:mb-6">
            <label
              for="tanggal_peminjaman"
              class="block text-lg font-normal text-gray-700 mb-2">Tanggal Peminjaman</label>
            <input
              type="date"
              id="tanggal_peminjaman"
              name="tanggal_peminjaman"
              placeholder="mm/dd/yy"
              class="w-full block flex-1 border border-slate-400 bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
          </div>

          <div class="mb-3 md:mb-6">
            <label
              for="tanggal_peminjaman"
              class="block text-lg font-normal text-gray-700 mb-2">Durasi Peminjaman</label>
            <input
              type="number"
              id="tanggal_peminjaman"
              name="lama_peminjaman"
              placeholder="mm/dd/yy"
              class="w-full block flex-1 border border-slate-400 bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
          </div>

          <div class="flex gap-2 items-center">
            <input
              type="checkbox"
              name="remember"
              id="remember"
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






  <?php require_once '../Template/footer.php'; ?>

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