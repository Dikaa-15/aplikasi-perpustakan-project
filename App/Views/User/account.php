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

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan trim() hanya dipanggil jika data POST ada
    $new_nisn = isset($_POST['nisn']) ? trim($_POST['nisn']) : '';
    $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Validasi NISN harus diisi
    if (empty($new_nisn)) {
        $error_message = "NISN tidak boleh kosong.";
    } else {
        // Update NISN
        if ($userModel->updateNISN($id_user, $new_nisn)) {
            $success_message = "NISN berhasil diperbarui.";
        } else {
            $error_message = "Gagal memperbarui NISN.";
        }
    }

    // Update password jika diisi
    if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)) {
        // Cek apakah password lama benar
        if (password_verify($old_password, $user['password'])) { // Pastikan password disimpan dalam bentuk hashed
            // Pastikan password baru cocok dengan konfirmasi password
            if ($new_password !== $confirm_password) {
                $error_message = "Password baru dan konfirmasi password tidak sesuai.";
            } else {
                if ($userModel->updatePassword($id_user, $new_password)) {
                    $success_message .= " Password berhasil diperbarui.";
                } else {
                    $error_message = "Gagal memperbarui password. Silakan coba lagi.";
                }
            }
        } else {
            $error_message = "Password lama salah. Silakan coba lagi.";
        }
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
        
        <!-- Menampilkan data pengguna -->
        <div class="profile-info">
            <p><strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
            <p><strong>NIS:</strong> <?php echo htmlspecialchars($user['nis']); ?></p>
            <p><strong>Kelas:</strong> <?php echo htmlspecialchars($user['kelas']); ?></p>
            <p><strong>No WhatsApp:</strong> <?php echo htmlspecialchars($user['no_whatsapp']); ?></p>
            <p><strong>No Kartu Perpustakaan:</strong> <?php echo htmlspecialchars($user['no_kartu']); ?></p>
        </div>

        <!-- Form untuk update NISN dan password -->
        <form method="POST" action="account.php">
            <div class="form-group">
                <label for="nisn">NISN:</label>
                <input type="text" id="nisn" name="nisn" value="<?php echo htmlspecialchars($nisn); ?>">
                </div>

            <div class="form-group">
                <label for="old_password">Password Lama:</label>
                <input type="password" id="old_password" name="old_password">
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru:</label>
                <input type="password" id="new_password" name="new_password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <button type="submit" class="btn">Update Data</button>
        </form>
    </div>
</body>
</html>
