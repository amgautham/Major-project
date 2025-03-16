-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 04:43 PM
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
-- Database: `buildwise`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
  `dealer_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `phone` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `district_id` int(11) NOT NULL,
  `district_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`district_id`, `district_name`) VALUES
(3, 'Ernakulam'),
(2, 'Kollam'),
(4, 'Kozhikode'),
(1, 'Thiruvananthapuram'),
(5, 'Thrissur');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `material_id` int(11) NOT NULL,
  `material_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`material_id`, `material_name`) VALUES
(8, 'BATHROOM FLOORING'),
(9, 'BATHROOM WALL'),
(4, 'BOND'),
(2, 'BRICK'),
(7, 'CEILING'),
(5, 'FLOORING'),
(12, 'KITCHEN CABINETS'),
(11, 'KITCHEN COUNTERTOPS'),
(10, 'KITCHEN FLOORING'),
(13, 'KITCHEN WALL TILES'),
(6, 'ROOFING'),
(1, 'Stone'),
(3, 'WALL');

-- --------------------------------------------------------

--
-- Table structure for table `material_prices`
--

CREATE TABLE `material_prices` (
  `price_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `material_type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `specification` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_prices`
--

INSERT INTO `material_prices` (`price_id`, `district_id`, `material_id`, `material_type`, `price`, `priority`, `last_updated`, `specification`) VALUES
(17, 5, 5, 'Cement Concrete Flooring', 52.50, 1, '2025-03-16 09:18:32', NULL),
(18, 5, 5, 'Terrazzo Flooring', 60.00, 10, '2025-03-16 09:18:15', NULL),
(19, 5, 5, 'Vetrified Tiles', 67.50, 10, '2025-03-16 09:18:15', NULL),
(20, 5, 5, 'Ceramic Tiles', 60.00, 10, '2025-03-16 09:18:15', NULL),
(21, 5, 5, 'Clay Tiles', 55.00, 10, '2025-03-16 09:18:15', NULL),
(22, 5, 5, 'Mosaic Flooring', 70.00, 10, '2025-03-16 09:18:15', NULL),
(23, 5, 5, 'PVC Flooring', 97.50, 10, '2025-03-16 09:18:15', NULL),
(24, 5, 5, 'Marble Flooring', 130.00, 10, '2025-03-16 09:18:15', NULL),
(25, 5, 5, 'Granite Flooring', 125.00, 10, '2025-03-16 09:18:15', NULL),
(26, 5, 5, 'Wooden Flooring', 200.00, 10, '2025-03-16 09:18:15', NULL),
(27, 5, 5, 'Polished Concrete Flooring', 57.50, 10, '2025-03-16 09:18:15', NULL),
(28, 5, 5, 'Kota Stone Flooring', 62.50, 10, '2025-03-16 09:18:15', NULL),
(29, 5, 6, 'Corrugnated Iron Sheets', 60.00, 10, '2025-03-16 09:18:15', NULL),
(30, 5, 6, 'Galvanized Iron Sheets', 80.00, 10, '2025-03-16 09:18:15', NULL),
(31, 5, 6, 'Absetos Sheets', 50.00, 10, '2025-03-16 09:18:15', NULL),
(32, 5, 6, 'Polycarbonate Sheets', 100.00, 10, '2025-03-16 09:18:15', NULL),
(33, 5, 6, 'Rcc Slab', 150.00, 10, '2025-03-16 09:18:15', NULL),
(34, 5, 6, 'Clay Tiles', 50.00, 10, '2025-03-16 09:18:15', NULL),
(35, 5, 6, 'Cement Tiles', 60.00, 10, '2025-03-16 09:18:15', NULL),
(36, 5, 6, 'Thatch Roof', 40.00, 10, '2025-03-16 09:18:15', NULL),
(37, 5, 6, 'Precast Concrete Roof Panels', 220.00, 10, '2025-03-16 09:18:15', NULL),
(38, 5, 6, 'Mild steel Trusses', 100.00, 10, '2025-03-16 09:18:15', NULL),
(39, 5, 6, 'Wooden Trusses', 125.00, 10, '2025-03-16 09:18:15', NULL),
(40, 5, 6, 'Slate Tiles', 70.00, 10, '2025-03-16 09:18:15', NULL),
(41, 5, 7, 'Gypsum Board Ceiling', 60.00, 10, '2025-03-16 09:18:15', NULL),
(42, 5, 7, 'Plaster Of Paris false Ceiling', 50.00, 10, '2025-03-16 09:18:15', NULL),
(43, 5, 7, 'PVC False Ceiling', 80.00, 10, '2025-03-16 09:18:15', NULL),
(44, 5, 7, 'Wooden Ceiling', 100.00, 10, '2025-03-16 09:18:15', NULL),
(45, 5, 7, 'Cement Fibre Board Ceiling', 100.00, 10, '2025-03-16 09:18:15', NULL),
(46, 5, 7, 'Exposed Concrete Ceiling end finish', 40.00, 10, '2025-03-16 09:18:15', NULL),
(47, 5, 7, 'Alluminium Ceiling', 120.00, 10, '2025-03-16 09:18:15', NULL),
(48, 5, 7, 'Mineral Fiber Ceiling', 75.00, 10, '2025-03-16 09:18:15', NULL),
(49, 5, 7, 'Plain Concrete Ceiling', 120.00, 10, '2025-03-16 09:18:15', NULL),
(50, 5, 7, 'Thatch Ceiling (Eco Friendly)', 50.00, 10, '2025-03-16 09:18:15', NULL),
(51, 5, 8, 'Ceramic Tiles', 50.00, 10, '2025-03-16 09:18:15', NULL),
(52, 5, 8, 'Mosale Tiles', 65.00, 10, '2025-03-16 09:18:15', NULL),
(53, 5, 8, 'Kota Stones', 60.00, 10, '2025-03-16 09:18:15', NULL),
(54, 5, 8, 'Anti-Stad Tiles', 70.00, 10, '2025-03-16 09:18:15', NULL),
(55, 5, 8, 'Vinyl Flooring', 80.00, 10, '2025-03-16 09:18:15', NULL),
(56, 5, 9, 'Ceremic Tiles', 45.00, 10, '2025-03-16 09:18:15', NULL),
(57, 5, 9, 'Moosaic Tiles', 55.00, 10, '2025-03-16 09:18:15', NULL),
(58, 5, 9, 'Wall Plate PVC', 110.00, 10, '2025-03-16 09:18:15', NULL),
(59, 5, 9, 'Marble Wall Tiles', 125.00, 10, '2025-03-16 09:18:15', NULL),
(60, 5, 9, 'Water Paint', 120.00, 10, '2025-03-16 09:18:15', NULL),
(61, 5, 10, 'Ceramic Tiles', 60.00, 10, '2025-03-16 09:18:15', NULL),
(62, 5, 10, 'Vitrified Tiles', 85.00, 10, '2025-03-16 09:18:15', NULL),
(63, 5, 10, 'PVC Flooring', 55.00, 10, '2025-03-16 09:18:15', NULL),
(64, 5, 10, 'Granite Flooring', 200.00, 10, '2025-03-16 09:18:15', NULL),
(65, 5, 11, 'Granite', 200.00, 10, '2025-03-16 09:18:15', NULL),
(66, 5, 11, 'Marble', 150.00, 10, '2025-03-16 09:18:15', NULL),
(67, 5, 11, 'Engineered Stone', 300.00, 10, '2025-03-16 09:18:15', NULL),
(68, 5, 11, 'Laminate', 75.00, 1, '2025-03-16 09:20:22', NULL),
(69, 5, 12, 'Wooden', 1000.00, 10, '2025-03-16 09:18:15', NULL),
(70, 5, 12, 'MDF', 750.00, 10, '2025-03-16 09:18:15', NULL),
(71, 5, 12, 'PVC', 550.00, 10, '2025-03-16 09:18:15', NULL),
(72, 5, 12, 'HDF', 850.00, 10, '2025-03-16 09:18:15', NULL),
(73, 5, 13, 'Ceramic Tiles', 50.00, 10, '2025-03-16 09:18:15', NULL),
(74, 5, 13, 'Porcelain Tiles', 75.00, 10, '2025-03-16 09:18:15', NULL),
(75, 5, 13, 'Glass Tiles', 200.00, 10, '2025-03-16 09:18:15', NULL),
(76, 5, 13, 'Mosaic Tiles', 100.00, 10, '2025-03-16 09:18:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`) VALUES
(23, 'oggy', '$2y$10$T2L3Zw5FWhVwd2A0ZlVenO4puv6uBKq3AXOTAoks77kXEJVkW8jHC', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`dealer_id`),
  ADD KEY `district_id` (`district_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`district_id`),
  ADD UNIQUE KEY `district_name` (`district_name`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_id`),
  ADD UNIQUE KEY `material_name` (`material_name`);

--
-- Indexes for table `material_prices`
--
ALTER TABLE `material_prices`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `dealer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `material_prices`
--
ALTER TABLE `material_prices`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dealers`
--
ALTER TABLE `dealers`
  ADD CONSTRAINT `dealers_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`district_id`);

--
-- Constraints for table `material_prices`
--
ALTER TABLE `material_prices`
  ADD CONSTRAINT `material_prices_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`district_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `material_prices_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
