-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 04:00 PM
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
-- Database: `ecourbancity`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `created_at`, `update_at`) VALUES
(3, 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '2024-12-19 22:08:13', '2024-12-19 22:08:13'),
(4, 'azril@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '2024-12-20 15:28:53', '2024-12-20 15:28:53'),
(5, 'agussedih@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '2024-12-24 02:20:36', '2024-12-24 02:20:36');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `event_date` datetime NOT NULL,
  `event_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `image_url`, `event_date`, `event_time`) VALUES
(12, 'expo', 'project', '../img_events/HD wallpaper_ ocean view, room, digital art, anime, painting, clutter, Japanese (1).jpeg', '2025-01-10 00:00:00', '23:56:00'),
(13, 'expo', 'pamer project', '../img_events/Reading glasses-rafiki.png', '2024-12-23 00:00:00', '12:12:00'),
(14, 'Kumpul Grup Sunggut Lele', 'Aduhaaii', '../img_events/HD wallpaper_ ocean view, room, digital art, anime, painting, clutter, Japanese (1).jpeg', '2025-12-11 00:00:00', '09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `government`
--

CREATE TABLE `government` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `government`
--

INSERT INTO `government` (`id`, `email`, `password`, `created_at`, `role`) VALUES
(4, 'perhubungan@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-12-16 16:12:45', 'perhubungan'),
(6, 'sipil@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-12-16 16:12:45', 'sipil'),
(8, 'lingkungan@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-12-23 14:47:14', 'lingkungan'),
(10, 'bmkg@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-12-24 04:21:55', 'lingkungan');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_sampah`
--

CREATE TABLE `jadwal_sampah` (
  `id` int(11) NOT NULL,
  `area` varchar(100) NOT NULL,
  `time` varchar(20) NOT NULL,
  `days` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_sampah`
--

INSERT INTO `jadwal_sampah` (`id`, `area`, `time`, `days`) VALUES
(3, 'barat', '09:00', 'Rabu, Sabtu'),
(4, 'selatan', '07:00', 'Senin, Kamis'),
(6, 'timur', '12:12', 'Senin, Kamis, Minggu');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_infrastruktur`
--

CREATE TABLE `laporan_infrastruktur` (
  `id` int(11) NOT NULL,
  `kategori` enum('perhubungan','lingkungan','sipil') NOT NULL,
  `deskripsi_masalah` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('perhubungan','lingkungan','sipil') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_infrastruktur`
--

INSERT INTO `laporan_infrastruktur` (`id`, `kategori`, `deskripsi_masalah`, `photo`, `created_at`, `role`) VALUES
(40, 'sipil', 'tolong perbaiki jalan', '676a5f8bec6a5_HD wallpaper_ ocean view, room, digital art, anime, painting, clutter, Japanese (1).jpeg', '2024-12-24 07:15:23', 'sipil');

-- --------------------------------------------------------

--
-- Table structure for table `transportasi`
--

CREATE TABLE `transportasi` (
  `id` int(11) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `asal` varchar(50) NOT NULL,
  `tujuan` varchar(50) NOT NULL,
  `berangkat` time NOT NULL,
  `durasi` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transportasi`
--

INSERT INTO `transportasi` (`id`, `jenis`, `asal`, `tujuan`, `berangkat`, `durasi`, `harga`, `tanggal`) VALUES
(1, 'Bus', 'Utara', 'Selatan', '10:00:00', 30, 3000, '2024-12-24'),
(2, 'MRT', 'Timur', 'Barat', '12:00:00', 15, 7000, '2024-12-24'),
(3, 'LRT', 'Barat', 'Selatan', '17:30:00', 20, 5000, '2024-12-24'),
(6, 'bus', 'timur', 'selatan', '12:12:00', 60, 70000, '2024-12-27'),
(7, 'Bus', 'kaliurang', 'condong catur', '14:35:00', 30, 200000, '2024-11-06'),
(8, 'MRT', 'Muna', 'mau ajah', '01:34:00', 365, 1000, '2024-12-24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `street` varchar(50) NOT NULL,
  `postalCode` varchar(10) NOT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `purpose` varchar(50) NOT NULL,
  `agreement` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `phone`, `password`, `street`, `postalCode`, `occupation`, `purpose`, `agreement`, `created_at`) VALUES
(1, 'Nathan', 'Achmadi', 'nathan@gmail.com', '789456', '$2y$10$6Ye9nqsi5r/tiFcnfZhT5OaK28cvJvm7UuxhVJqtR9crQKV4VIkRi', 'Selatan', '123', 'gak papa males aja', 'study', 1, '2024-12-19 06:01:31'),
(4, 'tio', 'sinaga', 'sinaga@gmail.com', '08123456789', '$2y$10$Cnjfp9jot50ckIHMHRuuQO0xGqjul.ABnA6eoZctk/9ZPM2RypfSW', 'Barat', '321', 'dekat dengan orang tua', 'study', 1, '2024-12-23 18:16:13'),
(5, 'sulthan', 'cihuy', 'sulthan@gmail.com', '0877777777', '$2y$10$5OGBK.x3CaEInhIPF6gfEuoZN1gG5YP9M3UaMxJDvjzKCCDNkjHAa', 'Selatan', '321', 'dekat dengan orang tua', 'business', 1, '2024-12-24 03:58:05'),
(6, 'genta', 'raihan', 'genta@gmail.com', '082384328428', '$2y$10$UZgMYcbYy/VBTMKfLt97DexIyZjj2LfAh9oFz5dRyd9mY4k6MC99a', 'Selatan', '321', 'dekat dengan orang tua', 'business', 1, '2024-12-24 07:19:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `government`
--
ALTER TABLE `government`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `jadwal_sampah`
--
ALTER TABLE `jadwal_sampah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_infrastruktur`
--
ALTER TABLE `laporan_infrastruktur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transportasi`
--
ALTER TABLE `transportasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `government`
--
ALTER TABLE `government`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jadwal_sampah`
--
ALTER TABLE `jadwal_sampah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `laporan_infrastruktur`
--
ALTER TABLE `laporan_infrastruktur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `transportasi`
--
ALTER TABLE `transportasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
