-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2025 at 06:42 PM
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
-- Database: `house`
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
(1, 'Thrissur');

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
(1, 'Concrete'),
(2, 'Wood'),
(3, 'Steel'),
(4, 'Brick'),
(5, 'Glass'),
(6, 'Drywall (Plasterboard)'),
(7, 'Insulation'),
(8, 'Tile'),
(9, 'Stone'),
(10, 'Roofing Shingles'),
(11, 'PVC'),
(12, 'Cement'),
(13, 'Paint'),
(14, 'Carpet'),
(15, 'Masonry'),
(16, 'Plywood'),
(17, 'Rebar'),
(18, 'Laminate');

-- --------------------------------------------------------

--
-- Table structure for table `material_prices`
--

CREATE TABLE `material_prices` (
  `price_id` int(11) NOT NULL,
  `sub_locality_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_prices`
--

INSERT INTO `material_prices` (`price_id`, `sub_locality_id`, `material_id`, `price`) VALUES
(2, 2, 1, 500.00),
(3, 2, 4, 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `sub_localities`
--

CREATE TABLE `sub_localities` (
  `sub_locality_id` int(11) NOT NULL,
  `sub_locality_name` varchar(100) NOT NULL,
  `district_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_localities`
--

INSERT INTO `sub_localities` (`sub_locality_id`, `sub_locality_name`, `district_id`) VALUES
(1, 'Mala', 1),
(2, 'Kodungallur', 1),
(3, 'Chalakudi', 1);

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
  ADD PRIMARY KEY (`district_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_id`);

--
-- Indexes for table `material_prices`
--
ALTER TABLE `material_prices`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `sub_locality_id` (`sub_locality_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `sub_localities`
--
ALTER TABLE `sub_localities`
  ADD PRIMARY KEY (`sub_locality_id`),
  ADD KEY `district_id` (`district_id`);

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
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `material_prices`
--
ALTER TABLE `material_prices`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_localities`
--
ALTER TABLE `sub_localities`
  MODIFY `sub_locality_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `material_prices_ibfk_1` FOREIGN KEY (`sub_locality_id`) REFERENCES `sub_localities` (`sub_locality_id`),
  ADD CONSTRAINT `material_prices_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`);

--
-- Constraints for table `sub_localities`
--
ALTER TABLE `sub_localities`
  ADD CONSTRAINT `sub_localities_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`district_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
