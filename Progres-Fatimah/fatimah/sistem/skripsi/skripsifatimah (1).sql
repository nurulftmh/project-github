-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 06:26 PM
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
-- Database: `skripsifatimah`
--

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
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `obat_id` bigint(20) UNSIGNED NOT NULL,
  `bulan` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `obat_id`, `bulan`, `jumlah`, `label`, `created_at`, `updated_at`) VALUES
(4, 1, '2021-01-31', 1900, 'sedikit', '2024-10-18 19:18:45', '2024-10-21 04:30:05'),
(5, 1, '2021-02-28', 500, 'sangat sedikit', '2024-10-18 19:20:16', '2024-10-18 19:20:16'),
(6, 1, '2021-03-31', 500, 'sangat sedikit', '2024-10-18 19:21:10', '2024-10-18 19:21:10'),
(7, 1, '2021-04-30', 0, 'sangat sedikit', '2024-10-18 19:22:15', '2024-10-18 19:24:32'),
(8, 1, '2021-05-31', 0, 'sangat sedikit', '2024-10-18 19:23:07', '2024-10-18 19:24:44'),
(9, 1, '2021-06-30', 0, 'sangat sedikit', '2024-10-18 19:24:06', '2024-10-18 19:24:06'),
(10, 1, '2021-07-31', 0, 'sangat sedikit', '2024-10-18 19:25:58', '2024-10-18 19:25:58'),
(11, 1, '2021-08-31', 0, 'sangat sedikit', '2024-10-18 19:27:17', '2024-10-18 19:27:17'),
(12, 1, '2021-09-19', 0, 'sangat sedikit', '2024-10-18 19:27:58', '2024-10-18 19:27:58'),
(13, 1, '2021-10-31', 1500, 'sedikit', '2024-10-18 19:28:49', '2024-10-18 19:28:49'),
(14, 1, '2021-11-30', 1500, 'sedikit', '2024-10-18 19:29:34', '2024-10-18 19:29:34'),
(15, 1, '2021-12-31', 600, 'sangat sedikit', '2024-10-18 19:30:20', '2024-10-18 19:30:20'),
(16, 1, '2022-01-31', 3300, 'sedang', '2024-10-18 19:31:45', '2024-10-18 19:34:07'),
(17, 1, '2022-02-28', 2000, 'sedikit', '2024-10-18 19:33:19', '2024-10-18 19:33:19'),
(18, 1, '2022-03-31', 1400, 'sedikit', '2024-10-18 19:35:24', '2024-10-18 19:35:24'),
(19, 1, '2022-04-30', 1900, 'sedikit', '2024-10-18 19:36:36', '2024-10-18 19:36:36'),
(20, 1, '2022-05-31', 1300, 'sedikit', '2024-10-18 19:37:25', '2024-10-18 19:37:25'),
(21, 1, '2022-06-30', 1500, 'sedikit', '2024-10-18 19:38:06', '2024-10-18 19:38:06'),
(22, 1, '2022-07-31', 2000, 'sedikit', '2024-10-18 19:39:06', '2024-10-18 19:39:06'),
(23, 1, '2022-08-31', 3100, 'sedang', '2024-10-18 19:40:07', '2024-10-18 19:40:07'),
(24, 1, '2022-09-30', 600, 'sangat sedikit', '2024-10-18 19:41:32', '2024-10-18 19:41:32'),
(25, 1, '2022-10-31', 3800, 'sedang', '2024-10-18 19:42:30', '2024-10-18 19:45:36'),
(26, 1, '2022-11-30', 3800, 'sedang', '2024-10-18 19:43:14', '2024-10-18 19:43:14'),
(27, 1, '2022-12-31', 3800, 'sedang', '2024-10-18 19:44:32', '2024-10-18 20:01:49'),
(28, 1, '2023-01-31', 3600, 'sedang', '2024-10-18 19:47:45', '2024-10-18 19:47:45'),
(29, 1, '2023-02-28', 1000, 'sangat sedikit', '2024-10-18 19:48:36', '2024-10-18 19:48:36'),
(30, 1, '2023-03-31', 0, 'sangat sedikit', '2024-10-18 19:49:36', '2024-10-18 19:49:52'),
(31, 1, '2023-04-30', 0, 'sangat sedikit', '2024-10-18 19:50:38', '2024-10-18 19:50:38'),
(32, 1, '2023-05-31', 0, 'sangat sedikit', '2024-10-18 19:52:21', '2024-10-18 19:54:19'),
(33, 1, '2023-06-30', 0, 'sangat sedikit', '2024-10-18 19:53:21', '2024-10-18 19:53:21'),
(34, 1, '2023-07-31', 1300, 'sedikit', '2024-10-18 19:55:31', '2024-10-18 19:56:42'),
(35, 1, '2023-08-31', 1500, 'sedikit', '2024-10-18 19:56:07', '2024-10-18 19:56:07'),
(36, 1, '2023-09-30', 2200, 'cukup', '2024-10-18 19:57:44', '2024-10-18 19:57:44'),
(37, 1, '2023-10-31', 1500, 'sedikit', '2024-10-18 19:58:32', '2024-10-18 19:58:32'),
(38, 1, '2023-11-30', 2500, 'cukup', '2024-10-18 19:59:25', '2024-10-18 19:59:25'),
(45, 1, '2023-12-31', 1700, 'sedikit', '2024-10-21 04:32:19', '2024-10-21 04:32:19'),
(47, 9, '2021-01-31', 2250, 'cukup', '2024-10-21 12:55:57', '2024-10-21 12:55:57'),
(48, 9, '2021-02-28', 1800, 'sedikit', '2024-10-21 12:56:29', '2024-10-21 12:56:29'),
(49, 9, '2021-03-31', 1800, 'sedikit', '2024-10-21 12:56:54', '2024-10-21 12:56:54'),
(50, 9, '2021-04-30', 1500, 'sedikit', '2024-10-21 12:57:33', '2024-10-21 12:57:33'),
(51, 9, '2021-05-31', 1200, 'sedikit', '2024-10-21 12:58:36', '2024-10-21 12:58:36'),
(52, 9, '2021-06-30', 1800, 'sedikit', '2024-10-21 12:59:26', '2024-10-21 12:59:26'),
(53, 9, '2021-07-31', 1200, 'sedikit', '2024-10-21 13:00:15', '2024-10-21 13:02:52'),
(54, 9, '2021-08-31', 2100, 'cukup', '2024-10-21 13:01:51', '2024-10-21 13:02:28'),
(55, 9, '2021-09-30', 2500, 'cukup', '2024-10-21 13:04:11', '2024-10-21 13:04:11'),
(56, 9, '2021-10-31', 3500, 'sedang', '2024-10-21 13:05:02', '2024-10-21 13:05:02'),
(57, 9, '2021-11-30', 1500, 'sedikit', '2024-10-21 13:05:48', '2024-10-21 13:10:25'),
(58, 9, '2021-12-31', 2100, 'cukup', '2024-10-21 13:06:12', '2024-10-21 13:06:12'),
(59, 9, '2022-01-31', 2400, 'cukup', '2024-10-21 13:08:09', '2024-10-21 13:08:09'),
(60, 9, '2022-02-28', 800, 'sangat sedikit', '2024-10-21 13:09:23', '2024-10-21 13:09:23'),
(61, 9, '2022-03-31', 700, 'sangat sedikit', '2024-10-21 13:11:48', '2024-10-21 13:11:48'),
(62, 9, '2022-04-30', 0, 'sangat sedikit', '2024-10-21 13:12:11', '2024-10-21 13:12:11'),
(63, 9, '2022-05-31', 0, 'sangat sedikit', '2024-10-21 13:12:39', '2024-10-21 13:12:39'),
(64, 9, '2022-06-30', 3100, 'sedang', '2024-10-21 13:13:10', '2024-10-21 13:13:10'),
(65, 9, '2022-07-31', 1900, 'sedikit', '2024-10-21 13:14:12', '2024-10-21 13:14:12'),
(66, 9, '2022-08-31', 4000, 'banyak', '2024-10-21 13:15:01', '2024-10-21 13:15:01'),
(67, 9, '2022-09-30', 3000, 'sedang', '2024-10-21 13:15:39', '2024-10-21 13:15:39'),
(68, 9, '2022-10-31', 3000, 'sedang', '2024-10-21 13:16:52', '2024-10-21 13:16:52'),
(69, 9, '2022-11-30', 2000, 'sedikit', '2024-10-21 13:18:00', '2024-10-21 13:18:00'),
(95, 9, '2022-12-31', 2000, 'sedikit', '2024-10-30 08:31:16', '2024-10-30 08:31:16'),
(96, 9, '2023-01-31', 2900, 'cukup', '2024-10-30 09:09:31', '2024-10-30 09:09:31'),
(97, 9, '2023-02-28', 600, 'sangat sedikit', '2024-10-30 09:10:09', '2024-10-30 09:10:09'),
(98, 9, '2023-03-31', 4600, 'banyak', '2024-10-30 09:10:42', '2024-10-30 09:10:42'),
(99, 9, '2023-04-30', 300, 'sangat sedikit', '2024-10-30 09:11:18', '2024-10-30 09:11:18'),
(100, 9, '2023-05-31', 3500, 'sedang', '2024-10-30 09:11:48', '2024-10-30 09:15:14'),
(101, 9, '2023-06-30', 3000, 'cukup', '2024-10-30 09:14:15', '2024-10-30 09:14:15'),
(102, 9, '2023-07-31', 4000, 'banyak', '2024-10-30 09:14:50', '2024-10-30 09:14:50'),
(103, 9, '2023-08-31', 3100, 'cukup', '2024-10-30 09:15:59', '2024-10-30 09:15:59'),
(104, 9, '2023-09-30', 4500, 'banyak', '2024-10-30 09:18:07', '2024-10-30 09:18:07'),
(105, 9, '2023-10-31', 4100, 'banyak', '2024-10-30 09:18:48', '2024-10-30 09:18:48'),
(106, 9, '2023-11-30', 4600, 'banyak', '2024-10-30 09:19:14', '2024-10-30 09:19:14'),
(107, 9, '2023-12-30', 1900, 'sedikit', '2024-10-30 09:20:04', '2024-10-30 09:20:04');

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
(6, '0001_01_01_000000_create_users_table', 1),
(7, '0001_01_01_000001_create_cache_table', 1),
(8, '0001_01_01_000002_create_jobs_table', 1),
(9, '2024_09_03_005224_create_obats_table', 1),
(10, '2024_09_03_005426_create_histories_table', 2),
(11, '2024_10_21_102115_create_predictions_table', 3),
(12, '2024_10_21_102516_create_predictions_table', 4),
(13, '2024_10_29_090204_create_predictions_table', 5),
(14, '2024_10_29_092143_create_prediction_history_table', 6),
(15, '2024_10_29_093550_create_prediction_histories_table', 7),
(16, '2024_10_30_154131_create_prediction_histories_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `obats`
--

CREATE TABLE `obats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_obat` varchar(255) NOT NULL,
  `nama_obat` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `obats`
--

INSERT INTO `obats` (`id`, `kode_obat`, `nama_obat`, `stok`, `created_at`, `updated_at`) VALUES
(1, '001', 'Ambroxol tablet 30 Mg', 0, '2024-10-18 17:56:21', '2024-10-21 04:20:46'),
(9, '002', 'Amlodipin 10 mg', 0, '2024-10-21 12:55:04', '2024-10-21 12:55:04');

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
-- Table structure for table `predictions`
--

CREATE TABLE `predictions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `obat_id` bigint(20) UNSIGNED NOT NULL,
  `predicted_date` date NOT NULL,
  `predicted_value` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prediction_histories`
--

CREATE TABLE `prediction_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `obat_id` bigint(20) UNSIGNED NOT NULL,
  `predicted_value` int(11) NOT NULL,
  `actual_value` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `mae` double NOT NULL,
  `mse` double NOT NULL,
  `mape` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prediction_histories`
--

INSERT INTO `prediction_histories` (`id`, `obat_id`, `predicted_value`, `actual_value`, `month`, `year`, `mae`, `mse`, `mape`, `created_at`, `updated_at`) VALUES
(342, 1, 1900, 1500, 1, 2024, 400, 162962.96296296, 21.647058823529, '2024-11-17 08:54:21', '2024-11-17 08:54:21'),
(343, 1, 2033, 2500, 2, 2024, 400, 162962.96296296, 21.647058823529, '2024-11-17 08:54:21', '2024-11-17 08:54:21'),
(344, 1, 1367, 1700, 3, 2024, 400, 162962.96296296, 21.647058823529, '2024-11-17 08:54:21', '2024-11-17 08:54:21'),
(345, 1, 1900, 1500, 1, 2024, 400, 162962.96296296, 21.647058823529, '2024-11-17 09:30:58', '2024-11-17 09:30:58'),
(346, 1, 2033, 2500, 2, 2024, 400, 162962.96296296, 21.647058823529, '2024-11-17 09:30:58', '2024-11-17 09:30:58'),
(347, 1, 1367, 1700, 3, 2024, 400, 162962.96296296, 21.647058823529, '2024-11-17 09:30:58', '2024-11-17 09:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `prediction_history`
--

CREATE TABLE `prediction_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `obat_id` bigint(20) UNSIGNED NOT NULL,
  `prediction_date` date NOT NULL,
  `predicted_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`predicted_values`)),
  `mae` double DEFAULT NULL,
  `mse` double DEFAULT NULL,
  `mape` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('JnvWSwcso7967dCQMoWAR05IXsGa52beEawltkUN', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQXBZRUhHem1tVExVYTR6djQ0a3l1U1h1a3d5OVE4VkFMOWhnMXU4dyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tb3ZpbmctYXZlcmFnZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1731861059);

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nurul Fatimah', 'nurulftmh085@gmail.com', NULL, '$2y$12$/W/mG/MEYg1wcirZIEZdRuMCqK0VpZplWqHZBEgmHSl4QmbxRCnkq', NULL, '2024-10-18 17:56:08', '2024-10-18 17:56:08');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `histories_obat_id_foreign` (`obat_id`);

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
-- Indexes for table `obats`
--
ALTER TABLE `obats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `obats_kode_obat_unique` (`kode_obat`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `predictions`
--
ALTER TABLE `predictions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `predictions_obat_id_foreign` (`obat_id`);

--
-- Indexes for table `prediction_histories`
--
ALTER TABLE `prediction_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prediction_histories_obat_id_foreign` (`obat_id`);

--
-- Indexes for table `prediction_history`
--
ALTER TABLE `prediction_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prediction_history_obat_id_foreign` (`obat_id`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `obats`
--
ALTER TABLE `obats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prediction_histories`
--
ALTER TABLE `prediction_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- AUTO_INCREMENT for table `prediction_history`
--
ALTER TABLE `prediction_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `histories`
--
ALTER TABLE `histories`
  ADD CONSTRAINT `histories_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `predictions`
--
ALTER TABLE `predictions`
  ADD CONSTRAINT `predictions_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prediction_histories`
--
ALTER TABLE `prediction_histories`
  ADD CONSTRAINT `prediction_histories_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prediction_history`
--
ALTER TABLE `prediction_history`
  ADD CONSTRAINT `prediction_history_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obats` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
