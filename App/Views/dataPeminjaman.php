    <?php
    // Mulai sesi
    session_start();

    // Sertakan file koneksi dan class Buku
    include_once '../core/Database.php';
    include_once '../Models/Buku.php';

    // Membuat instance koneksi ke database
    $database = new Database();
    $db = $database->getConnection();

    // Pastikan pengguna sudah login
    if (!isset($_SESSION['id_user'])) {
        header("Location: ../Views/auth/login.php");
        exit();
    }

    $id_user = $_SESSION['id_user'];

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
        <link rel="stylesheet" href="..//..//output.css">
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

                    <!-- Menu -->
                    <div class="flex flex-col gap-4 px-4 py-8">
                        <a href="userDashboard.html"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../public//svg//element-equal.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Dashboard</p>
                        </a>
                        <a href="daftarBuku.html"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../public//svg//book.svg" alt="" class="w-6 h-6 object-cover" />
                            
                        </a>
                        <a href=""
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../public//svg//clipboard-text.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Data Kunjungan</p>
                        </a>
                        <a href="profileUser.html"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../public//svg//user-octagon.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Profile</p>
                        </a>
                        <a href=""
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../public//svg//logout.svg" alt="" class="w-6 h-6 object-cover" />
                            <a href="../Views//auth//logout.php">
                                <p class="text-lg text-slate-500 group-hover:text-white">Logout</p>
                            </a>
                        </a>
                    </div>
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
                                    class="relative flex justify-center items-center gap-2 overflow-hidden rounded-full focus:outline-none">
                                    <div class="w-8 h-8 rounded-full">
                                        <img class="object-cover w-full h-full"
                                            src="../../public//Image Profile.png"
                                            alt="Your avatar" />
                                    </div>

                                    <p class="font-normal text-sm">Dimas Putra A</p>
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

                    <button id="openModal" class="px-4 py-2 bg-main text-black rounded-full">Absen Perpus</button>
                    <main class="flex-1 overflow-x-hidden overflow-y-auto px-4 md:px-8 pt-2">
                        <!-- Search Bar -->
                        <div class="mb-5 md:mb-4">
                            <div class="w-full mx-auto md:mx-0 md:w-[75%] relative">
                                <input type="text" id="search" placeholder="Search"
                                    class="w-full block flex-1 bg-inputColors px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full" />
                                <button type="submit" class="absolute right-4 top-3">
                                    <i class="fa-solid fa-search text-lg text-slate-500"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Heading -->
                        <div class="mb-4 md:mb-8">
                            <h2 class="font-bold text-2xl text-black">Data Peminjaman</h2>
                        </div>

                        <!-- Content Detail Peminjaman -->
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="flex items-center gap-6 w-full mb-10" >
                                <!-- Card Buku -->
                                <div class="w-[270px] hidden md:block shadow-lg rounded-lg bg-white px-3 py-3" id="book-table">
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
                                                <?= htmlspecialchars($row['status_peminjaman']) ?>
                                            </div>
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

        <!-- Absen START-->
        < <!-- Form Modal Start -->
            <div id="myModal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
                    <div class="relative mb-8 md:mb-12">
                        <h2 class="text-lg md:text-2xl font-bold md:text-center">Absen Perpustakaan</h2>
                        <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl md:text-4xl absolute top-0 right-0">&times;</button>
                    </div>

                    <form id="absenForm" method="POST" action="../Controller/prosesAbsen.php">
                        <div class="mb-3 md:mb-6">
                            <label for="name" class="block text-lg font-normal text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="name" required class="w-full block flex-1 border" />
                        </div>
                        <div class="mb-3 md:mb-6">
                            <label for="kelas" class="block text-lg font-normal text-gray-700 mb-2">Kelas</label>
                            <input type="text" name="kelas" id="kelas" required class="w-full block flex-1 border" />
                        </div>
                        <div class="mb-3 md:mb-6">
                            <label for="no_kartu" class="block text-lg font-normal text-gray-700 mb-2">No.Kartu</label>
                            <input type="text" name="no_kartu" id="card_no" required class="w-full block flex-1 border" />
                        </div>
                        <input type="submit" class="bg-main text-black px-4 py-2 block w-full rounded-full" value="Absen">
                    </form>
                    <div id="notification" class="hidden p-4 mt-4 rounded bg-red-200 text-red-800"></div>
                </div>
            </div>
        <!-- Form Modal End -->
         <!-- Absen END-->



            <!-- Buttom Form Submit Start -->
            <!-- <div class="flex justify-center mt-6">
            <button
                type="submit"
                class="bg-main text-white px-4 py-2 block w-full rounded-full">
                Pinjam Buku
            </button>
        </div> -->

            <!-- Buttom Form Submit End -->
            </form> -->
            <!-- Form Modal End -->

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <!-- Live searching ajax -->
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
                                    
                                        <!-- Card Buku -->
                                        <div class="w-[270px] hidden md:block shadow-lg rounded-lg bg-white px-3 py-3">
                                            <div class="w-[222px] rounded-md mx-auto mb-3">
                                                <img src="../../public/assets/Books/${book.cover}" class="w-full" alt="Gambar Buku" />
                                            </div>
                                            <div class="px-3">
                                                <div class="flex justify-between mb-3">
                                                    <p class="font-bold text-lg text-black">${book.judul_buku}</p>
                                                    <div class="flex items-center gap-1">
                                                        <i class="fa-solid fa-star text-yellow-500"></i>
                                                        <p class="font-normal">4.5</p>
                                                    </div>
                                                </div>
                                                <p class="font-normal text-sm">${book.sinopsis}</p>
                                            </div>
                                        </div>
                                        <!-- Card Buku End -->
                                        <div class="w-full md:w-[450px] h-[353px] mx-auto md:mx-0 bg-inputColors px-4 py-3 rounded-lg overflow-x-hidden">
                                            <h2 class="text-[28px] font-bold text-black">${book.judul_buku}</h2>
                                            <p class="font-normal text-sm text-grey my-3">Penerbit: ${book.penerbit}</p>
                                            <p class="font-bold text-[20px] text-black mb-4">Batas Peminjaman</p>
                                            <div class="w-fit md:w-[20rem] px-2 md:px-6 py-3 bg-pinkSec flex items-center gap-1 rounded-lg mb-5">
                                                <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">30</a>
                                                <a href="" class="px-2 py-1 text-primaryBlue">Day</a>
                                                <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">24</a>
                                                <a href="" class="px-2 py-1 text-primaryBlue">Hours</a>
                                                <a href="" class="px-2 py-1 text-white rounded-md bg-primaryBlue">60</a>
                                                <a href="" class="px-2 py-1 text-primaryBlue">Sec</a>
                                            </div>
                                            <h2 class="text-[20px] font-bold text-black mb-4">Status Peminjaman</h2>
                                            <div class="block w-[90%] md:w-[75%] text-center rounded-2xl px-8 py-3 ${statusClass} text-white font-bold text-[20px]">${book.status_peminjaman}</div>
                                        </div>
                                    `;
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
                // Modal functionality
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

                // Absensi form submission
                document.getElementById('absenForm').addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(this);

                    fetch('../Controller/prosesAbsen.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            const notification = document.getElementById('notification');

                            if (data.status === 'error') {
                                notification.classList.remove('hidden', 'bg-green-200', 'text-green-800');
                                notification.classList.add('bg-red-200', 'text-red-800');
                                notification.innerText = data.message;
                            } else if (data.status === 'success') {
                                notification.classList.remove('hidden', 'bg-red-200', 'text-red-800');
                                notification.classList.add('bg-green-200', 'text-green-800');
                                notification.innerText = data.message;

                                // Reset form after success
                                this.reset();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            </script>
            <!-- Modal end -->

            <!-- notifikasi pesan absen start--->
            <script>
                document.getElementById('absenForm').addEventListener('submit', function(event) {
                    event.preventDefault();

                    const formData = new FormData(this);

                    fetch('../Controller/prosesAbsen.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            const notification = document.getElementById('notification');

                            // Menampilkan pesan berdasarkan respons
                            if (data.status === 'error') {
                                notification.classList.remove('hidden', 'bg-green-200', 'text-green-800');
                                notification.classList.add('bg-red-200', 'text-red-800');
                                notification.innerText = data.message;
                            } else if (data.status === 'success') {
                                notification.classList.remove('hidden', 'bg-red-200', 'text-red-800');
                                notification.classList.add('bg-green-200', 'text-green-800');
                                notification.innerText = data.message;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            </script>
            <!-- notifikasi pesan absen end--->


    </body>

    </html>