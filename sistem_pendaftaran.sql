-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 03:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pendaftaran`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int(11) NOT NULL,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `nama_dokumen` varchar(100) DEFAULT NULL,
  `path_dokumen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `id_mahasiswa`, `nama_dokumen`, `path_dokumen`) VALUES
(1, 1, 'KTP', '/sistem_pendaftaran/uploads/pembayaran UKT.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `total_pendaftaran` int(11) DEFAULT NULL,
  `total_pembayaran` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `tanggal`, `total_pendaftaran`, `total_pembayaran`) VALUES
(1, '2025-01-28', 1, 0.00),
(2, '2025-01-28', 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_mahasiswa` varchar(100) DEFAULT NULL,
  `program_studi` varchar(100) DEFAULT NULL,
  `dokumen` varchar(255) DEFAULT NULL,
  `status_pendaftaran` enum('Menunggu','Diterima','Ditolak') DEFAULT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nama_mahasiswa`, `program_studi`, `dokumen`, `status_pendaftaran`, `no_telepon`, `email`) VALUES
(1, 1, 'John ', 'Teknik Informatika', NULL, 'Diterima', '553235', 'john@gmail.com'),
(6, 8, 'Aksal', 'Teknik Informatika', NULL, 'Diterima', '08828877', 'aksal@gmail.com'),
(18, 5, 'andi rusnandi', 'Teknik Informatika', NULL, 'Diterima', '081311143254', 'andi.rusnandy@gmail.com'),
(21, 16, 'akhlan', 'Teknik Informatika', NULL, 'Diterima', '081311143254', 'akhlan@gmail.com'),
(22, NULL, 'ghailan', NULL, NULL, 'Diterima', '', 'ghailan@gmail.com'),
(23, 17, 'Deni Indrawan', 'Teknik Elektro', NULL, 'Diterima', '081311143254', 'deni@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `status_pembayaran` enum('Menunggu','Lunas') DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_mahasiswa`, `status_pembayaran`, `bukti_pembayaran`, `tanggal_pembayaran`, `id_user`) VALUES
(1, 1, 'Lunas', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Mahasiswa','Petugas','Admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_user`, `email`, `password`, `role`) VALUES
(1, 'John Doe', 'johndoe@mail.com', 'password123', 'Mahasiswa'),
(2, 'Admin Sistem', 'admin@mail.com', 'admin123', 'Admin'),
(3, 'Petugas Akademik', 'petugas@mail.com', 'petugas123', 'Petugas'),
(5, 'andi rusnandi', 'andi.rusnandy@gmail.com', '123', 'Mahasiswa'),
(8, 'Aksal', 'aksal@gmail.com', '123', 'Mahasiswa'),
(15, 'Ghali', 'ghali@yahoo.com', '123', 'Mahasiswa'),
(16, 'akhlan', 'akhlan@gmail.com', '123', 'Mahasiswa'),
(17, 'deni indrawan', 'deni@gmail.com', '123', 'Mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `dokumen_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`);

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `mahasiswa` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
