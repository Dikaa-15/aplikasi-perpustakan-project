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

    // Tambahkan kunci enkripsi jika menggunakan enkripsi
    $encryption_key = getenv('ENCRYPTION_KEY'); // Sesuaikan cara mendapatkan kunci enkripsi

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : '';
        $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
        $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

        // Validasi password lama
        if ($userModel->verifyPassword($id_user, $old_password, $encryption_key)) {
            // Cek apakah password baru sesuai dengan konfirmasi
            if ($new_password === $confirm_password) {
                // Update password di database
                if ($userModel->updatePassword($id_user, $new_password, $encryption_key)) {
                    $success_message = "Password berhasil diperbarui.";
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



    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profil Akun</title>
        <link rel="stylesheet" href="style.css"> <!-- Pastikan file CSS sesuai -->
    </head>

    <body>
        <div class="profile-container">
            <h2>Profil Pengguna</h2>

            <?php if (!empty($success_message)) { ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php } ?>

            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>


            <!-- Menampilkan Foto Profil jika ada -->
            <div class="profil_user">
                <?php if (!empty($user['profil_user'])) { ?>
                    <img src="<?php echo htmlspecialchars($user['profil_user']); ?>" alt="Foto Profil" width="150" height="150">
                <?php } else { ?>
                    <img src="../../../public/profile/default.png" alt="Foto Profil Default" width="150" height="150">
                <?php } ?>
            </div>

            <!-- Menampilkan data pengguna -->
            <div class="profile-info">
                <p><strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
                <p><strong>NIS:</strong> <?php echo htmlspecialchars($user['nis']); ?></p>
                <p><strong>Kelas:</strong> <?php echo htmlspecialchars($user['kelas']); ?></p>
                <p><strong>No WhatsApp:</strong> <?php echo htmlspecialchars($user['no_whatsapp']); ?></p>
                <p><strong>No Kartu Perpustakaan:</strong> <?php echo htmlspecialchars($user['no_kartu']); ?></p>
            </div>

            <!-- Form untuk update NISN dan password -->
            <form method="POST" action="account.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nisn">NISN:</label>
                    <input type="text" id="nisn" name="nisn" value="<?php echo htmlspecialchars($nisn); ?>">
                </div>

                <div class="form-group">
                    <label for="profil_user">Foto Profil:</label>
                    <input type="file" id="profil_user" name="profil_user" accept="image/*">
                </div>

                <!-- Input Password Lama -->
                <div class="form-group">
                    <label for="old_password">Password Lama:</label>
                    <input type="password" id="old_password" name="password" required>
                </div>

                <!-- Input Password Baru -->
                <div class="form-group">
                    <label for="new_password">Password Baru:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>

                <!-- Input Konfirmasi Password Baru -->
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <?php if (!empty($success_message)) { ?>
                    <div class="success-message"><?php echo $success_message; ?></div>
                <?php } ?>

                <?php if (!empty($error_message)) { ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php } ?>


                <button type="submit" class="btn">Update Data</button>
            </form>
        </div>
    </body>

    </html>