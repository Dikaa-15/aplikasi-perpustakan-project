<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<div class="">

    <script
        src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
        defer></script>

    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        <div
            :class="sidebarOpen ? 'block' : 'hidden'"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

        <div
            :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
            class="fixed left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-pinkSec lg:translate-x-0 lg:static lg:inset-0 h-full md:min-h-screen">
            <div class="flex items-center justify-center pt-8 mb-8">
                <a href="../Views/Landing-page.php">
                <img src="../../public//logo 1.png" alt="Logo Buku" />
                </a>
            </div>

            <!-- Nav Menu Start -->
            <div class="flex flex-col gap-4 px-4 py-8">
                <a
                    href="userDashboard.html"
                    class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                    <img
                        src="../../public-admin//image//properti//kotak//vuesax//bold//element-equal.svg"
                        alt=""
                        class="w-6 h-6 object-cover" />

                    <p class="text-lg text-slate-500 group-hover:text-white">
                        Dashboard
                    </p>
                </a>

                <a
                    href="../Views/DataBuku.php"
                    class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                    <img
                        src="../../public//svg/book.svg"
                        alt=""
                        class="w-6 h-6 object-cover group-hover:text-white" />

                    <p class="text-lg text-slate-500 group-hover:text-white">
                        Data Buku
                    </p>
                </a>

                <a
                    href=""
                    class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                    <img
                        src="../../public//svg/clipboard-text.svg"
                        alt=""
                        class="w-6 h-6 object-cover group-hover:fill-white" />

                    <p class="text-lg text-slate-500 group-hover:text-white">
                        Data Kunjungan
                    </p>
                </a>

                <a
                    href="../Views/User/account.php"
                    class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                    <img
                        src="../../public//svg/user-octagon.svg"
                        alt=""
                        class="w-6 h-6 object-cover group-hover:fill-white" />

                    <p class="text-lg text-slate-500 group-hover:text-white">
                        Profile
                    </p>
                </a>

                <a
                    href=""
                    class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                    <img
                        src="../../public//svg/logout.svg"
                        alt=""
                        class="w-6 h-6 object-cover group-hover:fill-white" />

                    <p class="text-lg text-slate-500 group-hover:text-white">
                        Logout
                    </p>
                </a>
            </div>
            <!-- Nav Menu End -->
        </div>


        <div class="flex flex-col flex-1 overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                    <button
                        @click="sidebarOpen = true"
                        class="text-gray-500 focus:outline-none lg:hidden">
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

                <div class="flex items-center">
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button
                            @click="dropdownOpen = ! dropdownOpen"
                            class="relative flex justify-center items-center gap-2 overflow-hidden rounded-full focus:outline-none">
                            <div class="w-8 h-8 rounded-full">
                                <img
                                    class="object-cover w-full h-full"
                                    src="../../public//Image Profile.png"
                                    alt="Your avatar" />
                            </div>

                            <p class="font-normal text-sm">Dimas Putra A</p>
                        </button>

                        <div
                            x-show="dropdownOpen"
                            @click="dropdownOpen = false"
                            class="fixed inset-0 z-10 w-full h-full"
                            style="display: none"></div>

                        <!-- Dropdown Profile Start -->
                        <div
                            x-show="dropdownOpen"
                            class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl"
                            style="display: none">
                            <a
                                href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profile</a>
                            <a
                                href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Products</a>
                            <a
                                href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Logout</a>
                        </div>
                        <!-- Dropdown Profile End -->
                    </div>
                </div>
            </header>

            <!-- Main Content Start -->
          <main
            class="flex-1 overflow-x-hidden overflow-y-auto px-4 md:px-8 pt-2"
          >
            <!-- Search Bar Start -->
            <div class="mb-5 md:mb-4">
              <div class="w-full mx-auto md:mx-0 md:w-[75%] relative">
                <input
                  type="text"
                  id="name"
                  placeholder="Search"
                  class="w-full block flex-1 bg-inputColors px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:border-main text-sm md:text-[16px] sm:leading-6 rounded-full"
                />
                <button type="submit" class="absolute right-4 top-3">
                  <i class="fa-solid fa-search text-lg text-slate-500"></i>
                </button>
              </div>
            </div>
            <!-- Search Bar End -->

            <!-- Heading Start -->
            <div class="mb-4 md:mb-8">
              <h2 class="font-bold text-2xl text-black">Data Peminjaman</h2>
            </div>
            <!-- Heading End -->

            <!-- Content Detail Peminjaman Start -->
            <div class="flex items-center gap-6 w-full mb-10">
              <!-- Card Buku Start -->
              <div
                class="w-[270px] hidden md:block shadow-lg rounded-lg bg-white px-3 py-3"
              >
                <div class="w-[222px] rounded-md mx-auto mb-3">
                  <img
                    src="../../public//assets//Books/<?= htmlspecialchars($row['cover']) ?>"
                    class="w-full"
                    alt=""
                  />
                </div>

                <!-- Title Start -->
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
                <!-- Title End -->
              </div>
              <!-- Card Buku End -->

              <!-- Penjelasan Start -->
              <div
                class="w-full md:w-[450px] h-[353px] mx-auto md:mx-0 bg-inputColors px-4 py-3 rounded-lg overflow-x-hidden"
              >
                <!-- Heading Start -->
                <div class="">
                  <h2 class="text-[28px] font-bold text-black">
                    Buku Geografis
                  </h2>
                  <p class="font-normal text-sm text-grey my-3">
                    Penerbit : Kompas Gramedia
                  </p>
                  <p class="font-bold text-[20px] text-black mb-4">
                    Batas Peminjaman
                  </p>

                  <!-- Batas Peminjaman Start -->
                  <div
                    class="w-fit md:w-[20rem] px-2 md:px-6 py-3 bg-pinkSec flex items-center gap-1 rounded-lg mb-5"
                  >
                    <a
                      href=""
                      class="px-2 py-1 text-white rounded-md bg-primaryBlue"
                      >30</a
                    >
                    <a href="" class="px-2 py-1 text-primaryBlue">Day</a>
                    <a
                      href=""
                      class="px-2 py-1 text-white rounded-md bg-primaryBlue"
                      >24</a
                    >
                    <a href="" class="px-2 py-1 text-primaryBlue">Hours</a>
                    <a
                      href=""
                      class="px-2 py-1 text-white rounded-md bg-primaryBlue"
                      >60</a
                    >
                    <a href="" class="px-2 py-1 text-primaryBlue">Sec</a>
                  </div>
                  <!-- Batas Peminjaman End -->

                  <!-- Cek Status Peminjaman Start -->
                  <div class="">
                    <h2 class="text-[20px] font-bold text-black mb-4">
                      Status Peminjaman
                    </h2>

                    <a href="">
                      <div
                        class="block w-[90%] md:w-[75%] text-center rounded-2xl px-8 py-3 bg-pinkButton text-white font-bold text-[20px] border hover:border-pinkButton hover:bg-white hover:text-pinkButton transition-all duration-300"
                      >
                        Sedang Dipinjam
                      </div>
                    </a>
                  </div>
                  <!-- Cek Status Peminjaman End -->
                </div>
                <!-- Heading End -->
              </div>
              <!-- Penjelasan End -->
            </div>
            <!-- Content Detail Peminjaman End -->

            <!-- Content Detail Peminjaman Start -->
            <div class="flex items-center gap-6 w-full">
              <!-- Card Buku Start -->
              <div
                class="w-[270px] hidden md:block shadow-lg rounded-lg bg-white px-3 py-3"
              >
                <div class="w-[222px] rounded-md mx-auto mb-3">
                  <img
                    src="../public/image/card buku/3.png"
                    class="w-full"
                    alt=""
                  />
                </div>

                <!-- Title Start -->
                <div class="px-3">
                  <div class="flex justify-between mb-3">
                    <p class="font-bold text-lg text-black">Buku Geografis</p>

                    <div class="flex items-center gap-1">
                      <i class="fa-solid fa-star text-yellow-500"></i>
                      <p class="font-normal">4.5</p>
                    </div>
                  </div>

                  <p class="font-normal text-sm">
                    Buku yang tepat buat kamu yang ingin tahu letak geografis
                    dunia
                  </p>
                </div>
                <!-- Title End -->
              </div>
              <!-- Card Buku End -->

              <!-- Penjelasan Start -->
              <div
                class="w-full md:w-[450px] h-[353px] mx-auto md:mx-0 bg-inputColors px-4 py-3 rounded-lg overflow-x-hidden"
              >
                <!-- Heading Start -->
                <div class="">
                  <h2 class="text-[28px] font-bold text-black">
                    Buku Geografis
                  </h2>
                  <p class="font-normal text-sm text-grey my-3">
                    Penerbit : Kompas Gramedia
                  </p>
                  <p class="font-bold text-[20px] text-black mb-4">
                    Batas Peminjaman
                  </p>

                  <!-- Batas Peminjaman Start -->
                  <div
                    class="w-fit px-3 md:px-6 py-3 bg-pinkSec flex items-center gap-1 rounded-lg mb-5"
                  >
                    <a
                      href=""
                      class="px-2 py-1 text-white rounded-md bg-primaryBlue"
                      >30</a
                    >
                    <a href="" class="px-2 py-1 text-primaryBlue">Day</a>
                    <a
                      href=""
                      class="px-2 py-1 text-white rounded-md bg-primaryBlue"
                      >24</a
                    >
                    <a href="" class="px-2 py-1 text-primaryBlue">Hours</a>
                    <a
                      href=""
                      class="px-2 py-1 text-white rounded-md bg-primaryBlue"
                      >60</a
                    >
                    <a href="" class="px-2 py-1 text-primaryBlue">Sec</a>
                  </div>
                  <!-- Batas Peminjaman End -->

                  <!-- Cek Status Peminjaman Start -->
                  <div class="">
                    <h2 class="text-[20px] font-bold text-black mb-4">
                      Status Peminjaman
                    </h2>

                    <a href="">
                      <div
                        class="block w-[90%] md:w-[75%] text-center rounded-2xl px-8 py-3 bg-greens text-white font-bold text-[20px] border hover:border-greens hover:bg-white hover:text-greens transition-all duration-300"
                      >
                        Sedang Dipinjam
                      </div>
                    </a>
                  </div>
                  <!-- Cek Status Peminjaman End -->
                </div>
                <!-- Heading End -->
              </div>
              <!-- Penjelasan End -->
            </div>
            <!-- Content Detail Peminjaman End -->
          </main>
          <!-- Main Content End -->


        </div>

    </div>
</div>