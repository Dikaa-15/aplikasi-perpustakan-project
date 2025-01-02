<?php

session_start();

require_once '../Controller/Kunjungan.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
  header("Location: ../auth/login.php");
  exit;
}

$database = new Database();
$db = $database->getConnection();

$kunjungan = new Kunjungan($db);
// $kunjungan->id_user = $_SESSION['id_user'];

// get all visit user
$visits = $kunjungan->getAllVisits($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Kunjungan</title>
  <link rel="stylesheet" href="../../output.css" />
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

<body class="h-[2000px]">
  <!-- Script Alpine Start -->
  <script
    src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
    defer></script>
  <!-- Script Alpine End -->

  <div x-data="{ sidebarOpen: false }" class="flex h-screen">
    <div
      :class="sidebarOpen ? 'block' : 'hidden'"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

    <div
      :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
      class="fixed left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-pinkSec lg:translate-x-0 lg:static lg:inset-0 min-h-screen">
      <div class="flex items-center justify-center pt-8 mb-8">
        <a href="./Landing-page.php">
        <img src="../../public/logo 1.png" alt="" />
        </a>
      </div>

      <!-- Nav Menu Start -->
      <div class="flex flex-col gap-4 px-4 py-8">
        <?php
        // Menentukan halaman aktif
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <a href="./dashboard.php"
          class="flex items-center gap-2 px-4 py-2 group <?php echo $currentPage == 'dataPeminjaman.php' ? 'bg-main' : ''; ?> hover:bg-main rounded-lg transition-all duration-300">
          <svg
            width="18"
            height="18"
            viewBox="0 0 18 18"
            class="fill-current group-hover:fill-white "
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M14.8275 8.4375H11.7975C10.29 8.4375 9.5625 7.7025 9.5625 6.2025V3.1725C9.5625 1.665 10.2975 0.9375 11.7975 0.9375H14.8275C16.335 0.9375 17.0625 1.6725 17.0625 3.1725V6.2025C17.0625 7.7025 16.3275 8.4375 14.8275 8.4375ZM11.7975 2.0625C10.9125 2.0625 10.6875 2.2875 10.6875 3.1725V6.2025C10.6875 7.0875 10.9125 7.3125 11.7975 7.3125H14.8275C15.7125 7.3125 15.9375 7.0875 15.9375 6.2025V3.1725C15.9375 2.2875 15.7125 2.0625 14.8275 2.0625H11.7975Z" />
            <path
              d="M6.2025 8.4375H3.1725C1.665 8.4375 0.9375 7.77 0.9375 6.39V2.985C0.9375 1.605 1.6725 0.9375 3.1725 0.9375H6.2025C7.71 0.9375 8.4375 1.605 8.4375 2.985V6.3825C8.4375 7.77 7.7025 8.4375 6.2025 8.4375ZM3.1725 2.0625C2.1675 2.0625 2.0625 2.3475 2.0625 2.985V6.3825C2.0625 7.0275 2.1675 7.305 3.1725 7.305H6.2025C7.2075 7.305 7.3125 7.02 7.3125 6.3825V2.985C7.3125 2.34 7.2075 2.0625 6.2025 2.0625H3.1725Z" />
            <path
              d="M6.2025 17.0625H3.1725C1.665 17.0625 0.9375 16.3275 0.9375 14.8275V11.7975C0.9375 10.29 1.6725 9.5625 3.1725 9.5625H6.2025C7.71 9.5625 8.4375 10.2975 8.4375 11.7975V14.8275C8.4375 16.3275 7.7025 17.0625 6.2025 17.0625ZM3.1725 10.6875C2.2875 10.6875 2.0625 10.9125 2.0625 11.7975V14.8275C2.0625 15.7125 2.2875 15.9375 3.1725 15.9375H6.2025C7.0875 15.9375 7.3125 15.7125 7.3125 14.8275V11.7975C7.3125 10.9125 7.0875 10.6875 6.2025 10.6875H3.1725Z" />
            <path
              d="M15.75 12.1875H11.25C10.9425 12.1875 10.6875 11.9325 10.6875 11.625C10.6875 11.3175 10.9425 11.0625 11.25 11.0625H15.75C16.0575 11.0625 16.3125 11.3175 16.3125 11.625C16.3125 11.9325 16.0575 12.1875 15.75 12.1875Z" />
            <path
              d="M15.75 15.1875H11.25C10.9425 15.1875 10.6875 14.9325 10.6875 14.625C10.6875 14.3175 10.9425 14.0625 11.25 14.0625H15.75C16.0575 14.0625 16.3125 14.3175 16.3125 14.625C16.3125 14.9325 16.0575 15.1875 15.75 15.1875Z" />
          </svg>

          <p class="text-lg text-slate-500 <?php echo $currentPage == './dataPeminjaman.php' ? 'text-white' : ''; ?> group-hover:text-white">Dashboard</p>
        </a>

        <a
          href="./DataBuku.php"
          class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
          <svg
            width="18"
            height="18"
            viewBox="0 0 18 18"
            class="fill-current group-hover:fill-white"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M9 16.56C8.775 16.56 8.55 16.5075 8.3625 16.4025C6.96 15.6375 4.4925 14.8275 2.9475 14.625L2.73 14.595C1.7475 14.475 0.9375 13.5525 0.9375 12.555V3.49501C0.9375 2.90251 1.17 2.36251 1.5975 1.97251C2.025 1.58251 2.58 1.39501 3.165 1.44751C4.815 1.57501 7.305 2.40001 8.715 3.28501L8.895 3.39001C8.9475 3.42001 9.06 3.42001 9.105 3.39751L9.225 3.32251C10.635 2.43751 13.125 1.59751 14.7825 1.45501C14.7975 1.45501 14.8575 1.45501 14.8725 1.45501C15.42 1.40251 15.9825 1.59751 16.4025 1.98751C16.83 2.37751 17.0625 2.91751 17.0625 3.51001V12.5625C17.0625 13.5675 16.2525 14.4825 15.2625 14.6025L15.015 14.6325C13.47 14.835 10.995 15.6525 9.6225 16.41C9.4425 16.515 9.225 16.56 9 16.56ZM2.985 2.56501C2.745 2.56501 2.5275 2.64751 2.355 2.80501C2.1675 2.97751 2.0625 3.22501 2.0625 3.49501V12.555C2.0625 12.9975 2.445 13.425 2.8725 13.485L3.0975 13.515C4.785 13.74 7.3725 14.5875 8.8725 15.405C8.94 15.435 9.0375 15.4425 9.075 15.4275C10.575 14.595 13.1775 13.74 14.8725 13.515L15.1275 13.485C15.555 13.4325 15.9375 12.9975 15.9375 12.555V3.50251C15.9375 3.22501 15.8325 2.98501 15.645 2.80501C15.45 2.63251 15.2025 2.55001 14.925 2.56501C14.91 2.56501 14.85 2.56501 14.835 2.56501C13.4025 2.69251 11.0925 3.46501 9.8325 4.25251L9.7125 4.33501C9.3 4.59001 8.715 4.59001 8.3175 4.34251L8.1375 4.23751C6.855 3.45001 4.545 2.68501 3.075 2.56501C3.045 2.56501 3.015 2.56501 2.985 2.56501Z" />
            <path
              d="M9 15.9301C8.6925 15.9301 8.4375 15.6751 8.4375 15.3676V4.11755C8.4375 3.81005 8.6925 3.55505 9 3.55505C9.3075 3.55505 9.5625 3.81005 9.5625 4.11755V15.3676C9.5625 15.6826 9.3075 15.9301 9 15.9301Z" />
            <path
              d="M5.8125 6.93005H4.125C3.8175 6.93005 3.5625 6.67505 3.5625 6.36755C3.5625 6.06005 3.8175 5.80505 4.125 5.80505H5.8125C6.12 5.80505 6.375 6.06005 6.375 6.36755C6.375 6.67505 6.12 6.93005 5.8125 6.93005Z" />
            <path
              d="M6.375 9.18005H4.125C3.8175 9.18005 3.5625 8.92505 3.5625 8.61755C3.5625 8.31005 3.8175 8.05505 4.125 8.05505H6.375C6.6825 8.05505 6.9375 8.31005 6.9375 8.61755C6.9375 8.92505 6.6825 9.18005 6.375 9.18005Z" />
          </svg>

          <p class="text-lg text-slate-500 group-hover:text-white">
            Data Buku
          </p>
        </a>

        <a
          href="./dataKunjungan.php"
          class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'dataKunjungan.php' ? 'bg-main' : ''; ?>">
          <svg
            width="18"
            height="18"
            viewBox="0 0 18 18"
            class="fill-current group-hover:fill-white <?php echo $current_page == 'dataKunjungan.php' ? 'fill-white' : 'text-slate-500 group-hover:fill-white'; ?>"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M6 9.1499H11.25"
              stroke-width="1.5"
              stroke-miterlimit="10"
              stroke-linecap="round"
              stroke-linejoin="round" />
            <path
              d="M6 12.1499H9.285"
              stroke-width="1.5"
              stroke-miterlimit="10"
              stroke-linecap="round"
              stroke-linejoin="round" />
            <path
              d="M7.5 4.5H10.5C12 4.5 12 3.75 12 3C12 1.5 11.25 1.5 10.5 1.5H7.5C6.75 1.5 6 1.5 6 3C6 4.5 6.75 4.5 7.5 4.5Z"
              stroke-width="1.5"
              stroke-miterlimit="10"
              stroke-linecap="round"
              stroke-linejoin="round" />
            <path
              d="M12 3.01501C14.4975 3.15001 15.75 4.07251 15.75 7.50001V12C15.75 15 15 16.5 11.25 16.5H6.75C3 16.5 2.25 15 2.25 12V7.50001C2.25 4.08001 3.5025 3.15001 6 3.01501"
              stroke-width="1.5"
              stroke-miterlimit="10"
              stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>

          <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'dataKunjungan.php' ? 'text-white' : 'text-slate-500'; ?>">
            Data Kunjungan
          </p>
        </a>

        <a
          href="./User/account.php"
          class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
          <svg
            class="fill-current group-hover:fill-white w-6 h-6"
            width="24"
            height="24"
            viewBox="0 0 18 18"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M9.00012 17.0625C8.49762 17.0625 7.98762 16.9349 7.53762 16.6724L3.08262 14.0999C2.18262 13.5749 1.62012 12.6074 1.62012 11.5649V6.43496C1.62012 5.39246 2.18262 4.42496 3.08262 3.89996L7.53762 1.32747C8.43762 0.802466 9.55512 0.802466 10.4626 1.32747L14.9176 3.89996C15.8176 4.42496 16.3801 5.39246 16.3801 6.43496V11.5649C16.3801 12.6074 15.8176 13.5749 14.9176 14.0999L10.4626 16.6724C10.0126 16.9349 9.50262 17.0625 9.00012 17.0625ZM9.00012 2.06245C8.69262 2.06245 8.37762 2.14496 8.10012 2.30246L3.64512 4.87495C3.09012 5.19745 2.74512 5.78996 2.74512 6.43496V11.5649C2.74512 12.2024 3.09012 12.8025 3.64512 13.125L8.10012 15.6974C8.65512 16.0199 9.34512 16.0199 9.90012 15.6974L14.3551 13.125C14.9101 12.8025 15.2551 12.2099 15.2551 11.5649V6.43496C15.2551 5.79746 14.9101 5.19745 14.3551 4.87495L9.90012 2.30246C9.62262 2.14496 9.30762 2.06245 9.00012 2.06245Z" />
            <path
              d="M8.99994 8.81261C7.72494 8.81261 6.68994 7.77759 6.68994 6.50259C6.68994 5.22759 7.72494 4.19263 8.99994 4.19263C10.2749 4.19263 11.3099 5.22759 11.3099 6.50259C11.3099 7.77759 10.2749 8.81261 8.99994 8.81261ZM8.99994 5.31763C8.34744 5.31763 7.81494 5.85009 7.81494 6.50259C7.81494 7.15509 8.34744 7.68761 8.99994 7.68761C9.65244 7.68761 10.1849 7.15509 10.1849 6.50259C10.1849 5.85009 9.65244 5.31763 8.99994 5.31763Z" />
            <path
              d="M12 13.0575C11.6925 13.0575 11.4375 12.8025 11.4375 12.495C11.4375 11.46 10.3425 10.6125 9 10.6125C7.6575 10.6125 6.5625 11.46 6.5625 12.495C6.5625 12.8025 6.3075 13.0575 6 13.0575C5.6925 13.0575 5.4375 12.8025 5.4375 12.495C5.4375 10.8375 7.035 9.48755 9 9.48755C10.965 9.48755 12.5625 10.8375 12.5625 12.495C12.5625 12.8025 12.3075 13.0575 12 13.0575Z" />
          </svg>

          <p class="text-lg text-slate-500 group-hover:text-white">Profile</p>
        </a>

        <a
          href="./auth/logout.php"
          class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
          <svg
            width="18"
            height="18"
            viewBox="0 0 18 18"
            class="fill-current group-hover:fill-white"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M11.43 16.7025H11.3325C8.00251 16.7025 6.39751 15.39 6.12001 12.45C6.09001 12.1425 6.31501 11.865 6.63001 11.835C6.93001 11.805 7.21501 12.0375 7.24501 12.345C7.46251 14.7 8.57251 15.5775 11.34 15.5775H11.4375C14.49 15.5775 15.57 14.4975 15.57 11.445V6.55499C15.57 3.50249 14.49 2.42249 11.4375 2.42249H11.34C8.55751 2.42249 7.44751 3.31499 7.24501 5.71499C7.20751 6.02249 6.94501 6.25499 6.63001 6.22499C6.31501 6.20249 6.09001 5.92499 6.11251 5.61749C6.36751 2.63249 7.98001 1.29749 11.3325 1.29749H11.43C15.1125 1.29749 16.6875 2.87249 16.6875 6.55499V11.445C16.6875 15.1275 15.1125 16.7025 11.43 16.7025Z" />
            <path
              d="M11.25 9.5625H2.71503C2.40753 9.5625 2.15253 9.3075 2.15253 9C2.15253 8.6925 2.40753 8.4375 2.71503 8.4375H11.25C11.5575 8.4375 11.8125 8.6925 11.8125 9C11.8125 9.3075 11.5575 9.5625 11.25 9.5625Z" />
            <path
              d="M4.38752 12.075C4.24502 12.075 4.10252 12.0225 3.99002 11.91L1.47752 9.39751C1.26002 9.18001 1.26002 8.82001 1.47752 8.60251L3.99002 6.09C4.20752 5.8725 4.56752 5.8725 4.78502 6.09C5.00252 6.3075 5.00252 6.66751 4.78502 6.88501L2.67002 9.00001L4.78502 11.115C5.00252 11.3325 5.00252 11.6925 4.78502 11.91C4.68002 12.0225 4.53002 12.075 4.38752 12.075Z" />
          </svg>

          <p class="text-lg text-slate-500 group-hover:text-white">Logout</p>
        </a>
      </div>
      <!-- Nav Menu End -->
    </div>

    <div class="flex flex-col flex-1 overflow-hidden">
      <header class="flex items-center justify-between bg-primaryBlue px-6 py-3">
        <div class="flex items-center">
          <button
            @click="sidebarOpen = true"
            class="text-gray-800 focus:outline-none lg:hidden">
            <svg
              class="w-6 h-6 fill-white"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M4 6H20M4 12H20M4 18H11"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"></path>
            </svg>
          </button>
        </div>
        <!-- Title Start -->
        <div class="">
          <h1 class="text-lg md:text-2xl font-bold text-pinkSec">
            Data Kunjungan
          </h1>
        </div>
        <!-- Title End -->

        <!-- Kosong Start -->
        <div class="px-10"></div>
        <!-- Kosong End -->
      </header>

      <!-- Main Content Start -->
      <main
        class="flex-1 overflow-x-hidden overflow-y-auto px-4 md:px-8 pb-10">

        <!-- Content Section Start -->
        <section class="pt-24 pb-32">
          <div class="w-full px-4">
            <div class="container mx-auto">
              <!-- Filter Start -->
              <form action="" method="post" class="my-8">
                <div class="flex flex-wrap items-center gap-4">
                  <div
                    class="bg-primaryBlue md:w-fit px-4 py-2 text-white rounded">
                    <select name="" id="" class="bg-transparent w-full">
                      <option value="" class="text-black">Date</option>
                      <option value="" class="text-black">1</option>
                      <option value="" class="text-black">2</option>
                      <option value="" class="text-black">3</option>
                      <option value="" class="text-black">4</option>
                      <option value="" class="text-black">5</option>
                      <option value="" class="text-black">6</option>
                      <option value="" class="text-black">7</option>
                      <option value="" class="text-black">8</option>
                      <option value="" class="text-black">9</option>
                      <option value="" class="text-black">10</option>
                    </select>
                  </div>

                  <div
                    class="bg-primaryBlue md:w-fit px-3 py-2 text-white rounded">
                    <select name="" id="" class="bg-transparent w-full">
                      <option value="" class="text-black">Month</option>
                      <option value="" class="text-black">Januari</option>
                      <option value="" class="text-black">Februari</option>
                      <option value="" class="text-black">Maret</option>
                      <option value="" class="text-black">April</option>
                      <option value="" class="text-black">Mei</option>
                      <option value="" class="text-black">Juni</option>
                      <option value="" class="text-black">Juli</option>
                      <option value="" class="text-black">Agustus</option>
                      <option value="" class="text-black">September</option>
                      <option value="" class="text-black">Oktober</option>
                      <option value="" class="text-black">November</option>
                      <option value="" class="text-black">Desember</option>
                    </select>
                  </div>

                  <div
                    class="bg-primaryBlue md:w-fit px-4 py-2 text-white rounded">
                    <select name="" id="" class="bg-transparent w-full">
                      <option value="" class="text-black">Years</option>
                      <option value="" class="text-black">2020</option>
                      <option value="" class="text-black">2021</option>
                      <option value="" class="text-black">2022</option>
                      <option value="" class="text-black">2023</option>
                      <option value="" class="text-black">2024</option>
                    </select>
                  </div>

                  <button
                    type="submit"
                    class="px-4 py-2 md:w-fit bg-greenyellow text-black font-normal rounded">
                    Search
                  </button>
                </div>
              </form>
              <!-- Filter End -->

              <!-- Table Start -->
              <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                  <thead>
                    <tr
                      class="w-full bg-primaryBlue text-left text-white capitalize text-sm md:text-lg leading-normal rounded-3xl">
                      <th class="py-3 px-6">NO</th>
                      <th class="py-3 px-6">Nama Pengunjung</th>
                      <th class="py-3 px-6">Kelas</th>
                      <th class="py-3 px-6">No Kartu</th>
                      <th class="py-3 px-6">Tanggal Kunjungan</th>
                      <th class="py-3 px-6">keperluan</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-700 text-sm">
                    <?php $no = 1;
                    foreach ($visits as $visit) : ?>
                      <tr class="hover:bg-gray-100 text-primaryBlue font-bold">
                        <td class="py-3 px-6"><?= $no++; ?></td>
                        <td class="py-3 px-6"><?= htmlspecialchars($visit['nama_lengkap']); ?></td>
                        <td class="py-3 px-6"><?= htmlspecialchars($visit['kelas']); ?></td>
                        <td class="py-3 px-6"><?= htmlspecialchars($visit['no_kartu']); ?></td>
                        <td class="py-3 px-6">
                          <?= htmlspecialchars($visit['tanggal_kunjungan']); ?>
                        </td>
                        <td class="py-3 px-6"><?= htmlspecialchars($visit['keperluan']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!-- Table End -->
            </div>
          </div>
        </section>
        <!-- Content Section End -->
      </main>
      <!-- Main Content End -->
    </div>
  </div>
</body>

</html>