<?php
require_once '../Models/Kunjungan.php';

// Create a database connection
$database = new Database();
$db = $database->getConnection();

// Initialize the Kunjungan class
$kunjungan = new Kunjungan($db);

// Get duplicate visits
$duplicateVisits = $kunjungan->getDuplicateVisits();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../css//output.css" rel="stylesheet" />
    <title>Dashboard</title>
    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body class="scroll-smooth font-fontMain">
    <!-- Pembungkus Content Start -->
    <main class="w-full h-screen grid grid-cols-5 ">



        <?php require '../Controller/sidebar.php'; ?>

        <div class="col-span-4 w-full">
            <!-- <div class="py-[13px] border-b-[2px] border-slate-300 px-4"> -->
            <div class="py-[13px] border-b-[2px] border-slate-300 px-4">
                <!-- search bar -->
                <div class="flex items-center justify-between ">
                    <!-- Search Bar -->
                    <div class="relative h-full w-[60%] pt-2 ">
                        <input type="text" placeholder="Search"
                            class="w-full pl-4 pr-10 py-2 rounded-full bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <span class="absolute inset-y-0 right-3 flex items-center mt-2 me-2 text-gray-500">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </div>

                    <!-- User Profile and Notifications -->
                    <div class="flex items-center space-x-4">
                        <!-- User Profile -->
                        <div class="flex items-center space-x-2">
                            <img src="https://via.placeholder.com/40" alt="Profile Picture"
                                class="w-8 h-8 rounded-full">
                            <span class="font-medium text-gray-700">Rendi Fadillah</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <!-- Notification Icon -->
                        <div class="text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M10 2a6 6 0 00-6 6v3.586l-1.707 1.707a1 1 0 001.414 1.414L6 14h8l2.293 2.293a1 1 0 001.414-1.414L16 11.586V8a6 6 0 00-6-6zm2 14a2 2 0 11-4 0h4z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <main>

                    <!-- Main Content End -->
                </main>
                <!-- Pembung  </div>
        <!-- Search Bar End -->
            </div>
            <div class="grid grid-cols-2">
                <div class="grid mx-6">
                    <h1 class="font-semibold text-2xl mt-8">Dashboard</h1>
                    
                    <div class="max-w-4xl mx-auto -mt-52">
                        <h1 class="text-2xl font-bold mb-6">Siswa yang sering berkunjung</h1>
                        <!-- Navtabs Section di atas judul -->
                        <!-- <ul class="flex flex-wrap text-sm font-medium text-center text-black  mb-4">
          <li class="me-2">
              <a href="#" class="inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Kemarin</a>
          </li>
          <li class="me-2">
              <a href="#" class="inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">Hari ini</a>
          </li>
        
      </ul> -->

                       <table class="min-w-full bg-white rounded-lg border-collapse table-auto">
            <thead>
                <tr class="text-left bg-gray-200 font-bold text-gray-600 uppercase text-sm">
                    <th class="py-2 px-4 text-sm">No</th>
                    <th class="py-2 px-4 text-sm">Nama Pengunjung</th>
                    <th class="py-2 px-4 text-sm">Kelas</th>
                    <th class="py-2 px-4 text-sm">No Kartu</th>
                    <th class="py-2 px-4 text-sm">Tanggal Kunjungan</th>
                    <th class="py-2 px-4 text-sm">Keperluan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($duplicateVisits as $visit): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-2 px-4 text-left text-xs"><?= $no++; ?></td>
                        <td class="py-2 px-4 text-left text-xs"><?= htmlspecialchars($visit['nama_lengkap']); ?></td>
                        <td class="py-2 px-4 text-left text-xs"><?= htmlspecialchars($visit['kelas']); ?></td>
                        <td class="py-2 px-4 text-left text-xs"><?= htmlspecialchars($visit['no_kartu']); ?></td>
                        <td class="py-2 px-4 text-left text-xs"><?= htmlspecialchars($visit['tanggal_kunjungan']); ?></td>
                        <td class="py-2 px-4 text-left text-xs"><?= htmlspecialchars($visit['keperluan']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

                    </div>
                </div>
                <div class="grid mt-6 mx-6 col-span-1 w-full">
                    <div class="max-w-md mx-auto space-y-6 ">
                        <!-- Card 1: Peserta Didik -->
                        <div class="bg-blue-600 text-white rounded-lg  text-center p-5">
                            <div class="w-[170px] h-[170px] mx-auto bg-transparent">
                                <img src="/PERPUSTAKAANFINISH/public/image/frame/Orang png/vuesax/bold/vuesax/bold/user.png" alt="">
                            </div>
                            <h2 class="text-xl font-semibold">Jumlah Data Peserta Didik</h2>
                            <p class="text-lg">1,300 Peserta Didik</p>
                        </div>

                        <!-- Card 2: Data Buku -->
                        <div class="bg-blue-600 text-white rounded-lg text-center">
                            <div class="text-5xl mb-4">
                                <!-- Icon untuk Buku -->
                                <div class="w-[170px] h-[170px] mx-auto bg-transparent">
                                    <img src="/PERPUSTAKAANFINISH/public/image/frame/book.png" alt="" class="p-5" >
                                </div>
                            </div>
                            <h2 class="text-xl font-semibold ">Data Jumlah Buku</h2>
                            <p class="text-lg">2,500 Buku</p>
                        </div>
                    </div>

                </div>
            </div>