-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2026 at 10:02 PM
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
(8, 12, 'Student', 'uploads/discount_ids/discount_12_1778958691.png', 'approved', '', '2026-05-16 19:11:31', '2026-05-16 19:11:37');

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
(1, 1, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(2, 2, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(3, 3, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(4, 4, 50, 50, 10, 'pcs', '2026-05-14 16:56:31'),
(5, 5, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(6, 6, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(7, 7, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
(8, 8, 50, 50, 10, 'pcs', '2026-05-08 03:16:58'),
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
  `branch` varchar(150) DEFAULT 'Chick Chicken - Amang Rodriguez Pasig',
  `status` enum('pending','confirmed','cooking','in_transit','completed','cancelled') NOT NULL DEFAULT 'pending',
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

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `email`, `address`, `payment_method`, `gcash_proof`, `branch`, `status`, `created_at`, `discount_type`, `discount_rate`, `original_total`, `discount_amount`, `total`) VALUES
(19, 10, 'Robert Bayud', '+639201807155', 'r.jamesb.25@gmail.com', '175 P 23rd Ave', 'cod', NULL, 'Chick Chicken - Pasig', 'completed', '2026-05-15 08:13:19', '', 0.00, 1356.00, 0.00, 1356.00),
(20, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'OSMAK', 'gcash', 'uploads/gcash/20_1778956698.png', 'Chick Chicken - Makati', 'completed', '2026-05-16 18:38:18', '', 0.00, 1695.00, 0.00, 1695.00),
(21, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'awdasd', 'cod', NULL, 'Chick Chicken - Marikina', 'completed', '2026-05-16 18:54:58', '', 0.00, 319.00, 0.00, 319.00),
(22, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'OSMAK', 'cod', NULL, 'Chick Chicken - Makati', 'completed', '2026-05-16 19:11:55', 'Student', 0.10, 638.00, 0.00, 638.00),
(23, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'awdasd', 'cod', NULL, 'Chick Chicken - Pasig', 'completed', '2026-05-16 19:20:44', 'Student', 0.10, 678.00, 67.80, 610.20),
(24, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'awdasd', 'cod', NULL, 'Chick Chicken - Marikina', 'cancelled', '2026-05-16 19:39:21', 'Student', 0.10, 678.00, 67.80, 610.20),
(25, 12, 'Escriba John', '+636767676767', 'chesterganongan@gmail.com', 'wasd', 'cod', NULL, 'Chick Chicken - Timog', 'pending', '2026-05-16 19:46:56', 'Student', 0.10, 319.00, 31.90, 287.10);

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
(31, 25, 8, 1, 'Double (1000ml)', 'Garlic Mayo', 'Hot Buffalo', 'Separate', 319.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `image`) VALUES
(1, 'Chick Rice', 169.00, 'mains', 'menuassets/Chick_Rice.png'),
(2, 'Chick Fries', 169.00, 'mains', 'menuassets/Chick_Fries.png'),
(3, 'Mac & Chick', 189.00, 'mains', 'menuassets/Mac_Chick.png'),
(4, 'Additional Chicken Tender', 289.00, 'mains', 'menuassets/AddChickTender.png'),
(5, 'Super Chick', 339.00, 'combos', 'menuassets/SuperChick.png'),
(6, 'Chick One', 289.00, 'combos', 'menuassets/Chick_One.png'),
(7, 'Chick Two', 299.00, 'combos', 'menuassets/Chick_Two.png'),
(8, 'Chick Five', 319.00, 'combos', 'menuassets/Chick_Five.png'),
(9, 'Extra Sauce', 40.00, 'sauces', 'menuassets/Sauce2.png'),
(10, 'Jumbo Sauce (16oz)', 179.00, 'sauces', 'menuassets/Sauce16.png');

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
(7, 'Robert Bayud', 5, 'naisu!', '2026-05-15 16:13:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `discount_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone`, `email`, `password`, `discount_status`) VALUES
(10, 'Robert', 'Bayud', '+639201807155', 'r.jamesb.25@gmail.com', '$2y$10$eXsmXalVCrse0u51SS.6dubJOFRPJ6MAS93LrQP6ajRMmMgX1qkdm', 'approved'),
(11, 'Klein', 'Moretti', '+639922634538', 'blasphemydarem@gmail.com', '$2y$10$9mlHF/xSdOifXLs924BfYeKNhqg7Zv.BpcYGOfiEC2LHcUWcUjIjq', 'approved'),
(12, 'Escriba', 'John', '+636767676767', 'chesterganongan@gmail.com', '$2y$10$OESnbO9cU4g7AC7ZFEOk6OtnseUEc4bqbn0W7pk.DO46td6OAgwKi', 'approved');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `discount_applications`
--
ALTER TABLE `discount_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `raw_ingredients`
--
ALTER TABLE `raw_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
