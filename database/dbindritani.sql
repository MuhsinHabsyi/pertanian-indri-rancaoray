-- --------------------------------------------------------
-- File ini adalah versi PERBAIKAN dari dump aslinya.
-- Perbaikan: semua FOREIGN KEY dipindahkan ke bagian paling akhir
-- (via ALTER TABLE), setelah semua tabel dibuat dan semua data
-- di-insert. Ini menghindari error #1005 (errno 150) yang terjadi
-- karena urutan CREATE TABLE tidak sesuai urutan dependency FK,
-- dan phpMyAdmin kadang memecah import jadi beberapa koneksi
-- sehingga SET FOREIGN_KEY_CHECKS=0 tidak konsisten terbawa.
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

SET FOREIGN_KEY_CHECKS=0;

-- ============================================================
-- BAGIAN 1: CREATE TABLE (tanpa FOREIGN KEY, index biasa tetap ada)
-- ============================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Owner','Operational','Customer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` enum('Seed','Fertilizer','Rice') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `online_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `online_price` double DEFAULT NULL,
  `stock_available` int NOT NULL DEFAULT '0',
  `online_stock` int NOT NULL DEFAULT '0',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_for_sale_online` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `production_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `planting_start_date` date NOT NULL,
  `crop_season` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fertilizing_date` date DEFAULT NULL,
  `estimated_harvest_date` date DEFAULT NULL,
  `estimated_harvest` int NOT NULL DEFAULT '0',
  `actual_harvest_date` date DEFAULT NULL,
  `total_harvest` int NOT NULL DEFAULT '0',
  `status` enum('Ongoing','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ongoing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_schedules_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `package_size` int NOT NULL,
  `online_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online_price` double DEFAULT NULL,
  `online_stock` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_packages_product_id_package_size_unique` (`product_id`,`package_size`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `procurement_source` enum('Mandiri','Subsidi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mandiri',
  `submission_date` date NOT NULL,
  `total_cost` double NOT NULL DEFAULT '0',
  `validation_status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `receipt_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_realized` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchases_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` double NOT NULL,
  `subtotal` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_items_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `operational_id` bigint unsigned DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `total_payment` double NOT NULL,
  `shipping_cost` double NOT NULL DEFAULT '0',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Online',
  `order_status` enum('Pending','Paid','Processing','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_operational_id_foreign` (`operational_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` double NOT NULL,
  `subtotal` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `material_usages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `production_schedule_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `usage_date` date NOT NULL,
  `quantity_used` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_usages_production_schedule_id_foreign` (`production_schedule_id`),
  KEY `material_usages_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- BAGIAN 2: INSERT DATA
-- ============================================================

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
	(1, 'Ibu Indri Apriliani', 'pemilik', '$2y$12$gLHv7LUUdfgXEv/28VKnDuQ3LiyIJRUWmdZUXqHvDv5wyfoaKesnu', 'Owner', '2026-07-05 23:38:03', '2026-07-05 23:38:03'),
	(2, 'Staf Operasional', 'operasional', '$2y$12$Cbf5vLwyCJezCs9I00Z5B.BeEerG18KRmyiMAo3i2hlZTlktWJLD6', 'Operational', '2026-07-05 23:38:04', '2026-07-05 23:38:04'),
	(3, 'Pelanggan', 'pelanggan', '$2y$12$bRLKcke4xeJMZ5jaGSWyaO9.ff.UgPnwIkJjTMCCSnyQH1U77/D..', 'Customer', '2026-07-05 23:38:04', '2026-07-05 23:38:04'),
	(4, 'pelanggan', 'pelanggan2', '$2y$12$XQ6aau9IY2Ikmg2ADrxIpOszNNyez5HKd5fpI6fTFkphb8j2Atee6', 'Customer', '2026-07-06 06:34:39', '2026-07-06 06:34:39'),
	(5, 'pelanggan 3', 'pelanggan3', '$2y$12$dTn/Zykvw6t/Y2peRSJZyevSCRmDmD1xFlD75Kw9yNCOGH4s0W/1u', 'Customer', '2026-07-28 10:23:57', '2026-07-28 10:23:57'),
	(6, 'Budi Operasional', 'budi_operasional', '$2y$12$GKY5LrAUDw4ZMu1cLmn9gOzWxzvq/T1chrr7B/ANt366zuR83iacq', 'Operational', '2026-08-03 05:36:39', '2026-08-03 05:36:39');

INSERT INTO `products` (`id`, `category`, `name`, `online_name`, `unit`, `price`, `online_price`, `stock_available`, `online_stock`, `image_path`, `is_for_sale_online`, `created_at`, `updated_at`) VALUES
	(1, 'Seed', 'Bibit', NULL, 'Kg', 10000, NULL, 564, 0, NULL, 0, '2026-07-05 23:38:04', '2026-08-03 01:47:21'),
	(2, 'Fertilizer', 'Pupuk Urea', NULL, 'Kg', 5000, NULL, 388, 0, NULL, 0, '2026-07-05 23:38:04', '2026-07-28 11:22:59'),
	(3, 'Rice', 'Beras Pandan Wangi', 'Beras Pandan Wangi Premium 20KG', 'Kg', 15000, 30000, 21820, 4000, 'uploads/products/1783503507_chopper.jpg', 1, '2026-07-05 23:38:04', '2026-08-11 18:13:14');

INSERT INTO `production_schedules` (`id`, `user_id`, `planting_start_date`, `crop_season`, `fertilizing_date`, `estimated_harvest_date`, `estimated_harvest`, `actual_harvest_date`, `total_harvest`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, '2026-07-06', 'MT 2 (Apr-Jul)', NULL, '2026-11-03', 1500, '2026-07-06', 50, 'Completed', '2026-07-05 23:38:50', '2026-07-08 03:08:08'),
	(2, 1, '2026-07-06', 'MT 2 (Apr-Jul)', NULL, '2026-11-03', 4000, '2026-07-23', 3800, 'Completed', '2026-07-05 23:59:00', '2026-07-08 03:08:08'),
	(3, 1, '2026-07-06', 'MT 2 (Apr-Jul)', '2026-07-17', '2026-11-03', 2200, NULL, 0, 'Ongoing', '2026-07-06 01:17:44', '2026-07-08 03:08:08'),
	(4, 1, '2026-07-06', 'MT 2 (Apr-Jul)', '2026-07-06', '2026-11-03', 500, '2026-07-08', 600, 'Completed', '2026-07-06 01:24:00', '2026-07-08 03:10:32'),
	(5, 1, '2026-07-06', 'MT 2 (Apr-Jul)', '2026-07-06', '2026-11-03', 100, '2026-07-06', 110, 'Completed', '2026-07-06 01:28:19', '2026-07-06 01:28:43'),
	(6, 1, '2026-07-30', 'MT 2 (Apr-Jul)', '2026-07-30', '2026-11-27', 50000, '2026-07-06', 50000, 'Completed', '2026-07-06 01:33:10', '2026-07-06 01:33:54'),
	(7, 1, '2026-08-01', 'MT 3 (Aug-Oct)', NULL, '2026-11-29', 1000, NULL, 0, 'Ongoing', '2026-08-03 01:47:21', '2026-08-03 01:47:21');

INSERT INTO `product_packages` (`id`, `product_id`, `package_size`, `online_name`, `online_price`, `online_stock`, `is_active`, `image_path`, `created_at`, `updated_at`) VALUES
	(1, 3, 5, 'Beras Pandan Wangi Premium 5 Kg', 75000, 50, 1, 'uploads/products/1783950642_pkg5_pandanwangi.jpg', '2026-07-08 02:33:08', '2026-08-11 18:12:44'),
	(2, 3, 10, 'Beras Pandan Wangi Premium 10 Kg', 140000, 50, 1, 'uploads/products/1783950648_pkg10_pandanwangi.jpg', '2026-07-08 02:34:03', '2026-08-11 18:13:14'),
	(3, 3, 20, 'Beras Pandan Wangi (20 Kg)', 300000, 900, 1, 'uploads/products/1783950656_pkg20_pandanwangi.jpg', '2026-07-08 02:37:06', '2026-07-13 06:50:56');

INSERT INTO `purchases` (`id`, `user_id`, `procurement_source`, `submission_date`, `total_cost`, `validation_status`, `receipt_proof`, `is_realized`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Mandiri', '2026-07-06', 500000, 'Rejected', NULL, 0, '2026-07-05 23:50:43', '2026-07-05 23:50:49'),
	(2, 1, 'Mandiri', '2026-07-06', 30000, 'Approved', 'nota.jpg', 0, '2026-07-05 23:51:03', '2026-07-05 23:51:28'),
	(3, 1, 'Mandiri', '2026-07-30', 30000, 'Approved', 'nota.jpg', 0, '2026-07-06 01:32:15', '2026-07-06 01:32:31'),
	(4, 1, 'Mandiri', '2026-07-30', 50000, 'Approved', 'nota.jpg', 0, '2026-07-06 01:32:46', '2026-07-06 01:32:52'),
	(5, 1, 'Subsidi', '2026-07-06', 0, 'Approved', 'nota.jpg', 0, '2026-07-06 01:42:31', '2026-07-06 01:42:41'),
	(6, 1, 'Mandiri', '2026-07-13', 300000, 'Rejected', NULL, 0, '2026-07-13 06:05:52', '2026-07-13 06:11:41'),
	(7, 1, 'Mandiri', '2026-07-13', 400000, 'Rejected', NULL, 0, '2026-07-13 06:11:26', '2026-07-13 06:11:51'),
	(8, 1, 'Mandiri', '2026-07-13', 200000, 'Approved', 'nota.jpg', 0, '2026-07-13 06:12:16', '2026-07-13 06:15:14'),
	(9, 1, 'Mandiri', '2026-07-28', 60000, 'Approved', 'receipts/s27aORB2NlutwWCpLrqNi7nCs1mZGUJ12tVfyh94.jpg', 1, '2026-07-28 10:50:04', '2026-07-28 11:20:50'),
	(10, 1, 'Subsidi', '2026-07-28', 0, 'Approved', 'receipts/asCm6N4i0iaX2oJigAcQ77CXRkRg6KmBqNVgLkKD.jpg', 1, '2026-07-28 11:22:01', '2026-07-28 11:22:59');

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 100, 5000, 500000, '2026-07-05 23:50:44', '2026-07-05 23:50:44'),
	(2, 2, 2, 200, 150, 30000, '2026-07-05 23:51:03', '2026-07-05 23:51:03'),
	(3, 3, 1, 1000, 30, 30000, '2026-07-06 01:32:15', '2026-07-06 01:32:15'),
	(4, 4, 2, 1000, 50, 50000, '2026-07-06 01:32:46', '2026-07-06 01:32:46'),
	(5, 5, 1, 10, 0, 0, '2026-07-06 01:42:31', '2026-07-06 01:42:31'),
	(6, 6, 1, 50, 6000, 300000, '2026-07-13 06:05:52', '2026-07-13 06:05:52'),
	(7, 7, 1, 20, 20000, 400000, '2026-07-13 06:11:26', '2026-07-13 06:11:26'),
	(8, 8, 1, 10, 20000, 200000, '2026-07-13 06:12:16', '2026-07-13 06:12:16'),
	(9, 9, 1, 50, 1200, 60000, '2026-07-28 10:50:04', '2026-07-28 10:50:04'),
	(10, 10, 2, 20, 0, 0, '2026-07-28 11:22:01', '2026-07-28 11:22:01');

INSERT INTO `orders` (`id`, `customer_id`, `operational_id`, `transaction_date`, `shipping_address`, `total_payment`, `shipping_cost`, `payment_method`, `sale_channel`, `order_status`, `created_at`, `updated_at`) VALUES
	(1, 3, 1, '2026-07-06 07:10:47', NULL, 75000, 0, 'Midtrans', 'Online', 'Completed', '2026-07-06 00:10:47', '2026-07-06 00:14:51'),
	(2, 3, NULL, '2026-07-06 07:44:58', NULL, 150000, 0, 'Midtrans', 'Online', 'Pending', '2026-07-06 00:44:58', '2026-07-06 00:44:58'),
	(3, 3, NULL, '2026-07-06 07:46:24', NULL, 150000, 0, 'Midtrans', 'Online', 'Pending', '2026-07-06 00:46:24', '2026-07-06 00:46:24'),
	(4, 3, NULL, '2026-07-06 07:52:27', NULL, 150000, 0, 'Midtrans', 'Online', 'Pending', '2026-07-06 00:52:27', '2026-07-06 00:52:27'),
	(5, 3, NULL, '2026-07-06 07:53:03', NULL, 150000, 0, 'Midtrans', 'Online', 'Pending', '2026-07-06 00:53:03', '2026-07-06 00:53:03'),
	(6, 3, 1, '2026-07-06 07:56:23', NULL, 675000, 0, 'Midtrans', 'Online', 'Completed', '2026-07-06 00:56:23', '2026-07-06 01:29:20'),
	(7, 3, 1, '2026-07-06 08:34:12', NULL, 150000000, 0, 'Midtrans', 'Online', 'Completed', '2026-07-06 01:34:12', '2026-07-06 01:34:41'),
	(8, 3, NULL, '2026-07-06 09:29:08', NULL, 300000, 0, 'Midtrans', 'Online', 'Pending', '2026-07-06 02:29:08', '2026-07-06 02:29:08'),
	(9, 3, NULL, '2026-07-06 10:36:00', 'Jl. Dipatiukur', 1515000, 15000, 'Midtrans', 'Online', 'Pending', '2026-07-06 03:36:00', '2026-07-06 03:36:00'),
	(10, 3, NULL, '2026-07-06 13:57:36', 'Jl. Test No. 123, Bandung', 90000, 15000, 'Midtrans', 'Online', 'Pending', '2026-07-06 06:57:36', '2026-07-06 06:57:36'),
	(11, 4, 1, '2026-07-06 14:00:43', 'Jl. dIPADNKJFNDJNM', 165000, 15000, 'Midtrans', 'Online', 'Completed', '2026-07-06 07:00:43', '2026-07-06 07:02:17'),
	(12, 4, 1, '2026-07-13 13:56:21', 'Jl. Contoh', 90000, 15000, 'Midtrans', 'Online', 'Completed', '2026-07-13 06:56:21', '2026-07-13 07:20:36'),
	(13, 4, 1, '2026-07-14 01:57:18', 'Jl. Jakuang', 155000, 15000, 'Midtrans', 'Online', 'Completed', '2026-07-13 18:57:18', '2026-07-13 18:58:46'),
	(14, 4, 1, '2026-07-14 02:04:34', 'Jl. Testing', 540000, 15000, 'Midtrans', 'Online', 'Completed', '2026-07-13 19:04:34', '2026-07-13 19:05:17'),
	(15, 4, 1, '2026-07-26 12:14:13', 'Jl. dsahbdsakjbdasd', 315000, 15000, 'Midtrans', 'Online', 'Completed', '2026-07-26 05:14:13', '2026-07-26 05:16:12'),
	(16, 4, NULL, '2026-08-03 09:01:43', 'Jl. Raya 1', 915000, 15000, 'Midtrans', 'Online', 'Paid', '2026-08-03 02:01:43', '2026-08-03 02:01:59');

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 3, 5, 15000, 75000, '2026-07-06 00:10:47', '2026-07-06 00:10:47'),
	(2, 2, 3, 10, 15000, 150000, '2026-07-06 00:44:58', '2026-07-06 00:44:58'),
	(3, 3, 3, 10, 15000, 150000, '2026-07-06 00:46:24', '2026-07-06 00:46:24'),
	(4, 4, 3, 10, 15000, 150000, '2026-07-06 00:52:27', '2026-07-06 00:52:27'),
	(5, 5, 3, 10, 15000, 150000, '2026-07-06 00:53:03', '2026-07-06 00:53:03'),
	(6, 6, 3, 45, 15000, 675000, '2026-07-06 00:56:23', '2026-07-06 00:56:23'),
	(7, 7, 3, 10000, 15000, 150000000, '2026-07-06 01:34:12', '2026-07-06 01:34:12'),
	(8, 8, 3, 20, 15000, 300000, '2026-07-06 02:29:08', '2026-07-06 02:29:08'),
	(9, 9, 3, 100, 15000, 1500000, '2026-07-06 03:36:00', '2026-07-06 03:36:00'),
	(10, 10, 3, 5, 15000, 75000, '2026-07-06 06:57:36', '2026-07-06 06:57:36'),
	(11, 11, 3, 10, 15000, 150000, '2026-07-06 07:00:43', '2026-07-06 07:00:43'),
	(12, 12, 3, 5, 15000, 75000, '2026-07-13 06:56:21', '2026-07-13 06:56:21'),
	(13, 13, 3, 10, 14000, 140000, '2026-07-13 18:57:18', '2026-07-13 18:57:18'),
	(14, 14, 3, 35, 15000, 525000, '2026-07-13 19:04:34', '2026-07-13 19:04:34'),
	(15, 15, 3, 20, 15000, 300000, '2026-07-26 05:14:13', '2026-07-26 05:14:13'),
	(16, 16, 3, 60, 15000, 900000, '2026-08-03 02:01:43', '2026-08-03 02:01:43');

INSERT INTO `material_usages` (`id`, `production_schedule_id`, `product_id`, `usage_date`, `quantity_used`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, '2026-07-06', 150, '2026-07-05 23:58:30', '2026-07-05 23:58:30'),
	(2, 2, 1, '2026-07-06', 30, '2026-07-06 01:14:32', '2026-07-06 01:14:32'),
	(3, 2, 1, '2026-07-06', 10, '2026-07-06 01:17:17', '2026-07-06 01:17:17'),
	(4, 2, 2, '2026-07-06', 50, '2026-07-06 01:17:17', '2026-07-06 01:17:17'),
	(5, 4, 1, '2026-07-06', 5, '2026-07-06 01:24:00', '2026-07-06 01:24:00'),
	(6, 4, 2, '2026-07-06', 10, '2026-07-06 01:24:27', '2026-07-06 01:24:27'),
	(7, 3, 2, '2026-07-06', 20, '2026-07-06 01:27:53', '2026-07-06 01:27:53'),
	(8, 5, 1, '2026-07-06', 1, '2026-07-06 01:28:19', '2026-07-06 01:28:19'),
	(9, 5, 2, '2026-07-06', 2, '2026-07-06 01:28:27', '2026-07-06 01:28:27'),
	(10, 6, 1, '2026-07-30', 500, '2026-07-06 01:33:10', '2026-07-06 01:33:10'),
	(11, 6, 2, '2026-07-30', 500, '2026-07-06 01:33:35', '2026-07-06 01:33:35'),
	(12, 3, 2, '2026-07-16', 100, '2026-07-08 03:03:14', '2026-07-08 03:03:14'),
	(13, 3, 2, '2026-07-17', 100, '2026-07-08 03:03:33', '2026-07-08 03:03:33'),
	(14, 7, 1, '2026-08-01', 10, '2026-08-03 01:47:21', '2026-08-03 01:47:21');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '2026_06_21_110619_create_products_table', 1),
	(3, '2026_06_21_110652_create_purchases_table', 1),
	(4, '2026_06_21_110706_create_purchase_items_table', 1),
	(5, '2026_06_21_110719_create_production_schedules_table', 1),
	(6, '2026_06_21_110726_create_material_usages_table', 1),
	(7, '2026_06_21_110732_create_orders_table', 1),
	(8, '2026_06_21_110737_create_order_items_table', 1),
	(9, '2026_06_21_124135_create_sessions_table', 1),
	(10, '2026_07_06_081256_add_columns_to_production_schedules_table', 2),
	(11, '2026_07_06_083929_add_procurement_source_to_purchases_table', 3),
	(12, '2026_07_06_103042_add_shipping_to_orders_table', 4),
	(13, '2026_07_08_083500_add_online_fields_to_products_and_orders_table', 5),
	(14, '2026_07_08_092517_create_product_packages_table', 6),
	(15, '2026_07_08_094215_add_image_path_to_product_packages_table', 7),
	(16, '2026_07_08_100753_update_existing_production_schedules_data', 8),
	(17, '2026_07_29_000000_add_is_realized_to_purchases_table', 9);

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('hRU2HrykBhwRJEMyDarzvwqAHhk7KayC3NQiR9a0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJyNDcwb2JwSTFxaGtQazBxM2xWbkI4QmV1TEd2VW5aaTZucTd2dzk4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9pbnRlcm5hbFwvdXNlcnMiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5faW50ZXJuYWxfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1785286118),
	('lKBRV8VXzBPigI9gRNfvbYWIlzdkemcwe4Dq30Ra', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWNHJrQ2ExNkd3T3JVMURoMkdSM0d6ZWRzcnBTR3J4R0hpMmN5b1pFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jYXJ0Iiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo0LCJjYXJ0Ijp7IjMtMjAiOnsicHJvZHVjdF9pZCI6MywicGFja2FnZV9pZCI6MywibmFtZSI6IkJlcmFzIFBhbmRhbiBXYW5naSAoMjAgS2cpIiwicHJpY2UiOjMwMDAwMCwicGFja2FnZV9zaXplIjoyMCwicXVhbnRpdHkiOjF9fX0=', 1785747764),
	('OSKohefdNXzFHj0FyTWz5uioEZgHHZ5iHvdZ0tWm', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJXMG1xcE5meG11OHY3VXRUOEZEcXpSN2dPTzZ3SlRZU0hnYTZYN2llIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9vcmRlcnMiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjR9', 1785497382),
	('sibtMDISenTLfxNOOu4J73rcyehOXKlxKQj1fDuR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI0WDI5dEV5V0F4ZFRBSG9LMHE4UFQ1ODB5RjBQdHlHQlZZa0x5ZUpKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9pbnRlcm5hbFwvb3JkZXJzIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX2ludGVybmFsXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1785760665),
	('TBQGWVemIE4rcAx8adu7U7p7yixInjUz9fnR9rKn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIzVGtHTDBTbGZHSFE4ZUFIanV5eFFMVE16Wkc4eFozaFB2eDZIRWhSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1785730749),
	('ZIxq3RV5t33eEBn2HMnrH9lFk948zGS0rYcvFtH2', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ4cUlwTnYxU3ZkVlFuaHdzWXJPTWxOekxzWUFqUEx2QmNOcUlnbTRaIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5faW50ZXJuYWxfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1786497227);

-- ============================================================
-- BAGIAN 3: TAMBAHKAN SEMUA FOREIGN KEY DI AKHIR
-- (semua tabel & data sudah ada, jadi ini pasti aman)
-- ============================================================

ALTER TABLE `production_schedules`
  ADD CONSTRAINT `production_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `product_packages`
  ADD CONSTRAINT `product_packages_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_operational_id_foreign` FOREIGN KEY (`operational_id`) REFERENCES `users` (`id`);

ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `material_usages`
  ADD CONSTRAINT `material_usages_production_schedule_id_foreign` FOREIGN KEY (`production_schedule_id`) REFERENCES `production_schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_usages_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

SET FOREIGN_KEY_CHECKS=1;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;