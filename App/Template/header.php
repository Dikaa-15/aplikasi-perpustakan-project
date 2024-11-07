<?php
session_start();
// var_dump($_SESSION['profil_user']); // Debugging
?>

<!-- Header Start -->
<header class="w-full py-4 md:py-1 fixed top-0 left-0 bg-white z-10">
    <nav class="container mx-auto px-4 flex justify-between items-center">
        <!-- Content Left -->
        <div class="md:py-4 text-3xl font-bold text-lime-500 capitalize">
            <a href="../Views/Landing-page.php"><img src="../../public/logo 1.png" alt="" /></a>
        </div>

        <!-- Content Center -->
        <div>
            <ul class="hidden md:flex items-center gap-2">
                <li class="group px-3 py-2">
                    <a href="../Views/Landing-page.php" class="font-normal text-sm lg:text-lg text-slate-800 group-hover:text-main">Beranda</a>
                </li>
                <li class="group px-3 py-2">
                    <a href="" class="font-normal text-sm lg:text-lg text-slate-800 group-hover:text-main">Tentang Kami</a>
                </li>
                <li class="group px-3 py-2">
                    <a href="daftarBuku.html" class="font-normal text-sm lg:text-lg text-slate-800 group-hover:text-main">Kategori Buku</a>
                </li>
                <li class="group px-3 py-2">
                    <a href="" class="font-normal text-sm lg:text-lg text-slate-800 group-hover:text-main">FAQ</a>
                </li>
            </ul>
        </div>

        <!-- Nav Content for User -->
        <div class="hidden md:flex items-center gap-4">
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
                <!-- Tampilan Profil Jika User Sudah Login -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full overflow-hidden">
                        <img src="../../public/profile//<?php echo $_SESSION['profil_user']; ?>" alt="Profile Image" class="w-full h-full object-cover" />
                    </div>
                    <a href="../Views/User/account.php"><p class="font-normal text-sm"><?php echo $_SESSION['nama_lengkap']; ?></p></a>
                </div>
            <?php else: ?>
                <!-- Tampilan Login dan Register Jika User Belum Login -->
                <a href="../Views/auth/register.php" class="px-4 py-2 bg-white text-main border border-main rounded-full text-sm">Register</a>
                <a href="../Views/auth/login.php" class="px-4 py-2 bg-main text-white rounded-full text-sm">Login</a>
            <?php endif; ?>
        </div>

        <!-- Hamburger Menu for Mobile -->
        <div id="navMobile" class="hidden transition-all duration-300">
            <ul class="p-5 absolute h-56 right-7 left-24 top-20 inset-0 bg-white capitalize z-50 shadow-lg space-y-2">
                <li><a href="" class="hover:text-lime-500 text-[16px] transition-all duration-300">Beranda</a></li>
                <li><a href="" class="hover:text-lime-500 text-[16px] transition-all duration-300">Kelas</a></li>
                <li><a href="" class="hover:text-lime-500 text-[16px] transition-all duration-300">Alur Belajar</a></li>
                <li><a href="" class="hover:text-lime-500 text-[16px] transition-all duration-300">Event</a></li>
                <?php if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']): ?>
                    <!-- Mobile Register dan Login Jika Belum Login -->
                    <div class="flex gap-2 mt-4">
                        <a href="../Views/auth/register.php" class="px-4 py-2 bg-white text-main border border-main rounded-full text-sm">Register</a>
                        <a href="../Views/auth/login.php" class="px-4 py-2 bg-main text-white rounded-full text-sm">Login</a>
                    </div>
                <?php endif; ?>
            </ul>
        </div>

        <div id="menu" class="md:hidden cursor-pointer transition-all duration-300 z-50">
            <i class="fa-solid fa-bars text-2xl"></i>
        </div>
    </nav>
</header>
<!-- Header End -->
