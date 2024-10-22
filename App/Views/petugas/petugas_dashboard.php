<?php

session_start();
if (!isset($_SESSION['roles']) || $_SESSION['roles'] != 'petugas') {
    header("Location: ../Views/auth/login.php"); // Redirect ke halaman login jika bukan admin
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to Petugas Dashboard</h1>
    <!-- Konten dashboard admin -->
</body>
</html>
