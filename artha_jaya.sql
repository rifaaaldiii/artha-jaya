-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2026 at 04:54 AM
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
-- Database: `artha_jaya`
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:4;', 1775614426),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1775614426;', 1775614426),
('laravel-cache-livewire-rate-limiter:056fc329aaaa757d31db450f525da23fde4d1b36', 'i:1;', 1775616867),
('laravel-cache-livewire-rate-limiter:056fc329aaaa757d31db450f525da23fde4d1b36:timer', 'i:1775616867;', 1775616867),
('laravel-cache-poll_trigger:dashboard', 'i:8;', 2090972713),
('laravel-cache-poll_trigger:jasa', 'i:8;', 2090972713),
('laravel-cache-poll_trigger:produksi', 'i:1;', 2090972713);

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
-- Table structure for table `jasas`
--

CREATE TABLE `jasas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_jasa` varchar(255) NOT NULL,
  `no_ref` varchar(255) NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `jadwal` datetime DEFAULT NULL,
  `jadwal_petugas` datetime DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `progress_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`progress_images`)),
  `petugas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pelanggan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('jasa baru','terjadwal','selesai dikerjakan','selesai') NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jasa_petugas`
--

CREATE TABLE `jasa_petugas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jasa_id` bigint(20) UNSIGNED NOT NULL,
  `petugas_id` bigint(20) UNSIGNED NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jasa_items`
--

CREATE TABLE `jasa_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jasa_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_layanan` varchar(255) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_jasas`
--

CREATE TABLE `jenis_jasas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_jasas`
--

INSERT INTO `jenis_jasas` (`id`, `nama`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Pengecatan ', '...', '2026-04-06 02:07:51', '2026-04-06 02:07:51');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_produksis`
--

CREATE TABLE `jenis_produksis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_produksis`
--

