-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 03:36 AM
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
  `category_code` varchar(50) NOT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `category_code`, `img`) VALUES
(5, 'Rice', '2025-03-18 11:42:02', 'RIC', 'indexpic/riceicon.jpg'),
(6, 'Viands', '2025-03-18 11:43:07', 'VIA', 'indexpic/viandsicon.jpg'),
(7, 'Beverages', '2025-03-18 11:43:16', 'BEV', 'indexpic/beveragesicon.jpg'),
(8, 'Desserts', '2025-03-18 11:43:22', 'DES', 'indexpic/dessertsicon.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

CREATE TABLE `delivery_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `city` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `street_address` text NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_addresses`
--

INSERT INTO `delivery_addresses` (`id`, `user_id`, `order_id`, `full_name`, `phone_number`, `city`, `barangay`, `street_address`, `landmark`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 12, NULL, 'Jamaica Sohon', '09913598226', 'Liloan', 'Mulao', 'Milamil', NULL, 0, '2025-04-08 07:13:36', '2025-04-08 07:13:36'),
(2, 12, NULL, 'Juju Bobi', '09924610910', 'Liloan', 'Mulao', 'St Bernard', NULL, 0, '2025-04-08 07:19:14', '2025-04-08 07:19:14'),
(3, 12, NULL, 'Test3', '09924610910', 'Compostela', 'Mulao', 'Bobibobit', NULL, 0, '2025-04-08 07:34:04', '2025-04-08 07:34:04'),
(4, 12, NULL, 'Test4', '12345678910', 'Liloan', 'Mulao', 'sisi', NULL, 0, '2025-04-08 07:58:09', '2025-04-08 07:58:09'),
(5, 12, NULL, 'test5', '12345678910', 'Carmen', 'D', 'Lilo', NULL, 0, '2025-04-08 08:03:06', '2025-04-08 08:03:06'),
(6, 12, NULL, 'test6', '09913598226', 'Carmen', 'Ca', 'Bu', NULL, 0, '2025-04-08 08:24:09', '2025-04-08 08:24:09'),
(7, 12, NULL, 'Test7', '09913598226', 'Carmen', 'a', 'b', NULL, 0, '2025-04-08 08:30:05', '2025-04-08 08:30:05'),
(9, 12, NULL, 'Test7', '09913598226', 'Liloan', 'SanRoque', 'Sisi', NULL, 0, '2025-04-08 08:33:50', '2025-04-08 08:33:50'),
(10, 12, NULL, 'Jul', '09913598226', 'Catmon', 'Sela', 'Soli', NULL, 0, '2025-04-08 08:37:12', '2025-04-08 08:37:12'),
(11, 12, NULL, 'Jul', '09913598226', 'Carmen', 'Sela', 'Soli', NULL, 0, '2025-04-08 08:38:51', '2025-04-08 08:38:51'),
(12, 12, NULL, 'Test8', '09913598226', 'Mandaue', 'San', 'Roq', NULL, 0, '2025-04-08 08:41:37', '2025-04-08 08:41:37'),
(13, 12, NULL, 'Test8', '09913598226', 'Carmen', 'San', 'Roq', NULL, 0, '2025-04-08 08:41:49', '2025-04-08 08:41:49'),
(14, 12, NULL, 'Test8', '09913598226', 'Liloan', 'San', 'Roq', NULL, 0, '2025-04-08 08:42:47', '2025-04-08 08:42:47'),
(15, 12, NULL, 'Test9', '09913598226', 'Catmon', 'Mulao', 'San', NULL, 0, '2025-04-08 08:48:28', '2025-04-08 08:48:28'),
(16, 12, NULL, 'test10', '09913598226', 'Compostela', 'Estaca', 'Bay', NULL, 0, '2025-04-08 08:55:34', '2025-04-08 08:55:34'),
(17, 12, NULL, 'Test11', '09913598226', 'Catmon', 'Ca', 'D', NULL, 0, '2025-04-08 09:03:35', '2025-04-08 09:03:35'),
(22, 12, NULL, 'Test8', '09913598226', 'Carmen', 'Juju', 'jeje', NULL, 0, '2025-04-08 09:07:04', '2025-04-08 09:07:04'),
(25, 12, NULL, 'Test11', '09913598226', 'Catmon', 'Sola', 'Sela', NULL, 0, '2025-04-08 09:15:14', '2025-04-08 09:15:14'),
(28, 12, NULL, 'Test8', '09913598226', 'Liloan', 'Mulao', 'ggggg', NULL, 0, '2025-04-08 09:17:05', '2025-04-08 09:17:05'),
(29, 12, NULL, 'jam s', '09913598226', 'Compostela', 'Mulao', 'g', NULL, 0, '2025-04-08 09:24:50', '2025-04-08 09:24:50'),
(30, 12, NULL, 'Test8', '09913598226', 'Liloan', 'Mulao', 'A', NULL, 0, '2025-04-08 09:36:20', '2025-04-08 09:36:20'),
(31, 12, NULL, 'Test8', '09913598226', 'Carmen', 'aaa', 'aaa', NULL, 0, '2025-04-08 09:37:17', '2025-04-08 09:37:17'),
(32, 12, NULL, 'Test8', '09913598226', 'Liloan', 'Mulao', 'aaa', NULL, 0, '2025-04-08 09:41:41', '2025-04-08 09:41:41'),
(33, 12, NULL, 'Test8', '09913598226', 'Catmon', 'Mulao', 'aa', NULL, 0, '2025-04-08 09:43:03', '2025-04-08 09:43:03');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `current_stock` decimal(10,2) DEFAULT 0.00,
  `reorder_level` decimal(10,2) DEFAULT 0.00,
  `cost_price` decimal(10,2) DEFAULT 0.00,
  `supplier_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `item_name`, `category`, `unit`, `current_stock`, `reorder_level`, `cost_price`, `supplier_id`, `updated_at`, `last_updated`) VALUES
(2, 'Rice', 'Rice', '5kg', 5.00, 0.00, 500.00, 0, NULL, '2025-04-09 23:12:36'),
(3, 'Garlic', 'Lamas', '5pcs', 10.00, 0.00, 5.00, 0, NULL, '2025-04-09 23:13:06'),
(5, 'Onion', 'Lamas', '10pcs', 5.00, 0.00, 5.00, 0, NULL, '2025-04-10 00:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_purchases`
--

CREATE TABLE `inventory_purchases` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `purchase_date` date DEFAULT curdate(),
  `supplier_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `transaction_type` enum('purchase','sale','adjustment','recipe_usage') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_usage`
--

CREATE TABLE `inventory_usage` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity_used` decimal(10,2) NOT NULL,
  `used_for` varchar(100) DEFAULT NULL,
  `date_used` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL DEFAULT 1,
  `product_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_type` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `delivery_charge` decimal(10,2) DEFAULT 0.00,
  `change_due` decimal(10,2) DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `delivery_fee` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `discount_type` varchar(50) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `created_at`, `quantity`, `product_id`, `total_amount`, `order_type`, `payment_method`, `delivery_charge`, `change_due`, `user_id`, `delivery_address`, `delivery_fee`, `subtotal`, `payment_reference`, `payment_proof`, `discount_type`, `total`) VALUES
(19, '2025-04-05 13:08:09', 1, NULL, 50.00, 'dine-in', 'cash', 0.00, 61.00, 12, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(20, '2025-04-05 13:52:41', 1, NULL, 180.00, 'dine-in', 'cash', 0.00, 20.00, 12, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(21, '2025-04-05 13:53:11', 1, NULL, 180.00, 'dine-in', 'cash', 0.00, 20.00, 12, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(22, '2025-04-05 14:06:55', 1, NULL, 180.00, 'dine-in', 'cash', 0.00, 70.00, 12, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(23, '2025-04-05 14:07:34', 1, NULL, 270.00, 'delivery', 'cash', 90.00, 30.00, 12, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(24, '2025-04-05 15:08:16', 2, 34, 40.00, '', '', 0.00, 0.00, 11, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(25, '2025-04-05 15:29:53', 1, 34, 20.00, '', '', 0.00, 0.00, 11, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(26, '2025-04-05 15:37:00', 1, NULL, 730.00, 'dine-in', 'cash', 0.00, 70.00, 12, NULL, 0.00, 0.00, NULL, NULL, NULL, NULL),
(27, '2025-04-08 13:51:25', 1, NULL, 70.00, NULL, 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 30.00, '', NULL, NULL, NULL),
(28, '2025-04-08 13:52:38', 1, NULL, 70.00, NULL, 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 30.00, '', NULL, NULL, NULL),
(29, '2025-04-08 13:53:44', 1, NULL, 265.00, NULL, 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 225.00, '', NULL, NULL, NULL),
(30, '2025-04-08 14:02:44', 1, NULL, 540.00, NULL, 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09913598226\",\"city\":\"Catmon\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 500.00, '09694120592', NULL, NULL, NULL),
(31, '2025-04-08 14:09:42', 1, NULL, 540.00, NULL, 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09913598226\",\"city\":\"Catmon\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 500.00, '09694120592', NULL, NULL, NULL),
(32, '2025-04-08 14:10:39', 1, NULL, 125.00, NULL, 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"San\",\"streetAddress\":\"aa\"}', 40.00, 85.00, '1111111111111', NULL, NULL, NULL),
(33, '2025-04-08 14:19:43', 1, NULL, 105.00, NULL, 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Compostela\",\"barangay\":\"San\",\"streetAddress\":\"aa\"}', 20.00, 85.00, '2222222222222', NULL, NULL, NULL),
(34, '2025-04-08 14:34:27', 1, NULL, 105.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Compostela\",\"barangay\":\"San\",\"streetAddress\":\"aa\"}', 20.00, 85.00, '2222222222222', NULL, NULL, NULL),
(35, '2025-04-08 14:48:00', 1, NULL, 95.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 55.00, '3333333333333', NULL, NULL, NULL),
(36, '2025-04-08 14:57:42', 1, NULL, 200.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"aaaa\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 160.00, '4444444444444', NULL, NULL, NULL),
(37, '2025-04-08 15:02:23', 1, NULL, 200.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"aaaa\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 160.00, '4444444444444', NULL, NULL, NULL),
(38, '2025-04-08 15:03:00', 1, NULL, 105.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"aaaa\",\"streetAddress\":\"a\"}', 20.00, 85.00, '6666666666666', NULL, NULL, NULL),
(39, '2025-04-08 15:15:52', 1, NULL, 125.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09924610910\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 85.00, '7777777777777', NULL, NULL, NULL),
(40, '2025-04-08 15:16:20', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"aaaa\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"a\"}', 20.00, 55.00, '9999999999999', NULL, NULL, NULL),
(41, '2025-04-08 15:17:42', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"aaaa\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"a\"}', 20.00, 55.00, '9999999999999', NULL, NULL, NULL),
(42, '2025-04-08 15:17:51', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"aaaa\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"a\"}', 20.00, 55.00, '9999999999999', NULL, NULL, NULL),
(43, '2025-04-08 15:18:00', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"aaaa\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"a\"}', 20.00, 55.00, '9999999999999', NULL, NULL, NULL),
(44, '2025-04-09 07:24:10', 1, NULL, 170.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09913598226\",\"city\":\"Catmon\",\"barangay\":\"Mulao\",\"streetAddress\":\"Sis\"}', 40.00, 130.00, '1010101010101', NULL, NULL, NULL),
(45, '2025-04-09 07:38:40', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 20.00, 55.00, '888888888888', NULL, NULL, NULL),
(46, '2025-04-09 07:45:43', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 20.00, 55.00, '888888888888', NULL, NULL, NULL),
(47, '2025-04-09 07:46:18', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Compostela\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 20.00, 55.00, '0000000000000', NULL, NULL, NULL),
(48, '2025-04-09 07:48:31', 1, NULL, 95.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 55.00, '1212121212121', NULL, NULL, NULL),
(49, '2025-04-09 07:50:55', 1, NULL, 145.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"San\",\"streetAddress\":\"a\"}', 40.00, 105.00, '2323232323232', NULL, NULL, NULL),
(50, '2025-04-09 07:53:00', 1, NULL, 70.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 30.00, '1251251251254', NULL, NULL, NULL),
(51, '2025-04-09 07:57:23', 1, NULL, 95.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"bleh\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 55.00, '8787878787878', NULL, NULL, NULL),
(52, '2025-04-09 07:59:24', 1, NULL, 95.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"bluh\",\"phoneNumber\":\"09913598226\",\"city\":\"Catmon\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 40.00, 55.00, '9898989898989', NULL, NULL, NULL),
(53, '2025-04-09 08:03:34', 1, NULL, 290.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09924610910\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 250.00, '8585858585858', NULL, NULL, NULL),
(54, '2025-04-09 08:15:05', 1, NULL, 290.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09924610910\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 250.00, '8585858585858', NULL, NULL, NULL),
(55, '2025-04-09 08:16:08', 1, NULL, 70.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 30.00, '6565656565656', NULL, NULL, NULL),
(56, '2025-04-09 08:19:36', 1, NULL, 70.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 30.00, '6565656565656', NULL, NULL, NULL),
(57, '2025-04-09 08:20:07', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 20.00, 55.00, '2525252525252', NULL, NULL, NULL),
(58, '2025-04-09 08:20:31', 1, NULL, 75.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 20.00, 55.00, '2525252525252', NULL, NULL, NULL),
(59, '2025-04-09 08:20:59', 1, NULL, 75.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"erase\",\"phoneNumber\":\"09924610910\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 20.00, 55.00, '', NULL, NULL, NULL),
(60, '2025-04-09 08:38:50', 1, NULL, 105.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 20.00, 85.00, '', NULL, NULL, NULL),
(61, '2025-04-09 09:01:00', 1, NULL, 560.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Test7\",\"phoneNumber\":\"09913598226\",\"city\":\"Catmon\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 520.00, '', NULL, NULL, NULL),
(62, '2025-04-09 09:09:19', 1, NULL, 70.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Catmon\",\"barangay\":\"San\",\"streetAddress\":\"aaaaa\"}', 40.00, 30.00, '', NULL, NULL, NULL),
(63, '2025-04-09 09:10:57', 1, NULL, 280.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09924610910\",\"city\":\"Catmon\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 240.00, '', NULL, NULL, NULL),
(64, '2025-04-09 09:55:38', 1, NULL, 280.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09924610910\",\"city\":\"Catmon\",\"barangay\":\"Mulao\",\"streetAddress\":\"aaa\"}', 40.00, 240.00, '', NULL, NULL, NULL),
(65, '2025-04-09 09:57:58', 1, NULL, 95.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Test8\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"a\"}', 20.00, 75.00, '', NULL, NULL, NULL),
(66, '2025-04-09 10:12:37', 1, NULL, 190.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09924610910\",\"city\":\"Liloan\",\"barangay\":\"Mulao\",\"streetAddress\":\"a\"}', 40.00, 150.00, '77777777777777777', NULL, NULL, NULL),
(67, '2025-04-09 13:49:34', 1, NULL, 90.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 50.00, '', NULL, NULL, NULL),
(68, '2025-04-09 13:52:47', 1, NULL, 140.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"a\"}', 40.00, 100.00, '', NULL, NULL, NULL),
(69, '2025-04-09 13:53:31', 1, NULL, 440.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aaa\"}', 40.00, 400.00, '2582582582582', NULL, NULL, NULL),
(70, '2025-04-09 14:55:01', 1, NULL, 145.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"aa\",\"streetAddress\":\"a\"}', 40.00, 105.00, '', NULL, NULL, NULL),
(71, '2025-04-09 15:04:40', 1, NULL, 90.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"aa\",\"streetAddress\":\"aa\"}', 40.00, 50.00, '', NULL, NULL, NULL),
(72, '2025-04-09 15:05:22', 1, NULL, 90.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"aa\",\"streetAddress\":\"aa\"}', 40.00, 50.00, '', NULL, NULL, NULL),
(73, '2025-04-09 15:05:41', 1, NULL, 90.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aaa\"}', 40.00, 50.00, '', NULL, NULL, NULL),
(74, '2025-04-09 15:17:30', 1, NULL, 90.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aaa\"}', 40.00, 50.00, '', NULL, NULL, NULL),
(75, '2025-04-09 15:18:24', 1, NULL, 90.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aaa\"}', 40.00, 50.00, '', NULL, NULL, NULL),
(76, '2025-04-09 15:19:20', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(77, '2025-04-09 15:19:32', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(78, '2025-04-09 15:20:48', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(79, '2025-04-09 15:21:21', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(80, '2025-04-09 15:21:34', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(81, '2025-04-09 15:22:11', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(82, '2025-04-09 15:23:20', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(83, '2025-04-09 15:24:00', 1, NULL, 340.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Liloan\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 300.00, '1234567891025', NULL, NULL, NULL),
(84, '2025-04-09 15:32:11', 1, NULL, 430.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"John Marlon\",\"phoneNumber\":\"09123456789\",\"city\":\"Carmen\",\"barangay\":\"Cogon West\",\"streetAddress\":\"St Martin\"}', 20.00, 410.00, '1234567891011', NULL, NULL, NULL),
(85, '2025-04-09 15:42:28', 1, NULL, 405.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"gg\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"SanR\",\"streetAddress\":\"a\"}', 20.00, 385.00, '', NULL, NULL, NULL),
(86, '2025-04-09 15:56:04', 1, NULL, 2520.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"John Marlon\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 20.00, 2500.00, '', NULL, NULL, NULL),
(87, '2025-04-09 16:00:38', 1, NULL, 1270.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"John Marlon\",\"phoneNumber\":\"09123456789\",\"city\":\"Carmen\",\"barangay\":\"aa\",\"streetAddress\":\"aa\"}', 20.00, 1250.00, '', NULL, NULL, NULL),
(88, '2025-04-09 16:10:39', 1, NULL, 520.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"John Marlon\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 20.00, 500.00, '', NULL, NULL, NULL),
(89, '2025-04-09 17:01:41', 1, NULL, 290.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"John Marlon\",\"phoneNumber\":\"09913598226\",\"city\":\"Catmon\",\"barangay\":\"SanR\",\"streetAddress\":\"aa\"}', 40.00, 250.00, '', NULL, NULL, NULL),
(90, '2025-04-09 17:02:30', 1, NULL, 620.00, 'delivery', 'gcash', 0.00, 0.00, 12, '{\"fullName\":\"John Marlon\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"SanR\",\"streetAddress\":\"a\"}', 20.00, 600.00, '1234567891012', NULL, NULL, NULL),
(91, '2025-04-09 17:28:15', 1, NULL, 1520.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"John Marlon\",\"phoneNumber\":\"09123456789\",\"city\":\"Carmen\",\"barangay\":\"SanR\",\"streetAddress\":\"a\"}', 20.00, 1500.00, '', NULL, NULL, NULL),
(92, '2025-04-10 01:12:11', 1, 43, 300.00, 'dine-in', 'cash', 0.00, 36.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 300.00),
(93, '2025-04-10 01:16:14', 3, NULL, 900.00, 'dine-in', 'cash', 0.00, 108.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 900.00),
(94, '2025-04-10 01:18:15', 4, 43, 1200.00, 'dine-in', 'cash', 0.00, 12800.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 1200.00),
(95, '2025-04-10 01:39:14', 2, 57, 100.00, 'dine-in', 'cash', 0.00, 50.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 100.00),
(96, '2025-04-10 01:40:21', 2, 44, 600.00, 'dine-in', 'cash', 0.00, -62.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 600.00),
(97, '2025-04-10 01:42:23', 1, 44, 300.00, 'dine-in', 'cash', 0.00, 37.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 300.00),
(98, '2025-04-10 02:19:33', 1, NULL, 1520.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09924610910\",\"city\":\"Carmen\",\"barangay\":\"San\",\"streetAddress\":\"aaa\"}', 20.00, 1500.00, '', NULL, NULL, NULL),
(99, '2025-04-10 02:21:05', 8, 45, 2400.00, 'dine-in', 'cash', 0.00, 288.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 2400.00),
(100, '2025-04-10 05:43:11', 1, NULL, 70.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Juju Bobi\",\"phoneNumber\":\"09913598226\",\"city\":\"Carmen\",\"barangay\":\"Mulao\",\"streetAddress\":\"aa\"}', 20.00, 50.00, '', NULL, NULL, NULL),
(101, '2025-04-10 07:02:35', 1, NULL, 280.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 280.00),
(102, '2025-04-10 12:05:53', 1, NULL, 16.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 16.00),
(103, '2025-04-10 12:21:21', 1, NULL, 16.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 16.00),
(104, '2025-04-10 12:37:58', 1, NULL, 16.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 16.00),
(105, '2025-04-10 12:38:38', 1, NULL, 16.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 16.00),
(106, '2025-04-10 12:42:49', 2, NULL, 32.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 32.00),
(107, '2025-04-10 12:52:42', 1, NULL, 20.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 20.00),
(112, '2025-04-10 13:04:40', 1, 34, 20.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 20.00),
(113, '2025-04-10 13:07:36', 1, 34, 20.00, '0', 'gcash', 0.00, 0.00, 11, NULL, NULL, NULL, '1234567891012', NULL, '0', 20.00),
(114, '2025-04-10 13:09:38', 1, 34, 16.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, '0', 16.00),
(115, '2025-04-10 13:18:04', 1, 34, 16.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, 'pwd', 16.00),
(116, '2025-04-10 13:22:32', 1, 35, 25.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 25.00),
(117, '2025-04-11 00:51:27', 1, NULL, 100.00, 'delivery', 'cod', 0.00, 0.00, 12, '{\"fullName\":\"Jamaica Sohon\",\"phoneNumber\":\"09913598226\",\"city\":\"Danao\",\"barangay\":\"Mulao\",\"streetAddress\":\"yati liloan\"}', 20.00, 80.00, '', NULL, NULL, NULL),
(118, '2025-04-11 01:03:08', 13, 35, 1472.00, '0', 'cash', 0.00, 528.00, 11, NULL, NULL, NULL, NULL, NULL, 'senior_citizen', 1472.00),
(119, '2025-04-11 01:25:19', 9, 35, 755.00, '0', 'cash', 0.00, 245.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 755.00),
(120, '2025-04-11 01:26:47', 4, 35, 100.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 100.00),
(121, '2025-04-11 01:27:20', 1, 36, 30.00, '0', 'cash', 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, 'none', 30.00);

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
(19, 26, 36, 1, 30.00, 0.00),
(20, 28, 36, 1, 30.00, 0.00),
(21, 29, 35, 9, 25.00, 0.00),
(22, 30, 60, 10, 50.00, 0.00),
(23, 31, 60, 10, 50.00, 0.00),
(24, 32, 36, 2, 30.00, 0.00),
(25, 32, 35, 1, 25.00, 0.00),
(26, 33, 36, 2, 30.00, 0.00),
(27, 33, 35, 1, 25.00, 0.00),
(28, 34, 36, 2, 30.00, 0.00),
(29, 34, 35, 1, 25.00, 0.00),
(30, 35, 36, 1, 30.00, 0.00),
(31, 35, 35, 1, 25.00, 0.00),
(32, 36, 35, 1, 25.00, 0.00),
(33, 36, 36, 1, 30.00, 0.00),
(34, 36, 37, 1, 55.00, 0.00),
(35, 36, 38, 1, 50.00, 0.00),
(36, 37, 35, 1, 25.00, 0.00),
(37, 37, 36, 1, 30.00, 0.00),
(38, 37, 37, 1, 55.00, 0.00),
(39, 37, 38, 1, 50.00, 0.00),
(40, 38, 36, 2, 30.00, 0.00),
(41, 38, 35, 1, 25.00, 0.00),
(42, 39, 36, 2, 30.00, 0.00),
(43, 39, 35, 1, 25.00, 0.00),
(44, 40, 36, 1, 30.00, 0.00),
(45, 40, 35, 1, 25.00, 0.00),
(46, 41, 36, 1, 30.00, 0.00),
(47, 41, 35, 1, 25.00, 0.00),
(48, 42, 36, 1, 30.00, 0.00),
(49, 42, 35, 1, 25.00, 0.00),
(50, 43, 36, 1, 30.00, 0.00),
(51, 43, 35, 1, 25.00, 0.00),
(52, 44, 50, 2, 50.00, 0.00),
(53, 44, 36, 1, 30.00, 0.00),
(54, 45, 36, 1, 30.00, 0.00),
(55, 45, 35, 1, 25.00, 0.00),
(56, 46, 36, 1, 30.00, 0.00),
(57, 46, 35, 1, 25.00, 0.00),
(58, 47, 36, 1, 30.00, 0.00),
(59, 47, 35, 1, 25.00, 0.00),
(60, 48, 36, 1, 30.00, 0.00),
(61, 48, 35, 1, 25.00, 0.00),
(62, 49, 36, 1, 30.00, 0.00),
(63, 49, 35, 1, 25.00, 0.00),
(64, 49, 38, 1, 50.00, 0.00),
(65, 50, 36, 1, 30.00, 0.00),
(66, 51, 49, 1, 55.00, 0.00),
(67, 52, 36, 1, 30.00, 0.00),
(68, 52, 35, 1, 25.00, 0.00),
(69, 53, 41, 1, 250.00, 0.00),
(70, 54, 41, 1, 250.00, 0.00),
(71, 55, 36, 1, 30.00, 0.00),
(72, 56, 36, 1, 30.00, 0.00),
(73, 57, 36, 1, 30.00, 0.00),
(74, 57, 35, 1, 25.00, 0.00),
(75, 58, 36, 1, 30.00, 0.00),
(76, 58, 35, 1, 25.00, 0.00),
(77, 59, 36, 1, 30.00, 0.00),
(78, 59, 35, 1, 25.00, 0.00),
(79, 60, 36, 2, 30.00, 0.00),
(80, 60, 35, 1, 25.00, 0.00),
(81, 61, 36, 9, 30.00, 0.00),
(82, 61, 41, 1, 250.00, 0.00),
(83, 62, 36, 1, 30.00, 0.00),
(84, 63, 36, 8, 30.00, 0.00),
(85, 64, 36, 8, 30.00, 0.00),
(86, 65, 35, 3, 25.00, 0.00),
(87, 66, 35, 6, 25.00, 0.00),
(88, 67, 38, 1, 50.00, 0.00),
(89, 68, 39, 2, 50.00, 0.00),
(90, 69, 39, 8, 50.00, 0.00),
(91, 70, 38, 1, 50.00, 0.00),
(92, 70, 37, 1, 55.00, 0.00),
(93, 71, 38, 1, 50.00, 0.00),
(94, 72, 38, 1, 50.00, 0.00),
(95, 73, 48, 1, 50.00, 0.00),
(96, 74, 48, 1, 50.00, 0.00),
(97, 75, 48, 1, 50.00, 0.00),
(98, 76, 38, 6, 50.00, 0.00),
(99, 77, 38, 6, 50.00, 0.00),
(100, 78, 38, 6, 50.00, 0.00),
(101, 79, 38, 6, 50.00, 0.00),
(102, 80, 38, 6, 50.00, 0.00),
(103, 81, 38, 6, 50.00, 0.00),
(104, 82, 38, 6, 50.00, 0.00),
(105, 83, 38, 6, 50.00, 0.00),
(106, 84, 37, 1, 55.00, 0.00),
(107, 84, 45, 1, 300.00, 0.00),
(108, 84, 49, 1, 55.00, 0.00),
(109, 85, 37, 7, 55.00, 0.00),
(110, 86, 41, 10, 250.00, 0.00),
(111, 87, 42, 5, 250.00, 0.00),
(112, 88, 54, 10, 50.00, 0.00),
(113, 89, 42, 1, 250.00, 0.00),
(114, 90, 59, 10, 60.00, 0.00),
(115, 91, 40, 10, 150.00, 0.00),
(116, 92, 43, 1, 300.00, 0.00),
(117, 93, 44, 1, 300.00, 0.00),
(118, 93, 43, 1, 300.00, 0.00),
(119, 93, 45, 1, 300.00, 0.00),
(120, 94, 43, 4, 300.00, 0.00),
(121, 95, 57, 2, 50.00, 0.00),
(122, 96, 44, 2, 300.00, 0.00),
(123, 97, 44, 1, 300.00, 0.00),
(124, 98, 44, 5, 300.00, 0.00),
(125, 99, 45, 8, 300.00, 0.00),
(126, 100, 48, 1, 50.00, 0.00),
(127, 101, 46, 1, 350.00, 0.00),
(128, 102, 34, 1, 20.00, 0.00),
(129, 103, 34, 1, 20.00, 0.00),
(130, 104, 34, 1, 20.00, 0.00),
(131, 105, 34, 1, 20.00, 0.00),
(132, 106, 34, 2, 20.00, 0.00),
(133, 107, 34, 1, 20.00, 0.00),
(134, 112, 34, 1, 20.00, 0.00),
(135, 113, 34, 1, 20.00, 0.00),
(136, 114, 34, 1, 20.00, 0.00),
(137, 115, 34, 1, 20.00, 0.00),
(138, 116, 35, 1, 25.00, 0.00),
(139, 117, 36, 1, 30.00, 0.00),
(140, 117, 39, 1, 50.00, 0.00),
(141, 118, 35, 2, 25.00, 0.00),
(142, 118, 38, 3, 50.00, 0.00),
(143, 118, 37, 2, 55.00, 0.00),
(144, 118, 36, 1, 30.00, 0.00),
(145, 118, 44, 1, 300.00, 0.00),
(146, 118, 43, 1, 300.00, 0.00),
(147, 118, 45, 1, 300.00, 0.00),
(148, 118, 47, 1, 250.00, 0.00),
(149, 118, 46, 1, 350.00, 0.00),
(150, 119, 35, 3, 25.00, 0.00),
(151, 119, 41, 1, 250.00, 0.00),
(152, 119, 42, 1, 250.00, 0.00),
(153, 119, 55, 1, 25.00, 0.00),
(154, 119, 49, 1, 55.00, 0.00),
(155, 119, 61, 1, 50.00, 0.00),
(156, 119, 58, 1, 50.00, 0.00),
(157, 120, 35, 4, 25.00, 0.00),
(158, 121, 36, 1, 30.00, 0.00);

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
(6, 26, 'cash', 730.00, '2025-04-05 15:37:00', 'completed'),
(7, 92, 'cash', 336.00, '2025-04-10 01:12:11', 'completed'),
(8, 93, 'cash', 1008.00, '2025-04-10 01:16:14', 'completed'),
(9, 94, 'cash', 14000.00, '2025-04-10 01:18:15', 'completed'),
(10, 95, 'cash', 150.00, '2025-04-10 01:39:14', 'completed'),
(11, 96, 'cash', 538.00, '2025-04-10 01:40:21', 'completed'),
(12, 97, 'cash', 337.00, '2025-04-10 01:42:24', 'completed'),
(13, 99, 'cash', 2688.00, '2025-04-10 02:21:05', 'completed'),
(14, 101, 'cash', 280.00, '2025-04-10 07:02:35', 'completed'),
(15, 102, 'cash', 16.00, '2025-04-10 12:05:53', 'completed'),
(16, 103, 'cash', 16.00, '2025-04-10 12:21:21', 'completed'),
(17, 104, 'cash', 16.00, '2025-04-10 12:37:58', 'completed'),
(18, 105, 'cash', 16.00, '2025-04-10 12:38:38', 'completed'),
(19, 106, 'cash', 32.00, '2025-04-10 12:42:49', 'completed'),
(20, 107, 'cash', 20.00, '2025-04-10 12:52:42', 'completed'),
(21, 112, 'cash', 20.00, '2025-04-10 13:04:40', 'completed'),
(22, 113, 'gcash', 20.00, '2025-04-10 13:07:36', 'completed'),
(23, 114, 'cash', 16.00, '2025-04-10 13:09:38', 'completed'),
(24, 115, 'cash', 16.00, '2025-04-10 13:18:04', 'completed'),
(25, 116, 'cash', 25.00, '2025-04-10 13:22:32', 'completed'),
(26, 118, 'cash', 2000.00, '2025-04-11 01:03:08', 'completed'),
(27, 119, 'cash', 1000.00, '2025-04-11 01:25:19', 'completed'),
(28, 120, 'cash', 100.00, '2025-04-11 01:26:47', 'completed'),
(29, 121, 'cash', 30.00, '2025-04-11 01:27:20', 'completed');

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
(34, 'Plain Rice', 'One cup of plain white rice is perfect as a base for a variety of meals.', 20.00, 'plainrice.jpg', 0, '4808030510010', '2025-03-29 15:09:47', 'RIC', ''),
(35, 'Java Rice', 'One cup of Java rice is a flavorful, golden-yellow fried rice infused with garlic, soy sauce, and annatto, making it a perfect savory companion to grilled or fried dishes.', 25.00, 'javarice.jpg', 0, '4808030510027', '2025-03-29 15:10:34', 'RIC', ''),
(36, 'Egg Fried Rice', 'Rice cooked and fried with scrambled eggs, garlic, onions, and soy sauce. ', 30.00, 'eggfriedrice.jpg', 7, '4808030510034', '2025-03-29 15:12:03', 'RIC', ''),
(37, 'Sinangag', 'Fried rice sauted with garlic, Good for 2 - 3 persons', 55.00, 'Sinangag.JPG', 8, '4808030510041', '2025-03-29 15:12:47', 'RIC', ''),
(38, 'Pastil (Chicken)', 'Steamed rice topped with shredded, seasoned chicken wrapped in banana leaves.', 50.00, 'Pastil(Chicken).JPG', 7, '4808030510058', '2025-03-29 15:13:25', 'RIC', ''),
(39, 'Pastil (Beef)', 'Steamed rice topped with shredded, seasoned beef wrapped in banana leaves.', 50.00, 'Pastil(Beef) (1).JPG', 9, '4808030510065', '2025-03-29 15:13:55', 'RIC', ''),
(40, 'Adobo (Chicken/Pork)', 'Meat stewed in vinegar, soy sauce, garlic, bay leaves, and peppercorns, a Filipino staple.', 150.00, 'Adobo.JPG', 10, '4808030520019', '2025-03-29 15:16:40', 'VIA', ''),
(41, 'Sinigang', ' A tamarind-based sour soup loaded with pork, shrimp, or beef, and assorted vegetables.', 250.00, 'Sinigang.JPG', 9, '4808030520026', '2025-03-29 15:17:14', 'VIA', ''),
(42, 'Kare-Kare', 'A rich, peanut-based stew with oxtail, tripe, vegetables, and a side of bagoong (shrimp paste).', 250.00, 'Kare-Kare.JPG', 9, '4808030520033', '2025-03-29 15:17:54', 'VIA', ''),
(43, 'Bicol Express', 'Spicy stew made with pork, coconut milk, shrimp paste, and plenty of chilies.', 300.00, 'Bicol Express.JPG', 9, '4808030520040', '2025-03-29 15:18:38', 'VIA', ''),
(44, 'Pinakbet', 'A hearty dish of vegetables like squash, eggplant, and okra, flavored with bagoong.', 300.00, 'Pinakbet.JPG', 9, '4808030520057', '2025-03-29 15:19:14', 'VIA', ''),
(45, 'Dinuguan', 'Savory pork stew simmered in a dark, rich gravy made from pork blood and vinegar.', 300.00, 'Dinuguan.JPG', 9, '4808030520064', '2025-03-29 15:19:51', 'VIA', ''),
(46, 'Inihaw na Liempo', 'Marinated pork belly grilled until smoky and caramelized, best paired with vinegar dip.', 350.00, 'Inihaw na Liempo.JPG', 9, '4808030520071', '2025-03-29 15:20:24', 'VIA', ''),
(47, 'Pancit Bihon', 'Thin rice noodles stir-fried with pork, shrimp, and mixed vegetables.', 250.00, 'Pancit Bihon.JPG', 9, '4808030520088', '2025-03-29 15:20:57', 'VIA', ''),
(48, 'Salabat', 'Warm, soothing ginger tea, perfect for colds or chilly mornings.', 50.00, 'Salabat (Ginger Tea).JPG', 8, '4808030530018', '2025-03-29 15:25:12', 'BEV', ''),
(49, 'Buko Juice', 'Fresh coconut water served straight from the shell hydrating and mildly sweet.', 55.00, 'Buko Juice.JPG', 8, '4808030530025', '2025-03-29 15:25:50', 'BEV', ''),
(50, 'Calamansi Juice', 'Zesty, citrusy drink made from freshly squeezed calamansi limes. ', 50.00, 'Calamansi Juice.JPG', 7, '4808030530032', '2025-03-29 15:26:33', 'BEV', ''),
(51, 'Ube Samalamig', 'Cold, flavored drinks made with fruits, jelly, or tapioca pearls.', 55.00, 'Ube Samalamig.JPG', 10, '3003003004', '2025-03-29 15:27:05', 'BEV', ''),
(52, 'Barako Coffee', 'Bold, strong coffee made from Liberica beans, Batangas specialty.', 55.00, 'Barako Coffee.JPG', 10, '4808030530056', '2025-03-29 15:27:34', 'BEV', ''),
(53, 'Pandan Juice', 'Sweet, fragrant juice infused with pandan leaves.', 50.00, 'Pandan Juice.JPG', 10, '4808030530063', '2025-03-29 15:28:16', 'BEV', ''),
(54, 'Melon Juice', 'Refreshing cantaloupe juice with thin melon shreds.', 50.00, 'Melon Juice.JPG', 0, '4808030530070', '2025-03-29 15:28:57', 'BEV', ''),
(55, 'Coca-Cola Canned', 'A 325ml can of Coca-Cola offers a refreshing, crisp taste of the classic soda, perfectly sized for a quick, satisfying drink.', 25.00, 'coke.jpg', 9, '4808030530087', '2025-03-29 15:29:36', 'BEV', ''),
(56, 'Royal', 'A 325ml can of Royal delivers a sweet, fruity burst of orange flavor, offering a deliciously refreshing soda experience in a convenient size.', 25.00, 'royal.jpg', 10, '4808030530094', '2025-03-29 15:30:05', 'BEV', ''),
(57, 'Leche Flan', 'Smooth, creamy custard topped with rich caramel syrup.', 50.00, 'Leche Flan.JPG', 8, '4808030540017', '2025-03-29 15:30:33', 'DES', ''),
(58, 'Halo-Halo', 'A colorful mix of shaved ice, sweet beans, jellies, fruits, leche flan, and purple yam.', 50.00, 'Halo-Halo.JPG', 9, '4808030540024', '2025-03-29 15:31:19', 'DES', ''),
(59, 'Cassava Cake', 'Chewy, coconut-flavored cake made from grated cassava root.', 60.00, 'Cassava Cake.JPG', 0, '4808030540031', '2025-03-29 15:31:51', 'DES', ''),
(60, 'Brazo de Mercedes', 'Soft meringue roll filled with rich, custard-like yema filling.', 50.00, 'Brazo de Mercedes.JPG', 10, '4808030540048', '2025-03-29 15:32:23', 'DES', ''),
(61, 'Yema', 'Sweet, chewy custard candies wrapped in bright cellophane.', 50.00, 'Yema.JPG', 9, '4808030540055', '2025-03-29 15:32:55', 'DES', ''),
(62, 'Ube Halaya', 'Smooth, rich purple yam jam, a holiday favorite.', 50.00, 'Ube Halaya.JPG', 10, '4808030540079', '2025-03-29 15:34:29', 'DES', '');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `recipe_name`, `created_at`, `updated_at`) VALUES
(1, 'Sinangag', '2025-04-10 00:05:12', '2025-04-10 00:05:12');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`id`, `recipe_id`, `item_id`, `quantity`, `unit`) VALUES
(1, NULL, 2, 5.00, 'g'),
(2, NULL, 3, 4.00, 'pcs'),
(3, NULL, 2, 50.00, 'g'),
(4, NULL, 3, 2.00, 'pcs'),
(5, 1, 2, 50.00, 'g'),
(6, 1, 3, 4.00, 'pcs');

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
(12, 36, 30.00, '2025-04-05 15:37:00', 1),
(13, 43, 300.00, '2025-04-10 01:12:11', 1),
(14, 44, 300.00, '2025-04-10 01:16:14', 1),
(15, 43, 300.00, '2025-04-10 01:16:14', 1),
(16, 45, 300.00, '2025-04-10 01:16:14', 1),
(17, 43, 1200.00, '2025-04-10 01:18:15', 4),
(18, 57, 100.00, '2025-04-10 01:39:14', 2),
(19, 44, 600.00, '2025-04-10 01:40:21', 2),
(20, 44, 300.00, '2025-04-10 01:42:24', 1),
(21, 45, 2400.00, '2025-04-10 02:21:05', 8),
(22, 46, 350.00, '2025-04-10 07:02:35', 1),
(23, 34, 20.00, '2025-04-10 12:05:53', 1),
(24, 34, 20.00, '2025-04-10 12:21:21', 1),
(25, 34, 20.00, '2025-04-10 12:37:58', 1),
(26, 34, 20.00, '2025-04-10 12:38:38', 1),
(27, 34, 40.00, '2025-04-10 12:42:49', 2),
(28, 34, 20.00, '2025-04-10 12:52:42', 1),
(29, 34, 20.00, '2025-04-10 13:04:40', 1),
(30, 34, 20.00, '2025-04-10 13:07:36', 1),
(31, 34, 20.00, '2025-04-10 13:09:38', 1),
(32, 34, 20.00, '2025-04-10 13:18:04', 1),
(33, 35, 25.00, '2025-04-10 13:22:32', 1),
(34, 35, 50.00, '2025-04-11 01:03:08', 2),
(35, 38, 150.00, '2025-04-11 01:03:08', 3),
(36, 37, 110.00, '2025-04-11 01:03:08', 2),
(37, 36, 30.00, '2025-04-11 01:03:08', 1),
(38, 44, 300.00, '2025-04-11 01:03:08', 1),
(39, 43, 300.00, '2025-04-11 01:03:08', 1),
(40, 45, 300.00, '2025-04-11 01:03:08', 1),
(41, 47, 250.00, '2025-04-11 01:03:08', 1),
(42, 46, 350.00, '2025-04-11 01:03:08', 1),
(43, 35, 75.00, '2025-04-11 01:25:19', 3),
(44, 41, 250.00, '2025-04-11 01:25:19', 1),
(45, 42, 250.00, '2025-04-11 01:25:19', 1),
(46, 55, 25.00, '2025-04-11 01:25:19', 1),
(47, 49, 55.00, '2025-04-11 01:25:19', 1),
(48, 61, 50.00, '2025-04-11 01:25:19', 1),
(49, 58, 50.00, '2025-04-11 01:25:19', 1),
(50, 35, 100.00, '2025-04-11 01:26:47', 4),
(51, 36, 30.00, '2025-04-11 01:27:20', 1);

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
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `products_supplied` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `products_supplied`, `status`, `created_at`) VALUES
(1, 'JujuBeat', 'Juju', '01234567891', 'mnhi@gmail.com', 'patis', 'active', '2025-04-10 16:30:25'),
(2, 'test1', 'Juju', '09477702264', 'jamaica.sohon@gmail.com', 'aaa', 'active', '2025-04-10 19:27:23'),
(3, 'test1', 'Juju', '09477702264', 'jamaica.sohon@gmail.com', 'zxxzxzxzx', 'active', '2025-04-10 19:28:11');

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
-- Indexes for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_addresses` (`user_id`),
  ADD KEY `idx_order_address` (`order_id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_purchases`
--
ALTER TABLE `inventory_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

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
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `item_id` (`item_id`);

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
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory_purchases`
--
ALTER TABLE `inventory_purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD CONSTRAINT `delivery_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_addresses_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`);

--
-- Constraints for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  ADD CONSTRAINT `inventory_usage_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`);

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
