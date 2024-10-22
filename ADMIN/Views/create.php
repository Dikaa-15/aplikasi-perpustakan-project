<?php
require_once '../Controller/UserController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userController = new UserController();
    $result = $userController->createUser($_POST);
    echo $result;

      if ($result) { // Jika berhasil
        header("Location: User.php"); // Redirect ke halaman view.php
        exit(); // Pastikan untuk keluar setelah redirect
    } else {
        echo "Gagal menambahkan user. Silakan coba lagi.";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-700">Create User</h1>
        <form action="create.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-600">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">NIS</label>
                <input type="text" name="nis" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">NISN</label>
                <input type="text" name="nisn" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">Kelas</label>
                <input type="text" name="kelas" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">No Whatsapp</label>
                <input type="text" name="no_whatsapp" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">No Kartu</label>
                <input type="text" name="no_kartu" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <!-- <div>
                <label class="block text-gray-600">Roles</label>
                <select name="roles" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="user">User</option>
                </select>
            </div> -->
            <div>
                <input type="submit" value="Create User" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
            </div>
        </form>
    </div>
</body>
</html>
