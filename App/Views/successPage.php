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

    <?= require_once '../Template/header.php' ?>

    <!-- Content Success Start -->
    <section class="pt-10 pb-28">
        <div class="w-full px-4">
            <div class="container mx-auto">
                <div class="flex flex-col items-center gap-6">
                    <!-- Hero Success Start -->
                    <div class="mt-8 h-[300px] md:h-[420px]">
                        <img
                            src="../../public//success.png"
                            alt=""
                            class="w-full h-full object-cover" />
                    </div>
                    <!-- Hero Success End -->

                    <!-- Title Start -->
                    <div class="text-center">
                        <h2 class="font-bold text-xl md:text-2xl mb-2 md:mb-4">
                            Peminjaman terverifikasi, silahkan konfirmasi ya!
                        </h2>
                        <div class="md:w-[480px] md:px-3 text-center mx-auto mb-3">
                            <p class="font-normal text-sm text-slate-500">
                                Peminjaman buku kamu sudah terverifikasi, silahkan
                                keperpustakaan untuk mengambil buku yang diinginkan!
                            </p>
                        </div>
                        <div
                            class="w-[90%] md:w-[80%] h-fit py-3 px-6 text-center text-white bg-main rounded-full mx-auto transition-all duration-300 hover:bg-white hover:text-main border hover:border-main cursor-pointer">
                            <a href="./dashboard.php" class="">Confirmasi Peminjaman</a>
                        </div>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
        </div>
    </section>
    <!-- Content Success End -->

    <?= require_once '../Template/footer.php' ?>

</body>

</html>