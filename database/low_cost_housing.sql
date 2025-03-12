-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2025 at 05:49 PM
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
-- Database: `low_cost_housing`
--

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
(14, 'Kasaragod'),
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
(4, 'Aggregate'),
(3, 'Bricks'),
(1, 'Cement'),
(13, 'Contractor'),
(8, 'Doors'),
(9, 'Electrical Fittings'),
(6, 'Flooring'),
(12, 'Kitchen Work'),
(10, 'Painting'),
(5, 'Sand'),
(11, 'Sanitary Fittings'),
(2, 'Steel'),
(7, 'Windows');

-- --------------------------------------------------------

--
-- Table structure for table `material_prices`
--

CREATE TABLE `material_prices` (
  `district_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `low_price` decimal(10,2) NOT NULL,
  `medium_price` decimal(10,2) NOT NULL,
  `high_price` decimal(10,2) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_prices`
--

INSERT INTO `material_prices` (`district_id`, `material_id`, `low_price`, `medium_price`, `high_price`, `last_updated`) VALUES
(1, 1, 250.00, 270.00, 300.00, '2025-03-12 16:08:12'),
(1, 2, 54.00, 56.00, 58.00, '2025-03-12 15:32:04'),
(1, 3, 4.50, 5.00, 5.50, '2025-03-12 15:32:04'),
(1, 4, 950.00, 1000.00, 1050.00, '2025-03-12 15:32:04'),
(1, 5, 900.00, 950.00, 1000.00, '2025-03-12 15:32:04'),
(1, 6, 28.00, 30.00, 32.00, '2025-03-12 15:32:04'),
(1, 7, 3800.00, 4000.00, 4200.00, '2025-03-12 15:32:04'),
(1, 8, 4800.00, 5000.00, 5200.00, '2025-03-12 15:32:04'),
(1, 9, 48.00, 50.00, 52.00, '2025-03-12 15:32:04'),
(1, 10, 4.50, 5.00, 5.50, '2025-03-12 15:32:04'),
(1, 11, 14500.00, 15000.00, 15500.00, '2025-03-12 15:32:04'),
(1, 12, 190.00, 200.00, 210.00, '2025-03-12 15:32:04'),
(1, 13, 480.00, 500.00, 520.00, '2025-03-12 15:32:04'),
(2, 1, 255.00, 275.00, 295.00, '2025-03-12 15:32:04'),
(2, 2, 53.00, 55.00, 57.00, '2025-03-12 15:32:04'),
(2, 3, 4.40, 4.90, 5.40, '2025-03-12 15:32:04'),
(2, 4, 940.00, 990.00, 1040.00, '2025-03-12 15:32:04'),
(2, 5, 880.00, 930.00, 980.00, '2025-03-12 15:32:04'),
(2, 6, 27.00, 29.00, 31.00, '2025-03-12 15:32:04'),
(2, 7, 3750.00, 3950.00, 4150.00, '2025-03-12 15:32:04'),
(2, 8, 4700.00, 4900.00, 5100.00, '2025-03-12 15:32:04'),
(2, 9, 47.00, 49.00, 51.00, '2025-03-12 15:32:04'),
(2, 10, 4.40, 4.90, 5.40, '2025-03-12 15:32:04'),
(2, 11, 14000.00, 14500.00, 15000.00, '2025-03-12 15:32:04'),
(2, 12, 185.00, 195.00, 205.00, '2025-03-12 15:32:04'),
(2, 13, 470.00, 490.00, 510.00, '2025-03-12 15:32:04');

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
  ADD PRIMARY KEY (`district_id`,`material_id`),
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
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `material_prices`
--
ALTER TABLE `material_prices`
  ADD CONSTRAINT `material_prices_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`district_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_prices_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
