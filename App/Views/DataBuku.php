<?php
// Sertakan file koneksi dan class Buku
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat instance dari kelas Buku dengan menyertakan koneksi database
$buku = new Buku($db);

// Mengambil hanya 4 data buku
$stmt = $buku->readLimited(12); // Mengambil 4 data buku
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="../../output.css" rel="stylesheet" />


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

<body>


    <?php require_once '../Template/header.php' ?>
    <!-- Header Section Start -->
    <section class="pt-36 pb-14">
        <div class="w-full px-4">
            <div class="container mx-auto">
                <!-- Title Start -->
                <div class="text-center mx-auto">
                    <h2 class="font-bold text-2xl md:text-4xl mb-2 md:mb-3">
                        Data Buku
                    </h2>
                    <p class="font-normal text-sm text-grey">
                        Temukan buku buku favorit kalian diperpustakaan digital
                    </p>
                </div>
                <!-- Title End -->

                <!-- Search Form Start -->
                <div class="mt-16 w-[90%] md:w-[80%] lg:w-[70%] mx-auto">
                    <form action="" method="post">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg
                                    class="w-5 h-5 text-gray-500"
                                    viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </span>

                            <input
                                type="text" id="search"
                                class="w-full text-sm py-2 md:py-3 pl-10 pr-4 text-gray-700 bg-white border-[2px] border-slate-300 rounded-full dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
                                placeholder="Cari 300 buku  yang tersedia diperpus digital" />
                        </div>
                    </form>
                </div>
                <!-- Search Form End -->
            </div>
        </div>
    </section>
    <!-- Header Section End -->

    <!-- <input type="text" id="search" placeholder="Cari judul buku..." class="focus:outline outline-gray-500"> -->

    <!-- <a href="detailBuku.php?id_buku=<?= $data['id_buku']; ?>"> -->

    <!-- Content Daftar Buku Start-->
    <section>
        <div class="w-full px-4">
            <div class="container mx-auto">
                <!-- Nav Header Menu Start -->
                <div class="flex justify-between items-center">
                    <div class="max-w-sm md:max-w-md flex-wrap flex items-center gap-6">
                        <a href="#" class="font-bold text-[15px]">Semua Buku</a>
                        <a href="#" class="font-medium text-[15px] text-fontColor">Pelajaran</a>
                        <a href="#" class="font-medium text-[15px] text-fontColor">Sumber Pendukung</a>
                        <a href="#" class="font-medium text-[15px] text-fontColor">Literasi</a>
                    </div>
                </div>
                <!-- Nav Header Menu End -->
                <!-- Conntent Buku Start -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 items-center gap-4 mt-14">
                    <?php foreach ($books as $data) : ?>

                        
                            <!-- Card 1 Start -->
                            <div class="w-full shadow-xl rounded-lg mb-6 md:mb-5" id="book-table">
                                <div class="px-4 py-4">
                                    <a href="./detailBuku.php?id_buku= <?= $data['id_buku'] ?>">
                                        <div
                                            class="w-full h-[300px] md:h-[222px] lg:h-[280px] xl:h-[310px] rounded-md relative group">
                                            <div
                                                class="w-full h-full bg-black bg-opacity-35 items-center justify-center absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 hidden group-hover:flex transition-all duration-700 rounded-lg cursor-pointer">
                                                <div
                                                    class="flex flex-col justify-center items-center gap-2">
                                                    <i
                                                        class="fa-regular fa-eye text-white text-5xl md:text-6xl"></i>
                                                    <p class="text-white text-xl font-semibold">
                                                        Lihat Detail
                                                    </p>
                                                </div>
                                            </div>
                                            <img
                                                src="../../public//assets//Books/<?= $data['cover'] ?>"
                                                class="w-full h-full object-cover rounded-lg"
                                                alt="" />
                                        </div>
                                    </a>

                                    <!-- Title Start -->
                                    <div class="my-3 px-1">
                                        <div class="flex justify-between items-center mb-3">
                                            <h1 class="font-bold md:text-lg"><?= $data['judul_buku'] ?></h1>
                                            <p>
                                                <i class="fa-solid fa-star text-yellow-500 pe-2"></i><span>4.5</span>
                                            </p>
                                        </div>

                                        <p class="text-grey font-normal">
                                            <?= $data['sinopsis'] ?>
                                        </p>
                                    </div>
                                    <!-- Title End -->
                                </div>
                            </div>
                      
                    <?php endforeach; ?>
                    <!-- Card 1 End -->
                </div>
                <!-- Conntent Buku End -->

            </div>
        </div>
    </section>
    <!-- Content Daftar End -->
    <!-- <div class="flex gap-5 mx-5 mt-5" id="book-table">
        <?php foreach ($books as $data): ?>
            <div class="border-2 border-gray p-3">
                <a href="detailBuku.php?id_buku=<?= $data['id_buku']; ?>">
                    <img src="../assets//Books/<?= $data['cover']; ?>" alt="Cover Buku" width="100">
                    <h1><?= $data['judul_buku']; ?></h1>
                    <p><?= $data['penerbit']; ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div> -->

    <!-- Template Footer -->
    <?php require_once '../Template/footer.php' ?>
    <a href="auth/logout.php">Logout</a>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: '../Controller/live_searching-Books.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Kosongkan konten yang ada sebelum menampilkan hasil pencarian baru
                        $('#book-table').html('');

                        // Jika tidak ada data yang ditemukan, tampilkan pesan
                        if (data.length === 0) {
                            $('#book-table').html('<p class="text-center w-full">Tidak ada hasil yang ditemukan</p>');
                        } else {
                            var rows = '';
                            data.forEach(function(book) {
                                // Cek jika cover buku tidak ada atau undefined, gunakan gambar default
                                var cover = book.cover ? book.cover : 'default-cover.png';

                                rows += `
                        <div class="w-full shadow-xl rounded-lg mb-6 md:mb-5">
                            <div class="px-4 py-4">
                                <a href="detailBuku.php?id_buku=${book.id_buku}">
                                    <div class="w-full h-[300px] md:h-[222px] lg:h-[280px] xl:h-[310px] rounded-md relative group">
                                        <div class="w-full h-full bg-black bg-opacity-35 items-center justify-center absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 hidden group-hover:flex transition-all duration-700 rounded-lg cursor-pointer">
                                            <div class="flex flex-col justify-center items-center gap-2">
                                                <i class="fa-regular fa-eye text-white text-5xl md:text-6xl"></i>
                                                <p class="text-white text-xl font-semibold">Lihat Detail</p>
                                            </div>
                                        </div>
                                        <img src="../assets/Books/${cover}" class="w-full h-full object-cover rounded-lg" alt="Cover Buku">
                                    </div>
                                </a>
                                <div class="my-3 px-1">
                                    <div class="flex justify-between items-center mb-3">
                                        <h1 class="font-bold md:text-lg">${book.judul_buku}</h1>
                                        <p>
                                            <i class="fa-solid fa-star text-yellow-500 pe-2"></i><span>4.5</span>
                                        </p>
                                    </div>
                                    <p class="text-grey font-normal">${book.penerbit ? book.penerbit : 'Penerbit tidak diketahui'}</p>
                                </div>
                            </div>
                        </div>`;
                            });
                            // Mengisi hasil pencarian ke dalam #book-table
                            $('#book-table').html(rows);
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>