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
            class="fixed left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-pinkSec lg:translate-x-0 lg:static lg:inset-0 h-screen">
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
                        src="../../public//svg/element-equal.svg"
                        alt=""
                        class="w-6 h-6 object-cover" />

                    <p class="text-lg text-slate-500 group-hover:text-white">
                        Dashboard
                    </p>
                </a>

                <a
                    href="daftarBuku.html"
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
                    href="profileUser.html"
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
                            class="w-6 h-6"
                            viewBox="0 0 24 24"
                            fill="none"
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

        </div>

    </div>