INSERT INTO `jenis_produksis` (`id`, `nama`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Step Nosing', '...', '2026-04-06 02:01:21', '2026-04-06 02:01:21');

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
(4, '2025_11_19_024340_create_teams_table', 1),
(5, '2025_11_19_024347_create_produksis_table', 1),
(6, '2025_11_19_024409_create_petugas_table', 1),
(7, '2025_11_19_024444_create_pelanggans_table', 1),
(8, '2025_11_19_024445_create_jasas_table', 1),
(9, '2025_11_19_024505_create_petukangs_table', 1),
(10, '2025_11_22_000910_add_jadwal_petugas_to_jasas_table', 1),
(11, '2025_11_22_085634_create_jasa_petugas_pivot_table', 1),
(12, '2025_11_24_093608_add_username_to_users_table', 1),
(13, '2025_11_24_130000_create_jenis_jasas_table', 1),
(14, '2025_11_24_130100_create_jenis_produksis_table', 1),
(15, '2026_04_06_085829_add_progress_images_to_produksis_table', 2),
(16, '2026_04_06_085835_add_progress_images_to_jasas_table', 2),
(19, '2026_04_07_000001_add_no_ref_and_harga_to_produksis_table', 3),
(20, '2026_04_07_000002_add_harga_to_jasas_table', 3),
(21, '2026_04_07_000003_add_branch_to_jasas_table', 4),
(22, '2026_04_07_000004_add_branch_to_produksis_table', 4),
(23, '2026_04_08_000001_create_produksi_items_table', 5),
(24, '2026_04_08_000002_create_jasa_items_table', 5),
(25, '2026_04_08_000003_modify_produksis_table', 5),
(26, '2026_04_08_000004_modify_jasas_table', 5);

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
-- Table structure for table `pelanggans`
--

CREATE TABLE `pelanggans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kontak` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `UpdateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggans`
--

INSERT INTO `pelanggans` (`id`, `nama`, `kontak`, `alamat`, `createdAt`, `UpdateAt`) VALUES
(1, 'Rifaldi', '082123609953', 'Pandeglang', '2026-04-06 02:09:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status` enum('ready','busy') NOT NULL,
  `kontak` varchar(255) NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id`, `nama`, `status`, `kontak`, `createdAt`, `updateAt`) VALUES
(1, 'Petugas 1', 'busy', '082123609953', NULL, NULL),
(2, 'Petugas 2', 'busy', '082123609953', NULL, NULL),
(3, 'Petugas 3', 'busy', '082123609953', NULL, NULL),
(4, 'Petugas 4', 'busy', '082123609953', NULL, NULL),
(5, 'Petugas 5', 'ready', '082123609953', NULL, NULL),
(6, 'Petugas 6', 'ready', '082123609953', NULL, NULL),
(7, 'Petugas 7', 'ready', '082123609953', NULL, NULL),
(8, 'Petugas 8', 'ready', '082123609953', NULL, NULL),
(9, 'Petugas 9', 'ready', '082123609953', NULL, NULL),
(10, 'Petugas 10', 'busy', '082123609953', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `petukangs`
--

CREATE TABLE `petukangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status` enum('ready','busy') NOT NULL,
  `kontak` varchar(255) NOT NULL,
  `team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `petukangs`
--

INSERT INTO `petukangs` (`id`, `nama`, `status`, `kontak`, `team_id`, `createdAt`, `updateAt`) VALUES
(1, 'Petukang 1', 'busy', '082123609953', 1, NULL, NULL),
(2, 'Petukang 2', 'busy', '082123609953', 1, NULL, NULL),
(3, 'Petukang 3', 'ready', '082123609953', 2, NULL, NULL),
(4, 'Petukang 4', 'ready', '082123609953', 2, NULL, NULL),
(5, 'Petukang 5', 'ready', '082123609953', 3, NULL, NULL),
(6, 'Petukang 6', 'ready', '082123609953', 3, NULL, NULL),
(7, 'Petukang 7', 'ready', '082123609953', 4, NULL, NULL),
(8, 'Petukang 8', 'ready', '082123609953', 4, NULL, NULL),
(9, 'Petukang 9', 'ready', '082123609953', 5, NULL, NULL),
(10, 'Petukang 10', 'ready', '082123609953', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produksis`
--

CREATE TABLE `produksis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_produksi` varchar(255) NOT NULL,
  `no_ref` varchar(255) NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `status` enum('produksi baru','siap produksi','dalam pengerjaan','selesai dikerjakan','lolos qc','produksi siap diambil','selesai') NOT NULL,
  `catatan` text DEFAULT NULL,
  `progress_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`progress_images`)),
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produksi_items`
--

CREATE TABLE `produksi_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produksi_id` bigint(20) UNSIGNED NOT NULL,
  `nama_produksi` varchar(255) NOT NULL,
  `nama_bahan` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updateAt` timestamp NULL DEFAULT NULL
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
('lFCkdBBZ0XHUYdvkGgviAHbhrOeus71rSc8iHhZ5', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoicWx2V2YwNHhFQUpNWUJoNEdPbDBHdFQ5NHdWVURwdU1iQkpzbHk3cCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcHJvZHVrc2lzIjtzOjU6InJvdXRlIjtzOjQwOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMucHJvZHVrc2lzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJFBHSUNRQVlMeUpERnp6RUlWcmcuVk9raHRwNUljVXhYampvOXhWanJ1T0E5Z3pCejRWN0NHIjt9', 1775616710),
('qpQgaTdnjaYCRORXOBIRoBwJnkvIH2v4Zm26kJIA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSVhiS3o0aXdBUlNNdWFNbGRZeWhmSG1sOHp0TjFhVHd0UmRXam1MWSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcHJvZHVrc2lzIjtzOjU6InJvdXRlIjtzOjQwOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMucHJvZHVrc2lzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJFBHSUNRQVlMeUpERnp6RUlWcmcuVk9raHRwNUljVXhYampvOXhWanJ1T0E5Z3pCejRWN0NHIjt9', 1775616811);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status` enum('ready','busy') NOT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `nama`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 'Team A', 'busy', NULL, '2026-04-06 03:19:21'),
(2, 'Team B', 'ready', NULL, '2026-04-06 03:19:26'),
(3, 'Team C', 'ready', NULL, '2026-04-06 03:30:47'),
(4, 'Team D', 'ready', NULL, NULL),
(5, 'Team E', 'ready', NULL, NULL),
(6, 'Team F', 'ready', NULL, NULL),
(7, 'Team G', 'ready', NULL, NULL),
(8, 'Team H', 'ready', NULL, NULL),
(9, 'Team I', 'ready', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('administrator','admin_toko','admin_gudang','kepala_teknisi_lapangan','kepala_teknisi_gudang','petugas','petukang') NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `UpdateAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `email_verified_at`, `remember_token`, `createdAt`, `UpdateAt`) VALUES
(1, 'Rifaldi Yuda', 'rifaldi', 'rifaldiyuda29@gmail.com', '$2y$12$PGICQAYLyJDFzzEIVrg.VOkhtp5IcUxXjjo9xVjruOA9gzBz4V7CG', 'administrator', '2026-04-06 01:05:09', '4baWnT3tgMC0TVe2kVhignrsgu1lyBLVw3jvjxQtANhUGwiDbZrwsqxQ6C6F', NULL, '2026-04-07 04:03:46'),
(2, 'Admin Toko', 'admintoko', 'admintoko@artha-jaya.com', '$2y$12$5mCimeOUKrAAhK2Ava47.eP/sJT27jjeVyoaQZNFTdB7JlHrJy7rO', 'admin_toko', '2026-04-06 01:05:09', 'trb6F7ShCU', NULL, NULL),
(3, 'Admin Gudang', 'admingudang', 'admingudang@artha-jaya.com', '$2y$12$wh/sL.31HCqquLJU55eAGePC5LJNBxhi.HkWtWeuSIW5AxtcE2WN.', 'admin_gudang', '2026-04-06 01:05:09', 'crop0miCIk', NULL, NULL),
(4, 'Kepala Teknisi Lapangan', 'kepalateknisilapangan', 'kepalateknisilapangan@artha-jaya.com', '$2y$12$bO5qWadVo/TLx921tKHCjeBfZxxCD8C.qd8SkfeClbivA9egydCra', 'kepala_teknisi_lapangan', '2026-04-06 01:05:10', 'OvFTd2AhvJ', NULL, NULL),
(5, 'Kepala Teknisi Gudang', 'kepalateknisigudang', 'kepalateknisigudang@artha-jaya.com', '$2y$12$VPfBDtRuVGNvS5acWFWaROzTJzds0Fq0Vhmqk4CwdhBhciqhWSEEG', 'kepala_teknisi_gudang', '2026-04-06 01:05:10', 'W67dRpJUN8', NULL, NULL),
(6, 'Petugas', 'petugas', 'petugas@artha-jaya.com', '$2y$12$HFX3GAyTzJI4Za.V3u0xOOje4ZW6f.KoBH6E4N0elGi0FKpGjSTVy', 'petugas', '2026-04-06 01:05:10', 'K8tSuUVfOc', NULL, NULL),
(7, 'Petukang', 'petukang', 'petukang@artha-jaya.com', '$2y$12$QZZcx5pcrAz5h.oO47HcS.rtmvgqXDHb074wfGSz0XPCf.AVadsJK', 'petukang', '2026-04-06 01:05:11', 'tb1wc2EB2H', NULL, NULL);

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
-- Indexes for table `jasas`
--
ALTER TABLE `jasas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jasas_petugas_id_foreign` (`petugas_id`),
  ADD KEY `jasas_pelanggan_id_foreign` (`pelanggan_id`);

--
-- Indexes for table `jasa_petugas`
--
ALTER TABLE `jasa_petugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jasa_petugas_jasa_id_petugas_id_unique` (`jasa_id`,`petugas_id`),
  ADD KEY `jasa_petugas_petugas_id_foreign` (`petugas_id`);

--
-- Indexes for table `jasa_items`
--
ALTER TABLE `jasa_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jasa_items_jasa_id_foreign` (`jasa_id`);

--
-- Indexes for table `jenis_jasas`
--
ALTER TABLE `jenis_jasas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis_jasas_nama_unique` (`nama`);

--
-- Indexes for table `jenis_produksis`
--
ALTER TABLE `jenis_produksis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis_produksis_nama_unique` (`nama`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pelanggans`
--
ALTER TABLE `pelanggans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petukangs`
--
ALTER TABLE `petukangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `petukangs_team_id_foreign` (`team_id`);

--
-- Indexes for table `produksis`
--
ALTER TABLE `produksis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produksis_team_id_foreign` (`team_id`);

--
-- Indexes for table `produksi_items`
--
ALTER TABLE `produksi_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produksi_items_produksi_id_foreign` (`produksi_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jasas`
--
ALTER TABLE `jasas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jasa_petugas`
--
ALTER TABLE `jasa_petugas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jasa_items`
--
ALTER TABLE `jasa_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_jasas`
--
ALTER TABLE `jenis_jasas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jenis_produksis`
--
ALTER TABLE `jenis_produksis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pelanggans`
--
ALTER TABLE `pelanggans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `petukangs`
--
ALTER TABLE `petukangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produksis`
--
ALTER TABLE `produksis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produksi_items`
--
ALTER TABLE `produksi_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jasas`
--
ALTER TABLE `jasas`
  ADD CONSTRAINT `jasas_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jasas_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jasa_petugas`
--
ALTER TABLE `jasa_petugas`
  ADD CONSTRAINT `jasa_petugas_jasa_id_foreign` FOREIGN KEY (`jasa_id`) REFERENCES `jasas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jasa_petugas_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jasa_items`
--
ALTER TABLE `jasa_items`
  ADD CONSTRAINT `jasa_items_jasa_id_foreign` FOREIGN KEY (`jasa_id`) REFERENCES `jasas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `petukangs`
--
ALTER TABLE `petukangs`
  ADD CONSTRAINT `petukangs_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `produksis`
--
ALTER TABLE `produksis`
  ADD CONSTRAINT `produksis_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

--
-- Constraints for table `produksi_items`
--
ALTER TABLE `produksi_items`
  ADD CONSTRAINT `produksi_items_produksi_id_foreign` FOREIGN KEY (`produksi_id`) REFERENCES `produksis` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
