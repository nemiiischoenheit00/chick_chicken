-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 04:53 AM
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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `option_selected`, `sauce`, `extra_flavor`, `mix_preference`, `created_at`) VALUES
(32, 7, 1, 1, 'Solo (600ml)', 'Garlic Mayo', 'Hot Buffalo', 'Separate', '2026-04-17 02:46:04');

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
  `payment_method` enum('online','cod') NOT NULL,
  `card_number` varchar(19) DEFAULT NULL,
  `branch` varchar(150) DEFAULT 'Chick Chicken - Amang Rodriguez Pasig',
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `email`, `address`, `payment_method`, `card_number`, `branch`, `status`, `created_at`) VALUES
(1, 7, 'DeltaDarems', '', '', '', 'online', '1231231231231231', 'Chick Chicken - Amang Rodriguez Pasig', 'pending', '2026-04-16 15:47:48'),
(2, 7, 'DeltaDarems', '', '', '', 'online', '1231231231231231', 'Chick Chicken - Amang Rodriguez Pasig', 'pending', '2026-04-16 16:01:40'),
(3, 7, 'DeltaDarems', '123123', 'blasphemydarem@gmail.com', 'awdasd', 'online', '1231231231231231', 'Chick Chicken - Amang Rodriguez Pasig', 'pending', '2026-04-16 16:21:03');

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
(1, 1, 3, 1, 'Double (1000ml)', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 189.00),
(2, 1, 3, 2, 'Double (1000ml)', 'Garlic Mayo', 'Hot Buffalo', 'Separate', 189.00),
(3, 1, 4, 1, 'Double (1000ml)', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 289.00),
(4, 2, 4, 2, 'Double (1000ml)', 'Cheese Sauce', 'Hot Buffalo', 'Separate', 289.00),
(5, 3, 3, 1, 'Double (1000ml)', 'Garlic Mayo', 'Hot Buffalo', 'Separate', 189.00);

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
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(3, 'admin', 'admin@gmail.com', 'admin'),
(5, 'Chester Louis', 'was@gmail.com', '$2y$10$pcpA9o4mx3ImUZ2/0zZg1esdD9FIJjykD681eJc0vzTSntzfQFTpe'),
(7, 'Stephanie', 'blasphemydarem@gmail.com', '$2y$10$dO4NKtmPpm4Z7aa5GykPyONcwUxp/ORr9iAdZwPRkTrgVEgKGSRYK');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
