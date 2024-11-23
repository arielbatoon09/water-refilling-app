-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 06:30 AM
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
-- Database: `refillwaterdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `address`, `municipality`, `city`, `postal_code`, `phone_number`, `created_at`, `updated_at`) VALUES
(13, 1, 'Gabi', 'Cordova', 'Cebu', '6017', '9674214635', '2024-11-16 23:50:50', '2024-11-16 23:50:50'),
(14, 2, 'Poblacion', 'Cordova', 'Cebu', '6017', '9674214632', '2024-11-18 07:19:36', '2024-11-18 07:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `flag` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `order_quantity`, `unit_price`, `flag`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 7, 100, 1, '2024-11-11 18:22:24', '2024-11-12 04:51:09'),
(3, 1, 2, 1, 20, 0, '2024-11-11 18:25:25', '2024-11-11 21:59:47'),
(4, 1, 2, 5, 20, 0, '2024-11-11 18:26:37', '2024-11-11 21:59:47'),
(5, 1, 2, 5, 20, 0, '2024-11-11 18:26:42', '2024-11-11 21:58:26'),
(7, 1, 1, 2, 100, 0, '2024-11-11 18:48:44', '2024-11-11 21:58:26'),
(9, 1, 2, 1, 20, 0, '2024-11-11 22:29:56', '2024-11-11 22:30:27'),
(10, 1, 1, 5, 100, 0, '2024-11-11 22:30:05', '2024-11-11 22:30:27'),
(11, 1, 2, 1, 20, 0, '2024-11-11 22:33:41', '2024-11-11 22:39:22'),
(12, 1, 2, 1, 20, 0, '2024-11-11 22:39:11', '2024-11-11 22:39:22'),
(13, 1, 2, 1, 20, 0, '2024-11-11 22:44:52', '2024-11-11 22:54:22'),
(14, 1, 2, 1, 20, 0, '2024-11-11 22:54:34', '2024-11-11 22:54:41'),
(15, 1, 1, 1, 100, 0, '2024-11-11 22:55:25', '2024-11-11 22:55:30'),
(16, 1, 1, 3, 100, 0, '2024-11-11 23:06:54', '2024-11-11 23:35:35'),
(17, 1, 2, 1, 20, 0, '2024-11-11 23:35:04', '2024-11-11 23:35:35'),
(18, 1, 2, 4, 20, 0, '2024-11-12 00:09:12', '2024-11-12 00:09:32'),
(19, 1, 1, 1, 100, 0, '2024-11-12 00:09:15', '2024-11-12 00:09:32'),
(23, 1, 2, 1, 20, 0, '2024-11-12 00:12:51', '2024-11-12 00:13:30'),
(24, 1, 1, 2, 100, 0, '2024-11-12 00:13:16', '2024-11-12 00:13:29'),
(25, 1, 1, 2, 100, 0, '2024-11-12 00:39:44', '2024-11-12 00:40:09'),
(26, 1, 2, 3, 20, 0, '2024-11-12 00:39:52', '2024-11-12 00:40:09'),
(27, 1, 2, 1, 20, 0, '2024-11-12 00:51:50', '2024-11-12 00:52:00'),
(28, 1, 2, 1, 20, 0, '2024-11-12 00:52:11', '2024-11-12 00:52:19'),
(29, 1, 2, 1, 20, 0, '2024-11-12 00:52:40', '2024-11-12 00:52:46'),
(30, 1, 2, 1, 20, 0, '2024-11-12 00:57:15', '2024-11-12 00:57:23'),
(31, 1, 2, 1, 20, 0, '2024-11-12 00:58:10', '2024-11-12 00:58:14'),
(32, 1, 2, 1, 20, 0, '2024-11-12 01:00:25', '2024-11-12 01:00:35'),
(33, 1, 1, 1, 100, 0, '2024-11-12 01:00:27', '2024-11-12 01:00:35'),
(34, 1, 2, 1, 20, 0, '2024-11-12 01:47:35', '2024-11-12 01:47:41'),
(35, 1, 1, 3, 100, 0, '2024-11-12 01:53:14', '2024-11-12 01:53:42'),
(36, 1, 2, 5, 20, 0, '2024-11-12 01:53:27', '2024-11-12 01:53:42'),
(37, 1, 2, 1, 20, 0, '2024-11-12 01:56:36', '2024-11-12 01:56:42'),
(39, 1, 2, 1, 20, 0, '2024-11-12 03:27:21', '2024-11-12 06:45:43'),
(40, 1, 4, 1, 10, 0, '2024-11-16 19:12:13', '2024-11-16 21:20:43'),
(41, 1, 4, 1, 10, 0, '2024-11-19 17:00:26', '2024-11-19 17:00:26'),
(42, 1, 3, 3, 11, 0, '2024-11-19 17:32:18', '2024-11-19 17:32:18'),
(43, 1, 4, 2, 10, 0, '2024-11-19 17:34:05', '2024-11-19 17:34:05'),
(44, 1, 2, 2, 20, 0, '2024-11-19 17:34:39', '2024-11-19 17:34:39'),
(45, 1, 4, 1, 10, 0, '2024-11-19 17:36:28', '2024-11-19 17:36:28'),
(46, 1, 4, 1, 10, 0, '2024-11-19 17:37:29', '2024-11-19 17:37:29'),
(47, 1, 4, 1, 10, 0, '2024-11-19 17:39:53', '2024-11-19 17:39:53'),
(48, 1, 4, 1, 10, 0, '2024-11-19 17:42:44', '2024-11-19 17:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallon_types`
--

