<?php
session_start();
require_once '../../core/Database.php';
require_once '../../Models/user.php';

$database = new Database();
$db = $database->getConnection();

// Cek apakah pengguna sudah login
if (isset($_SESSION['no_kartu'])) {
    header("Location: ./Landing-page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($db);
    $user->no_kartu = $_POST['no_kartu'] ?? null;
    $user->password = $_POST['password'] ?? null;

    if ($user->login()) {
        // Redirect sesuai dengan roles
        switch ($_SESSION['roles']) {
            case 'admin':
                header("Location: ../admin/admin_dashboard.php");
                break;
            case 'user':
                header("Location: ../User/userAbsen.php");
                break;
            case 'petugas':
                header("Location: ../petugas/petugas_dashboard.php");
                break;
            default:
                echo "Role tidak dikenali.";
                break;
        }
        exit();
    } else {
        $_SESSION['error_message'] = "Login gagal. Periksa nomor kartu dan password.";
        header("Location: login.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Perpus</title>
  <link rel="stylesheet" href="output.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="font-fontMain scroll-smooth">
  <div class="container mx-auto flex flex-col md:flex-row px-5 py-4 md:px-16 md:py-8 gap-8">
    <div class="grid grid-cols-2 items-center pt-4">

      <div class="min-h-screen w-full col-span-1 ">
        <div class="py-2 px-6 w-full mx-auto">
          <img src="../../../public//logo 1.png" alt="Logo" />
        </div>

        <div class="mt-20 mx-4">
          <div>
            <h1 class="font-bold text-3xl mb-4">Selamat Datang</h1>
            <span>Belum punya akun perpus?
              <a href="./register.php" class="text-main">Daftar disini</a>
            </span>

            <!-- Form Login -->
            <form action="login.php" method="POST">
              <label for="no_kartu" class="block text-sm font-medium text-gray-700 mt-8">No Kartu</label>
              <input
                type="text"
                id="no_kartu"
                name="no_kartu"
                placeholder="Masukan nomor kartu perpus kamu.."
                class="block w-full h-14 border border-gray-300 rounded-full text-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                required
              />

              <label for="password" class="block text-sm font-medium text-gray-700 mt-8">Password</label>
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukan password kamu.."
                class="block w-full h-14 border border-gray-300 rounded-full text-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                required
              />

              <div class="flex items-center justify-between mt-10">
                <div class="flex items-center">
                  <input id="remember" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                  <label for="remember" class="ml-2 text-sm text-gray-700">Ingat Akun Saya</label>
                </div>
                <a href="#" class="text-sm hover:underline">Lupa Password?</a>
              </div>

              <button type="submit" class="mt-4 px-5 py-3 bg-main bg-blue-700 text-white rounded-full shadow-lg text-center uppercase w-full mx-auto tracking-wider sm:mt-4 sm:text-base">
                Masuk Perpus Digital
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="flex-1 flex items-center justify-center">
        <img src="../../../public/Frame 28 (1).png" alt="Gambar Login" class="hidden md:block w-full h-auto object-contain">
      </div>
    </div>
  </div>
</body>
</html>

