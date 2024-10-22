<?php
session_start();

// Sertakan file koneksi ke database dan model user
require_once '../../core/Database.php';
require_once '../../Models/user.php';

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil input dari form dan melakukan sanitasi
    $user->nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $user->nis = htmlspecialchars($_POST['nis']);
    $user->nisn = htmlspecialchars($_POST['nisn']);
    $user->no_kartu = htmlspecialchars($_POST['no_kartu']);
    $user->kelas = htmlspecialchars($_POST['kelas']);
    $user->no_whatsapp = htmlspecialchars($_POST['no_whatsapp']);
    $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $user->roles = 'user'; // Set default role sebagai 'user'

    // Melakukan registrasi pengguna
    if ($user->register()) {
        // Menyimpan data session
        $_SESSION['id_user'] = $user->id_user;
        $_SESSION['nama_lengkap'] = $user->nama_lengkap;
        $_SESSION['roles'] = $user->roles;

        // Redirect setelah registrasi sesuai dengan roles
        switch ($user->roles) {
            case 'admin':
                header("Location: ../admin/dashboard.php");
                break;
            case 'user':
                header("Location: ../viewBuku.php");
                break;
            case 'petugas':
                header("Location: ../petugas/dashboard.php");
                break;
            default:
                echo "Role tidak dikenali.";
                break;
        }
        exit();
    } else {
        echo "Registrasi gagal. Silakan coba lagi.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="output.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <!-- Container Utama -->
    <div class="flex items-center justify-center font-fontMain">
        <!-- Konten Flex -->
        <div class="container flex flex-col md:flex-row px-8 py-4 md:px-16 md:py-8 gap-8">
            <!-- Kolom Formulir -->
            <div class="flex-1">
                <nav><img src="../../../public//assets//logo 1.png" alt="Logo"></nav>
                <h1 class="mt-5 font-bold text-4xl md:mt-28">Daftarkan Akunmu Gratis!</h1>
                <div class="mt-4" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2 );">
                    Akunmu sudah terdaftar? <a href="login.php" class="text-blue-700">Masuk Disini</a>
                </div>

                <!-- Form Sign Up -->
                <form action="register.php" method="POST" class="mt-4">
                    <!-- Nama Lengkap -->
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mt-8">
                        Nama Lengkap<span class="text-red-500 mb-1"></span>
                    </label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Afrizal Dwi Handika"
                        class="block w-full h-14 border border-gray-300 rounded-full px-4 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />

                    <!-- NIS -->
                    <label for="nis" class="block text-sm font-medium text-gray-700 mt-8">
                        NIS<span class="text-red-500"></span>
                    </label>
                    <input type="text" id="nis" name="nis" placeholder="Masukkan Nomor NIS kamu"
                        class="block w-full h-14 border border-gray-300 rounded-full px-4 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                    <!-- NISN -->
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mt-8">
                        NISN<span class="text-red-500"></span>
                    </label>
                    <input type="text" id="nisn" name="nisn" placeholder="Masukkan Nomor NISN kamu"
                        class="block w-full h-14 border border-gray-300 rounded-full px-4 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />


                    <!-- No Kartu -->
                    <label for="no_kartu" class="block text-sm font-medium text-gray-700 mt-8">
                        No Kartu Perpustakaan<span class="text-red-500"></span>
                    </label>
                    <input type="text" id="no_kartu" name="no_kartu" placeholder="Masukkan Nomor Kartu Perpus kamu"
                        class="block w-full h-14 border border-gray-300 rounded-full px-4 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />
                    <!-- Kelas -->
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mt-8">
                        Kelas<span class="text-red-500"></span>
                    </label>
                    <input type="text" id="kelas" name="kelas" placeholder="Masukkan Nomor Kartu Perpus kamu"
                        class="block w-full h-14 border border-gray-300 rounded-full px-4 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />

                    <!-- No WhatsApp -->
                    <label for="no_whatsapp" class="block text-sm font-medium text-gray-700 mt-8">
                        No Telp<span class="text-red-500"></span>
                    </label>
                    <input type="text" id="no_whatsapp" name="no_whatsapp" placeholder="08123456789"
                        class="block w-full h-14 border border-gray-300 rounded-full px-4 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />

                    <!-- Password -->
                    <label for="password" class="block text-sm font-medium text-gray-700 mt-8">
                        Password<span class="text-red-500"></span>
                    </label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi"
                        class="block w-full h-14 border border-gray-300 rounded-full px-4 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required />

                    <!-- Terms and Conditions -->
                    <div class="flex items-center gap-x-3 mt-4 md:mt-8">
                        <input id="terms" name="terms" type="checkbox" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" required>
                        <label for="terms" class="block text-sm font-medium leading-6 text-gray-900">
                            Saya Menyutujui <a href="#" class="text-blue-500 font-bold">Syarat dan Ketentuan</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="mt-4 px-5 py-3 bg-main text-white rounded-full shadow-lg text-center uppercase w-[100%] md:w-[60%] mx-auto md:mx-0 tracking-wider sm:mt-4 sm:text-base">
                        Daftar akun Perpus
                    </button>
                </form>
            </div>

            <!-- Kolom Gambar -->
            <div class="flex-1 flex items-center justify-center">
                <img src="../../../public//assets//Frame 28 (1).png" alt="Gambar Login" class="hidden md:block w-full h-auto object-contain">
            </div>
        </div>
    </div>
</body>

</html>