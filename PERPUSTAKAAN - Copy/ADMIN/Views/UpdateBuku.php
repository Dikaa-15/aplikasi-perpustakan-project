<?php
// update.php
include_once '../core/Database.php';
include_once '../Models/Buku.php';

$database = new Database();
$db = $database->getConnection();

$buku = new Buku($db);

if(isset($_GET['id'])) {
    $buku->id_buku = $_GET['id'];
    $buku->readOne();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = isset($_POST['id_buku']) ? $_POST['id_buku'] : die('ID tidak ditemukan.');
    $buku->kode_buku = $_POST['kode_buku'];
    $buku->judul_buku = $_POST['judul_buku'];
    $buku->kategori = $_POST['kategori'];
    $buku->penerbit = $_POST['penerbit'];
    $buku->sinopsis = $_POST['sinopsis'];
    $buku->stok_buku = $_POST['stok_buku'];

    if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["cover"]["name"]);

        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
            $buku->cover = basename($target_file);
        }
    } else {
        $buku->cover = $buku->getOldCover($id_buku);
    }

    if ($buku->update($id_buku)) {
        header("Location: ../Views/DataBuku.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat memperbarui buku.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Update Buku</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="id_buku" value="<?php echo $buku->id_buku; ?>">

            <?php
            $fields = [
                "kode_buku" => "Kode Buku",
                "judul_buku" => "Judul Buku",
                "penerbit" => "Penerbit",
                "stok_buku" => "Stok Buku"
            ];

            foreach ($fields as $field => $label) {
                $value = $buku->$field;
                $type = ($field === 'stok_buku') ? 'number' : 'text';
                echo "
                <div>
                    <label class='block text-gray-700'>$label</label>
                    <input type='$type' name='$field' value='$value' required
                        class='w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400'>
                </div>";
            }
            ?>

            <div>
                <label class="block text-gray-700">Kategori</label>
                <select name="kategori" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php
                    $categories = ["Umum", "Psikologi", "Agama", "Sosial", "Bahasa", "Ilmu Murni", "Ilmu Terapan", "Kesenian", "Fiksi dan Dongeng", "Biografi / Sejarah"];
                    foreach ($categories as $category) {
                        $selected = ($buku->kategori == $category) ? "selected" : "";
                        echo "<option value='$category' $selected>$category</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Cover Buku</label>
                <input type="file" name="cover" class="w-full px-4 py-2 border rounded-lg">
                <?php if($buku->cover): ?>
                    <img src="uploads/<?php echo $buku->cover; ?>" alt="Current cover" class="mt-2" width="100">
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700">Sinopsis</label>
                <textarea name="sinopsis" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"><?php echo $buku->sinopsis; ?></textarea>
            </div>

            <div class="text-center">
                <input type="submit" value="Update Buku" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 cursor-pointer">
            </div>
        </form>
        <div class="text-center mt-4">
            <a href="index.php" class="text-blue-500 hover:underline">Kembali ke Daftar Buku</a>
        </div>
    </div>
</body>
</html>
