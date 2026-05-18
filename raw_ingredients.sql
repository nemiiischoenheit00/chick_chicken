-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 09:11 AM
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
-- Database: `chickchicken`
--

-- --------------------------------------------------------

--
-- Table structure for table `raw_ingredients`
--

CREATE TABLE `raw_ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'Ingredient',
  `unit` varchar(50) NOT NULL DEFAULT 'kg',
  `initial_stock` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remaining` decimal(10,2) NOT NULL DEFAULT 0.00,
  `low_stock_threshold` decimal(10,2) NOT NULL DEFAULT 10.00,
  `supplier` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `stock_status` enum('ok','low','out') GENERATED ALWAYS AS (case when `remaining` <= 0 then 'out' when `remaining` <= `low_stock_threshold` then 'low' else 'ok' end) STORED,
  `stock_pct` decimal(5,2) GENERATED ALWAYS AS (case when `initial_stock` <= 0 then 0 else `remaining` / `initial_stock` * 100 end) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_ingredients`
--

INSERT INTO `raw_ingredients` (`id`, `name`, `category`, `unit`, `initial_stock`, `remaining`, `low_stock_threshold`, `supplier`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Chicken Breast', 'Meat', 'kg', 100.00, 100.00, 10.00, 'ABC Farm', 'Keep frozen', '2026-05-14 06:39:38', '2026-05-14 06:39:38'),
(2, 'Potatoes', 'Vegetables', 'kg', 50.00, 50.00, 5.00, 'Local Market', 'Store in dry area', '2026-05-14 06:39:38', '2026-05-14 06:39:38'),
(3, 'Cooking Oil', 'Oils & Fats', 'liters', 30.00, 30.00, 5.00, 'Golden Oil', 'Avoid sunlight', '2026-05-14 06:39:38', '2026-05-14 06:39:38'),
(4, 'Pepper', 'Spices', 'g', 5000.00, 5000.00, 500.00, 'Spice Hub', 'Seal tightly', '2026-05-14 06:39:38', '2026-05-14 06:39:38'),
(5, 'Salt', 'Spices', 'g', 3000.00, 3000.00, 300.00, 'Spice Hub', NULL, '2026-05-14 06:39:38', '2026-05-14 06:39:38'),
(6, 'Burger Buns', 'Bakery', 'pcs', 200.00, 200.00, 20.00, 'Bread House', NULL, '2026-05-14 06:39:38', '2026-05-14 06:39:38'),
(7, 'Cheese Slices', 'Dairy', 'packs', 40.00, 40.00, 5.00, 'Dairy Fresh', 'Refrigerate', '2026-05-14 06:39:38', '2026-05-14 06:39:38'),
(8, 'Softdrink Cups', 'Packaging', 'pcs', 500.00, 500.00, 50.00, 'PackPro', NULL, '2026-05-14 06:39:38', '2026-05-14 06:39:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `raw_ingredients`
--
ALTER TABLE `raw_ingredients`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `raw_ingredients`
--
ALTER TABLE `raw_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