CREATE TABLE `gallon_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gallon_size` varchar(255) NOT NULL,
  `gallon_price` int(11) NOT NULL,
  `gallon_image` varchar(255) NOT NULL,
  `delivery_fee` int(11) NOT NULL,
  `flag` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallon_types`
--

INSERT INTO `gallon_types` (`id`, `gallon_size`, `gallon_price`, `gallon_image`, `delivery_fee`, `flag`, `created_at`, `updated_at`) VALUES
(1, '1L Gallon', 25, 'http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg', 5, 1, '2024-11-03 09:21:53', '2024-11-03 09:21:53'),
(2, '2L Gallon', 30, 'http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg', 7, 1, '2024-11-03 09:22:13', '2024-11-03 09:22:13'),
(3, '3L Gallon', 35, 'http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg', 9, 1, '2024-11-03 09:22:25', '2024-11-03 09:22:25'),
(11, '1234', 1, 'http://127.0.0.1:8000/storage/gallon/image-dkIWtvRW4hE9A8yz1s5CABJ5ohg77r0nZADyo1nz.png', 1, 1, '2024-11-10 02:20:38', '2024-11-17 00:21:08'),
(12, '133', 1, 'http://127.0.0.1:8000/storage/gallon/image-PFaXOW1xmV7LbnDC0y3tkbV0Eft5FVbqTTur3MRL.jpeg', 1, 0, '2024-11-10 02:24:24', '2024-11-17 02:56:56'),
(13, '12', 1, 'http://127.0.0.1:8000/storage/gallon/image-PWRwhlnWXFqKvCSFgZukxDb06PzcG3WJ9qm9l7f3.jpeg', 1, 0, '2024-11-10 02:24:57', '2024-11-15 20:17:04'),
(14, '1', 1, 'http://127.0.0.1:8000/storage/gallon/image-DNGc15M0g31s7zfpb7Z9tdvJhfvidva6X3Jhc2LE.png', 1, 1, '2024-11-10 02:34:24', '2024-11-10 02:34:24'),
(15, '12', 1, 'http://127.0.0.1:8000/storage/gallon/image-aMErkv0QDSjmMusN356NmZqW6tq0x4Zu6JjY1xG7.jpeg', 1, 0, '2024-11-10 02:46:44', '2024-11-10 02:46:44'),
(16, 'Test', 1, 'http://127.0.0.1:8000/storage/gallon/image-vyfOFSqYyutw8vQRtXu2maZ1NV7s4CQGFWxz8cbg.png', 1, 0, '2024-11-15 19:57:13', '2024-11-15 19:58:58'),
(17, 'Test1', 1, 'http://127.0.0.1:8000/storage/gallon/image-8CXuTdgaetaal7gjnxGx6ruGIfqEIoe81JB4wR6B.png', 1, 1, '2024-11-15 19:59:12', '2024-11-15 19:59:12');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_10_17_045852_create_personal_access_tokens_table', 1),
(14, '2024_10_18_064029_create_table_gallon_type', 4),
(15, '2024_10_18_064103_create_table_refilling', 5),
(19, '2024_11_12_012327_create_cart_table', 8),
(20, '2024_11_10_091157_create_product_table', 9),
(24, '2024_11_12_043201_create_orders_table', 10),
(27, '2024_11_12_105614_create_review_table', 11),
(30, '2024_11_14_083717_create_address_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `refid` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `cart_id` int(11) NOT NULL,
  `order_quantity` int(11) NOT NULL,
  `total_item_price` int(11) NOT NULL,
  `mop` varchar(255) NOT NULL,
  `delivery_type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `refid`, `user_id`, `cart_id`, `order_quantity`, `total_item_price`, `mop`, `delivery_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ref_40941', 1, 24, 2, 200, 'Online Pay', 'Walk-In', 'To Receive', '2024-11-12 00:13:29', '2024-11-12 00:38:49'),
(2, 'Ref_40941', 1, 23, 1, 20, 'Online Pay', 'Walk-In', 'To Receive', '2024-11-12 00:13:29', '2024-11-12 00:38:49'),
(3, 'Ref_41972', 1, 26, 3, 60, 'Online Pay', 'Door-To-Door', 'Delivered', '2024-11-12 00:40:08', '2024-11-16 21:03:19'),
(4, 'Ref_41972', 1, 25, 2, 200, 'Online Pay', 'Door-To-Door', 'Delivered', '2024-11-12 00:40:09', '2024-11-16 21:03:19'),
(5, 'Ref_47512', 1, 27, 1, 20, 'COD', 'Walk-In', 'Rated', '2024-11-12 00:52:00', '2024-11-12 00:52:00'),
(6, 'Ref_12413', 1, 28, 1, 20, 'COD', 'Door-To-Door', 'Delivered', '2024-11-12 00:52:19', '2024-11-16 21:03:42'),
(7, 'Ref_45107', 1, 29, 1, 20, 'Online Pay', 'Door-To-Door', 'Completed', '2024-11-12 00:52:46', '2024-11-12 05:54:55'),
(10, 'Ref_92743', 1, 33, 1, 100, 'Online Pay', 'Walk-In', 'Rated', '2024-11-12 01:00:35', '2024-11-12 05:46:47'),
(11, 'Ref_92743', 1, 32, 1, 20, 'Online Pay', 'Walk-In', 'Rated', '2024-11-12 01:00:35', '2024-11-12 05:46:47'),
(13, 'Ref_43213', 1, 36, 5, 100, 'Online Pay', 'Door-To-Door', 'Completed', '2024-11-12 01:53:42', '2024-11-12 05:54:49'),
(14, 'Ref_43213', 1, 35, 3, 300, 'Online Pay', 'Door-To-Door', 'Completed', '2024-11-12 01:53:42', '2024-11-12 05:54:49'),
(15, 'Ref_42564', 1, 37, 1, 20, 'Online Pay', 'Walk-In', 'Delivered', '2024-11-12 01:56:42', '2024-11-14 06:12:40'),
(16, 'Ref_41617', 1, 39, 1, 20, 'COD', 'Walk-In', 'Rated', '2024-11-12 06:45:43', '2024-11-16 21:02:04'),
(17, 'Ref_12440', 1, 40, 1, 10, 'COD', 'Walk-In', 'Completed', '2024-11-16 21:20:43', '2024-11-16 21:23:00'),
(18, 'Ref_28082', 1, 41, 1, 10, 'COD', 'Door-To-Door', 'Delivered', '2024-11-19 17:00:26', '2024-11-19 17:24:59'),
(19, 'Ref_14301', 1, 42, 3, 33, 'COD', 'Walk-In', 'To Receive', '2024-11-19 17:32:18', '2024-11-19 17:32:18'),
(20, 'Ref_85513', 1, 43, 2, 20, 'Online Pay', 'Walk-In', 'To Pay', '2024-11-19 17:34:05', '2024-11-19 17:34:05'),
(21, 'Ref_50770', 1, 44, 2, 40, 'COD', 'Door-To-Door', 'To Receive', '2024-11-19 17:34:39', '2024-11-19 17:34:39'),
(22, 'Ref_82348', 1, 45, 1, 10, 'Online Pay', 'Walk-In', 'To Pay', '2024-11-19 17:36:28', '2024-11-19 17:36:28'),
(23, 'Ref_38011', 1, 46, 1, 10, 'COD', 'Door-To-Door', 'To Receive', '2024-11-19 17:37:29', '2024-11-19 17:37:29'),
(24, 'Ref_48644', 1, 47, 1, 10, 'COD', 'Walk-In', 'To Receive', '2024-11-19 17:39:53', '2024-11-19 17:39:53'),
(25, 'Ref_85265', 1, 48, 1, 10, 'Online Pay', 'Walk-In', 'To Pay', '2024-11-19 17:42:44', '2024-11-19 17:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '768b662d54c7756ddd8ca9662b276550b09ec0c98cb72fc163ee7a0320e56157', '[\"*\"]', NULL, NULL, '2024-11-02 19:05:34', '2024-11-02 19:05:34'),
(2, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '2a092275a4cd6a992de5323345a17036813197926a8d1b8e96ba90b3f2e47109', '[\"*\"]', NULL, NULL, '2024-11-02 19:09:20', '2024-11-02 19:09:20'),
(3, 'App\\Models\\User', 3, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'af46e1d1af00ca22ae1878708cac2ff581c0a3eaf42c0e10650f1b910adabd5a', '[\"*\"]', NULL, NULL, '2024-11-02 19:13:20', '2024-11-02 19:13:20'),
(4, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '5f4bf8189f77eeff3840def28b8d77f89289bfaa4ac6183f0ac801c8b35bf6f4', '[\"*\"]', NULL, NULL, '2024-11-02 19:23:13', '2024-11-02 19:23:13'),
(5, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'fe125fec7128e2fabf1290ffbf9690011b2602e843dca5f9d0eed476c83509ff', '[\"*\"]', NULL, NULL, '2024-11-02 19:23:13', '2024-11-02 19:23:13'),
(6, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '470550275515c886989f2ecf21acd04ab24ab2ed7d78984f91018fcea4b95a96', '[\"*\"]', NULL, NULL, '2024-11-02 19:24:37', '2024-11-02 19:24:37'),
(7, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '805a6eab0bc8accfedd6251f36ad3d524c5a07a835e696a45c69bad332c8089d', '[\"*\"]', NULL, NULL, '2024-11-02 19:32:01', '2024-11-02 19:32:01'),
(8, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'a71ca4e475327a1cafa7f0adfedf364edfe563c579efa357220bd0e2ec24a96f', '[\"*\"]', NULL, NULL, '2024-11-02 19:32:02', '2024-11-02 19:32:02'),
(9, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '5ad688ae9556627cce29faf301e5b74719e5b79f7b8be581854029d8f5dec869', '[\"*\"]', NULL, NULL, '2024-11-02 19:35:36', '2024-11-02 19:35:36'),
(10, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '8b5d50b724518aee206b8713a5a7bce64965fe2793e436a62d1a02e1d26999a3', '[\"*\"]', NULL, NULL, '2024-11-02 19:37:29', '2024-11-02 19:37:29'),
(11, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'fd7d01b0edd47cce530929f7846213aa533935638fedccf37f00cdf266c59878', '[\"*\"]', NULL, NULL, '2024-11-02 19:37:30', '2024-11-02 19:37:30'),
(12, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '14f3bf77cae0f1d6d0cc16ce928cd9c49114301004c4c7979692781a8e6116da', '[\"*\"]', NULL, NULL, '2024-11-02 19:40:54', '2024-11-02 19:40:54'),
(13, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '0750db636ec678949652b97b1ff27c9b678482c97e7b8a98948d0718d9cd175a', '[\"*\"]', NULL, NULL, '2024-11-02 19:41:32', '2024-11-02 19:41:32'),
(14, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '2fbb7f2664d41eebe9d8341c5bc4acefe6ada607c28f5abfd9e4b3a4a7c0cda1', '[\"*\"]', NULL, NULL, '2024-11-02 19:42:27', '2024-11-02 19:42:27'),
(15, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '75bba846f8ddc56d3e3b01540bd667e86ed98f82e185cfe3964f38287cb6ee4c', '[\"*\"]', NULL, NULL, '2024-11-02 19:42:28', '2024-11-02 19:42:28'),
(16, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'd1c92cd702c6c802619c09a20b46b72eda58474f2dbe96a0e1c4469be29cb8fa', '[\"*\"]', NULL, NULL, '2024-11-02 19:43:20', '2024-11-02 19:43:20'),
(17, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'fb2243bf7dc733ec4a32a23eb96dd4f742ff8663d56d301c7bb90ce166244d89', '[\"*\"]', NULL, NULL, '2024-11-02 19:44:10', '2024-11-02 19:44:10'),
(18, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '2870b0311e8b05e469aa9195bfb1466e7a0fa3b3810c8afeeef4870f8e0cd8f7', '[\"*\"]', NULL, NULL, '2024-11-02 19:44:45', '2024-11-02 19:44:45'),
(19, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'ee457f0d723ba9044ed2a23874661207c7e09203abd3de86423bacecd674c7a5', '[\"*\"]', NULL, NULL, '2024-11-02 19:44:46', '2024-11-02 19:44:46'),
(20, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '59f1d56edaa1f0c177126716515bde6fa754c72df505261044401f21861597d0', '[\"*\"]', NULL, NULL, '2024-11-02 19:45:18', '2024-11-02 19:45:18'),
(21, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'e659068d79c9c30584ff45354188489019c2c0730781a63a82c559b6a9d91a77', '[\"*\"]', '2024-11-02 19:49:54', NULL, '2024-11-02 19:49:44', '2024-11-02 19:49:54'),
(22, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'f7dd17c6ce27d326ce4f7003ca5d7ca85a45c9524e1d0053963713373ecad89e', '[\"*\"]', '2024-11-02 19:51:15', NULL, '2024-11-02 19:50:31', '2024-11-02 19:51:15'),
(23, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '5240b9e22bfe409f3726279e868e3fcbdb8a241f07b064ffd58739b487035536', '[\"*\"]', '2024-11-02 19:51:38', NULL, '2024-11-02 19:51:34', '2024-11-02 19:51:38'),
(24, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '69404b692ea27b1fb9735abd625b8c2b50f9d165e78b47094e1f768677cbc4a1', '[\"*\"]', '2024-11-02 19:56:42', NULL, '2024-11-02 19:52:49', '2024-11-02 19:56:42'),
(25, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'd36ed1e70a9f2345ff3055cde01185712155d4c848361bd1f0b90429b9ed37e0', '[\"*\"]', '2024-11-02 19:57:00', NULL, '2024-11-02 19:56:58', '2024-11-02 19:57:00'),
(26, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '41282c3f250dcbfee49fa6e76ea42796149500c9bee17925a917e464bc51ef26', '[\"*\"]', '2024-11-02 19:58:04', NULL, '2024-11-02 19:57:59', '2024-11-02 19:58:04'),
(27, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'bccf9d91ee417092c9a5447d3ea04fe99f821091f90eb93c2c005a62016684ff', '[\"*\"]', '2024-11-02 19:58:45', NULL, '2024-11-02 19:58:24', '2024-11-02 19:58:45'),
(28, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'e0bb22e9b078fa24892657ae5fd6829284c38a01a839470714266f6ddf578264', '[\"*\"]', '2024-11-02 19:58:52', NULL, '2024-11-02 19:58:52', '2024-11-02 19:58:52'),
(29, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '47c6631868454ac61f430f8bd9d6ddbf1a7096e7b257d334dbd7204d4ead8ffd', '[\"*\"]', '2024-11-02 20:00:28', NULL, '2024-11-02 19:59:57', '2024-11-02 20:00:28'),
(30, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'b96059948d6b2f970b7a028855c7e794449644d34f35e3af701dd75f1955713a', '[\"*\"]', '2024-11-02 20:00:35', NULL, '2024-11-02 20:00:35', '2024-11-02 20:00:35'),
(31, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'bb858b5c84de729915c6bec017e09f71857f3bf4500366ac44d1517682b2a820', '[\"*\"]', '2024-11-02 20:09:01', NULL, '2024-11-02 20:00:51', '2024-11-02 20:09:01'),
(32, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '8b5cc246e9177356003bb6da1861c01c3a3185a8f7692ded0364e0717b1f981d', '[\"*\"]', '2024-11-02 20:18:42', NULL, '2024-11-02 20:09:20', '2024-11-02 20:18:42'),
(33, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'a6b6cdf9daf479ac4cea935d39998e6301df694806f25e7bdb18f4079a3a9146', '[\"*\"]', '2024-11-02 20:20:02', NULL, '2024-11-02 20:18:50', '2024-11-02 20:20:02'),
(34, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'b46ae3304608da29cfe1ef1990070e6fbb0aeeb74c017dc40a5293d661aae68a', '[\"*\"]', '2024-11-02 22:19:48', NULL, '2024-11-02 20:20:07', '2024-11-02 22:19:48'),
(35, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'a9c0bdb4e76114d7a994d46c4b76321676d88b9d366b2266afae19e231008e68', '[\"*\"]', '2024-11-03 02:47:43', NULL, '2024-11-02 22:36:23', '2024-11-03 02:47:43'),
(36, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '15e3a8103ea0c22a783d719889e431bb7d9209d52de9efc20691c41780153de3', '[\"*\"]', '2024-11-02 22:57:12', NULL, '2024-11-02 22:57:11', '2024-11-02 22:57:12'),
(37, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'c9f8d195546ff64fd31def3b2db01650f93850965fda67fe3859db8da67302c6', '[\"*\"]', '2024-11-02 22:57:12', NULL, '2024-11-02 22:57:11', '2024-11-02 22:57:12'),
(38, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '0b83365e2ec1c36f8bc4a7345d15ae840f8640811c477c6a2ff773dc98d4fc50', '[\"*\"]', '2024-11-03 09:22:25', NULL, '2024-11-03 00:47:07', '2024-11-03 09:22:25'),
(39, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'ccad4eecffe1568f8158411046d0a999e6a5d9ebe9224141121cfb9dd20843d2', '[\"*\"]', '2024-11-03 08:46:42', NULL, '2024-11-03 08:36:40', '2024-11-03 08:46:42'),
(40, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'e6d07edd1a8f1352ba4b5a0d0042ff96ce251db8fa6c12a097175e3ff923caac', '[\"*\"]', '2024-11-04 04:35:09', NULL, '2024-11-03 08:46:49', '2024-11-04 04:35:09'),
(41, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'ba65742489da73d20deff443b08a8d801dc478352f64115989c88d51fb9ba79c', '[\"*\"]', '2024-11-03 21:22:04', NULL, '2024-11-03 21:17:03', '2024-11-03 21:22:04'),
(42, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '3c3f64dd2a7e6c280a1f2b3cf50b8e8d3a0a1bda82ce7a06d73b3864d2c1c885', '[\"*\"]', '2024-11-03 21:22:23', NULL, '2024-11-03 21:22:19', '2024-11-03 21:22:23'),
(43, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '146a0b4ae80d888f1bef3cf02d46d9bfd7ce7a8dca0402df3bbfdde5693ee7d2', '[\"*\"]', '2024-11-03 21:44:36', NULL, '2024-11-03 21:22:29', '2024-11-03 21:44:36'),
(44, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '0ee6cf3032614a7eb7bdbbf83c334ed972a7eed4b6c15d106de189beeb1bf38f', '[\"*\"]', '2024-11-06 21:46:45', NULL, '2024-11-06 18:24:37', '2024-11-06 21:46:45'),
(45, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '9a6c8a0080c4cc72cd23172052b6589ee5aac08d8e6ba05d2a10aec4926e92cc', '[\"*\"]', '2024-11-06 19:18:25', NULL, '2024-11-06 18:52:14', '2024-11-06 19:18:25'),
(46, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'abb34ca8bbee833044aa3411c3e1a12dfe93c3368a5a6d63dbea2e86a887502a', '[\"*\"]', '2024-11-08 01:45:10', NULL, '2024-11-07 18:52:15', '2024-11-08 01:45:10'),
(47, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '77e434a14f13ddf94f1e43fab8ef790f2a5e804dc46b98362f3ffad6a9bf93b3', '[\"*\"]', '2024-11-07 19:06:32', NULL, '2024-11-07 18:54:39', '2024-11-07 19:06:32'),
(48, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '5a716ab8b011fcc4c83302442fb0a237ae917f05b470573f19fdd48ad15a3b74', '[\"*\"]', '2024-11-07 22:35:27', NULL, '2024-11-07 22:35:27', '2024-11-07 22:35:27'),
(49, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'e8875dd49f09723f472b0e2b4b56120708ae80dbce048aa036b6f7867c33ff89', '[\"*\"]', '2024-11-10 03:28:11', NULL, '2024-11-09 19:51:11', '2024-11-10 03:28:11'),
(50, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '738135ae2c0c66e839633933d19add109afc657806706dfcad7fe277a63fba1d', '[\"*\"]', '2024-11-10 03:29:56', NULL, '2024-11-09 19:54:41', '2024-11-10 03:29:56'),
(51, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '54a9a3005ee2a0d0e89804925f21ab779b4bfb0cd53553bdeee54d1c424e8995', '[\"*\"]', '2024-11-11 16:40:16', NULL, '2024-11-11 01:08:38', '2024-11-11 16:40:16'),
(52, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '2c5934cede986bdf7ef8c9b28713efbfa2950ffca849f19020bfd985e3f12f0b', '[\"*\"]', '2024-11-11 16:40:25', NULL, '2024-11-11 16:40:25', '2024-11-11 16:40:25'),
(53, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '68768d8b4ce8ef0d531e67adc99daceaa8a9c115b1b0595e3d59d0400b029862', '[\"*\"]', '2024-11-11 17:10:25', NULL, '2024-11-11 16:40:26', '2024-11-11 17:10:25'),
(54, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'b6c2ed4191bb0527360cd24342219202f3a9c94be2f26983800cecd222139156', '[\"*\"]', '2024-11-11 18:15:33', NULL, '2024-11-11 17:10:32', '2024-11-11 18:15:33'),
(55, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '63f3a7f08bde90670b4b12c43909ff4fdc7258a894e29f6e08bd65fdfc121627', '[\"*\"]', '2024-11-12 07:32:42', NULL, '2024-11-11 17:15:14', '2024-11-12 07:32:42'),
(56, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '8205061a48d8a96420a8b0a7feb59a79213e5e497ae526f34d9ef8115c6ca464', '[\"*\"]', '2024-11-12 05:38:27', NULL, '2024-11-11 17:42:27', '2024-11-12 05:38:27'),
(57, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '025a4e6f80e8d95793a9d91014c8065b132432c8ea76933a26bbf77ecaaae920', '[\"*\"]', '2024-11-12 07:32:46', NULL, '2024-11-11 18:15:41', '2024-11-12 07:32:46'),
(58, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'bcfb261d5e06c916d40b4ef123378d176583f91456fcb97391881690524a3f0d', '[\"*\"]', '2024-11-13 23:22:35', NULL, '2024-11-13 23:22:31', '2024-11-13 23:22:35'),
(59, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'aab5fbcac79021ffde0c91c68c9a6dc2e92525cfcd754f935411688e8fa508ca', '[\"*\"]', '2024-11-14 06:17:17', NULL, '2024-11-13 23:22:45', '2024-11-14 06:17:17'),
(60, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '62498aeaa8fe2bba9e8c437ea1cf9182712bc133da0bac81383fe979063ae2c0', '[\"*\"]', '2024-11-14 03:06:45', NULL, '2024-11-13 23:26:30', '2024-11-14 03:06:45'),
(61, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '6d30215574622a164d3a3169efee7b0bac7aed011b90eb5a8fd7f8af2b89993b', '[\"*\"]', '2024-11-14 05:54:05', NULL, '2024-11-13 23:33:34', '2024-11-14 05:54:05'),
(62, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '3d9a0cf752a76dcec1d477ecaaac84723cbc2944d0d1aaa6c5ed14da1ab3b2f0', '[\"*\"]', '2024-11-14 02:59:38', NULL, '2024-11-14 02:59:38', '2024-11-14 02:59:38'),
(63, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '87174eedafaee29ac6d946636a890e718180f080e31596b07f724ea8c7d31738', '[\"*\"]', '2024-11-14 03:06:21', NULL, '2024-11-14 03:02:21', '2024-11-14 03:06:21'),
(64, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'bea3f6269234ac817877eb5cd700b38373a866d8426316b50c1145a3f617dc2a', '[\"*\"]', '2024-11-14 05:51:45', NULL, '2024-11-14 03:11:11', '2024-11-14 05:51:45'),
(65, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '962ee6cade0e2f967b8e0ae44692934482aa2bdcfaa7cc84560d050f39f8e2fa', '[\"*\"]', '2024-11-14 19:43:31', NULL, '2024-11-14 19:43:14', '2024-11-14 19:43:31'),
(66, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'b12798c03d41422a364496381409b5269536a86e1b71b20282aa6c002cfd48ef', '[\"*\"]', '2024-11-14 19:43:39', NULL, '2024-11-14 19:43:38', '2024-11-14 19:43:39'),
(67, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '1ea8d25861e7f315bede14ab3ed35718f962fd11d15a4a5ba509edfbf6d6a56b', '[\"*\"]', '2024-11-15 18:51:59', NULL, '2024-11-14 19:43:38', '2024-11-15 18:51:59'),
(68, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '3b5ed88e9fb3f27da7022eb10b0ebcd8f924a012d2de5b11235dcfa31b6a6c38', '[\"*\"]', '2024-11-15 21:12:18', NULL, '2024-11-15 18:52:18', '2024-11-15 21:12:18'),
(69, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '1dff13427bc0ebc863a31d9fc39f852975c7d7d7eac37a53c328485aee5062b8', '[\"*\"]', '2024-11-15 21:03:11', NULL, '2024-11-15 18:52:28', '2024-11-15 21:03:11'),
(70, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'b193fb491e227d610f281fc8e8e8d654975fe125180256235c14750b50dfd8b0', '[\"*\"]', '2024-11-16 17:02:00', NULL, '2024-11-16 17:01:59', '2024-11-16 17:02:00'),
(71, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'a215aae2b292231265cefed7f208fe8217e2dbcfedea21bbafef7e52eb551d0a', '[\"*\"]', '2024-11-16 22:14:10', NULL, '2024-11-16 17:02:01', '2024-11-16 22:14:10'),
(72, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'f4631b3e54b21a0c53212a6d63f730f588aecccece9aef3c6d814cdfb622f4dc', '[\"*\"]', '2024-11-16 21:32:25', NULL, '2024-11-16 17:02:08', '2024-11-16 21:32:25'),
(73, 'App\\Models\\User', 3, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'ab91fe912d4eb2498d6d5a8acb21e5207212d6a265d58c8cdbfd33721ddcd55a', '[\"*\"]', '2024-11-16 21:35:08', NULL, '2024-11-16 21:32:57', '2024-11-16 21:35:08'),
(74, 'App\\Models\\User', 3, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '512c3fa5bd9f2dda7e4b06e2f14e66092901c34ee188f1b957482e7679ce6cf6', '[\"*\"]', '2024-11-16 21:35:26', NULL, '2024-11-16 21:35:15', '2024-11-16 21:35:26'),
(75, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '53938247da255d5c10a9a1ec4d28bdc62ebcd8c2df49ba22db6cc196ea8d0478', '[\"*\"]', '2024-11-16 22:07:24', NULL, '2024-11-16 21:35:32', '2024-11-16 22:07:24'),
(76, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '9bd79691324c986d1a61c98a1e8cbc7ca198e19de968f61387016ab41848884d', '[\"*\"]', '2024-11-16 22:07:40', NULL, '2024-11-16 22:07:30', '2024-11-16 22:07:40'),
(77, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '7c094019e2887fc38d76e97f36a58b0ca793b6ff42d0feee833c131b4cca8690', '[\"*\"]', '2024-11-16 22:07:49', NULL, '2024-11-16 22:07:47', '2024-11-16 22:07:49'),
(78, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '22c7b1e8afa00ee880ce9be344f9fedc823ddda8f261fa8c048aaf6b8abd2526', '[\"*\"]', '2024-11-16 22:09:20', NULL, '2024-11-16 22:09:12', '2024-11-16 22:09:20'),
(79, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '5f86debf22b889e18a75a2e7f7d334636e5f65ce5399cbeb98b03429f8a5f763', '[\"*\"]', '2024-11-16 22:10:27', NULL, '2024-11-16 22:10:04', '2024-11-16 22:10:27'),
(80, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'f37aaefc8e07224bafd55f5122082aa8fe69166c2cf94131e165938b10485de5', '[\"*\"]', '2024-11-16 22:12:01', NULL, '2024-11-16 22:11:57', '2024-11-16 22:12:01'),
(81, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '41dfc068488c0c123537d2b4eb31c06561b36f474745b454492bcd59508baa4a', '[\"*\"]', '2024-11-16 22:14:36', NULL, '2024-11-16 22:13:26', '2024-11-16 22:14:36'),
(82, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '79f86dcc420913265c3f985f7537079760873f1016cd550830f2505c6e4b2972', '[\"*\"]', '2024-11-17 03:17:51', NULL, '2024-11-16 22:14:16', '2024-11-17 03:17:51'),
(83, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '92e21786ab0ac53852878131eb2c95461c8241b5929beb2544a4b55f8a719683', '[\"*\"]', '2024-11-16 22:14:51', NULL, '2024-11-16 22:14:44', '2024-11-16 22:14:51'),
(84, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'bcea61c465504d37283684adb4c4bc30e3aec3332bea08e207ad062193d2b062', '[\"*\"]', '2024-11-16 22:20:35', NULL, '2024-11-16 22:20:23', '2024-11-16 22:20:35'),
(85, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '6f761bb60a151c0a743cfb3e38ec6fb6b663592c9ad4deeaacb5aa8cd9ad723d', '[\"*\"]', '2024-11-16 22:37:07', NULL, '2024-11-16 22:20:53', '2024-11-16 22:37:07'),
(86, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '25ef82347a40a8e6a24b252237ddeb148b86b92760720b0b38255db12eeb3db1', '[\"*\"]', '2024-11-16 22:37:33', NULL, '2024-11-16 22:37:15', '2024-11-16 22:37:33'),
(87, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'd7948c35196e699439cb20eda0abf230c5d41f6e056bb6745de31acbfca9ba0d', '[\"*\"]', '2024-11-16 22:39:26', NULL, '2024-11-16 22:37:39', '2024-11-16 22:39:26'),
(88, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'f7d0a2ad5485d1f3b2e44b1e0cd4086dd8a05a28f00fb7aba7500276fd3ff74e', '[\"*\"]', '2024-11-16 22:40:14', NULL, '2024-11-16 22:39:36', '2024-11-16 22:40:14'),
(89, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'a5455cb170921c263d38833930fbe521e0f950d6541e1f5be15e6e68f80a0145', '[\"*\"]', '2024-11-16 22:43:25', NULL, '2024-11-16 22:40:24', '2024-11-16 22:43:25'),
(90, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '8edc0800d4f0e48d981083144d75a1672962f32369ce5ec8bd2504ab222ddb3c', '[\"*\"]', '2024-11-16 22:44:42', NULL, '2024-11-16 22:43:31', '2024-11-16 22:44:42'),
(91, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'fade8a253f54ae5ca01b43b38abc799888151b8ff0d098508ca2e11360b35821', '[\"*\"]', '2024-11-16 23:12:12', NULL, '2024-11-16 22:44:51', '2024-11-16 23:12:12'),
(92, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'b69467da0d7a29b3685b5c7d52d9fb8953ca5657ada3052ae552311fc5e7d48b', '[\"*\"]', '2024-11-16 23:18:14', NULL, '2024-11-16 23:12:20', '2024-11-16 23:18:14'),
(93, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '85cf7bdc00d6a57621578e97de8cf59741262e7f4a257d52c8e0f8f6ef639bc7', '[\"*\"]', '2024-11-17 00:00:27', NULL, '2024-11-16 23:14:22', '2024-11-17 00:00:27'),
(94, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '0de87b54e90851396d3cb1fe1180e012d612dc1c10050720cf92c0bb2aa7c940', '[\"*\"]', '2024-11-17 02:52:05', NULL, '2024-11-16 23:18:21', '2024-11-17 02:52:05'),
(95, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'ba426c79fc7992bd887d45db177867923303a5d9c3d0644132b0378b732fec13', '[\"*\"]', '2024-11-17 17:58:47', NULL, '2024-11-17 17:32:19', '2024-11-17 17:58:47'),
(96, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '00349ef01e811774112e74dedbd1b4bcca05499e16b3829d5b11e95ae5e18329', '[\"*\"]', '2024-11-17 17:32:41', NULL, '2024-11-17 17:32:31', '2024-11-17 17:32:41'),
(97, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '8d67cbfba1de672af1a61ffddfb41139f2c376fdfbda4eab0eae49b88c00e402', '[\"*\"]', '2024-11-18 09:25:23', NULL, '2024-11-18 07:02:04', '2024-11-18 09:25:23'),
(98, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'f5baf1eddc14f614a6f973f9d72349313b732cbf1a8e4b0905fd538255204dc8', '[\"*\"]', '2024-11-18 09:28:21', NULL, '2024-11-18 07:02:12', '2024-11-18 09:28:21'),
(99, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'e0acb7a9f4eed55b2e7c69895302db19b278638e134a054660a4a6cf780607cd', '[\"*\"]', '2024-11-18 08:04:33', NULL, '2024-11-18 07:38:09', '2024-11-18 08:04:33'),
(100, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '9a138f14c71cd228cd90b8a2e2c3f8b9e67ea7d62c5136836b88b6d3b45bc1b1', '[\"*\"]', '2024-11-19 17:43:22', NULL, '2024-11-19 16:58:58', '2024-11-19 17:43:22'),
(101, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '8ea06aa5f038c3c9a71e08b22025811e2818217f3f01f890b4d536821bd17f0f', '[\"*\"]', '2024-11-19 17:43:09', NULL, '2024-11-19 16:59:53', '2024-11-19 17:43:09'),
(102, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '932ea0ea8e5bc8fa7bfcde0c39f0573a88c6bc5102db99b4582b368d3c7e2120', '[\"*\"]', '2024-11-19 17:51:39', NULL, '2024-11-19 17:49:50', '2024-11-19 17:51:39'),
(103, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'a2883ee899d5071c90f7d58a342a1e91a0db2068904dc93754555a7a0d3396e2', '[\"*\"]', '2024-11-22 21:23:10', NULL, '2024-11-22 21:21:42', '2024-11-22 21:23:10'),
(104, 'App\\Models\\User', 2, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', 'e2515cdba0dc268a08396bfc7799f87a20a56ac2e6217917fd7e44744c8dcb9b', '[\"*\"]', '2024-11-22 21:29:43', NULL, '2024-11-22 21:22:30', '2024-11-22 21:29:43'),
(105, 'App\\Models\\User', 1, 'pDE6g70A=ZE7medrby5O3V$S22%3=R&9h', '99a9be424216cb3f0fd28d5040a4f6816af02c628befb2df2c1655d13c219c52', '[\"*\"]', '2024-11-22 21:28:04', NULL, '2024-11-22 21:24:43', '2024-11-22 21:28:04');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `item_stocks` int(11) NOT NULL,
  `item_price` int(11) NOT NULL,
  `flag` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `image_url`, `item_name`, `item_description`, `item_stocks`, `item_price`, `flag`, `created_at`, `updated_at`) VALUES
(1, 'http://127.0.0.1:8000/storage/product/image-AePFZU5M19HQJKTxP2n3nOUjpNNAbLKHcJ9G2IzZ.jpeg', 'Empty Gallon', 'A 1L Empty Gallon', 70, 100, 1, '2024-11-11 17:55:18', '2024-11-12 01:53:42'),
(2, 'http://127.0.0.1:8000/storage/product/image-mzUDKXZN3DFgGAs0qSl7PCnODgxOVuSykz14F4Wa.jpeg', 'Water Bottle Caps', 'A water bottle caps.', 63, 20, 1, '2024-11-11 17:55:42', '2024-11-19 17:34:39'),
(3, 'http://127.0.0.1:8000/storage/product/image-FgNuVr04vggK3BT6WdwJL27wNT5Z6H1rYsEs2OmP.png', 'Test1', 'Testinng', 8, 11, 1, '2024-11-16 18:54:33', '2024-11-19 17:32:18'),
(4, 'http://127.0.0.1:8000/storage/product/image-KueNwdKcaiFh0DKVtxXhQld08UTKIh0KlYgv6upG.jpeg', 'Small Bottles', 'A small empty water bottle for branding.', 92, 10, 1, '2024-11-16 19:11:56', '2024-11-19 17:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `refills`
--

CREATE TABLE `refills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `gallon_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`gallon_details`)),
  `delivery_type` varchar(255) NOT NULL,
  `mop` varchar(255) NOT NULL,
  `delivery_date` varchar(255) NOT NULL,
  `t_refill_fee` int(11) NOT NULL,
  `t_delivery_fee` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `refills`
--

INSERT INTO `refills` (`id`, `user_id`, `gallon_details`, `delivery_type`, `mop`, `delivery_date`, `t_refill_fee`, `t_delivery_fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Door-To-Door', 'COD', '2024-11-17', 25, 5, 'Completed', '2024-11-15 21:00:59', '2024-11-15 21:01:15'),
(2, 1, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Door-To-Door', 'COD', '2024-11-17', 25, 5, 'Completed', '2024-11-15 21:02:49', '2024-11-16 17:02:33'),
(4, 2, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Walk-In', 'Over-The-Counter', '2024-11-17', 25, 0, 'Completed', '2024-11-16 17:11:31', '2024-11-16 17:11:31'),
(5, 2, '[{\"gallon_size\":\"2L Gallon\",\"gallon_each_price\":30,\"gallon_each_dfee\":7,\"no_of_gallon\":12}]', 'Walk-In', 'Over-The-Counter', '2024-11-17', 360, 0, 'Completed', '2024-11-16 17:12:38', '2024-11-16 17:12:38'),
(6, 1, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Door-To-Door', 'COD', '2024-11-18', 25, 5, 'Completed', '2024-11-16 17:17:28', '2024-11-16 17:22:06'),
(7, 1, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Door-To-Door', 'Online Pay', '2024-11-18', 25, 5, 'Completed', '2024-11-16 17:22:20', '2024-11-22 21:22:21'),
(8, 1, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Door-To-Door', 'COD', '2024-11-18', 25, 5, 'Completed', '2024-11-16 23:52:21', '2024-11-16 23:53:50'),
(9, 1, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Door-To-Door', 'COD', '2024-11-22', 25, 5, 'Waiting Delivery', '2024-11-19 17:01:36', '2024-11-19 17:01:36'),
(10, 1, '[{\"gallon_size\":\"1L Gallon\",\"gallon_each_price\":25,\"gallon_each_dfee\":5,\"no_of_gallon\":1}]', 'Door-To-Door', 'Online Pay', '2024-11-21', 25, 5, 'Waiting Delivery', '2024-11-19 17:16:33', '2024-11-22 21:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `resource` varchar(255) NOT NULL,
  `resource_ref` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `rate` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `user_id`, `resource`, `resource_ref`, `details`, `rate`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 'Orders', 'Ref_92743', 'Empty Gallon (1pcs), Water Bottle Caps (1pcs)', 5, 'Test comment', '2024-11-12 05:23:06', '2024-11-12 05:23:06'),
(2, 1, 'Orders', 'Ref_92743', 'Empty Gallon (1pcs), Water Bottle Caps (1pcs)', 5, 'Test comment', '2024-11-12 05:23:27', '2024-11-12 05:23:27'),
(3, 1, 'Orders', 'Ref_92743', 'Empty Gallon (1pcs)', 5, 'Test comment', '2024-11-12 05:23:37', '2024-11-12 05:23:37'),
(4, 1, 'Orders', 'Ref_92743', 'Empty Gallon (1pcs), Water Bottle Caps (1pcs)', 5, 'Test comment', '2024-11-12 05:23:46', '2024-11-12 05:23:46'),
(5, 1, 'Orders', 'Ref_92743', 'Empty Gallon (1pcs), Water Bottle Caps (1pcs)', 5, 'Test comment', '2024-11-12 05:38:27', '2024-11-12 05:38:27'),
(6, 1, 'Orders', 'Ref_92743', 'Empty Gallon (1pcs), Water Bottle Caps (1pcs)', 4, '122', '2024-11-12 05:42:45', '2024-11-12 05:42:45'),
(7, 1, 'Orders', 'Ref_92743', 'Empty Gallon (1pcs), Water Bottle Caps (1pcs)', 5, '1222', '2024-11-12 05:46:47', '2024-11-12 05:46:47'),
(8, 1, 'Orders', 'Ref_42564', 'Water Bottle Caps (1pcs)', 5, 'Hiiii', '2024-11-12 05:55:05', '2024-11-12 05:55:05'),
(9, 1, 'Orders', 'Ref_41617', 'Water Bottle Caps (1pcs)', 5, 'Perfect!', '2024-11-16 21:02:04', '2024-11-16 21:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9RItDithUdhU2dBk51bNXQvJd5D7g37aZ6msHlzI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidG44bVRsMXlSVUZlNWZKdzZwRmQwdGFKemEwY2UxTFJtUVNiclo0cCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zYW5jdHVtL2NzcmYtY29va2llIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1732339684),
('DQNRJ1Vnpr3y6bU9PeJvtRqbYO7lIX61CoF4nTnv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFpYMzI4OEhWbWpObHp1MG04M2gyUjVrV2JiTHdqaGNpVFpheVMxVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zYW5jdHVtL2NzcmYtY29va2llIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1732339782),
('lLJGSu1dzckYktZqlafUgJbXzaM9RQG5ipYNwnqx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoianQ0R21UZlhOOFVpWkJHSUJ0YWpXNkdsWmlHWlp2N3dGU2hLMWtvciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zYW5jdHVtL2NzcmYtY29va2llIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1732339282),
('z4P1esIxDhoJzGZVNJ2UsKNU0Mex0rb4J5nxsVKC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFlWMjB3aEIzUzQyeVNad0Q2VzIxWTBwdmRFOGJCYjJqNjQzaDBxSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zYW5jdHVtL2NzcmYtY29va2llIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1732339279),
('ZTZc3LKyNRAXHbIrdiTsYqPE7IpX7dR4gKPZOH06', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemRjb2NkbkRJSEw3aE5UMVJwOTZMMXRnZ0x1c0NrVE1IVlFxVHJxWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zYW5jdHVtL2NzcmYtY29va2llIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1732339278);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` varchar(255) NOT NULL DEFAULT 'user',
  `flag` varchar(255) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `user_role`, `flag`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'User Account', 'user@gmail.com', NULL, '$2y$12$nNtBek30DlQ4GqffAVGA/u8aOZ.ZZxWRj5ipqC4SYox1FcbanLZQC', 'user', '1', NULL, '2024-11-02 19:57:59', '2024-11-22 21:24:32'),
(2, 'Admin Account', 'admin@gmail.com', NULL, '$2y$12$7MNUznYtlxeeujB3scOHMOYp.r.U6RDUiIe7glv3EBNDNBsEiX/WK', 'admin', '1', NULL, '2024-11-02 19:58:24', '2024-11-18 08:21:09'),
(3, 'Staff Account', 'staff@gmail.com', NULL, '$2y$12$lf2a2qcIAfpdJr7vO6A6X.A4GNzCSYxK8hxbWe5BwiNrh0t204dG2', 'staff', '1', NULL, '2024-11-16 21:32:57', '2024-11-22 21:23:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gallon_types`
--
ALTER TABLE `gallon_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refills`
--
ALTER TABLE `refills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallon_types`
--
ALTER TABLE `gallon_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `refills`
--
ALTER TABLE `refills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
