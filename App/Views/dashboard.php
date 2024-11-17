    <?php
    // Mulai sesi
    session_start();
    // var_dump($_SESSION['profil_user']); // Debugging

    $id_user = $_SESSION['id_user'];

    // Sertakan file koneksi dan class Buku
    include_once '../core/Database.php';
    include_once '../Models/Buku.php';
    include_once '../Models/user.php';

    // Membuat instance koneksi ke database
    $database = new Database();
    $db = $database->getConnection();

    // Pastikan pengguna sudah login
    if (!isset($_SESSION['id_user'])) {
        header("Location: ../Views/auth/login.php");
        exit();
    }

    // Mengambil data profil pengguna
    $user = new User($db);
    $profil = $user->getUserProfile($id_user); // Pastikan Anda punya method ini di kelas User



    // Mengambil data peminjaman buku dari database
    $buku = new Buku($db);
    $stmt = $buku->getPeminjaman($id_user);
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Peminjaman</title>
        <link rel="stylesheet" href="../../output.css">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Font Family -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
            integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

    </head>

    <body class="bg-gray-100">
        <!-- <button
            id="openModal"
            class="block w-full md:w-[30%] lg:w-[40%] xl:w-[30%]">
            <a
                href="#"
                class="px-8 py-3 block w-full rounded-full bg-main text-black text-sm hover:bg-white hover:text-main border hover:border-main transition-all duration-300">Pinjam Buku</a>
        </button> -->

        <div>
            <div x-data="{ sidebarOpen: false }" class="flex h-screen">
                <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
                    class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

                <!-- Sidebar -->
                <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
                    class="fixed left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-pinkSec lg:translate-x-0 lg:static lg:inset-0 h-screen">
                    <div class="flex items-center justify-center pt-8 mb-8">
                        <a href="./Landing-page.php">
                            <img src="../../public//logo 1.png" alt="" />
                        </a>
                    </div>

                    <!-- Nav Menu Start -->
                    <div class="flex flex-col gap-4 px-4 py-8">
                        <?php
                        // Menentukan halaman aktif
                        $current_page = basename($_SERVER['PHP_SELF']);
                        ?>
                        <a
                            href="./dashboard.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'dashboard.php' ? 'bg-main' : ''; ?>">
                            <svg width="18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white <?php echo $current_page == 'dashboard.php' ? 'fill-white' : 'text-slate-500 group-hover:fill-white'; ?>" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8275 8.4375H11.7975C10.29 8.4375 9.5625 7.7025 9.5625 6.2025V3.1725C9.5625 1.665 10.2975 0.9375 11.7975 0.9375H14.8275C16.335 0.9375 17.0625 1.6725 17.0625 3.1725V6.2025C17.0625 7.7025 16.3275 8.4375 14.8275 8.4375ZM11.7975 2.0625C10.9125 2.0625 10.6875 2.2875 10.6875 3.1725V6.2025C10.6875 7.0875 10.9125 7.3125 11.7975 7.3125H14.8275C15.7125 7.3125 15.9375 7.0875 15.9375 6.2025V3.1725C15.9375 2.2875 15.7125 2.0625 14.8275 2.0625H11.7975Z" />
                                <path d="M6.2025 8.4375H3.1725C1.665 8.4375 0.9375 7.77 0.9375 6.39V2.985C0.9375 1.605 1.6725 0.9375 3.1725 0.9375H6.2025C7.71 0.9375 8.4375 1.605 8.4375 2.985V6.3825C8.4375 7.77 7.7025 8.4375 6.2025 8.4375ZM3.1725 2.0625C2.1675 2.0625 2.0625 2.3475 2.0625 2.985V6.3825C2.0625 7.0275 2.1675 7.305 3.1725 7.305H6.2025C7.2075 7.305 7.3125 7.02 7.3125 6.3825V2.985C7.3125 2.34 7.2075 2.0625 6.2025 2.0625H3.1725Z" />
                                <path d="M6.2025 17.0625H3.1725C1.665 17.0625 0.9375 16.3275 0.9375 14.8275V11.7975C0.9375 10.29 1.6725 9.5625 3.1725 9.5625H6.2025C7.71 9.5625 8.4375 10.2975 8.4375 11.7975V14.8275C8.4375 16.3275 7.7025 17.0625 6.2025 17.0625ZM3.1725 10.6875C2.2875 10.6875 2.0625 10.9125 2.0625 11.7975V14.8275C2.0625 15.7125 2.2875 15.9375 3.1725 15.9375H6.2025C7.0875 15.9375 7.3125 15.7125 7.3125 14.8275V11.7975C7.3125 10.9125 7.0875 10.6875 6.2025 10.6875H3.1725Z" />
                                <path d="M15.75 12.1875H11.25C10.9425 12.1875 10.6875 11.9325 10.6875 11.625C10.6875 11.3175 10.9425 11.0625 11.25 11.0625H15.75C16.0575 11.0625 16.3125 11.3175 16.3125 11.625C16.3125 11.9325 16.0575 12.1875 15.75 12.1875Z" />
                                <path d="M15.75 15.1875H11.25C10.9425 15.1875 10.6875 14.9325 10.6875 14.625C10.6875 14.3175 10.9425 14.0625 11.25 14.0625H15.75C16.0575 14.0625 16.3125 14.3175 16.3125 14.625C16.3125 14.9325 16.0575 15.1875 15.75 15.1875Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'dashboard.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Dashboard
                            </p>
                        </a>

                        <a
                            href="./DataBuku.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'dataBuku.php' ? 'bg-main' : ''; ?>"">
                        <svg width=" 18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.56C8.775 16.56 8.55 16.5075 8.3625 16.4025C6.96 15.6375 4.4925 14.8275 2.9475 14.625L2.73 14.595C1.7475 14.475 0.9375 13.5525 0.9375 12.555V3.49501C0.9375 2.90251 1.17 2.36251 1.5975 1.97251C2.025 1.58251 2.58 1.39501 3.165 1.44751C4.815 1.57501 7.305 2.40001 8.715 3.28501L8.895 3.39001C8.9475 3.42001 9.06 3.42001 9.105 3.39751L9.225 3.32251C10.635 2.43751 13.125 1.59751 14.7825 1.45501C14.7975 1.45501 14.8575 1.45501 14.8725 1.45501C15.42 1.40251 15.9825 1.59751 16.4025 1.98751C16.83 2.37751 17.0625 2.91751 17.0625 3.51001V12.5625C17.0625 13.5675 16.2525 14.4825 15.2625 14.6025L15.015 14.6325C13.47 14.835 10.995 15.6525 9.6225 16.41C9.4425 16.515 9.225 16.56 9 16.56ZM2.985 2.56501C2.745 2.56501 2.5275 2.64751 2.355 2.80501C2.1675 2.97751 2.0625 3.22501 2.0625 3.49501V12.555C2.0625 12.9975 2.445 13.425 2.8725 13.485L3.0975 13.515C4.785 13.74 7.3725 14.5875 8.8725 15.405C8.94 15.435 9.0375 15.4425 9.075 15.4275C10.575 14.595 13.1775 13.74 14.8725 13.515L15.1275 13.485C15.555 13.4325 15.9375 12.9975 15.9375 12.555V3.50251C15.9375 3.22501 15.8325 2.98501 15.645 2.80501C15.45 2.63251 15.2025 2.55001 14.925 2.56501C14.91 2.56501 14.85 2.56501 14.835 2.56501C13.4025 2.69251 11.0925 3.46501 9.8325 4.25251L9.7125 4.33501C9.3 4.59001 8.715 4.59001 8.3175 4.34251L8.1375 4.23751C6.855 3.45001 4.545 2.68501 3.075 2.56501C3.045 2.56501 3.015 2.56501 2.985 2.56501Z" />
                            <path d="M9 15.9301C8.6925 15.9301 8.4375 15.6751 8.4375 15.3676V4.11755C8.4375 3.81005 8.6925 3.55505 9 3.55505C9.3075 3.55505 9.5625 3.81005 9.5625 4.11755V15.3676C9.5625 15.6826 9.3075 15.9301 9 15.9301Z" />
                            <path d="M5.8125 6.93005H4.125C3.8175 6.93005 3.5625 6.67505 3.5625 6.36755C3.5625 6.06005 3.8175 5.80505 4.125 5.80505H5.8125C6.12 5.80505 6.375 6.06005 6.375 6.36755C6.375 6.67505 6.12 6.93005 5.8125 6.93005Z" />
                            <path d="M6.375 9.18005H4.125C3.8175 9.18005 3.5625 8.92505 3.5625 8.61755C3.5625 8.31005 3.8175 8.05505 4.125 8.05505H6.375C6.6825 8.05505 6.9375 8.31005 6.9375 8.61755C6.9375 8.92505 6.6825 9.18005 6.375 9.18005Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'dataBuku.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Data Buku
                            </p>
                        </a>

                        <a
                            href="./dataKunjungan.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'dataKunjungan.php' ? 'bg-main' : ''; ?>"">
                        <svg width=" 18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9.1499H11.25" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6 12.1499H9.285" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.5 4.5H10.5C12 4.5 12 3.75 12 3C12 1.5 11.25 1.5 10.5 1.5H7.5C6.75 1.5 6 1.5 6 3C6 4.5 6.75 4.5 7.5 4.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 3.01501C14.4975 3.15001 15.75 4.07251 15.75 7.50001V12C15.75 15 15 16.5 11.25 16.5H6.75C3 16.5 2.25 15 2.25 12V7.50001C2.25 4.08001 3.5025 3.15001 6 3.01501" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'dataKunjungan.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Data Kunjungan
                            </p>
                        </a>

                        <a
                            href="./User/account.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'account.php' ? 'bg-main' : ''; ?>"">
                        <svg class=" fill-current <?php echo $current_page == 'account.php' ? 'fill-white' : 'text-slate-500 group-hover:fill-white'; ?> group-hover:fill-white w-6 h-6 " width="24" height="24" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.00012 17.0625C8.49762 17.0625 7.98762 16.9349 7.53762 16.6724L3.08262 14.0999C2.18262 13.5749 1.62012 12.6074 1.62012 11.5649V6.43496C1.62012 5.39246 2.18262 4.42496 3.08262 3.89996L7.53762 1.32747C8.43762 0.802466 9.55512 0.802466 10.4626 1.32747L14.9176 3.89996C15.8176 4.42496 16.3801 5.39246 16.3801 6.43496V11.5649C16.3801 12.6074 15.8176 13.5749 14.9176 14.0999L10.4626 16.6724C10.0126 16.9349 9.50262 17.0625 9.00012 17.0625ZM9.00012 2.06245C8.69262 2.06245 8.37762 2.14496 8.10012 2.30246L3.64512 4.87495C3.09012 5.19745 2.74512 5.78996 2.74512 6.43496V11.5649C2.74512 12.2024 3.09012 12.8025 3.64512 13.125L8.10012 15.6974C8.65512 16.0199 9.34512 16.0199 9.90012 15.6974L14.3551 13.125C14.9101 12.8025 15.2551 12.2099 15.2551 11.5649V6.43496C15.2551 5.79746 14.9101 5.19745 14.3551 4.87495L9.90012 2.30246C9.62262 2.14496 9.30762 2.06245 9.00012 2.06245Z" />
                            <path d="M8.99994 8.81261C7.72494 8.81261 6.68994 7.77759 6.68994 6.50259C6.68994 5.22759 7.72494 4.19263 8.99994 4.19263C10.2749 4.19263 11.3099 5.22759 11.3099 6.50259C11.3099 7.77759 10.2749 8.81261 8.99994 8.81261ZM8.99994 5.31763C8.34744 5.31763 7.81494 5.85009 7.81494 6.50259C7.81494 7.15509 8.34744 7.68761 8.99994 7.68761C9.65244 7.68761 10.1849 7.15509 10.1849 6.50259C10.1849 5.85009 9.65244 5.31763 8.99994 5.31763Z" />
                            <path d="M12 13.0575C11.6925 13.0575 11.4375 12.8025 11.4375 12.495C11.4375 11.46 10.3425 10.6125 9 10.6125C7.6575 10.6125 6.5625 11.46 6.5625 12.495C6.5625 12.8025 6.3075 13.0575 6 13.0575C5.6925 13.0575 5.4375 12.8025 5.4375 12.495C5.4375 10.8375 7.035 9.48755 9 9.48755C10.965 9.48755 12.5625 10.8375 12.5625 12.495C12.5625 12.8025 12.3075 13.0575 12 13.0575Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'account.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Profile
                            </p>
                        </a>

                        <a
                            href="./auth/logout.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'logout.php' ? 'bg-main' : ''; ?>"">
                        <svg width=" 18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.43 16.7025H11.3325C8.00251 16.7025 6.39751 15.39 6.12001 12.45C6.09001 12.1425 6.31501 11.865 6.63001 11.835C6.93001 11.805 7.21501 12.0375 7.24501 12.345C7.46251 14.7 8.57251 15.5775 11.34 15.5775H11.4375C14.49 15.5775 15.57 14.4975 15.57 11.445V6.55499C15.57 3.50249 14.49 2.42249 11.4375 2.42249H11.34C8.55751 2.42249 7.44751 3.31499 7.24501 5.71499C7.20751 6.02249 6.94501 6.25499 6.63001 6.22499C6.31501 6.20249 6.09001 5.92499 6.11251 5.61749C6.36751 2.63249 7.98001 1.29749 11.3325 1.29749H11.43C15.1125 1.29749 16.6875 2.87249 16.6875 6.55499V11.445C16.6875 15.1275 15.1125 16.7025 11.43 16.7025Z" />
                            <path d="M11.25 9.5625H2.71503C2.40753 9.5625 2.15253 9.3075 2.15253 9C2.15253 8.6925 2.40753 8.4375 2.71503 8.4375H11.25C11.5575 8.4375 11.8125 8.6925 11.8125 9C11.8125 9.3075 11.5575 9.5625 11.25 9.5625Z" />
                            <path d="M4.38752 12.075C4.24502 12.075 4.10252 12.0225 3.99002 11.91L1.47752 9.39751C1.26002 9.18001 1.26002 8.82001 1.47752 8.60251L3.99002 6.09C4.20752 5.8725 4.56752 5.8725 4.78502 6.09C5.00252 6.3075 5.00252 6.66751 4.78502 6.88501L2.67002 9.00001L4.78502 11.115C5.00252 11.3325 5.00252 11.6925 4.78502 11.91C4.68002 12.0225 4.53002 12.075 4.38752 12.075Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'logout.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Logout
                            </p>
                        </a>
                    </div>
                    <!-- Nav Menu End -->
                </div>

                <!-- Main Content -->
                <div class="flex flex-col flex-1 overflow-hidden">
                    <header class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center">
                            <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center">
                            <div x-data="{ dropdownOpen: false }" class="relative">
                                <button @click="dropdownOpen = ! dropdownOpen"
                                    class="relative flex justify-center items-center gap-2 rounded-full focus:outline-none">
                                    <div class="w-8 h-8 rounded-full">
                                        <img class="object-cover w-full h-full rounded-full"
                                            src="../../public/profile//<?php echo $profil['profil_user']; ?>"
                                            alt="Your avatar" />
                                    </div>

                                    <p class="font-normal text-sm"><?php echo htmlspecialchars($profil['nama_lengkap']); ?></p>
                                </button>

                                <div x-show="dropdownOpen" @click="dropdownOpen = false"
                                    class="fixed inset-0 z-10 w-full h-full" style="display: none"></div>

                                <!-- Dropdown Profile -->
                                <div x-show="dropdownOpen"
                                    class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl"
                                    style="display: none">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profile</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Products</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Logout</a>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Main Content Start -->

                    <main class="flex-1 overflow-x-hidden overflow-y-auto px-4 md:px-8 pt-2">
                        <!-- Search Bar -->
                        <div class="mb-5 md:mb-4">
                            <div class="w-full mx-auto md:mx-0 md:w-[75%] xl:w-[58%] flex gap-5">
                                <input type="text" id="search" placeholder="Search"
                                    class="w-full block flex-1 bg-white px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
                                <button type="submit" class="ml-[-50px]">
                                    <i class="fa-solid fa-search text-lg text-slate-500"></i>
                                </button>
                                <button id="openModal" class="px-4 py-2 text-white bg-main text-black rounded-full">Absen Perpus</button>

                            </div>
                        </div>

                        <!-- Heading -->
                        <div class="mb-4 md:mb-8">
                            <h2 class="font-bold text-2xl text-black">Data Peminjaman</h2>
                        </div>

                        <!-- Content Detail Peminjaman -->
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="flex items-center gap-6 w-full mb-10" id="book-table">
                                <!-- Card Buku -->
                                <div class="w-[270px] hidden md:block shadow-lg rounded-lg bg-white px-3 py-3">
                                    <div class="w-[222px] rounded-md mx-auto mb-3">
                                        <img src="../../public//assets//Books/<?= htmlspecialchars($row['cover']) ?>"
                                            class="w-full" alt="Gambar Buku" />
                                    </div>

                                    <!-- Title -->
                                    <div class="px-3">
                                        <div class="flex justify-between mb-3">
                                            <p class="font-bold text-lg text-black"><?= htmlspecialchars($row['judul_buku']) ?></p>

                                            <div class="flex items-center gap-1">
                                                <i class="fa-solid fa-star text-yellow-500"></i>
                                                <p class="font-normal">4.5</p>
                                            </div>
                                        </div>

                                        <p class="font-normal text-sm">
                                            <?= htmlspecialchars($row['sinopsis']) ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Card Buku End -->

                                <!-- Detail Peminjaman -->
                                <div class="w-full md:w-[450px] h-[353px] mx-auto md:mx-0 bg-inputColors px-4 py-3 rounded-lg overflow-x-hidden">
                                    <!-- Heading -->
                                    <div class="">
                                        <h2 class="text-[28px] font-bold text-black">
                                            <?= htmlspecialchars($row['judul_buku']) ?>
                                        </h2>
                                        <p class="font-normal text-sm text-grey my-3">
                                            Penerbit: <?= htmlspecialchars($row['penerbit']) ?>
                                        </p>
                                        <p class="font-bold text-[20px] text-black mb-4">
                                            Batas Peminjaman
                                        </p>

                                        <!-- Batas Peminjaman -->
                                        <!-- <div class="w-fit md:w-[20rem] px-2 md:px-6 py-3 bg-pinkSec flex items-center gap-1 rounded-lg mb-5">
                                            <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">30</a>
                                            <a href="" class="px-2 py-1 text-primaryBlue">Day</a>
                                            <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">24</a>
                                            <a href="" class="px-2 py-1 text-primaryBlue">Hours</a>
                                            <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">60</a>
                                            <a href="" class="px-2 py-1 text-primaryBlue">Sec</a>
                                        </div> -->

                                        <div>
                                            <span class="px-2 py-1 text-white rounded-md bg-primaryBlue"><?= htmlspecialchars($row['tanggal_kembalian']) ?></span>
                                        </div>
                                        <!-- Status Peminjaman -->
                                        <h2 class="text-[20px] font-bold text-black mb-4">
                                            Status Peminjaman
                                        </h2>

                                        <a href="">
                                            <?php if($row['status_peminjaman'] === "proses" || $row['status_peminjaman'] === "sedang dipinjam" || $row['status_peminjaman'] === "telat mengembalikan"): ?>
                                            <div class="block w-[90%] md:w-[75%] text-center rounded-2xl px-8 py-3 bg-pinkButton text-white font-bold text-[20px] border hover:border-pinkButton hover:bg-white hover:text-pinkButton transition-all duration-300">
                                                <?= htmlspecialchars($row['status_peminjaman']) ?>
                                            </div>
                                            <?php elseif ($row['status_peminjaman'] === "sudah dikembalikan") : ?>
                                                <div class="block w-[90%] md:w-[75%] text-center rounded-2xl px-8 py-3 bg-green-500 text-white font-bold text-[20px] border hover:border-green-500 hover:bg-white hover:text-green-500  transition-all duration-300">
                                                <?= htmlspecialchars($row['status_peminjaman']) ?>
                                            </div>
                        
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                </div>
                                <!-- Detail Peminjaman End -->
                            </div>
                        <?php endwhile ?>
                    </main>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div
            id="myModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-y-scroll pt-10 md:pt-10 lg:pl-16">
            <div
                id="modalContent"
                class="bg-white rounded-3xl shadow-lg px-6 py-4 w-[80%] md:w-[60%] modal-enter">
                <!-- Header Modal Start -->
                <div class="relative mb-8 md:mb-12">
                    <h2 class="text-lg md:text-2xl font-bold md:text-center">
                        Absen Perpustakaan
                    </h2>
                    <button
                        id="closeModal"
                        class="text-gray-500 hover:text-gray-700 text-2xl md:text-4xl absolute top-0 right-0">
                        &times;
                    </button>
                </div>
                <!-- Header Modal End -->

                <!-- Form Modal Start -->
                <form method="post" action="../Views/User/prosesAbsen.php">
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
                            for="kelas"
                            class="block text-lg font-normal text-gray-700 mb-2">Kelas</label>
                        <input
                            type="text"
                            id="kelas"
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

                            class="bg-main text-white px-4 py-2 block w-full rounded-full">
                            Pinjam Buku
                        </button>
                    </div>

                    <!-- Buttom Form Submit End -->
                </form>
                <!-- Form Modal End -->
            </div>
        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#search').on('keyup', function() {
                    var query = $(this).val();

                    $.ajax({
                        url: '../Controller/live_searching.php', // URL ke live_searching.php
                        method: 'POST',
                        data: {
                            query: query
                        },
                        dataType: 'json',
                        success: function(data) {
                            var rows = '';
                            if (data.length > 0) {
                                data.forEach(function(book) {
                                    var statusClass = '';
                                    if (book.status_peminjaman === 'proses') {
                                        statusClass = 'bg-gray-500';
                                    } else if (book.status_peminjaman === 'sedang dipinjam') {
                                        statusClass = 'bg-red-500';
                                    } else if (book.status_peminjaman === 'sudah dikembalikan') {
                                        statusClass = 'bg-green-500';
                                    }

                                    rows += `
                                    <div class="flex items-center gap-6 w-full mb-10" id="book-table">
                                <!-- Card Buku -->
                                <div class="w-[270px] hidden md:block shadow-lg rounded-lg bg-white px-3 py-3">
                                    <div class="w-[222px] rounded-md mx-auto mb-3">
                                        <img src="../../public//assets//Books/${book.cover}"
                                            class="w-full" alt="Gambar Buku" />
                                    </div>

                                    <!-- Title -->
                                    <div class="px-3">
                                        <div class="flex justify-between mb-3">
                                            <p class="font-bold text-lg text-black">${book.judul_buku}</p>

                                            <div class="flex items-center gap-1">
                                                <i class="fa-solid fa-star text-yellow-500"></i>
                                                <p class="font-normal">4.5</p>
                                            </div>
                                        </div>

                                        <p class="font-normal text-sm">
                                            ${book.sinopsis}
                                        </p>
                                    </div>
                                </div>
                                <!-- Card Buku End -->

                                <!-- Detail Peminjaman -->
                                <div class="w-full md:w-[450px] h-[353px] mx-auto md:mx-0 bg-inputColors px-4 py-3 rounded-lg overflow-x-hidden">
                                    <!-- Heading -->
                                    <div class="">
                                        <h2 class="text-[28px] font-bold text-black">
                                            ${book.judul_buku}
                                        </h2>
                                        <p class="font-normal text-sm text-grey my-3">
                                            Penerbit: ${book.penerbit}
                                        </p>
                                        <p class="font-bold text-[20px] text-black mb-4">
                                            Batas Peminjaman
                                        </p>

                                        <!-- Batas Peminjaman -->
                                        <div class="w-fit md:w-[20rem] px-2 md:px-6 py-3 bg-pinkSec flex items-center gap-1 rounded-lg mb-5">
                                            <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">30</a>
                                            <a href="" class="px-2 py-1 text-primaryBlue">Day</a>
                                            <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">24</a>
                                            <a href="" class="px-2 py-1 text-primaryBlue">Hours</a>
                                            <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">60</a>
                                            <a href="" class="px-2 py-1 text-primaryBlue">Sec</a>
                                        </div>

                                        <!-- Status Peminjaman -->
                                        <h2 class="text-[20px] font-bold text-black mb-4">
                                            Status Peminjaman
                                        </h2>

                                        <a href="">
                                            <div class="block w-[90%] md:w-[75%] text-center rounded-2xl px-8 py-3 bg-pinkButton text-white font-bold text-[20px] border hover:border-pinkButton hover:bg-white hover:text-pinkButton transition-all duration-300">
                                                ${book.status_peminjaman}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- Detail Peminjaman End -->
                            </div>`;
                                });
                            } else {
                                rows = '<p class="text-center text-gray-500">Tidak ada data ditemukan.</p>';
                            }
                            $('#book-table').html(rows); // Hanya hasil pencarian yang ditampilkan
                        }
                    });
                });
            });
        </script>

        <!-- Modal start -->
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const openModalButton = document.getElementById("openModal");
                const closeModalButton = document.getElementById("closeModal");
                const modal = document.getElementById("myModal");

                openModalButton.addEventListener("click", () => {
                    modal.classList.remove("hidden");
                });

                closeModalButton.addEventListener("click", () => {
                    modal.classList.add("hidden");
                });

                window.addEventListener("click", (event) => {
                    if (event.target === modal) {
                        modal.classList.add("hidden");
                    }
                });
            });
        </script>
        <!-- Modal end -->

        <!-- notifikasi pesan absen start--->
        <script>
            document.getElementById('absenForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                fetch('../Views/User/prosesAbsen.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        const notification = document.getElementById('notification');
                        const modal = document.getElementById('myModal');

                        // Tampilkan pesan respons
                        if (data.status === 'error') {
                            notification.classList.remove('hidden', 'bg-green-200', 'text-green-800');
                            notification.classList.add('bg-red-200', 'text-red-800');
                            notification.innerText = data.message;
                        } else if (data.status === 'success') {
                            notification.classList.remove('hidden', 'bg-red-200', 'text-red-800');
                            notification.classList.add('bg-green-200', 'text-green-800');
                            notification.innerText = data.message;

                            // Tutup modal sebagai tanda berhasil absen
                            modal.classList.add('hidden'); // Tutup modal
                            notification.classList.add('hidden'); // Sembunyikan notifikasi
                            notification.innerText = ''; // Reset teks notifikasi
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
        <!-- notifikasi pesan absen end--->

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Modal Start
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
                // Modal End

                // Kalender Start
                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                let date = new Date();
                let currentMonth = date.getMonth();
                let currentYear = date.getFullYear();

                function renderCalendar(month, year) {
                    const firstDay = new Date(year, month, 1).getDay();
                    const lastDate = new Date(year, month + 1, 0).getDate();

                    document.getElementById("monthYear").textContent = `${monthNames[month]} ${year}`;

                    const calendarBody = document.getElementById("calendar-body");
                    calendarBody.innerHTML = `
            <div class="font-bold text-center">Sun</div>
            <div class="font-bold text-center">Mon</div>
            <div class="font-bold text-center">Tue</div>
            <div class="font-bold text-center">Wed</div>
            <div class="font-bold text-center">Thu</div>
            <div class="font-bold text-center">Fri</div>
            <div class="font-bold text-center">Sat</div>
          `;
                    calendarBody.innerHTML += "<div></div>".repeat(firstDay);

                    for (let i = 1; i <= lastDate; i++) {
                        const dayDiv = document.createElement("div");
                        dayDiv.classList.add("text-center", "p-1", "rounded", "cursor-pointer", "hover:bg-gray-200");

                        if (i === date.getDate() && month === date.getMonth() && year === date.getFullYear()) {
                            dayDiv.classList.add("bg-greens", "text-white");
                        }

                        dayDiv.textContent = i;
                        calendarBody.appendChild(dayDiv);
                    }
                }

                document.getElementById("prevBtn").addEventListener("click", () => {
                    if (currentMonth === 0) {
                        currentMonth = 11;
                        currentYear--;
                    } else {
                        currentMonth--;
                    }
                    renderCalendar(currentMonth, currentYear);
                });

                document.getElementById("nextBtn").addEventListener("click", () => {
                    if (currentMonth === 11) {
                        currentMonth = 0;
                        currentYear++;
                    } else {
                        currentMonth++;
                    }
                    renderCalendar(currentMonth, currentYear);
                });

                renderCalendar(currentMonth, currentYear);
                // Kalender End
            });
        </script>

    </body>

    </html>