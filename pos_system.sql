-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 08:42 AM
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
-- Database: `pos_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `category_code`) VALUES
(5, 'Rice Bowls', '2025-03-18 11:42:02', 'RIC'),
(6, 'Viands', '2025-03-18 11:43:07', 'VIA'),
(7, 'Beverages', '2025-03-18 11:43:16', 'BEV'),
(8, 'Desserts', '2025-03-18 11:43:22', 'DES');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL DEFAULT 1,
  `product_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_type` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `delivery_charge` decimal(10,2) DEFAULT 0.00,
  `change_due` decimal(10,2) DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `total`, `created_at`, `quantity`, `product_id`, `total_amount`, `order_type`, `payment_method`, `delivery_charge`, `change_due`, `user_id`) VALUES
(19, 0.00, '2025-04-05 13:08:09', 1, NULL, 50.00, 'dine-in', 'cash', 0.00, 61.00, 12),
(20, 0.00, '2025-04-05 13:52:41', 1, NULL, 180.00, 'dine-in', 'cash', 0.00, 20.00, 12),
(21, 0.00, '2025-04-05 13:53:11', 1, NULL, 180.00, 'dine-in', 'cash', 0.00, 20.00, 12),
(22, 0.00, '2025-04-05 14:06:55', 1, NULL, 180.00, 'dine-in', 'cash', 0.00, 70.00, 12),
(23, 0.00, '2025-04-05 14:07:34', 1, NULL, 270.00, 'delivery', 'cash', 90.00, 30.00, 12),
(24, 40.00, '2025-04-05 15:08:16', 2, 34, 40.00, '', '', 0.00, 0.00, 11),
(25, 20.00, '2025-04-05 15:29:53', 1, 34, 20.00, '', '', 0.00, 0.00, 11),
(26, 0.00, '2025-04-05 15:37:00', 1, NULL, 730.00, 'dine-in', 'cash', 0.00, 70.00, 12);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `discount`) VALUES
(1, 19, 48, 1, 50.00, 0.00),
(2, 20, 48, 1, 50.00, 0.00),
(3, 20, 36, 1, 30.00, 0.00),
(4, 20, 39, 2, 50.00, 0.00),
(5, 21, 48, 1, 50.00, 0.00),
(6, 21, 36, 1, 30.00, 0.00),
(7, 21, 39, 2, 50.00, 0.00),
(8, 22, 48, 1, 50.00, 0.00),
(9, 22, 36, 1, 30.00, 0.00),
(10, 22, 39, 2, 50.00, 0.00),
(11, 23, 48, 1, 50.00, 0.00),
(12, 23, 36, 1, 30.00, 0.00),
(13, 23, 39, 2, 50.00, 0.00),
(14, 24, 34, 2, 20.00, 0.00),
(15, 25, 34, 1, 20.00, 0.00),
(16, 26, 41, 1, 250.00, 0.00),
(17, 26, 40, 1, 150.00, 0.00),
(18, 26, 44, 1, 300.00, 0.00),
(19, 26, 36, 1, 30.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `amount`, `created_at`, `status`) VALUES
(2, 22, 'cash', 180.00, '2025-04-05 14:06:55', 'completed'),
(3, 23, 'cash', 270.00, '2025-04-05 14:07:34', 'completed'),
(4, 24, 'cash', 50.00, '2025-04-05 15:08:16', 'completed'),
(5, 25, 'cash', 20.00, '2025-04-05 15:29:53', 'completed'),
(6, 26, 'cash', 730.00, '2025-04-05 15:37:00', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_code` varchar(10) DEFAULT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `stock`, `barcode`, `created_at`, `category_code`, `category`) VALUES
(34, 'Plain Rice', 'One cup of plain white rice is perfect as a base for a variety of meals.', 20.00, 'Plain Rice.PNG', 0, '1001001001', '2025-03-29 15:09:47', 'RIC', ''),
(35, 'Java Rice', 'One cup of Java rice is a flavorful, golden-yellow fried rice infused with garlic, soy sauce, and annatto, making it a perfect savory companion to grilled or fried dishes.', 25.00, 'Java Rice.PNG', 9, '1001001002', '2025-03-29 15:10:34', 'RIC', ''),
(36, 'Egg Fried Rice', 'Rice cooked and fried with scrambled eggs, garlic, onions, and soy sauce. ', 30.00, 'Egg Fried Rice.PNG', 9, '1001001003', '2025-03-29 15:12:03', 'RIC', ''),
(37, 'Sinangag', 'Fried rice sautéed with garlic, Good for 2 - 3 persons', 55.00, 'Sinangag.JPG', 9, '1001001004', '2025-03-29 15:12:47', 'RIC', ''),
(38, 'Pastil (Chicken)', 'Steamed rice topped with shredded, seasoned chicken wrapped in banana leaves.', 50.00, 'Pastil(Chicken).JPG', 9, '1001001005', '2025-03-29 15:13:25', 'RIC', ''),
(39, 'Pastil (Beef)', 'Steamed rice topped with shredded, seasoned beef wrapped in banana leaves.', 50.00, 'Pastil(Beef) (1).JPG', 10, '1001001006', '2025-03-29 15:13:55', 'RIC', ''),
(40, 'Adobo (Chicken/Pork)', 'Meat stewed in vinegar, soy sauce, garlic, bay leaves, and peppercorns — a Filipino staple.', 150.00, 'Adobo.JPG', 10, '2002002001', '2025-03-29 15:16:40', 'VIA', ''),
(41, 'Sinigang', ' A tamarind-based sour soup loaded with pork, shrimp, or beef, and assorted vegetables.', 250.00, 'Sinigang.JPG', 10, '2002002002', '2025-03-29 15:17:14', 'VIA', ''),
(42, 'Kare-Kare', 'A rich, peanut-based stew with oxtail, tripe, vegetables, and a side of bagoong (shrimp paste).', 250.00, 'Kare-Kare.JPG', 10, '2002002003', '2025-03-29 15:17:54', 'VIA', ''),
(43, 'Bicol Express', 'Spicy stew made with pork, coconut milk, shrimp paste, and plenty of chilies.', 300.00, 'Bicol Express.JPG', 10, '2002002004', '2025-03-29 15:18:38', 'VIA', ''),
(44, 'Pinakbet', 'A hearty dish of vegetables like squash, eggplant, and okra, flavored with bagoong.', 300.00, 'Pinakbet.JPG', 10, '2002002005', '2025-03-29 15:19:14', 'VIA', ''),
(45, 'Dinuguan', 'Savory pork stew simmered in a dark, rich gravy made from pork blood and vinegar.', 300.00, 'Dinuguan.JPG', 10, '2002002006', '2025-03-29 15:19:51', 'VIA', ''),
(46, 'Inihaw na Liempo', 'Marinated pork belly grilled until smoky and caramelized, best paired with vinegar dip.', 350.00, 'Inihaw na Liempo.JPG', 10, '2002002007', '2025-03-29 15:20:24', 'VIA', ''),
(47, 'Pancit Bihon', 'Thin rice noodles stir-fried with pork, shrimp, and mixed vegetables.', 250.00, 'Pancit Bihon.JPG', 10, '2002002008', '2025-03-29 15:20:57', 'VIA', ''),
(48, 'Salabat', 'Warm, soothing ginger tea, perfect for colds or chilly mornings.', 50.00, 'Salabat (Ginger Tea).JPG', 10, '3003003001', '2025-03-29 15:25:12', 'BEV', ''),
(49, 'Buko Juice', 'Fresh coconut water served straight from the shell — hydrating and mildly sweet.', 55.00, 'Buko Juice.JPG', 10, '3003003002', '2025-03-29 15:25:50', 'BEV', ''),
(50, 'Calamansi Juice', 'Zesty, citrusy drink made from freshly squeezed calamansi limes. ', 50.00, 'Calamansi Juice.JPG', 10, '3003003003', '2025-03-29 15:26:33', 'BEV', ''),
(51, 'Ube Samalamig', 'Cold, flavored drinks made with fruits, jelly, or tapioca pearls.', 55.00, 'Ube Samalamig.JPG', 10, '3003003004', '2025-03-29 15:27:05', 'BEV', ''),
(52, 'Barako Coffee', 'Bold, strong coffee made from Liberica beans — a Batangas specialty.', 55.00, 'Barako Coffee.JPG', 10, '3003003005', '2025-03-29 15:27:34', 'BEV', ''),
(53, 'Pandan Juice', 'Sweet, fragrant juice infused with pandan leaves.', 50.00, 'Pandan Juice.JPG', 10, '3003003006', '2025-03-29 15:28:16', 'BEV', ''),
(54, 'Melon Juice', 'Refreshing cantaloupe juice with thin melon shreds.', 50.00, 'Melon Juice.JPG', 10, '3003003007', '2025-03-29 15:28:57', 'BEV', ''),
(55, 'Coca-Cola Canned', '325ml', 25.00, 'Coca-Cola.JPG', 10, '3003003008', '2025-03-29 15:29:36', 'BEV', ''),
(56, 'Royal', '325ml', 25.00, 'Royal.JPG', 10, '3003003009', '2025-03-29 15:30:05', 'BEV', ''),
(57, 'Leche Flan', 'Smooth, creamy custard topped with rich caramel syrup.', 50.00, 'Leche Flan.JPG', 10, '4004004001', '2025-03-29 15:30:33', 'DES', ''),
(58, 'Halo-Halo', 'A colorful mix of shaved ice, sweet beans, jellies, fruits, leche flan, and purple yam.', 50.00, 'Halo-Halo.JPG', 10, '4004004002', '2025-03-29 15:31:19', 'DES', ''),
(59, 'Cassava Cake', 'Chewy, coconut-flavored cake made from grated cassava root.', 60.00, 'Cassava Cake.JPG', 10, '4004004003', '2025-03-29 15:31:51', 'DES', ''),
(60, 'Brazo de Mercedes', 'Soft meringue roll filled with rich, custard-like yema filling.', 50.00, 'Brazo de Mercedes.JPG', 10, '4004004004', '2025-03-29 15:32:23', 'DES', ''),
(61, 'Yema', 'Sweet, chewy custard candies wrapped in bright cellophane.', 50.00, 'Yema.JPG', 10, '4004004005', '2025-03-29 15:32:55', 'DES', ''),
(62, 'Ube Halaya', 'Smooth, rich purple yam jam — a holiday favorite.', 50.00, 'Ube Halaya.JPG', 10, '400400407', '2025-03-29 15:34:29', 'DES', '');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `amount`, `created_at`, `quantity`) VALUES
(1, 48, 50.00, '2025-04-05 14:06:55', 1),
(2, 36, 30.00, '2025-04-05 14:06:55', 1),
(3, 39, 50.00, '2025-04-05 14:06:55', 2),
(4, 48, 50.00, '2025-04-05 14:07:34', 1),
(5, 36, 30.00, '2025-04-05 14:07:34', 1),
(6, 39, 50.00, '2025-04-05 14:07:34', 2),
(7, 34, 40.00, '2025-04-05 15:08:16', 2),
(8, 34, 20.00, '2025-04-05 15:29:53', 1),
(9, 41, 250.00, '2025-04-05 15:37:00', 1),
(10, 40, 150.00, '2025-04-05 15:37:00', 1),
(11, 44, 300.00, '2025-04-05 15:37:00', 1),
(12, 36, 30.00, '2025-04-05 15:37:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `role` enum('user','admin','super_admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `profile_pic`, `role`, `created_at`) VALUES
(10, 'superadmin', 'superadmin123', '', NULL, NULL, 'super_admin', '2025-03-15 16:23:36'),
(11, 'admin', 'admin123', 'admin@gmail.com', NULL, NULL, 'admin', '2025-03-18 06:10:01'),
(12, 'john12', '$2y$10$ARYKCcHZBHf3iNQOhukdgugXVjuM70dkj6WSPQVDh7uFQDEpg2tJO', 'johnmarlon212@gmail.com', '13123123123213', 'C:\\xampp\\htdocs\\POS/Super_Admin/uploads/profilepic/Picsart_24-05-16_14-54-37-374.jpg', 'user', '2025-03-18 07:46:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_code` (`category_code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_products` (`product_id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `fk_category_code` (`category_code`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_product` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category_code` FOREIGN KEY (`category_code`) REFERENCES `categories` (`category_code`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
