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

                    <!-- Nav Menu Start -->
                    <div class="flex flex-col gap-4 px-4 py-8">
                        <?php
                        // Menentukan halaman aktif
                        $current_page = basename($_SERVER['PHP_SELF']);
                        ?>
                        <a
                            href="./dashboard-1.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'dashboard-1.php' ? 'bg-main' : ''; ?>">
                            <svg width="18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white <?php echo $current_page == 'dashboard-1.php' ? 'fill-white' : 'text-slate-500 group-hover:fill-white'; ?>" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8275 8.4375H11.7975C10.29 8.4375 9.5625 7.7025 9.5625 6.2025V3.1725C9.5625 1.665 10.2975 0.9375 11.7975 0.9375H14.8275C16.335 0.9375 17.0625 1.6725 17.0625 3.1725V6.2025C17.0625 7.7025 16.3275 8.4375 14.8275 8.4375ZM11.7975 2.0625C10.9125 2.0625 10.6875 2.2875 10.6875 3.1725V6.2025C10.6875 7.0875 10.9125 7.3125 11.7975 7.3125H14.8275C15.7125 7.3125 15.9375 7.0875 15.9375 6.2025V3.1725C15.9375 2.2875 15.7125 2.0625 14.8275 2.0625H11.7975Z" />
                                <path d="M6.2025 8.4375H3.1725C1.665 8.4375 0.9375 7.77 0.9375 6.39V2.985C0.9375 1.605 1.6725 0.9375 3.1725 0.9375H6.2025C7.71 0.9375 8.4375 1.605 8.4375 2.985V6.3825C8.4375 7.77 7.7025 8.4375 6.2025 8.4375ZM3.1725 2.0625C2.1675 2.0625 2.0625 2.3475 2.0625 2.985V6.3825C2.0625 7.0275 2.1675 7.305 3.1725 7.305H6.2025C7.2075 7.305 7.3125 7.02 7.3125 6.3825V2.985C7.3125 2.34 7.2075 2.0625 6.2025 2.0625H3.1725Z" />
                                <path d="M6.2025 17.0625H3.1725C1.665 17.0625 0.9375 16.3275 0.9375 14.8275V11.7975C0.9375 10.29 1.6725 9.5625 3.1725 9.5625H6.2025C7.71 9.5625 8.4375 10.2975 8.4375 11.7975V14.8275C8.4375 16.3275 7.7025 17.0625 6.2025 17.0625ZM3.1725 10.6875C2.2875 10.6875 2.0625 10.9125 2.0625 11.7975V14.8275C2.0625 15.7125 2.2875 15.9375 3.1725 15.9375H6.2025C7.0875 15.9375 7.3125 15.7125 7.3125 14.8275V11.7975C7.3125 10.9125 7.0875 10.6875 6.2025 10.6875H3.1725Z" />
                                <path d="M15.75 12.1875H11.25C10.9425 12.1875 10.6875 11.9325 10.6875 11.625C10.6875 11.3175 10.9425 11.0625 11.25 11.0625H15.75C16.0575 11.0625 16.3125 11.3175 16.3125 11.625C16.3125 11.9325 16.0575 12.1875 15.75 12.1875Z" />
                                <path d="M15.75 15.1875H11.25C10.9425 15.1875 10.6875 14.9325 10.6875 14.625C10.6875 14.3175 10.9425 14.0625 11.25 14.0625H15.75C16.0575 14.0625 16.3125 14.3175 16.3125 14.625C16.3125 14.9325 16.0575 15.1875 15.75 15.1875Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'dashboard-1.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Dashboard
                            </p>
                        </a>

                        <a
                            href="./DataBuku.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'dataBuku.php' ? 'bg-main' : ''; ?>"">
                        <svg width=" 18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.56C8.775 16.56 8.55 16.5075 8.3625 16.4025C6.96 15.6375 4.4925 14.8275 2.9475 14.625L2.73 14.595C1.7475 14.475 0.9375 13.5525 0.9375 12.555V3.49501C0.9375 2.90251 1.17 2.36251 1.5975 1.97251C2.025 1.58251 2.58 1.39501 3.165 1.44751C4.815 1.57501 7.305 2.40001 8.715 3.28501L8.895 3.39001C8.9475 3.42001 9.06 3.42001 9.105 3.39751L9.225 3.32251C10.635 2.43751 13.125 1.59751 14.7825 1.45501C14.7975 1.45501 14.8575 1.45501 14.8725 1.45501C15.42 1.40251 15.9825 1.59751 16.4025 1.98751C16.83 2.37751 17.0625 2.91751 17.0625 3.51001V12.5625C17.0625 13.5675 16.2525 14.4825 15.2625 14.6025L15.015 14.6325C13.47 14.835 10.995 15.6525 9.6225 16.41C9.4425 16.515 9.225 16.56 9 16.56ZM2.985 2.56501C2.745 2.56501 2.5275 2.64751 2.355 2.80501C2.1675 2.97751 2.0625 3.22501 2.0625 3.49501V12.555C2.0625 12.9975 2.445 13.425 2.8725 13.485L3.0975 13.515C4.785 13.74 7.3725 14.5875 8.8725 15.405C8.94 15.435 9.0375 15.4425 9.075 15.4275C10.575 14.595 13.1775 13.74 14.8725 13.515L15.1275 13.485C15.555 13.4325 15.9375 12.9975 15.9375 12.555V3.50251C15.9375 3.22501 15.8325 2.98501 15.645 2.80501C15.45 2.63251 15.2025 2.55001 14.925 2.56501C14.91 2.56501 14.85 2.56501 14.835 2.56501C13.4025 2.69251 11.0925 3.46501 9.8325 4.25251L9.7125 4.33501C9.3 4.59001 8.715 4.59001 8.3175 4.34251L8.1375 4.23751C6.855 3.45001 4.545 2.68501 3.075 2.56501C3.045 2.56501 3.015 2.56501 2.985 2.56501Z" />
                            <path d="M9 15.9301C8.6925 15.9301 8.4375 15.6751 8.4375 15.3676V4.11755C8.4375 3.81005 8.6925 3.55505 9 3.55505C9.3075 3.55505 9.5625 3.81005 9.5625 4.11755V15.3676C9.5625 15.6826 9.3075 15.9301 9 15.9301Z" />
                            <path d="M5.8125 6.93005H4.125C3.8175 6.93005 3.5625 6.67505 3.5625 6.36755C3.5625 6.06005 3.8175 5.80505 4.125 5.80505H5.8125C6.12 5.80505 6.375 6.06005 6.375 6.36755C6.375 6.67505 6.12 6.93005 5.8125 6.93005Z" />
                            <path d="M6.375 9.18005H4.125C3.8175 9.18005 3.5625 8.92505 3.5625 8.61755C3.5625 8.31005 3.8175 8.05505 4.125 8.05505H6.375C6.6825 8.05505 6.9375 8.31005 6.9375 8.61755C6.9375 8.92505 6.6825 9.18005 6.375 9.18005Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'dataBuku.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Data Buku
                            </p>
                        </a>

                        <a
                            href="./dataKunjungan.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'dataKunjungan.php' ? 'bg-main' : ''; ?>"">
                        <svg width=" 18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9.1499H11.25" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6 12.1499H9.285" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.5 4.5H10.5C12 4.5 12 3.75 12 3C12 1.5 11.25 1.5 10.5 1.5H7.5C6.75 1.5 6 1.5 6 3C6 4.5 6.75 4.5 7.5 4.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 3.01501C14.4975 3.15001 15.75 4.07251 15.75 7.50001V12C15.75 15 15 16.5 11.25 16.5H6.75C3 16.5 2.25 15 2.25 12V7.50001C2.25 4.08001 3.5025 3.15001 6 3.01501" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'dataKunjungan.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Data Kunjungan
                            </p>
                        </a>

                        <a
                            href="./User/account.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'account.php' ? 'bg-main' : ''; ?>"">
                        <svg class=" fill-current group-hover:fill-white w-6 h-6" width="24" height="24" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.00012 17.0625C8.49762 17.0625 7.98762 16.9349 7.53762 16.6724L3.08262 14.0999C2.18262 13.5749 1.62012 12.6074 1.62012 11.5649V6.43496C1.62012 5.39246 2.18262 4.42496 3.08262 3.89996L7.53762 1.32747C8.43762 0.802466 9.55512 0.802466 10.4626 1.32747L14.9176 3.89996C15.8176 4.42496 16.3801 5.39246 16.3801 6.43496V11.5649C16.3801 12.6074 15.8176 13.5749 14.9176 14.0999L10.4626 16.6724C10.0126 16.9349 9.50262 17.0625 9.00012 17.0625ZM9.00012 2.06245C8.69262 2.06245 8.37762 2.14496 8.10012 2.30246L3.64512 4.87495C3.09012 5.19745 2.74512 5.78996 2.74512 6.43496V11.5649C2.74512 12.2024 3.09012 12.8025 3.64512 13.125L8.10012 15.6974C8.65512 16.0199 9.34512 16.0199 9.90012 15.6974L14.3551 13.125C14.9101 12.8025 15.2551 12.2099 15.2551 11.5649V6.43496C15.2551 5.79746 14.9101 5.19745 14.3551 4.87495L9.90012 2.30246C9.62262 2.14496 9.30762 2.06245 9.00012 2.06245Z" />
                            <path d="M8.99994 8.81261C7.72494 8.81261 6.68994 7.77759 6.68994 6.50259C6.68994 5.22759 7.72494 4.19263 8.99994 4.19263C10.2749 4.19263 11.3099 5.22759 11.3099 6.50259C11.3099 7.77759 10.2749 8.81261 8.99994 8.81261ZM8.99994 5.31763C8.34744 5.31763 7.81494 5.85009 7.81494 6.50259C7.81494 7.15509 8.34744 7.68761 8.99994 7.68761C9.65244 7.68761 10.1849 7.15509 10.1849 6.50259C10.1849 5.85009 9.65244 5.31763 8.99994 5.31763Z" />
                            <path d="M12 13.0575C11.6925 13.0575 11.4375 12.8025 11.4375 12.495C11.4375 11.46 10.3425 10.6125 9 10.6125C7.6575 10.6125 6.5625 11.46 6.5625 12.495C6.5625 12.8025 6.3075 13.0575 6 13.0575C5.6925 13.0575 5.4375 12.8025 5.4375 12.495C5.4375 10.8375 7.035 9.48755 9 9.48755C10.965 9.48755 12.5625 10.8375 12.5625 12.495C12.5625 12.8025 12.3075 13.0575 12 13.0575Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'account.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Profile
                            </p>
                        </a>

                        <a
                            href="./auth/logout.php"
                            class="flex items-center gap-2 px-4 py-2 group hover:bg-main rounded-lg transition-all duration-300 <?php echo $current_page == 'logout.php' ? 'bg-main' : ''; ?>"">
                        <svg width=" 18" height="18" viewBox="0 0 18 18" class="fill-current group-hover:fill-white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.43 16.7025H11.3325C8.00251 16.7025 6.39751 15.39 6.12001 12.45C6.09001 12.1425 6.31501 11.865 6.63001 11.835C6.93001 11.805 7.21501 12.0375 7.24501 12.345C7.46251 14.7 8.57251 15.5775 11.34 15.5775H11.4375C14.49 15.5775 15.57 14.4975 15.57 11.445V6.55499C15.57 3.50249 14.49 2.42249 11.4375 2.42249H11.34C8.55751 2.42249 7.44751 3.31499 7.24501 5.71499C7.20751 6.02249 6.94501 6.25499 6.63001 6.22499C6.31501 6.20249 6.09001 5.92499 6.11251 5.61749C6.36751 2.63249 7.98001 1.29749 11.3325 1.29749H11.43C15.1125 1.29749 16.6875 2.87249 16.6875 6.55499V11.445C16.6875 15.1275 15.1125 16.7025 11.43 16.7025Z" />
                            <path d="M11.25 9.5625H2.71503C2.40753 9.5625 2.15253 9.3075 2.15253 9C2.15253 8.6925 2.40753 8.4375 2.71503 8.4375H11.25C11.5575 8.4375 11.8125 8.6925 11.8125 9C11.8125 9.3075 11.5575 9.5625 11.25 9.5625Z" />
                            <path d="M4.38752 12.075C4.24502 12.075 4.10252 12.0225 3.99002 11.91L1.47752 9.39751C1.26002 9.18001 1.26002 8.82001 1.47752 8.60251L3.99002 6.09C4.20752 5.8725 4.56752 5.8725 4.78502 6.09C5.00252 6.3075 5.00252 6.66751 4.78502 6.88501L2.67002 9.00001L4.78502 11.115C5.00252 11.3325 5.00252 11.6925 4.78502 11.91C4.68002 12.0225 4.53002 12.075 4.38752 12.075Z" />
                            </svg>

                            <p class="text-lg text-slate-500 group-hover:text-white <?php echo $current_page == 'logout.php' ? 'text-white' : 'text-slate-500'; ?>">
                                Logout
                            </p>
                        </a>
                    </div>
                    <!-- Nav Menu End -->
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
                                                onclick="togglePasswordVisibility('oldPasswordInput', 'showOldPasswordCheckbox')" />

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
                                                onclick="togglePasswordVisibility('newPasswordInput', 'showNewPasswordCheckbox')" />
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
                                                onclick="togglePasswordVisibility('confirmPasswordInput', 'showConfirmPasswordCheckbox')" />
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
                passwordInput.type = 'text'; // Tampilkan password
            } else {
                passwordInput.type = 'password'; // Sembunyikan password
            }
        }
    </script>



</body>

</html>