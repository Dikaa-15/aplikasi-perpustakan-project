-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 12, 2024 at 03:25 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int NOT NULL,
  `kode_buku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `judul_buku` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('Umum','Psikologi','Agama','Sosial','Bahasa','Ilmu Murni','Ilmu Terapan','Kesenian & Olahraga','Fiksi dan Dongeng','Biografi / Sejarah') COLLATE utf8mb4_general_ci NOT NULL,
  `cover` text COLLATE utf8mb4_general_ci,
  `penerbit` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sinopsis` text COLLATE utf8mb4_general_ci NOT NULL,
  `stok_buku` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `kode_buku`, `judul_buku`, `kategori`, `cover`, `penerbit`, `sinopsis`, `stok_buku`) VALUES
(1, '1234', 'Agama', 'Agama', 'agama.jpg', 'gramedia', 'buku agama', 8),
(2, '1234', 'Ipas', 'Sosial', 'Print-1 1.png', 'gramedia', 'ipas ilmu sosial', 0),
(3, '4321', 'Informatika', 'Ilmu Terapan', 'informatika.jpg', 'gramedia', 'buku untuk RPL', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id_kunjungan` int NOT NULL,
  `id_user` int NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `waktu_kunjungan` time NOT NULL,
  `keperluan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kunjungan`
--

INSERT INTO `kunjungan` (`id_kunjungan`, `id_user`, `tanggal_kunjungan`, `waktu_kunjungan`, `keperluan`) VALUES
(1, 41, '2024-10-12', '10:57:57', 'Mengunjungi Perpustakaan'),
(2, 41, '2024-10-12', '11:08:45', 'Mengunjungi Perpustakaan'),
(3, 41, '2024-10-12', '11:09:00', 'Mengunjungi Perpustakaan'),
(4, 41, '2024-10-12', '11:09:07', 'Mengunjungi Perpustakaan');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL,
  `id_user` int NOT NULL,
  `id_buku` int NOT NULL,
  `kuantitas_buku` int NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `waktu_peminjaman` time NOT NULL,
  `tanggal_kembalian` date NOT NULL,
  `waktu_kembalian` time NOT NULL,
  `status_peminjaman` enum('proses','sedang dipinjam','sudah dikembalikan') COLLATE utf8mb4_general_ci NOT NULL,
  `nama_petugas` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `id_buku`, `kuantitas_buku`, `tanggal_peminjaman`, `waktu_peminjaman`, `tanggal_kembalian`, `waktu_kembalian`, `status_peminjaman`, `nama_petugas`) VALUES
(15, 34, 2, 1, '2024-10-11', '10:50:15', '2024-10-11', '10:50:15', 'sedang dipinjam', 'Tidak Diketahui'),
(16, 34, 1, 2, '2024-10-11', '10:51:09', '2024-10-11', '10:51:09', 'sedang dipinjam', 'Tidak Diketahui'),
(17, 34, 1, 10, '2024-10-11', '11:19:05', '2024-10-11', '11:19:05', 'sedang dipinjam', 'Tidak Diketahui'),
(18, 41, 1, 1, '2024-10-12', '03:22:29', '2024-10-13', '03:22:29', 'sedang dipinjam', 'Tidak Diketahui'),
(19, 41, 1, 1, '2024-10-12', '03:22:56', '2024-10-13', '03:22:56', 'sedang dipinjam', 'Tidak Diketahui');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nis` int DEFAULT NULL,
  `nisn` int DEFAULT NULL,
  `kelas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_whatsapp` int DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_kartu` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `roles` enum('admin','petugas','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `nis`, `nisn`, `kelas`, `no_whatsapp`, `password`, `no_kartu`, `roles`) VALUES
(34, 'dika', 12345, 1528, NULL, 895, '$2y$10$6.NHbR0tazmx2Pd0iEjJxeT/sgQM5hKpD.lxlhVBWJogF3A9PhNtq', '123', 'user'),
(35, 'dika', 12345, NULL, NULL, 895, '$2y$10$FOLppLJr15dTdEMclAATfu6tVu9NqTUOXfcsYeEjcDbj7AT8pO3B6', '123', 'user'),
(36, 'dika', 12345, NULL, NULL, 895, '$2y$10$rGLuNjyVPxxwaa5.pbPhQOzan0.1r5..CUOsj12Q05k7YNju/KJL.', '123', 'user'),
(37, 'dika', 12345, NULL, NULL, 895, '$2y$10$dpw9VsPrgqelkc7pkatUl.B2aFUihAmNP3P2jhnrOZ.aE89XOKOeG', '123', 'user'),
(38, 'dika', 12345, NULL, NULL, 895, '$2y$10$S3rbZQ7RXaYrHYKzOqr6keudPhQLvnjMhuL2pYsT/lG.ZMrL810SG', '123', 'user'),
(39, 'alta', 12345, NULL, NULL, 896, '$2y$10$Fhl6rZEo9Vtzu9vbPQpCaOMjmSk/JgX/lM60oBPlnECPuntb/Nh2K', '123', 'user'),
(40, 'candy', 12345, NULL, NULL, 895, '$2y$10$GdAkhTIo5zwubtTetpvwF.2kQwlw38.MJMU6kSJPqd4Em5NdLMKkK', '123', 'user'),
(41, 'candy', 12345, NULL, 'XII DKV', 768, '$2y$10$VOEmWswWeJkisi7wwkl6Iu0KncGViwYQOuil85fJnwH28taXNNPSW', '1001', 'user'),
(43, 'candy', NULL, NULL, 'XII DKV', NULL, NULL, '1001', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`id_kunjungan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id_kunjungan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD CONSTRAINT `kunjungan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
