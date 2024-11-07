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
$stmt = $buku->readLimited(4); // Mengambil 4 data buku
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="../../output.css" rel="stylesheet" />
    <title>Document</title>
    


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
     <a href="../Views/auth/logout.php"><h1>Logout</h1></a>


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
                <div
                    class="grid md:grid-cols-2 lg:grid-cols-4 items-center gap-4 mt-14">
                    <?php foreach ($books as $data) : ?>
                        <a href="detailBuku.php?id_buku=<?= $data['id_buku']; ?>">
                            <div class="w-52 shadow-lg rounded-lg mb-6 md:mb-0">
                                <div class="px-4 py-4">
                                    <div
                                        class="w-full h-[300px] md:h-[222px] lg:h-[310px] rounded-md relative group">
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
                                        <img class
                                            src="../../public//assets//Books/<?= $data['cover'] ?>"
                                            class="w-full h-full object-cover rounded-lg"
                                            alt="" />
                                    </div>

                                    <!-- Title Start -->
                                    <div class="my-3 px-1">
                                        <div class="flex justify-between items-center mb-3">
                                            <h1 class="font-bold md:text-lg"><?= $data['judul_buku'] ?></h1>
                                            <p>
                                                <i class="fa-solid fa-star text-yellow-500 pe-2"></i><span>4.5</span>
                                            </p>
                                        </div>

                                        <p class="text-grey font-normal text-wrap">
                                            <?= $data['sinopsis'] ?>
                                        </p>
                                    </div>
                                    
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    
                    <div class="flex justify-end md:mt-8">
                        <a
                            href="./DataBuku.php"
                            class="self-start text-sm md:text-lg hover:text-main font-semibold">Lihat Lebih Banyak <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section Start -->
    <section class="pt-16 pb-20">
      <div class="w-full px-4">
        <div class="container mx-auto">
          <div class="grid lg:grid-cols-2 items-stretch justify-between">
            <!-- Content Left Start -->
            <div class="mb-10 lg:mb-0">
              <p class="text-lg font-medium text-main md:text-xl">
                Tentang Kami
              </p>
              <h1
                class="text-2xl md:text-4xl font-semibold text-slate-800 my-3 md:my-4"
              >
                Membantu anda untuk meminjam buku perpus secara praktis
              </h1>
              <p class="font-normal text-sm text-grey md:text-lg md:max-w-md">
                Sekarang tidak perlu antri dan ribet untuk meminjam buku perpus,
                tinggal sat set sat set selesai.
              </p>

              <div class="mt-8">
                <a
                  href="./DataBuku.php"
                  class="px-6 py-3 bg-main text-white font-normal rounded-full text-sm hover:bg-main/85 transition-all duration-300"
                  >Pinjam Buku Sekarang</a
                >
              </div>
            </div>
            <!-- Content Left End -->

            <!-- Content Right Start -->
            <div class="md:mt-8">
              <div
                class="grid md:grid-cols-2 md:gap-4 justify-center md:justify-between"
              >
                <!-- Card 1 Start -->
                <div
                  class="w-full mx-auto border-t-4 border-t-main px-6 py-6 shadow-xl mb-4 lg:mb-3"
                >
                  <div class="flex flex-col gap-5">
                    <div class="">
                      <img src="../../public//assets//vuesax//blue.png" alt="" />
                    </div>

                    <!-- Title Start -->
                    <h3
                      class="text-xl md:text-2xl font-semibold text-slate-900"
                    >
                      Berbagai banyak macam buku.
                    </h3>
                    <p class="font-normal text-grey text-sm">
                      Banyak sekali buku buku menarik yang bisa kamu pinjam
                      diperpus.
                    </p>
                    <!-- Title End -->
                  </div>
                </div>
                <!-- Card 1 End -->

                <!-- Card 2 Start -->
                <div
                  class="w-full mx-auto border-t-4 border-t-pink px-6 py-6 shadow-xl mb-4 lg:mb-3"
                >
                  <div class="flex flex-col gap-3">
                    <div class="">
                      <img
                        src="../../public//assets//vuesax//red.png"
                        alt=""
                      />
                    </div>

                    <!-- Title Start -->
                    <h3
                      class="text-xl md:text-2xl font-semibold text-slate-900"
                    >
                      Pinjam sekarang baca dimana saja
                    </h3>
                    <p class="font-normal text-grey text-sm">
                      Kamu bisa pinjam buku lalu dapat dibaca kapanpun kamu mau.
                    </p>
                    <!-- Title End -->
                  </div>
                </div>
                <!-- Card 2 End -->

                <!-- Card 3 Start -->
                <div
                  class="w-full mx-auto border-t-4 border-t-greenyellow px-6 py-6 shadow-xl mb-4 lg:mb-3"
                >
                  <div class="flex flex-col gap-3">
                    <div class="">
                      <img
                        src="../../public//svg//message-tick.svg"
                        alt=""
                      />
                    </div>

                    <!-- Title Start -->
                    <h3
                      class="text-xl md:text-2xl font-semibold text-slate-900"
                    >
                      Update Buku Terbaru
                    </h3>
                    <p class="font-normal text-grey text-sm">
                      Banyak buku buku terbaru yang dapat kamu pinjam diperpus
                      ini.
                    </p>
                    <!-- Title End -->
                  </div>
                </div>
                <!-- Card 3 End -->

                <!-- Card 4 Start -->
                <div
                  class="w-full mx-auto border-t-4 border-t-secondaryBrigt px-6 py-6 shadow-xl mb-4 lg:mb-3"
                >
                  <div class="flex flex-col gap-3">
                    <div class="">
                      <img
                        src="../../public//assets//vuesax//notification-bing.png"
                        alt=""
                      />
                    </div>

                    <!-- Title Start -->
                    <h3
                      class="text-xl md:text-2xl font-semibold text-slate-900"
                    >
                      Update Email Notifikasi
                    </h3>
                    <p class="font-normal text-grey text-sm">
                      Mendapatkan email notifikasi kapan harus memulangkan buku
                    </p>
                    <!-- Title End -->
                  </div>
                </div>
                <!-- Card 4 End -->
              </div>
            </div>
            <!-- Content Right End -->
          </div>
        </div>
      </div>
    </section>
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
            class="max-w-screen-xl mx-auto px-5 bg-white min-h-sceen space-y-3"
          >
            <!-- Content 1 Start -->
            <div
              class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto"
            >
              <details class="group">
                <summary
                  class="flex justify-between items-center font-medium cursor-pointer list-none"
                >
                  <span class="text-sm md:text-lg">
                    Bagaimana cara meminjam buku disini?</span
                  >
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
                      width="24"
                    >
                      <path d="M6 9l6 6 6-6"></path>
                    </svg>
                  </span>
                </summary>
                <p
                  class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500"
                >
                  SAAS platform is a cloud-based software service that allows
                  users to access and use a variety of tools and functionality.
                </p>
              </details>
            </div>
            <!-- Content 1 End -->

            <!-- Content 2 Start -->
            <div
              class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto"
            >
              <details class="group">
                <summary
                  class="flex justify-between items-center font-medium cursor-pointer list-none"
                >
                  <span class="text-sm md:text-lg">
                    Bagaimana cara meminjam buku disini?</span
                  >
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
                      width="24"
                    >
                      <path d="M6 9l6 6 6-6"></path>
                    </svg>
                  </span>
                </summary>
                <p
                  class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500"
                >
                  SAAS platform is a cloud-based software service that allows
                  users to access and use a variety of tools and functionality.
                </p>
              </details>
            </div>
            <!-- Content 2 End -->

            <!-- Content 3 Start -->
            <div
              class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto"
            >
              <details class="group">
                <summary
                  class="flex justify-between items-center font-medium cursor-pointer list-none"
                >
                  <span class="text-sm md:text-lg">
                    Bagaimana cara meminjam buku disini?</span
                  >
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
                      width="24"
                    >
                      <path d="M6 9l6 6 6-6"></path>
                    </svg>
                  </span>
                </summary>
                <p
                  class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500"
                >
                  SAAS platform is a cloud-based software service that allows
                  users to access and use a variety of tools and functionality.
                </p>
              </details>
            </div>
            <!-- Content 3 End -->

            <!-- Content 4 Start -->
            <div
              class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto"
            >
              <details class="group">
                <summary
                  class="flex justify-between items-center font-medium cursor-pointer list-none"
                >
                  <span class="text-sm md:text-lg">
                    Bagaimana cara meminjam buku disini?</span
                  >
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
                      width="24"
                    >
                      <path d="M6 9l6 6 6-6"></path>
                    </svg>
                  </span>
                </summary>
                <p
                  class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500"
                >
                  SAAS platform is a cloud-based software service that allows
                  users to access and use a variety of tools and functionality.
                </p>
              </details>
            </div>
            <!-- Content 4 End -->

            <!-- Content 5 Start -->
            <div
              class="lg:w-[80%] py-2 md:py-4 px-6 border border-slate-400 rounded-3xl mx-auto"
            >
              <details class="group">
                <summary
                  class="flex justify-between items-center font-medium cursor-pointer list-none"
                >
                  <span class="text-sm md:text-lg">
                    Bagaimana cara meminjam buku disini?</span
                  >
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
                      width="24"
                    >
                      <path d="M6 9l6 6 6-6"></path>
                    </svg>
                  </span>
                </summary>
                <p
                  class="text-neutral-600 mt-3 text-sm group-open:animate-fadeIn transition-all duration-500"
                >
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
		(function () {
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
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>

</html>