-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2025 at 12:52 PM
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
-- Database: `healventory`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_laporan_bulanan` (IN `thn` INT, IN `bln` INT)   BEGIN
  INSERT INTO laporan (id_obat, periode, stok_awal, pemasukan, pengeluaran)
  SELECT 
    o.id,
    DATE(CONCAT(thn, '-', LPAD(bln,2,'0'), '-01')),
    o.stok_awal,
    IFNULL((SELECT SUM(jumlah) FROM transaksi WHERE id_obat=o.id AND jenis='masuk' AND MONTH(tgl_transaksi)=bln AND YEAR(tgl_transaksi)=thn),0),
    IFNULL((SELECT SUM(jumlah) FROM transaksi WHERE id_obat=o.id AND jenis='keluar' AND MONTH(tgl_transaksi)=bln AND YEAR(tgl_transaksi)=thn),0)
  FROM obat o;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `periode` date NOT NULL,
  `stok_awal` int(11) DEFAULT 0,
  `pemasukan` int(11) DEFAULT 0,
  `pengeluaran` int(11) DEFAULT 0,
  `stok_akhir` int(11) GENERATED ALWAYS AS (`stok_awal` + `pemasukan` - `pengeluaran`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `id_obat`, `periode`, `stok_awal`, `pemasukan`, `pengeluaran`, `created_at`) VALUES
(1, 1, '2025-10-01', 150, 0, 60, '2025-10-16 16:39:38'),
(2, 2, '2025-10-01', 90, 30, 0, '2025-10-16 16:39:38'),
(3, 3, '2025-10-01', 500, 0, 100, '2025-10-16 16:39:38'),
(4, 1, '2025-10-01', 150, 0, 60, '2025-10-16 16:41:00'),
(5, 2, '2025-10-01', 90, 30, 0, '2025-10-16 16:41:00'),
(6, 3, '2025-10-01', 500, 0, 100, '2025-10-16 16:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notif` int(11) NOT NULL,
  `id_obat` int(11) DEFAULT NULL,
  `jenis` enum('stok','kadaluarsa') DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'belum_dibaca'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notif`, `id_obat`, `jenis`, `pesan`, `tanggal`, `status`) VALUES
(1, 2, 'stok', '⚠️ Stok obat Amoxicillin hanya 90 unit, di bawah batas minimum.', '2025-10-16 16:39:00', 'belum_dibaca');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `kode_obat` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `stok_awal` int(11) DEFAULT 0,
  `stok_minimum` int(11) DEFAULT 100,
  `tgl_kadaluarsa` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `kode_obat`, `nama`, `kategori`, `stok_awal`, `stok_minimum`, `tgl_kadaluarsa`, `created_at`) VALUES
(1, 'OB001', 'Paracetamol', 'Analgesik', 150, 100, '2025-12-05', '2025-10-16 16:36:50'),
(2, 'OB002', 'Amoxicillin', 'Antibiotik', 90, 100, '2025-11-10', '2025-10-16 16:36:50'),
(3, 'OB003', 'Vitamin C', 'Suplemen', 500, 100, '2026-03-01', '2025-10-16 16:36:50');

--
-- Triggers `obat`
--
DELIMITER $$
CREATE TRIGGER `trigger_notif_stok_min` AFTER UPDATE ON `obat` FOR EACH ROW BEGIN
  IF NEW.stok_awal < NEW.stok_minimum THEN
    INSERT INTO notifikasi (id_obat, jenis, pesan)
    VALUES (
      NEW.id,
      'stok',
      CONCAT('⚠️ Stok obat ', NEW.nama, ' hanya ', NEW.stok_awal, ' unit, di bawah batas minimum.')
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tgl_transaksi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_user`, `id_obat`, `jenis`, `jumlah`, `keterangan`, `tgl_transaksi`) VALUES
(1, 1, 1, 'keluar', 60, 'Terjual ke pelanggan', '2025-10-16 23:36:50'),
(2, 1, 2, 'masuk', 30, 'Penambahan stok dari supplier', '2025-10-16 23:36:50'),
(3, 2, 3, 'keluar', 100, 'Distribusi ke cabang', '2025-10-16 23:36:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `role` enum('admin','manager','staff') DEFAULT 'staff',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `role`, `password`, `created_at`) VALUES
(1, 'admin1', 'bita', 'admin', '$2y$10$mXS5kknJbrg8iCubR3v7H.LWnxq5NmkhlZBhCgrsQK4UkK5zIVzGW', '2025-10-16 16:36:50'),
(2, 'staff1', 'ilham', 'staff', '$2y$10$gCGHnZABPWCEVWp7pT7r8u67uxrbaTpRhRu8oiWeRm/BTxoQcJj7a', '2025-10-16 16:36:50'),
(3, 'admin2', 'ai', 'admin', '$2y$10$.kwG7dexw9CWZy1dfvNHvOLvrd3bd8KeibGLhAwCFzIYqv4Z7FGnm', '2025-10-16 16:36:50'),
(4, 'menejer1', 'ilham', 'manager', '$2y$10$vpE0omBCJ8kPI8O73uFore5dFn5JUYdmk5oK.urWkYjoeDVDctsiS', '2025-10-16 16:36:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_obat` (`kode_obat`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `event_generate_laporan_bulanan` ON SCHEDULE EVERY 1 MONTH STARTS '2025-11-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL generate_laporan_bulanan(YEAR(CURDATE()), MONTH(CURDATE()))$$

CREATE DEFINER=`root`@`localhost` EVENT `event_notif_kadaluarsa` ON SCHEDULE EVERY 1 DAY STARTS '2025-10-16 23:38:02' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  INSERT INTO notifikasi (id_obat, jenis, pesan)
  SELECT id, 'kadaluarsa',
  CONCAT('⚠️ Obat ', nama, ' akan kedaluwarsa pada ', DATE_FORMAT(tgl_kadaluarsa, '%d-%m-%Y'))
  FROM obat
  WHERE DATEDIFF(tgl_kadaluarsa, CURDATE()) <= 60
  AND DATEDIFF(tgl_kadaluarsa, CURDATE()) > 0
  AND id NOT IN (
    SELECT id_obat FROM notifikasi
    WHERE jenis='kadaluarsa' AND DATE(tanggal)=CURDATE()
  );
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
