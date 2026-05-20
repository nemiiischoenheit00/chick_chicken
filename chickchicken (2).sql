-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2026 at 04:01 PM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `phone`, `email`, `password`, `created_at`) VALUES
(1, 'JERWIN', 'CARMONA', 4294967295, 'jcarmona.0872@umak.edu.ph', '$2y$10$PNylw.aqTOvpcA45aLfkV.E.DnMiK.uHsqmcci6KoX.jvJWD/QbBi', '2026-05-18 12:39:49'),
(2, 'Chester', 'Ganongan', 4294967295, 'blasphemydarem@gmail.com', '$2y$10$6J1qOAtEqtoHfQo2.MhGH.x7UEBDAh56IDNUbvHCBCM0nIIl6hppW', '2026-05-18 17:03:10');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `option_selected` varchar(255) DEFAULT NULL,
  `sauce` varchar(100) DEFAULT NULL,
  `extra_flavor` varchar(100) DEFAULT NULL,
  `mix_preference` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `option_selected`, `sauce`, `extra_flavor`, `mix_preference`, `created_at`) VALUES
(81, 11, 5, 2, 'Solo (600ml)', 'Garlic Mayo', 'Hot Buffalo', 'Mixed', '2026-05-18 22:56:16');

-- --------------------------------------------------------

--
-- Table structure for table `discount_applications`
--

CREATE TABLE `discount_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `id_image_path` varchar(500) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount_applications`
--

INSERT INTO `discount_applications` (`id`, `user_id`, `type`, `id_image_path`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(5, 10, 'PWD', 'uploads/discount_ids/discount_10_1778832683.png', 'approved', '', '2026-05-15 08:11:23', '2026-05-15 08:11:31'),
(7, 12, 'Student', 'uploads/discount_ids/discount_12_1778957073.png', 'rejected', '', '2026-05-16 18:44:33', '2026-05-16 19:11:06'),
(8, 12, 'Student', 'uploads/discount_ids/discount_12_1778958691.png', 'approved', '', '2026-05-16 19:11:31', '2026-05-16 19:11:37'),
(9, 14, 'PWD', 'uploads/discount_ids/discount_14_1779022634.png', 'rejected', '', '2026-05-17 12:57:14', '2026-05-17 14:18:44'),
(10, 14, 'Senior Citizen', 'uploads/discount_ids/discount_14_1779028374.png', 'approved', '', '2026-05-17 14:32:54', '2026-05-17 14:38:01');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `initial_stock` int(11) NOT NULL DEFAULT 0,
  `remaining` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 10,
  `unit` varchar(30) NOT NULL DEFAULT 'pcs',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `initial_stock`, `remaining`, `low_stock_threshold`, `unit`, `updated_at`) VALUES
