-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 17, 2024 at 06:08 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

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
  `kode_buku` varchar(50) DEFAULT NULL,
  `judul_buku` varchar(100) DEFAULT NULL,
  `kategori` enum('Umum','Psikologi','Agama','Sosial','Bahasa','Ilmu Murni','Ilmu Terapan','Kesenian','Fiksi dan Dongeng','Biografi / Sejarah') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cover` text,
  `penerbit` varchar(100) DEFAULT NULL,
  `sinopsis` text,
  `stok_buku` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `kode_buku`, `judul_buku`, `kategori`, `cover`, `penerbit`, `sinopsis`, `stok_buku`) VALUES
(26, '1', 'My Wife is from thousand years ago', 'Sosial', 'f8006b2f-3d7f-44df-aabf-a2ad9532153e.jpeg', 'Kompute Indo', 'Menceritakan tentang seorang pemuda bernama pejeet yang menemukan ada seorang wanita yang berasal dari jaman kuno', 33),
(28, '1111', 'Biografi', 'Kesenian', 'Print-1 1.png', 'Gramedia', 'JAJAJAJAJAJJAJAJAJAJJAJA', 12),
(29, '1122', 'My Wife is from thousand years ago', 'Fiksi dan Dongeng', '★.jpeg', 'AAA', 'WADADA', 22);

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id_kunjungan` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `tanggal_kunjungan` date DEFAULT NULL,
  `waktu_kunjungan` time DEFAULT NULL,
  `keperluan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kunjungan`
--

INSERT INTO `kunjungan` (`id_kunjungan`, `id_user`, `tanggal_kunjungan`, `waktu_kunjungan`, `keperluan`) VALUES
(1, 16, '2024-09-23', NULL, 'Memulangkan Buku'),
(3, 23, '2025-09-22', NULL, 'Meminjam Buku');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_buku` int DEFAULT NULL,
  `kuantitas_buku` int DEFAULT NULL,
  `tanggal_peminjaman` datetime DEFAULT NULL,
  `tanggal_kembalian` datetime DEFAULT NULL,
  `status_peminjaman` enum('proses','sedang dipinjam','sudah dikembalikan') DEFAULT NULL,
  `nama_petugas` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `id_buku`, `kuantitas_buku`, `tanggal_peminjaman`, `tanggal_kembalian`, `status_peminjaman`, `nama_petugas`) VALUES
(4, 23, 26, 12, '2024-09-24 17:08:05', '2024-09-30 00:00:00', 'sudah dikembalikan', 'hahaahahah'),
(6, 16, 28, 22, '2024-10-16 13:43:15', '2024-10-16 00:00:00', 'sudah dikembalikan', 'Mas Heri');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nis` int DEFAULT NULL,
  `nisn` int DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `no_whatsapp` bigint DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_kartu` varchar(5) DEFAULT NULL,
  `roles` enum('admin','petugas','user') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `nis`, `nisn`, `kelas`, `no_whatsapp`, `password`, `no_kartu`, `roles`) VALUES
(16, 'Agung Prakoso', 1111, 22221, 'XI RPL 2', 83893164726, '$2y$10$1pgpKENkB4hEjDzlqcFytODJitFv0bGsRgyjeX8b5DkxdJhBcc6em', '11111', 'user'),
(23, 'Akulah Gung', 22221, 22234, 'x rpl 3', 83893164726, '$2y$10$Z1T1DPuVZO2oFY6YqnDIoOFdr.DkqO7S311.zgjJR.srAdpEwx3ea', '13091', 'user');

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
  ADD KEY `kunjungan_ibfk_1` (`id_user`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `peminjaman_ibfk_1` (`id_user`),
  ADD KEY `peminjaman_ibfk_2` (`id_buku`);

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
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id_kunjungan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
