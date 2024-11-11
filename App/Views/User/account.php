<?php
session_start();

require_once '../../Models/user.php'; // Pastikan path ini sesuai dengan struktur folder Anda
require_once '../../core/Database.php'; // Pastikan path ini sesuai dengan struktur folder Anda

// Inisialisasi objek database
$database = new Database();
$db = $database->getConnection();

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$userModel = new User($db); // Pastikan Anda meneruskan koneksi database ke User

// Mendapatkan data user
$user = $userModel->getUserData($id_user);

// Periksa apakah 'nisn' ada, jika tidak ada, beri nilai kosong
$nisn = isset($user['nisn']) ? $user['nisn'] : '';

// Pesan sukses atau error
$success_message = "";
$error_message = "";

// Proses upload foto profil
if (isset($_FILES['profil_user']) && $_FILES['profil_user']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = '../../../public/profile/upload/';
    $fileTmpPath = $_FILES['profil_user']['tmp_name'];
    $fileName = uniqid() . '-' . $_FILES['profil_user']['name'];
    $filePath = $uploadDir . $fileName;

    // Cek dan buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Pindahkan file ke direktori tujuan
    if (move_uploaded_file($fileTmpPath, $filePath)) {
        // Simpan path foto ke database
        if ($userModel->updateProfilePhoto($id_user, $filePath)) {
            $success_message .= " Foto profil berhasil diperbarui.";
        } else {
            $error_message = "Gagal memperbarui foto profil di database.";
        }
    } else {
        $error_message = "Gagal mengunggah foto profil. Silakan coba lagi.";
    }
} else if (isset($_FILES['profil_user']) && $_FILES['profil_user']['error'] != UPLOAD_ERR_NO_FILE) {
    $error_message = "Terjadi kesalahan saat upload file.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $kelas = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
    $no_whatsapp = isset($_POST['no_whatsapp']) ? trim($_POST['no_whatsapp']) : '';
    $no_kartu = isset($_POST['no_kartu']) ? trim($_POST['no_kartu']) : '';

    $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Update data pribadi
    if ($userModel->updateUserData($id_user, $nama_lengkap, $kelas, $no_whatsapp, $no_kartu)) {
        $success_message = "Data pribadi berhasil diperbarui.";
    } else {
        $error_message = "Gagal memperbarui data pribadi. Silakan coba lagi.";
    }

    // Update password jika field password diisi
    if (!empty($old_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($userModel->verifyPassword($id_user, $old_password)) {
            if ($new_password === $confirm_password) {
                if ($userModel->updatePassword($id_user, $new_password)) {
                    $success_message .= " Password berhasil diperbarui.";
                } else {
                    $error_message = "Gagal memperbarui password. Silakan coba lagi.";
                }
            } else {
                $error_message = "Password baru dan konfirmasi password tidak sesuai.";
            }
        } else {
            $error_message = "Password lama salah. Silakan coba lagi.";
        }
    }
}

$new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile User</title>
    <link rel="stylesheet" href="../../../output.css" />
    <link
        rel="shortcut icon"
        href="../public/image/logo/logo 1.png"
        type="image/x-icon" />
    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
</head>

<body>
    <div class="profile-container">


        <?php if (!empty($success_message)) { ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php } ?>

        <?php if (!empty($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Menampilkan Foto Profil jika ada -->
        <!-- <div class="profil_user">
            <?php if (!empty($user['profil_user'])) { ?>
                <img src="<?php echo htmlspecialchars($user['profil_user']); ?>" alt="Foto Profil" width="150" height="150">
            <?php } else { ?>
                <img src="../../../public/profile/default.png" alt="Foto Profil Default" width="150" height="150">
            <?php } ?>
        </div> -->

        <!-- Menampilkan data pengguna -->
        <!-- <div class="profile-info">
            <p><strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
            <p><strong>NIS:</strong> <?php echo htmlspecialchars($user['nis']); ?></p>
            <p><strong>Kelas:</strong> <?php echo htmlspecialchars($user['kelas']); ?></p>
            <p><strong>No WhatsApp:</strong> <?php echo htmlspecialchars($user['no_whatsapp']); ?></p>
            <p><strong>No Kartu Perpustakaan:</strong> <?php echo htmlspecialchars($user['no_kartu']); ?></p>
        </div> -->

        <!-- Form untuk update NISN dan password -->

        <div>
            <div x-data="{ sidebarOpen: false }" class="flex h-screen">
                <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
                    class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden">
                </div>
                <!-- sidebar -->
                <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
                    class="fixed left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-pinkSec lg:translate-x-0 lg:static lg:inset-0 h-screen">
                    <div class="flex items-center justify-center pt-8 mb-8">
                        <a href="../Landing-page.php">
                            <img src="../../../public//logo 1.png" alt="" />
                        </a>
                    </div>

                    <!-- Menu -->
                    <div class="flex flex-col gap-4 px-4 py-8">
                        <a href="../dataPeminjaman.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../../public//svg//element-equal.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Dashboard</p>
                        </a>
                        <a href="daftarBuku.html"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../../public//svg//book.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Data Buku</p>
                        </a>
                        <a href="../dataKunjungan.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../../public//svg//clipboard-text.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Data Kunjungan</p>
                        </a>
                        <a href="../Views/User/account.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../../public//svg//user-octagon.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Profile</p>
                        </a>
                        <a href="./auth/logout.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300">
                            <img src="../../../public//svg//logout.svg" alt="" class="w-6 h-6 object-cover" />
                            <p class="text-lg text-slate-500 group-hover:text-white">Logout</p>
                        </a>
                    </div>
                </div>

                <!-- main content start -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto px-4 md:px-8 pb-10">
                    <div class="flex-1 pt-1 md:pt-6 mb-8">
                        <!-- header start -->
                        <div class="flex justify-center mb-4">
                            <h2 class="font-bold text-3xl">Profile</h2>
                        </div>
                        <!-- header end -->

                        <!-- content foto start -->
                        <div class="w-full h-[197px] bg-pinkSec rounded-md relative pt-4 pr-6">
                            <div class="flex justify-end w-full">
                                <a href="">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                            </div>

                            <div class="flex justify-center">
                                <!-- <div class="w-[123px] h-[123px] rounded-full absolute -bottom-1/4">
                                    <img src="<?php echo htmlspecialchars($user['profil_user']); ?>" alt="Foto Profil" class="w-full h-full object-cover">
                                    <label for="profil_user" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white cursor-pointer opacity-0 hover:opacity-100 transition-opacity">
                                        Ubah Foto
                                    </label>
                                    <input type="file" name="profil_user" id="profil_user" accept="image/*" class="hidden hover:bg-black opacity-20" onchange="previewProfileImage(event)">
                                </div> -->
                            </div>
                        </div>
                        <!-- content foto end -->

                    </div>
                    <div class="flex justify-end mb-10">
                        <button type="submit" class="px-4 py-2 bg-primaryBlue text-white rounded-md">
                            Simpan
                        </button>
                    </div>

                    <!-- Content form start -->
                    <div class="grid md:grid-cols-2 md:gap-3 lg:gap-4 xl:gap-6">
                        <!-- form 1 start -->
                        <div class="w-full px-6 lg:px-10 xl:px-14 py-8 bg-inputColors rounded-lg mb-4 md:mb-0">
                            <!-- Header Start -->
                            <div class="">
                                <h2 class="font-bold text-[20px] mb-8">Detail Info</h2>
                            </div>
                            <!-- Header End -->


                            <form action="" method="post" enctype="multipart/form-data">

                                <div class="w-[123px] h-[123px] rounded-full overflow-hidden relative ">
                                    <img id="profileImage" src="<?php echo htmlspecialchars($user['profil_user'] ?? '../../../public/profile/default.png'); ?>" alt="Foto Profil" class="w-full h-full object-cover">
                                    <label for="profil_user" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white cursor-pointer opacity-0 hover:opacity-100 transition-opacity">
                                        Ubah Foto
                                    </label>
                                    <input type="file" name="profil_user" id="profil_user" accept="image/*" class="hidden hover:bg-black opacity-20" onchange="previewProfileImage(event)">
                                </div>

                                <div class="flex flex-col gap-3 mb-6 md:mb-8">
                                    <label
                                        for="nama_lengkap"
                                        class="font-semibold text-[1rem] md:text-[1.2rem]">Nama Lengkap</label>
                                    <input
                                        type="text"
                                        name="nama_lengkap"
                                        id="nama_lengkap"
                                        placeholder="Masukkan nama anda"
                                        value="<?php echo htmlspecialchars($user['nama_lengkap'] ?? ''); ?>"
                                        class="w-full block flex-1 bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />
                                </div>

                                <div class="flex flex-col gap-3 mb-6 md:mb-8">
                                    <label
                                        for="kelas"
                                        class="font-semibold text-[1rem] md:text-[1.2rem]">Kelas</label>
                                    <input
                                        type="text"
                                        name="kelas"
                                        id="kelas"
                                        placeholder=""
                                        value="<?php echo htmlspecialchars($user['kelas'] ?? ''); ?>"
                                        class="w-full block flex-1 bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />
                                </div>

                                <div class="flex flex-col gap-3 mb-6 md:mb-8">
                                    <label
                                        for="nomor_whatsapp"
                                        class="font-semibold text-[1rem] md:text-[1.2rem]">Nomor Whatsapp</label>
                                    <input
                                        type="text"
                                        name="no_whatsapp"
                                        id="no_whatsapp"
                                        placeholder=""
                                        value="<?php echo htmlspecialchars($user['no_whatsapp']); ?>"
                                        class="w-full block flex-1 bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />
                                </div>

                                <div class="flex flex-col gap-3 mb-6 md:mb-8">
                                    <label
                                        for="nomor_kartu_perpus"
                                        class="font-semibold text-[1rem] md:text-[1.2rem]">Nomor Kartu Perpus</label>
                                    <input
                                        type="text"
                                        name="no_kartu"
                                        id="no_kartu"
                                        placeholder=""
                                        value="<?php echo htmlspecialchars($user['no_kartu']); ?>"
                                        class="w-full block flex-1 bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />
                                </div>

                                <div class="detail-account justify-start">


                                    <div class="flex flex-col gap-3 mb-4 md:mb-4">
                                        <label
                                            for="nisn"
                                            class="font-semibold text-[1rem] md:text-[1.2rem]">NISN</label>
                                        <input
                                            type="text"
                                            name="nisn"
                                            id="nisn"
                                            value="<?php echo htmlspecialchars($user['nisn'] ?? ''); ?>"
                                            class="w-full block flex-1 bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />
                                    </div>

                                    <div class="flex flex-col justify-start gap-3 mb-4 md:mb-4">
                                        <label
                                            for="password"
                                            class="font-semibold text-[1rem] md:text-[1.2rem]">Password Lama</label>
                                        <input
                                            type="password"
                                            name="old_password"
                                            id="oldPasswordInput"
                                            placeholder=""

                                            class="w-full block bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />

                                        <div class="flex justify-start gap-2">
                                            <input
                                                type="checkbox"
                                                name="password"
                                                id="showOldPasswordCheckbox"
                                                class="" 
                                                onclick="togglePasswordVisibility('oldPasswordInput', 'showOldPasswordCheckbox')"/>
                                            
                                            <p class="font-normal text-sm text-gray-400">
                                                Tampilkan Password
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col justify-start gap-3 mb-4 md:mb-4">
                                        <label
                                            for="new_password" class="font-semibold text-[1rem] md:text-[1.2rem]">Password Baru</label>
                                        <input
                                            type="password"
                                            name="password"
                                            id="newPasswordInput"
                                            placeholder=""
                                            <?php echo htmlspecialchars($user['new_password'] ?? ''); ?>
                                            class="w-full block bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />

                                        <div class="flex justify-start gap-2">
                                            <input
                                                type="checkbox"
                                                name="password"
                                                id="showNewPasswordCheckbox"
                                                class="" 
                                                onclick="togglePasswordVisibility('newPasswordInput', 'showNewPasswordCheckbox')"/>
                                            <p class="font-normal text-sm text-gray-400">
                                                Tampilkan Password
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col justify-start gap-3 mb-4 md:mb-4">
                                        <label
                                            for="Konfirmasi"
                                            class="font-semibold text-[1rem] md:text-[1.2rem]">Konfirmasi Password Baru</label>
                                        <input
                                            type="password"
                                            name="password"
                                            id="confirmPasswordInput"
                                            placeholder=""
                                            value="<?php echo htmlspecialchars($user['confirm_password'] ?? ''); ?>"
                                            class="w-full block bg-white px-4 md:px-6 py-3 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 text-sm md:text-[16px] sm:leading-6 rounded" />

                                        <div class="flex justify-start gap-2">
                                            <input
                                                type="checkbox"
                                                name="password"
                                                id="showConfirmPasswordCheckbox"
                                                class="" 
                                                onclick="togglePasswordVisibility('confirmPasswordInput', 'showConfirmPasswordCheckbox')"/>
                                            <p class="font-normal text-sm text-gray-400">
                                                Tampilkan Password
                                            </p>
                                        </div>
                                    </div>
                                    <button class="px-4 py-2 bg-primaryBlue text-white rounded-md">Simpan</button>
                                </div>
                            </form>


                        </div>
                        <!-- Form 2 Start -->

                        <!-- Form 2 End -->
                    </div>
                    <!-- Content form end -->
                </main>

            </div>
        </div>



    </div>

    <script>
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const showButton = passwordInput.nextElementSibling;

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                showButton.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                showButton.textContent = "Show";
            }
        }
    </script>

    <script>
        function previewProfileImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profileImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

<script>
   function togglePasswordVisibility(inputId, checkboxId) {
    var passwordInput = document.getElementById(inputId);
    var checkbox = document.getElementById(checkboxId);

    if (checkbox.checked) {
        passwordInput.type = 'text';  // Tampilkan password
    } else {
        passwordInput.type = 'password';  // Sembunyikan password
    }
}

</script>



</body>

</html>