(1, 1, 50, 44, 10, 'pcs', '2026-05-17 12:56:28'),
(2, 2, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(3, 3, 50, 49, 10, 'pcs', '2026-05-17 13:00:21'),
(4, 4, 50, 50, 10, 'pcs', '2026-05-14 16:56:31'),
(5, 5, 50, 45, 10, 'pcs', '2026-05-18 17:22:46'),
(6, 6, 50, 49, 10, 'pcs', '2026-05-18 14:36:31'),
(7, 7, 50, 46, 10, 'pcs', '2026-05-17 14:11:20'),
(8, 8, 50, 44, 10, 'pcs', '2026-05-18 17:22:46'),
(9, 9, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(10, 10, 50, 50, 10, 'pcs', '2026-05-08 03:16:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `payment_method` enum('gcash','cod') NOT NULL,
  `gcash_proof` varchar(255) DEFAULT NULL,
  `gcash_reference` varchar(50) DEFAULT NULL,
  `branch` varchar(150) DEFAULT 'Chick Chicken - Amang Rodriguez Pasig',
  `status` enum('pending','confirmed','preparing','in_transit','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `discount_type` varchar(50) DEFAULT '',
  `discount_rate` decimal(5,2) DEFAULT 0.00,
  `original_total` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `email`, `address`, `payment_method`, `gcash_proof`, `gcash_reference`, `branch`, `status`, `created_at`, `discount_type`, `discount_rate`, `original_total`, `discount_amount`, `total`) VALUES
(19, 10, 'Robert Bayud', '+639201807155', 'r.jamesb.25@gmail.com', '175 P 23rd Ave', 'cod', NULL, NULL, 'Chick Chicken - Pasig', 'completed', '2026-05-15 08:13:19', '', 0.00, 1356.00, 0.00, 1356.00),
(20, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'OSMAK', 'gcash', 'uploads/gcash/20_1778956698.png', NULL, 'Chick Chicken - Makati', 'completed', '2026-05-16 18:38:18', '', 0.00, 1695.00, 0.00, 1695.00),
(21, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'awdasd', 'cod', NULL, NULL, 'Chick Chicken - Marikina', 'completed', '2026-05-16 18:54:58', '', 0.00, 319.00, 0.00, 319.00),
(22, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'OSMAK', 'cod', NULL, NULL, 'Chick Chicken - Makati', 'completed', '2026-05-16 19:11:55', 'Student', 0.10, 638.00, 0.00, 638.00),
(23, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'awdasd', 'cod', NULL, NULL, 'Chick Chicken - Pasig', 'completed', '2026-05-16 19:20:44', 'Student', 0.10, 678.00, 67.80, 610.20),
(24, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'awdasd', 'cod', NULL, NULL, 'Chick Chicken - Marikina', 'cancelled', '2026-05-16 19:39:21', 'Student', 0.10, 678.00, 67.80, 610.20),
(25, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'wasd', 'cod', NULL, NULL, 'Chick Chicken - Timog', 'pending', '2026-05-16 19:46:56', 'Student', 0.10, 319.00, 31.90, 287.10),
(26, 13, 'JERWIN CARMONA', '+639999999', 'jcarmona.0872@umak.edu.ph', '1832 Guadalupe Bliss Cembo Taguig City', 'cod', NULL, NULL, 'Chick Chicken - Makati', 'cancelled', '2026-05-17 07:13:49', '', 0.00, 339.00, 0.00, 339.00),
(27, 13, 'JERWIN CARMONA', '+639999999', 'jcarmona.0872@umak.edu.ph', '1832 Guadalupe Bliss Cembo Taguig City', 'cod', NULL, NULL, 'Chick Chicken - Makati', 'cancelled', '2026-05-17 07:50:42', '', 0.00, 319.00, 0.00, 319.00),
(28, 13, 'JERWIN CARMONA', '+639999999', 'jcarmona.0872@umak.edu.ph', '1832 Guadalupe Bliss Cembo Taguig City', 'cod', NULL, NULL, 'Chick Chicken - Makati', 'pending', '2026-05-17 08:01:36', '', 0.00, 319.00, 0.00, 319.00),
(29, 14, 'Stephanie Queypo', '+636767676767', 'stephanie@gmail.com', 'OSMAK', 'cod', NULL, NULL, 'Chick Chicken - Maginhawa, QC', 'cancelled', '2026-05-17 12:48:49', '', 0.00, 169.00, 0.00, 169.00),
(30, 14, 'Stephanie Queypo', '+636767676767', 'stephanie@gmail.com', 'OSMAK', 'cod', NULL, NULL, 'Chick Chicken - Maginhawa, QC', 'cancelled', '2026-05-17 12:56:28', '', 0.00, 1445.00, 0.00, 1445.00),
(31, 14, 'Stephanie Ganda', '+636767676767', 'stephanie@gmail.com', 'OSMAK', 'gcash', 'uploads/gcash/31_1779022821.png', NULL, 'Chick Chicken - Makati', 'cancelled', '2026-05-17 13:00:21', 'PWD', 0.20, 1147.00, 229.40, 917.60),
(32, 14, 'Stephanie Ganda', '+636767676767', 'stephanie@gmail.com', 'OSMAK', 'cod', NULL, NULL, 'Chick Chicken - Pasig', 'completed', '2026-05-17 13:11:26', 'PWD', 0.20, 339.00, 67.80, 271.20),
(33, 14, 'Stephanie Ganda', '+636767676767', 'stephanie@gmail.com', 'OSMAK', 'gcash', 'uploads/gcash/33_1779027080.png', NULL, 'Chick Chicken - Maginhawa, QC', 'cancelled', '2026-05-17 14:11:20', 'PWD', 0.20, 638.00, 127.60, 510.40),
(34, 14, 'Stephanie Ganda', '+636767676767', 'stephanie@gmail.com', 'OSMAK', 'gcash', 'uploads/gcash/34_1779027396.png', '1234567890123', 'Chick Chicken - Marikina', 'completed', '2026-05-17 14:16:35', 'PWD', 0.20, 459.00, 91.80, 367.20),
(35, 13, 'JERWIN CARMONA', '639999999', 'jcarmona.0872@umak.edu.ph', '1832 Guadalupe Bliss Cembo Taguig City', 'cod', NULL, NULL, 'Chick Chicken - Pasig', 'pending', '2026-05-18 12:22:29', '', 0.00, 319.00, 0.00, 319.00),
(36, 11, 'Klein Moretti', '4294967295', 'blasphemydarem@gmail.com', 'OSMAK', 'cod', NULL, NULL, 'Chick Chicken - Pasig', 'completed', '2026-05-18 14:36:31', '', 0.00, 309.00, 0.00, 309.00),
(37, 11, 'Klein Moretti', '4294967295', 'blasphemydarem@gmail.com', 'OSMAK', 'cod', NULL, NULL, 'Chick Chicken - Maginhawa, QC', 'cancelled', '2026-05-18 17:22:46', '', 0.00, 2255.00, 0.00, 2255.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `option_selected` varchar(255) DEFAULT NULL,
  `sauce` varchar(100) DEFAULT NULL,
  `extra_flavor` varchar(100) DEFAULT NULL,
  `mix_preference` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `option_selected`, `sauce`, `extra_flavor`, `mix_preference`, `price`) VALUES
(25, 19, 5, 4, 'Solo (600ml)', 'Garlic Mayo', 'Hot Buffalo', 'Mixed', 339.00),
(26, 20, 5, 5, 'Solo (600ml)', 'Garlic Mayo', 'Hot Buffalo', 'Separate', 339.00),
(27, 21, 8, 1, 'Double (1000ml)', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 319.00),
(28, 22, 8, 2, 'Double (1000ml)', 'Chick Sauce', 'Hot Buffalo', 'Separate', 319.00),
(29, 23, 5, 2, 'Double (1000ml)', 'Chick Sauce', 'Hot Buffalo', 'Separate', 339.00),
(30, 24, 5, 2, 'Solo (600ml)', 'Chick Sauce', 'Hot Buffalo', 'Separate', 339.00),
(31, 25, 8, 1, 'Double (1000ml)', 'Garlic Mayo', 'Hot Buffalo', 'Separate', 319.00),
(32, 26, 5, 1, 'Solo (600ml)', 'Chick Sauce', 'Salted Egg', 'Separate', 339.00),
(33, 27, 8, 1, 'Solo (600ml)', 'Cheese Sauce', '', 'Separate', 319.00),
(34, 28, 8, 1, 'Solo (600ml)', 'Chick Sauce', '', 'Separate', 319.00),
(35, 29, 1, 1, 'Double (1000ml) +₱100', 'Cheese Sauce', 'Salted Egg', 'Separate', 169.00),
(36, 30, 1, 5, 'Double (1000ml) +₱100', 'Cheese Sauce', 'Salted Egg', 'Separate', 289.00),
(37, 31, 7, 2, 'Double (1000ml) +₱100', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 419.00),
(38, 31, 3, 1, 'Double (1000ml) +₱100', 'Garlic Mayo', 'Hot Buffalo', 'Separate', 309.00),
(39, 32, 8, 1, 'Solo (600ml)', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 339.00),
(40, 33, 7, 2, 'Solo (600ml)', 'Chick Sauce', 'Hot Buffalo', 'Separate', 319.00),
(41, 34, 5, 1, 'Double (1000ml) +₱100', 'Chick Sauce', 'Salted Egg', 'Separate', 459.00),
(42, 35, 8, 1, '', '', '', '', 319.00),
(43, 36, 6, 1, 'Solo (600ml)', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 309.00),
(44, 37, 5, 3, 'Double (1000ml) +₱100', 'Chick Sauce', 'Salted Egg', 'Separate', 459.00),
(45, 37, 8, 2, 'Double (1000ml) +₱100', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 439.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `image`, `deleted_at`) VALUES
(1, 'Chick Rice', 169.00, 'mains', 'menuassets/Chick_Rice.png', NULL),
(2, 'Chick Fries', 169.00, 'mains', 'menuassets/Chick_Fries.png', NULL),
(3, 'Mac & Chick', 189.00, 'mains', 'menuassets/Mac_Chick.png', NULL),
(4, 'Additional Chicken Tender', 289.00, 'mains', 'menuassets/AddChickTender.png', NULL),
(5, 'Super Chick', 339.00, 'combos', 'menuassets/SuperChick.png', NULL),
(6, 'Chick One', 289.00, 'combos', 'menuassets/Chick_One.png', NULL),
(7, 'Chick Two', 299.00, 'combos', 'menuassets/Chick_Two.png', NULL),
(8, 'Chick Five', 319.00, 'combos', 'menuassets/Chick_Five.png', NULL),
(9, 'Extra Sauce', 40.00, 'sauces', 'menuassets/Sauce2.png', NULL),
(10, 'Jumbo Sauce (16oz)', 179.00, 'sauces', 'menuassets/Sauce16.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredients`
--

CREATE TABLE `product_ingredients` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity_used` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_ingredients`
--

INSERT INTO `product_ingredients` (`id`, `product_id`, `ingredient_id`, `quantity_used`) VALUES
(1, 8, 14, 5.00),
(2, 8, 19, 100.00),
(3, 8, 21, 500.00),
(4, 8, 20, 1.00),
(5, 8, 17, 4.00),
(6, 8, 15, 5.00);

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
(10, 'Whole Chicken', 'Chicken', 'kg', 100.00, 100.00, 40.00, 'Local Market', 'Keep Frozen', '2026-05-17 07:35:58', '2026-05-17 07:38:51'),
(11, 'Chicken Wings', 'Chicken', 'kg', 100.00, 100.00, 10.00, 'Local Market', 'Keep Frozen', '2026-05-17 07:35:58', '2026-05-17 07:39:22'),
(12, 'Drumsticks', 'Chicken', 'kg', 100.00, 100.00, 10.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-17 07:41:44'),
(13, 'Thighs', 'Chicken', 'kg', 100.00, 100.00, 10.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-17 07:41:48'),
(14, 'Breast Fillet', 'Chicken', 'kg', 100.00, 75.00, 10.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-18 17:22:46'),
(15, 'All-Purpose Flour', 'Breading / Coating', 'pack', 100.00, 75.00, 10.00, 'Local Market', NULL, '2026-05-17 07:35:58', '2026-05-18 17:22:46'),
(16, 'Cornstarch', 'Breading / Coating', 'pack', 500.00, 500.00, 100.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-17 07:42:46'),
(17, 'Breadcrumbs', 'Breading / Coating', 'pack', 100.00, 80.00, 20.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-18 17:22:46'),
(18, 'Baking Powder', 'Breading / Coating', 'pack', 100.00, 100.00, 20.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-17 07:37:05'),
(19, 'Spices', 'Spices', 'g', 1000.00, 500.00, 100.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-18 17:22:46'),
(20, 'Eggs', 'Wet Ingredients', 'box', 500.00, 495.00, 100.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-18 17:22:46'),
(21, 'Milk / Buttermilk', 'Wet Ingredients', 'ml', 10000.00, 8000.00, 100.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-18 17:22:46'),
(22, 'Water', 'Wet Ingredients', 'ml', 100000.00, 100000.00, 1000.00, NULL, NULL, '2026-05-17 07:35:58', '2026-05-17 07:40:38');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `rating`, `review_text`, `created_at`) VALUES
(7, 'Robert Bayud', 5, 'naisu!', '2026-05-15 16:13:28'),
(9, 'Stephanie Ganda', 5, 'Oks', '2026-05-17 21:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `discount_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone`, `email`, `password`, `discount_status`) VALUES
(10, 'Robert', 'Bayud', 4294967295, 'r.jamesb.25@gmail.com', '$2y$10$eXsmXalVCrse0u51SS.6dubJOFRPJ6MAS93LrQP6ajRMmMgX1qkdm', 'approved'),
(11, 'Klein', 'Moretti', 4294967295, 'blasphemydarem@gmail.com', '$2y$10$9mlHF/xSdOifXLs924BfYeKNhqg7Zv.BpcYGOfiEC2LHcUWcUjIjq', 'approved'),
(12, 'Escriba', 'John', 4294967295, 'chesterganongan@gmail.com', '$2y$10$OESnbO9cU4g7AC7ZFEOk6OtnseUEc4bqbn0W7pk.DO46td6OAgwKi', 'approved'),
(13, 'JERWIN', 'CARMONA', 639999999, 'jcarmona.0872@umak.edu.ph', '$2y$10$5ThFKkXuasZYgKBQ7SLNJ.TSOmpJmdAA/oI/FaU5Taj8EuvIbcWX2', 'none'),
(14, 'Stephanie', 'Ganda', 4294967295, 'stephanie@gmail.com', '$2y$10$mU9Cf1nvuUhdWCUt9KvIzezzlzhWxhpk.8zgwpT2gs48jlHXDFDcq', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `discount_applications`
--
ALTER TABLE `discount_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_product` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_prod_ing` (`product_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `raw_ingredients`
--
ALTER TABLE `raw_ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `discount_applications`
--
ALTER TABLE `discount_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `raw_ingredients`
--
ALTER TABLE `raw_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inv_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD CONSTRAINT `product_ingredients_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `raw_ingredients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
