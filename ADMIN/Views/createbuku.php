<?php
// create.php
include_once '../core/Database.php';
include_once '../Models/Buku.php';


session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ");
    exit();
}


$database = new Database();
$db = $database->getConnection();

$buku = new Buku($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buku->kode_buku = $_POST['kode_buku'];
    $buku->judul_buku = $_POST['judul_buku'];
    $buku->kategori = $_POST['kategori'];
    $buku->penerbit = $_POST['penerbit'];
    $buku->sinopsis = $_POST['sinopsis'];
    $buku->stok_buku = $_POST['stok_buku'];
    $buku->url_buku = $_POST['url_buku'];

    // Handle file upload for cover
    if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $target_dir = "../../public/assets/Books/";
        $target_file = $target_dir . basename($_FILES["cover"]["name"]);
        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
            $buku->cover = $target_file;
        }
    }

    if($buku->create()) {
        echo "Buku berhasil ditambahkan.";
        header("Location: ./index.php");
    } else {
        echo "Gagal menambahkan buku.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-700">Tambah Buku Baru</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-gray-600">Kode Buku</label>
                <input type="text" name="kode_buku" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">Judul Buku</label>
                <input type="text" name="judul_buku" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">Kategori</label>
                <select name="kategori" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="Umum">Umum</option>
                    <option value="Psikologi">Psikologi</option>
                    <option value="Agama">Agama</option>
                    <option value="Sosial">Sosial</option>
                    <option value="Bahasa">Bahasa</option>
                    <option value="Ilmu Murni">Ilmu Murni</option>
                    <option value="Ilmu Terapan">Ilmu Terapan</option>
                    <option value="Kesenian">Kesenian</option>
                    <option value="Olahraga">Olahraga</option>
                    <option value="Fiksi dan Dongeng">Fiksi dan Dongeng</option>
                    <option value="Biografi / Sejarah">Biografi / Sejarah</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-600">Cover Buku</label>
                <input type="file" name="cover" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">Penerbit</label>
                <input type="text" name="penerbit" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">Sinopsis</label>
                <textarea name="sinopsis" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            </div>
            <div>
                <label class="block text-gray-600">Stok Buku</label>
                <input type="number" name="stok_buku" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-600">URL Buku</label>
                <input type="text" name="url_buku" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <input type="submit" value="Tambah Buku" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
            </div>
        </form>
    </div>
</body>
</html>
