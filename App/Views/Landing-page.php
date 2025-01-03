<?php
// Sertakan file koneksi dan class Buku
include_once '../core/Database.php';
include_once '../Models/Buku.php';

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat instance dari kelas Buku dengan menyertakan koneksi database
$buku = new Buku($db);

// Inisialisasi $offset dan $limit
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; // Default ke 0 jika tidak ada offset
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 4;   // Default ke 4 jika tidak ada limit

// Mengambil data buku dengan pagination
$stmt = $buku->readPaginated($offset, $limit);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>

  <link href="../../output.css" rel="stylesheet" />
  <link rel="shortcut icon" href="/perpustakaan-jp/public/logo 1.png" type="image/x-icon">
  <title>Landing-page</title>



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

  <!-- <?php require_once '../Template/header.php' ?> -->

  <!-- Hero Section Start -->
  <section class="pt-20 md:pt-36 pb-16 xl:pt-52">
    <div class="w-full px-4">
      <div class="container mx-auto">
        <div class="flex flex-col md:flex-row justify-between">
          <!-- Content Left Start -->
          <div
            class="w-full md:w-1/2 order-2 md:order-1 pt-4 text-center md:text-start">
            <h1
              class="text-2xl md:text-5xl font-bold text-slate-800 md:leading-snug">
              Perpustakaan Digital SMK Jakarta Pusat 1
            </h1>
            <p
              class="text-grey font-normal text-sm md:text-lg mt-8 mb-10 md:mb-8 md:my-10">
              Cara terbaik untuk meningkatkan kualitas karakter, kompetensi
              dan kesejahteraan hidup seseorang, adalah dengan menanamkan
              budaya literasi
            </p>

            <a
              href="./DataBuku.php"
              class="px-8 py-4 text-white bg-main text-sm rounded-full">Explore Sekarang</a>
          </div>
          <!-- Content Left End -->

          <!-- Content Right Start -->
          <div class="w-full md:w-1/2 order-1 md:order-2 md:-mt-8">
            <div class="">
              <img
                src="../../public//content 1.png"
                alt=""
                class="w-full h-full" />
            </div>
          </div>
          <!-- Content Right End -->
        </div>
      </div>
    </div>
  </section>
  <!-- Hero Section End -->
  
  <!--Data buku section start --> 
  <section class="pt-14 pb-20">
    <div class="w-full px-4">
      <div class="container mx-auto">
        <!-- Header Start -->
        <div class="space-y-3 mb-8">
          <h1 class="font-bold text-xl text-center md:text-3xl">Data Buku</h1>
          <p class="text-grey text-sm md:text-lg text-center">
            berikut buku buku yang tersedia yang dapat kamu pinjam
          </p>
        </div>
        <!-- Header End -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 items-center gap-4 mt-14">
          <?php foreach ($books as $data) : ?>
            <a href="detailBuku-try.php?id_buku=<?= $data['id_buku']; ?>">
              <div class="w-full shadow-lg rounded-lg mb-6 md:mb-0 flex flex-col h-[400px] overflow-hidden">
                <div class="flex-grow">
                  <div class="px-4 py-4">
                    <div class="w-full h-[222px] lg:h-[250px] rounded-md relative group overflow-hidden">
                      <div class="w-full h-full bg-black bg-opacity-35 items-center justify-center absolute top-0 left-0 hidden group-hover:flex transition-all duration-700 rounded-lg cursor-pointer">
                        <div class="flex flex-col justify-center items-center gap-2">
                          <i class="fa-regular fa-eye text-white text-5xl md:text-6xl"></i>
                          <p class="text-white text-xl font-semibold">Lihat Detail</p>
                        </div>
                      </div>
                      <img src="../../public/assets/Books/<?= $data['cover'] ?>"
                        class="w-full h-full object-cover rounded-lg" alt="" />
                    </div>
                  </div>
                </div>

                <!-- Title and Content -->
                <div class="my-3 px-4 flex flex-col">
                  <div class="flex justify-between items-center mb-3">
                    <h1 class="font-bold text-lg"><?= $data['judul_buku'] ?></h1>
                    <p>
                      <i class="fa-solid fa-star text-yellow-500"></i><span class="ml-1">4.5</span>
                    </p>
                  </div>
                  <p class="text-gray-500 font-normal text-sm line-clamp-3">
                    <?= $data['sinopsis'] ?>
                  </p>
                </div>
              </div>
            </a>
          <?php endforeach; ?>

        </div>
        <div class="flex justify-end md:mt-8">
          <a href="./DataBuku.php" class="text-sm md:text-lg hover:text-main font-semibold">Lihat Lebih Banyak <i class="fa-solid fa-arrow-right"></i></a>
        </div>



      </div>
    </div>
  </section>
  <!--Data buku section end -->

  <!-- Tentang Kami Section Start -->
  <?php require_once '../Template/abousUs.php'  ?>
  <!-- Tentang Kami Section End -->

  <!-- FAQ Section Start -->
  <section class="pt-14 pb-20">
    <div class="w-full px-4">
      <div class="container mx-auto">
        <!-- Header Start -->
        <div class="space-y-2 mb-10">
          <h3 class="text-2xl font-bold text-center">FAQ</h3>
          <p class="text-grey font-normal text-sm md:text-lg text-center">
            Butuh jawaban? temukan disini
          </p>
        </div>
        <!-- Header End -->

        <!-- Content FAQ Start -->
        <!-- component -->
        <div
          class="max-w-screen-xl mx-auto px-5 bg-white min-h-sceen space-y-3">
          <!-- Content 1 Start -->
          <div
            class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto">
            <details class="group">
              <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none">
                <span class="text-sm md:text-lg">
                  Bagaimana cara meminjam buku disini?</span>
                <span class="transition group-open:rotate-180">
                  <svg
                    fill="none"
                    height="24"
                    shape-rendering="geometricPrecision"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                    width="24">
                    <path d="M6 9l6 6 6-6"></path>
                  </svg>
                </span>
              </summary>
              <p
                class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500">
                SAAS platform is a cloud-based software service that allows
                users to access and use a variety of tools and functionality.
              </p>
            </details>
          </div>
          <!-- Content 1 End -->

          <!-- Content 2 Start -->
          <div
            class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto">
            <details class="group">
              <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none">
                <span class="text-sm md:text-lg">
                  Bagaimana cara meminjam buku disini?</span>
                <span class="transition group-open:rotate-180">
                  <svg
                    fill="none"
                    height="24"
                    shape-rendering="geometricPrecision"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                    width="24">
                    <path d="M6 9l6 6 6-6"></path>
                  </svg>
                </span>
              </summary>
              <p
                class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500">
                SAAS platform is a cloud-based software service that allows
                users to access and use a variety of tools and functionality.
              </p>
            </details>
          </div>
          <!-- Content 2 End -->

          <!-- Content 3 Start -->
          <div
            class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto">
            <details class="group">
              <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none">
                <span class="text-sm md:text-lg">
                  Bagaimana cara meminjam buku disini?</span>
                <span class="transition group-open:rotate-180">
                  <svg
                    fill="none"
                    height="24"
                    shape-rendering="geometricPrecision"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                    width="24">
                    <path d="M6 9l6 6 6-6"></path>
                  </svg>
                </span>
              </summary>
              <p
                class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500">
                SAAS platform is a cloud-based software service that allows
                users to access and use a variety of tools and functionality.
              </p>
            </details>
          </div>
          <!-- Content 3 End -->

          <!-- Content 4 Start -->
          <div
            class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto">
            <details class="group">
              <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none">
                <span class="text-sm md:text-lg">
                  Bagaimana cara meminjam buku disini?</span>
                <span class="transition group-open:rotate-180">
                  <svg
                    fill="none"
                    height="24"
                    shape-rendering="geometricPrecision"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                    width="24">
                    <path d="M6 9l6 6 6-6"></path>
                  </svg>
                </span>
              </summary>
              <p
                class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500">
                SAAS platform is a cloud-based software service that allows
                users to access and use a variety of tools and functionality.
              </p>
            </details>
          </div>
          <!-- Content 4 End -->

          <!-- Content 5 Start -->
          <div
            class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto">
            <details class="group">
              <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none">
                <span class="text-sm md:text-lg">
                  Bagaimana cara meminjam buku disini?</span>
                <span class="transition group-open:rotate-180">
                  <svg
                    fill="none"
                    height="24"
                    shape-rendering="geometricPrecision"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                    width="24">
                    <path d="M6 9l6 6 6-6"></path>
                  </svg>
                </span>
              </summary>
              <p
                class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500">
                SAAS platform is a cloud-based software service that allows
                users to access and use a variety of tools and functionality.
              </p>
            </details>
          </div>
          <!-- Content 5 End -->
        </div>
        <!-- Content FAQ End -->
      </div>
    </div>
  </section>
  <!-- FAQ Section End -->

  <?php require_once '../Template/footer.php' ?>

  <!-- Code injected by live-server -->
  <script>
    // <![CDATA[  <-- For SVG support
    if ('WebSocket' in window) {
      (function() {
        function refreshCSS() {
          var sheets = [].slice.call(document.getElementsByTagName("link"));
          var head = document.getElementsByTagName("head")[0];
          for (var i = 0; i < sheets.length; ++i) {
            var elem = sheets[i];
            var parent = elem.parentElement || head;
            parent.removeChild(elem);
            var rel = elem.rel;
            if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
              var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
              elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
            }
            parent.appendChild(elem);
          }
        }
        var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
        var address = protocol + window.location.host + window.location.pathname + '/ws';
        var socket = new WebSocket(address);
        socket.onmessage = function(msg) {
          if (msg.data == 'reload') window.location.reload();
          else if (msg.data == 'refreshcss') refreshCSS();
        };
        if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
          console.log('Live reload enabled.');
          sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
        }
      })();
    } else {
      console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
    }
    // ]]>
  </script>
</body>

</html>