-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 09, 2026 at 01:45 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siperkom`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporans`
--

CREATE TABLE `laporans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `pelapor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Gunungjati',
  `desa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_laporan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_pelapor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_perangkat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perangkat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kendala` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Dalam Perbaikan','Menunggu Part','Selesai','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dalam Perbaikan',
  `tanggal_keluar` date DEFAULT NULL,
  `prioritas` enum('Rendah','Sedang','Tinggi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sedang',
  `teknisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_rekap` text COLLATE utf8mb4_unicode_ci,
  `history` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dokumen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporans`
--

INSERT INTO `laporans` (`id`, `user_id`, `pelapor`, `alamat`, `kecamatan`, `desa`, `kode_laporan`, `nama_pelapor`, `jenis_perangkat`, `perangkat`, `kendala`, `status`, `tanggal_keluar`, `prioritas`, `teknisi`, `keterangan_rekap`, `history`, `foto`, `dokumen`, `created_at`, `updated_at`, `catatan`) VALUES
(12, 19, 'nifary', 'kuningan', 'Cirebon', 'Cirebon', '#CRB-2026-09-001', NULL, 'laptop', 'asus', 'mati', 'Selesai', '2026-09-25', 'Sedang', 'Fahry Rizky Prayogi', 'di ambil', '[25-09-2026 09:01] Laporan berhasil dikirim oleh pelapor.', 'laporan/foto/TzevaASGw8eiUAEyvsrQSbzcv60OGW6haizCyw1g.jpg', 'laporan/dokumen/kMIixMWB8CCXj2eJu809FucNgqRv5SKXkcFHy4vb.pdf', '2026-09-25 02:01:16', '2026-09-25 02:03:05', NULL),
(13, 20, 'Ajis', 'Cirebon', 'Cirebon', 'Cirebon', '#CRB-2026-09-002', NULL, 'Printer', 'Epson', 'Tinta enggak keluar', 'Selesai', '2026-09-26', 'Rendah', 'Fahry Rizky Prayogi', 'udah selesai', '[26-09-2026 15:33] Laporan berhasil dikirim oleh pelapor.', 'laporan/foto/mMkkgA3R7Ptqi8BzTYHZjF51xVbFJu6Hyq2mDMr1.jpg', 'laporan/dokumen/RmMvtK0vbBmxMmF6hFoaYKJbnGFOCwTynQxWWMBP.pdf', '2026-09-26 08:33:37', '2026-09-26 09:39:14', NULL),
(14, 20, 'Ajis', 'Cirebon', 'Cirebon', 'Cirebon', '#CRB-2026-09-003', NULL, 'Router', 'Engga tau', 'Engga nyala', 'Ditolak', '2026-10-02', 'Sedang', 'Fahry Rizky Prayogi', 'Status diperbarui menjadi Menunggu Part', '[26-09-2026 16:52] Laporan berhasil dikirim oleh pelapor.', 'laporan/foto/ZwULNFwZbhb1ysePsJsmjAylm0WBtOkMulT0ysSR.jpg', 'laporan/dokumen/BOmgCkX33AjD2eFMNU8Yzv3Gvs7KtWegQM4dsq1Z.pdf', '2026-09-26 09:52:22', '2026-10-02 06:36:16', NULL),
(15, 19, 'nifary', 'kuningan', 'Cirebon', 'Cirebon', '#CRB-2026-10-001', NULL, 'hahaha', 'ajis', 'lapar', 'Selesai', '2026-10-02', 'Sedang', 'Fahry Rizky Prayogi', 'Status diperbarui menjadi Dalam Perbaikan', '[02-10-2026 13:35] Laporan berhasil dikirim oleh pelapor.', 'laporan/foto/R69IvFOeuxopOJpZhweQgPqEBscmDN2JocBfP50q.jpg', 'laporan/dokumen/gtFw07mDXgID68NN0Dj00MaUttoj0RsO8VYcSX3m.pdf', '2026-10-02 06:35:03', '2026-10-02 06:36:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_31_025907_create_laporans_table', 1),
(5, '2026_08_04_063948_create_reports_table', 1),
(6, '2026_08_06_041437_add_nama_desa_to_users_table', 1),
(7, '2026_08_06_151439_add_google_columns_to_users_table', 1),
(8, '2026_08_14_025002_add_kecamatan_to_users_table', 1),
(9, '2026_08_19_075615_add_tanggal_keluar_to_laporans_table', 1),
(10, '2026_08_21_022323_create_notifications_table', 1),
(11, '2026_08_27_033947_add_must_change_password_to_users_table', 1),
(12, '2026_08_30_063710_add_catatan_to_laporans_table', 1),
(13, '2026_09_04_075437_create_laporans_table', 1),
(14, '2026_09_04_080850_add_profile_columns_to_users_table', 1),
(15, '2026_09_04_080945_add_kecamatan_and_desa_to_laporans_table', 1),
(16, '2026_09_04_081238_add_keterangan_rekap_to_laporans_table', 1),
(17, '2026_09_04_081802_add_dokumen_to_laporans_table', 1),
(18, '2026_09_04_083209_add_pelapor_to_laporans_table', 1),
(19, '2026_09_15_120000_add_name_changed_at_to_users_table', 1),
(20, '2026_09_17_224000_add_alamat_to_users_table', 2),
(21, '2026_09_17_232500_add_alamat_to_laporans_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'Laporan Selesai: #CRB-2026-09-001', 'Laporan lenovo (nifary) telah berstatus Selesai. ambil', 0, '2026-09-18 02:38:12', '2026-09-18 02:38:12'),
(2, 'Laporan Selesai: #CRB-2026-09-001', 'Laporan lenovo (nifary) telah berstatus Selesai. selesai', 0, '2026-09-18 02:44:15', '2026-09-18 02:44:15'),
(3, 'Laporan Selesai: #CRB-2026-09-001', 'Laporan lenovo (nifary) telah berstatus Selesai. selesai', 0, '2026-09-18 02:45:51', '2026-09-18 02:45:51'),
(4, 'Laporan Selesai: #CRB-2026-09-001', 'Laporan lenovo (nifary) telah berstatus Selesai. selesai', 0, '2026-09-18 02:46:34', '2026-09-18 02:46:34'),
(5, 'Laporan Menunggu Part: #CRB-2026-09-002', 'Laporan Lenovo (nifary) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-18 03:26:29', '2026-09-18 03:26:29'),
(6, 'Laporan Selesai: #CRB-2026-09-002', 'Laporan Lenovo (nifary) telah berstatus Selesai. ambil sekarang', 0, '2026-09-18 03:27:02', '2026-09-18 03:27:02'),
(7, 'Laporan Menunggu Part: #CRB-2026-09-003', 'Laporan Lenovo (Ajis) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-18 04:01:13', '2026-09-18 04:01:13'),
(8, 'Laporan Selesai: #CRB-2026-09-003', 'Laporan Lenovo (Ajis) telah berstatus Selesai. Status diperbarui menjadi Menunggu Part', 0, '2026-09-18 04:01:43', '2026-09-18 04:01:43'),
(9, 'Laporan Ditolak: #CRB-2026-09-004', 'Laporan Lenovo (Nifary) telah berstatus Ditolak. 1', 0, '2026-09-18 04:04:54', '2026-09-18 04:04:54'),
(10, 'Laporan Ditolak: #CRB-2026-09-005', 'Laporan Ni dari thinkpas (Dockerid) telah berstatus Ditolak. karena tisak sesui', 0, '2026-09-19 02:01:33', '2026-09-19 02:01:33'),
(11, 'Laporan Menunggu Part: #CRB-2026-09-001', 'Laporan Lenovo (Nifary) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-19 08:52:12', '2026-09-19 08:52:12'),
(12, 'Laporan Menunggu Part: #CRB-2026-09-001', 'Laporan Lenovo (Nifary) telah berstatus Menunggu Part. belum bisa', 0, '2026-09-19 08:52:43', '2026-09-19 08:52:43'),
(13, 'Laporan Selesai: #CRB-2026-09-001', 'Laporan Lenovo (Nifary) telah berstatus Selesai. bisa di ambil', 0, '2026-09-19 08:54:21', '2026-09-19 08:54:21'),
(14, 'Laporan Menunggu Part: #CRB-2026-09-001', 'Laporan Lenovo (Ajis) telah berstatus Menunggu Part. lagi di kerjain dulu', 0, '2026-09-21 02:12:35', '2026-09-21 02:12:35'),
(15, 'Laporan Selesai: #CRB-2026-09-001', 'Laporan Lenovo (Ajis) telah berstatus Selesai. bisa di ambil sekarang', 0, '2026-09-21 02:15:01', '2026-09-21 02:15:01'),
(16, 'Laporan Menunggu Part: #CRB-2026-09-002', 'Laporan Lenovo (Ajis) telah berstatus Menunggu Part. lagi cek dulu', 0, '2026-09-21 02:24:00', '2026-09-21 02:24:00'),
(17, 'Laporan Ditolak: #CRB-2026-09-002', 'Laporan Lenovo (Ajis) telah berstatus Ditolak. tidal lengkap karena tidak sesui', 0, '2026-09-21 02:30:48', '2026-09-21 02:30:48'),
(18, 'Laporan Menunggu Part: #CRB-2026-09-003', 'Laporan Lenovo (Fatan) telah berstatus Menunggu Part. sedang di perbaiki', 0, '2026-09-21 02:36:56', '2026-09-21 02:36:56'),
(19, 'Laporan Selesai: #CRB-2026-09-003', 'Laporan Lenovo (Fatan) telah berstatus Selesai. bisa di ambil', 0, '2026-09-21 02:37:35', '2026-09-21 02:37:35'),
(20, 'Laporan Ditolak: #CRB-2026-09-004', 'Laporan hp (hp) telah berstatus Ditolak. Status diperbarui menjadi Ditolak', 0, '2026-09-21 04:17:21', '2026-09-21 04:17:21'),
(21, 'Laporan Menunggu Part: #CRB-2026-09-004', 'Laporan epson (Ajis) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-23 07:23:05', '2026-09-23 07:23:05'),
(22, 'Laporan Selesai: #CRB-2026-09-004', 'Laporan epson (Ajis) telah berstatus Selesai. Status diperbarui menjadi Menunggu Part', 0, '2026-09-23 07:23:11', '2026-09-23 07:23:11'),
(23, 'Laporan Menunggu Part: #CRB-2026-09-001', 'Laporan asus (nifary) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-25 02:02:30', '2026-09-25 02:02:30'),
(24, 'Laporan Selesai: #CRB-2026-09-001', 'Laporan asus (nifary) telah berstatus Selesai. di ambil', 0, '2026-09-25 02:03:05', '2026-09-25 02:03:05'),
(25, 'Laporan Menunggu Part: #CRB-2026-09-002', 'Laporan Epson (Ajis) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-26 08:34:23', '2026-09-26 08:34:23'),
(26, 'Laporan Selesai: #CRB-2026-09-002', 'Laporan Epson (Ajis) telah berstatus Selesai. udah selesai', 0, '2026-09-26 09:39:14', '2026-09-26 09:39:14'),
(27, 'Laporan Menunggu Part: #CRB-2026-09-003', 'Laporan Engga tau (Ajis) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-26 09:53:21', '2026-09-26 09:53:21'),
(28, 'Laporan Dalam Perbaikan: #CRB-2026-09-003', 'Laporan Engga tau (Ajis) telah berstatus Dalam Perbaikan. Status diperbarui menjadi Menunggu Part', 0, '2026-09-26 10:00:08', '2026-09-26 10:00:08'),
(29, 'Laporan Menunggu Part: #CRB-2026-09-003', 'Laporan Engga tau (Ajis) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-26 10:00:31', '2026-09-26 10:00:31'),
(30, 'Laporan Dalam Perbaikan: #CRB-2026-09-003', 'Laporan Engga tau (Ajis) telah berstatus Dalam Perbaikan. Status diperbarui menjadi Menunggu Part', 0, '2026-09-26 10:09:31', '2026-09-26 10:09:31'),
(31, 'Laporan Menunggu Part: #CRB-2026-09-003', 'Laporan Engga tau (Ajis) telah berstatus Menunggu Part. Status diperbarui menjadi Menunggu Part', 0, '2026-09-26 17:27:26', '2026-09-26 17:27:26'),
(32, 'Laporan Dalam Perbaikan: #CRB-2026-10-001', 'Laporan ajis (nifary) telah berstatus Dalam Perbaikan. Status diperbarui menjadi Dalam Perbaikan', 0, '2026-10-02 06:36:01', '2026-10-02 06:36:01'),
(33, 'Laporan Selesai: #CRB-2026-10-001', 'Laporan ajis (nifary) telah berstatus Selesai. Status diperbarui menjadi Dalam Perbaikan', 0, '2026-10-02 06:36:11', '2026-10-02 06:36:11'),
(34, 'Laporan Ditolak: #CRB-2026-09-003', 'Laporan Engga tau (Ajis) telah berstatus Ditolak. Status diperbarui menjadi Menunggu Part', 0, '2026-10-02 06:36:16', '2026-10-02 06:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `report_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perangkat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kendala` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `prioritas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sedang',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dalam Perbaikan',
  `teknisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dokumen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dokumen_nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fSFJHy7RLO2YaxZLp62UZv522q7dbQweRWUUy3bF', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJOOGJFQ0RwVGM0dDV4ZHI3MFVneTNsSXp6bWtFNWpNODhmckxpN25zIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcHJvZmlsIiwicm91dGUiOiJhZG1pbi5wcm9maWwuZWRpdCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo2fQ==', 1791341069);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_changed_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_desa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pelapor',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `name_changed_at`, `email`, `google_id`, `nama_desa`, `avatar`, `email_verified_at`, `password`, `must_change_password`, `role`, `remember_token`, `created_at`, `updated_at`, `kecamatan`, `desa`, `alamat`) VALUES
(6, 'Administrator SIPERKOM', NULL, 'admin@cirebonkab.go.id', NULL, NULL, 'avatars/admin-avatar.svg', NULL, 'cirebon2', 0, 'admin', NULL, '2026-09-17 15:39:34', '2026-09-21 06:19:07', NULL, NULL, 'Jl. Sunan Kalijaga No. 7, Sumber'),
(18, 'Nifary', NULL, 'nifaryyuusrahman@gmail.com', NULL, NULL, NULL, NULL, '123456', 0, 'admin', NULL, '2026-09-21 06:20:53', '2026-09-21 06:20:53', NULL, NULL, NULL),
(19, 'nifary', NULL, 'nifary@gmail.com', NULL, NULL, 'avatars/rxgNk4DBf48cPcYgALMOQesLOQtm25IUNlWCk0as.jpg', NULL, 'Nifary16@', 0, 'pelapor', NULL, '2026-09-24 06:31:41', '2026-09-26 07:13:59', NULL, NULL, 'kuningan'),
(20, 'Ajis', NULL, 'ajis@gmail.com', NULL, NULL, NULL, NULL, 'Ajis1234@', 0, 'pelapor', NULL, '2026-09-26 06:18:41', '2026-09-26 06:30:31', NULL, NULL, 'Cirebon');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `laporans`
--
ALTER TABLE `laporans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laporans_kode_laporan_unique` (`kode_laporan`),
  ADD KEY `laporans_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reports_report_id_unique` (`report_id`),
  ADD KEY `reports_user_id_foreign` (`user_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporans`
--
ALTER TABLE `laporans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporans`
--
ALTER TABLE `laporans`
  ADD CONSTRAINT `laporans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
