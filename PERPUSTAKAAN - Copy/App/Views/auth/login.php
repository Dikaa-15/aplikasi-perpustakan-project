<?php
session_start();

// Sertakan file koneksi ke database dan model user
include_once '../../core/Database.php'; 
include_once '../../Models/user.php'; 

// Membuat instance koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Cek apakah pengguna sudah login
if (isset($_SESSION['no_kartu'])) {
    // Jika sudah login, redirect ke viewBuku.php
    header("Location: ../viewBuku.php");
    exit();
}

// Tangkap input dari form hanya jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($db);
    $user->no_kartu = $_POST['no_kartu'] ?? null;
    $user->password = $_POST['password'] ?? null;

    // Debugging input
    error_log('Submitted no_kartu: ' . $user->no_kartu);
    error_log('Submitted password: ' . $user->password);

    // Periksa login
    if ($user->login()) {
        // Jika berhasil login, simpan informasi di sesi
        $_SESSION['roles'] = $user->roles;
        $_SESSION['no_kartu'] = $user->no_kartu;
        $_SESSION['id_user'] = $user->id_user; // Tambahkan id_user ke sesi

        // Debugging session
        error_log('Session after login: ' . print_r($_SESSION, true));

        // Redirect ke halaman viewBuku.php setelah login berhasil
        header("Location: ../viewBuku.php");
        exit();
    } else {
        // Tampilkan pesan error jika login gagal
        echo "Login gagal. Periksa no kartu dan password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<form action="" method="POST">
    <label for="no_kartu">No Kartu</label>
    <input type="text" name="no_kartu" id="no_kartu" required>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>

    <input type="submit" value="Login">
</form>

</body>
</html>


