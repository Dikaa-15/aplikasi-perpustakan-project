<?php
include_once '../core/Database.php';
include_once '../Models/Buku.php';

session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ");
    exit();
}



$database = new Database();
$db = $database->getConnection();

$buku = new Buku($db);
$stmt = $buku->read();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../css/output.css" rel="stylesheet" />
    <title>Halaman Data Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="scroll-smooth font-fontMain">
<main class="w-full h-screen grid grid-cols-5">
    <?php require '../Controller/sidebar.php'; ?>

    <div class="col-span-4 w-full">
        <div class="py-[13px] border-b-[2px] border-slate-300 px-4">
            <div class="flex items-center justify-between">
                <div class="relative h-full w-[60%] pt-2">
                <input  type="text" placeholder="Search" class="w-full pl-4 pr-10 py-2 rounded-full bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300">

                    <span class="absolute inset-y-0 right-3 flex items-center mt-2 me-2 text-gray-500">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <img src="https://via.placeholder.com/40" alt="Profile Picture" class="w-8 h-8 rounded-full">
                        <span class="font-medium text-gray-700">Rendi Fadillah</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-1.707 1.707a1 1 0 001.414 1.414L6 14h8l2.293 2.293a1 1 0 001.414-1.414L16 11.586V8a6 6 0 00-6-6zm2 14a2 2 0 11-4 0h4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-4">
            <div class="pt-5 mb-4">
                <h1 class="text-2xl font-bold">Daftar buku</h1>
            </div>
            <div class="flex justify-between items-center mb-4">
                <button type="button" class="inline-flex text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    <a href="createbuku.php">Tambah</a>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto mt-3 ml-4">
            <table class="min-w-full bg-white shadow-md rounded-lg border-collapse table-auto">
                <thead>
                    <tr class="text-left bg-gray-200 font-bold text-gray-600 uppercase text-sm">
                        <th class="py-2 px-4">No.</th>
                        <th class="py-2 px-4">Kode Buku</th>
                        <th class="py-2 px-4">Judul</th>
                        <th class="py-2 px-4">Kategori</th>
                        <th class="py-2 px-4">Penerbit</th>
                        <th class="py-2 px-4">Stok</th>
                        <th class="py-2 px-4">Cover</th>
                        <th class="py-2 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bukuData">
                    <?php foreach ($rows as $i => $row): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-2 px-4 text-left whitespace-nowrap"><?= htmlspecialchars($i + 1); ?></td>
                        <td class="py-2 px-4 text-left"><?= htmlspecialchars($row['kode_buku']); ?></td>
                        <td class="py-2 px-4 text-left"><?= htmlspecialchars($row['judul_buku']); ?></td>
                        <td class="py-2 px-4 text-left"><?= htmlspecialchars($row['kategori']); ?></td>
                        <td class="py-2 px-4 text-left"><?= htmlspecialchars($row['penerbit']); ?></td>
                        <td class="py-2 px-4 text-left"><?= htmlspecialchars($row['stok_buku']); ?></td>
                        <td class="py-2 px-4 text-left">
                            <img src="../../public/assets/Books/<?= htmlspecialchars($row['cover']); ?>" alt="Cover Buku" width="60">
                        </td>
                        <td class="py-2 px-4 text-left">
                            <a href="updatebuku.php?id=<?= htmlspecialchars($row['id_buku']); ?>" class="text-blue-600">Edit</a>
                            <button onclick="confirmDelete(<?= htmlspecialchars($row['id_buku']); ?>)" class="text-red-700 ml-2">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        

        
        <script>
    document.querySelector('input[type="text"]').addEventListener("keyup", function() {
        let query = this.value;
        if (query.length > 0) {
            // Lakukan AJAX
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "searchbuku.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("bukuData").innerHTML = xhr.responseText;
                }
            };
            xhr.send("query=" + query);
        } else {
            // Jika input kosong, tampilkan ulang data awal
            location.reload();
        }
    });
</script>

<script>

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `deletebuku.php?id=${id}`;
                    }
                });
            }
        </script>
    </div>
</main>
</body>
</html>
