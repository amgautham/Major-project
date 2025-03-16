-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 05:41 PM
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
(4, 'Alappuzha'),
(7, 'Ernakulam'),
(6, 'Idukki'),
(13, 'Kannur'),
(14, 'Kasargod'),
(2, 'Kollam'),
(5, 'Kottayam'),
(11, 'Kozhikode'),
(10, 'Malappuram'),
(9, 'Palakkad'),
(3, 'Pathanamthitta'),
(1, 'Thiruvananthapuram'),
(8, 'Thrissur'),
(12, 'Wayanad');

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
(11, 'Bathroom flooring material'),
(12, 'Bathroom wall material'),
(4, 'Bond'),
(2, 'Brick'),
(9, 'Ceiling'),
(7, 'Doors'),
(5, 'Flooring'),
(15, 'Kitchen cabinet'),
(14, 'Kitchen countertop'),
(18, 'Kitchen faucet'),
(13, 'Kitchen flooring'),
(17, 'Kitchen sink'),
(16, 'Kitchen wall tile'),
(10, 'Plumbing'),
(6, 'Roofing'),
(1, 'Stone'),
(8, 'Window');

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
(1, 8, 1, 'Laterite stones', 1600.00, 3, '2025-03-16 16:24:31', '35*20*20cm roughly dressed, 100 NOS'),
(2, 8, 1, 'Laterite stones', 1350.00, 2, '2025-03-16 16:24:31', '35*20*14cm roughly dressed, 100 NOS'),
(3, 8, 1, 'Laterite stones', 1250.00, 1, '2025-03-16 16:24:31', '29*20*14cm roughly dressed, 100 NOS'),
(4, 8, 1, 'Laterite rough stones', 150.00, 1, '2025-03-16 16:24:31', 'including soiling stones, 150mm size, 1m3'),
(5, 8, 1, 'Laterite boulders', 170.00, 2, '2025-03-16 16:24:31', 'quarry stones for revetment, 1m3'),
(6, 8, 1, 'Blasted rubble', 270.00, 2, '2025-03-16 16:24:31', 'at quarry 20-40 dm3, 1m3'),
(7, 8, 1, 'Blasted rubble', 270.00, 2, '2025-03-16 16:24:31', 'at quarry above 40 dm3, 1m3'),
(8, 8, 1, 'Coursed rubble stone', 520.00, 3, '2025-03-16 16:24:31', 'for 1st sort work, 1m3'),
(9, 8, 1, 'Split stone', 580.00, 3, '2025-03-16 16:24:31', 'for ashlar masonry, 1m3'),
(10, 8, 2, 'Fly Ash Brick', 5500.00, 1, '2025-03-16 16:24:31', '1000 NOS, 4500-6500 range'),
(11, 8, 2, 'Clay Brick', 9250.00, 3, '2025-03-16 16:24:31', '1000 NOS, 8500-10000 range'),
(12, 8, 2, 'Concrete Brick', 6000.00, 2, '2025-03-16 16:24:31', '1000 NOS, 5000-7000 range'),
(13, 8, 2, 'Sand Lime Brick', 7000.00, 3, '2025-03-16 16:24:31', '1000 NOS, 6000-8000 range'),
(19, 8, 6, 'Corrugnated Iron Sheets', 60.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(20, 8, 6, 'Galvanized Iron Sheets', 80.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(21, 8, 6, 'Absetos Sheets', 50.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(22, 8, 6, 'Polycarbonate Sheets', 100.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(23, 8, 6, 'Rcc Slab', 150.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(24, 8, 6, 'Clay Tiles', 50.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(25, 8, 6, 'Cement Tiles', 60.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(26, 8, 6, 'Thatch Roof', 40.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(27, 8, 6, 'Precast Concrete Roof Panels', 220.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(28, 8, 6, 'Mild steel Trusses', 100.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(29, 8, 6, 'Wooden Trusses', 125.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(30, 8, 6, 'Slate Tiles', 70.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(31, 8, 6, 'Bitumen Roofing sheet', 0.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(32, 8, 7, 'Wooden Doors', 3500.00, 1, '2025-03-16 16:24:31', '1NOS'),
(33, 8, 7, 'PVC Doors', 3000.00, 1, '2025-03-16 16:24:31', '1NOS'),
(34, 8, 7, 'Mild Steel Doors', 5000.00, 2, '2025-03-16 16:24:31', '1NOS'),
(35, 8, 7, 'Alluminium Doors', 6000.00, 3, '2025-03-16 16:24:31', '1NOS'),
(36, 8, 7, 'Steel Frame with Plywood Panel Doors', 4500.00, 2, '2025-03-16 16:24:31', '1NOS'),
(37, 8, 7, 'FRP Door', 3500.00, 1, '2025-03-16 16:24:31', '1NOS'),
(38, 8, 7, 'Hollow Corew Door', 4500.00, 2, '2025-03-16 16:24:31', '1NOS'),
(39, 8, 8, 'Wooden Window', 2500.00, 1, '2025-03-16 16:24:31', '1NOS'),
(40, 8, 8, 'PVC window', 3000.00, 2, '2025-03-16 16:24:31', '1NOS'),
(41, 8, 8, 'Alluminium Window', 4000.00, 3, '2025-03-16 16:24:31', '1NOS'),
(42, 8, 8, 'Steel Frame with glass panel', 3500.00, 2, '2025-03-16 16:24:31', '1NOS'),
(43, 8, 8, 'Mild Steel Grille', 2500.00, 1, '2025-03-16 16:24:31', '1NOS'),
(44, 8, 8, 'Sliding window', 4000.00, 3, '2025-03-16 16:24:31', '1NOS'),
(45, 8, 8, 'Casement Window', 4000.00, 3, '2025-03-16 16:24:31', '1NOS'),
(46, 8, 9, 'Gypsum Board Ceiling', 60.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(47, 8, 9, 'Plaster Of Paris false Ceiling', 50.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(48, 8, 9, 'PVC False Ceiling', 80.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(49, 8, 9, 'Wooden Ceiling', 100.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(50, 8, 9, 'Cement Fibre Board Ceiling', 100.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(51, 8, 9, 'Exposed Concrete Ceiling end finish', 40.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(52, 8, 9, 'Alluminium Ceiling', 120.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(53, 8, 9, 'Mineral Fiber Ceiling', 75.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(54, 8, 9, 'Plain Concrete Ceiling', 120.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(55, 8, 9, 'Thatch Ceiling (Eco Friendly)', 50.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(56, 8, 10, 'Tap &', 300.00, 1, '2025-03-16 16:24:31', '1 each'),
(57, 8, 11, 'Ceramic Tiles', 50.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(58, 8, 11, 'Mosale tiles', 65.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(59, 8, 11, 'Kota Stones', 60.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(60, 8, 11, 'Anti-Stad Tiles', 70.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(61, 8, 11, 'Vinyl Flooring', 80.00, 4, '2025-03-16 16:24:31', '1sq.ft'),
(62, 8, 12, 'Ceremic Tiles', 45.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(63, 8, 12, 'Moosaic Tiles', 55.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(64, 8, 12, 'Wall Plate PVC', 110.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(65, 8, 12, 'Marble Wall tiles', 125.00, 4, '2025-03-16 16:24:31', '1sq.ft'),
(66, 8, 12, 'Water paint', 120.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(67, 8, 11, 'Low cost sanitary fitting', 3500.00, 1, '2025-03-16 16:24:31', '1 each'),
(68, 8, 11, 'Wash Basin', 1800.00, 1, '2025-03-16 16:24:31', '2 each'),
(69, 8, 11, 'Shower (low cost)', 1000.00, 1, '2025-03-16 16:24:31', '3 each'),
(70, 8, 11, 'Bathroom fixture', 550.00, 1, '2025-03-16 16:24:31', '4 each'),
(71, 8, 11, 'Water Tank (plastic)', 4500.00, 1, '2025-03-16 16:24:31', '1000L'),
(72, 8, 13, 'Ceremic Tiles', 60.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(73, 8, 13, 'Vitrified Tiles', 85.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(74, 8, 13, 'PVC Flooring', 55.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(75, 8, 13, 'Granite Flooring', 200.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(76, 8, 14, 'Granite', 200.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(77, 8, 14, 'Marble', 150.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(78, 8, 14, 'Engineered Stone', 300.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(79, 8, 14, 'Laminate', 75.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(80, 8, 15, 'Wooden', 1000.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(81, 8, 15, 'MDF', 750.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(82, 8, 15, 'PVC', 550.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(83, 8, 15, 'HDF', 850.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(84, 8, 16, 'Ceramic Tiles', 50.00, 1, '2025-03-16 16:24:31', '1sq.ft'),
(85, 8, 16, 'Porcelain Tiles', 75.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(86, 8, 16, 'Glass Tiles', 200.00, 3, '2025-03-16 16:24:31', '1sq.ft'),
(87, 8, 16, 'Mossaic Tiles', 100.00, 2, '2025-03-16 16:24:31', '1sq.ft'),
(88, 8, 17, 'Stainless Steel Sink', 2500.00, 1, '2025-03-16 16:24:31', 'EACH'),
(89, 8, 17, 'Granite Composite Sink', 5000.00, 2, '2025-03-16 16:24:31', 'EACH'),
(90, 8, 17, 'Ceremic Sink', 3500.00, 1, '2025-03-16 16:24:31', 'EACH'),
(91, 8, 17, 'PVC Sink', 1500.00, 1, '2025-03-16 16:24:31', 'EACH'),
(92, 8, 18, 'Standard Chrome Faucets', 1000.00, 1, '2025-03-16 16:24:31', 'EACH'),
(93, 8, 18, 'Brass Faucets', 3000.00, 2, '2025-03-16 16:24:31', 'EACH'),
(94, 8, 18, 'Sensor Faucets', 8000.00, 3, '2025-03-16 16:24:31', 'EACH'),
(95, 8, 18, 'Deck-Mounted Faucets', 3500.00, 2, '2025-03-16 16:24:31', 'EACH'),
(96, 8, 4, 'Cement Mortar', 398.00, 1, '2025-03-16 16:24:31', '50 bags'),
(97, 8, 4, 'Sand', 45.00, 1, '2025-03-16 16:24:31', '50 bags'),
(98, 8, 4, 'Lime mortar bond', 18.00, 1, '2025-03-16 16:24:31', 'hydrated lime powder, 1 kg'),
(99, 8, 5, 'Cement Concrete Flooring', 52.50, 1, '2025-03-16 16:24:31', '1sq.ft, 45-60'),
(100, 8, 5, 'Terrazzo Flooring', 60.00, 1, '2025-03-16 16:24:31', '1sq.ft, 50-70'),
(101, 8, 5, 'Vetrified Tiles', 67.50, 2, '2025-03-16 16:24:31', '1sq.ft, 50-85'),
(102, 8, 5, 'Ceramic Tiles', 60.00, 1, '2025-03-16 16:24:31', '1sq.ft, 40-80'),
(103, 8, 5, 'Clay Tiles', 55.00, 1, '2025-03-16 16:24:31', '1sq.ft, 45-65'),
(104, 8, 5, 'Mosaic Flooring', 70.00, 2, '2025-03-16 16:24:31', '1sq.ft, 55-85'),
(105, 8, 5, 'PVC flooring', 97.50, 3, '2025-03-16 16:24:31', '1sq.ft, 65-130'),
(106, 8, 5, 'Marble Flooring', 130.00, 3, '2025-03-16 16:24:31', '1sq.ft, 100-160'),
(107, 8, 5, 'Granite Flooring', 125.00, 2, '2025-03-16 16:24:31', '1sq.ft, 90-160'),
(108, 8, 5, 'Wooden Flooring', 200.00, 3, '2025-03-16 16:24:31', '1sq.ft, 130-270'),
(109, 8, 5, 'Polished Concrete Flooring', 57.50, 1, '2025-03-16 16:24:31', '1sq.ft, 45-70'),
(110, 8, 5, 'Kota Stone Flooring', 62.50, 2, '2025-03-16 16:24:31', '1sq.ft, 50-75');

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
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `material_prices`
--
ALTER TABLE `material_prices`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

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
