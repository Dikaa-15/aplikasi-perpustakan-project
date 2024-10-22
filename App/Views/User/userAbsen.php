<?php

session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['no_kartu'])) {
    header("Location: ../auth/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Absensi Perpustakaan</title>
    <style>
        /* Styling for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #6200ee;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3700b3;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Absensi Perpustakaan</h2>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message"><?= htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- Form for Absensi -->
        <form action="prosesAbsen.php" method="POST">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" >

            <label for="kelas">Kelas</label>
            <input type="text" name="kelas" id="kelas" placeholder="Masukkan kelas anda" required>

            <label for="no_kartu">No. Kartu</label>
            <input type="text" name="no_kartu" id="no_kartu" >

            <label>
                <input type="checkbox" name="agreement" required> Saya telah membaca dan menyetujui syarat dan ketentuan.
            </label>

            <input type="submit" value="Absen">
        </form>
    </div>

</body>
</html>
