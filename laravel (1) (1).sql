-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 11 Jun 2026 pada 08.08
-- Versi server: 8.0.30
-- Versi PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `laravel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aset`
--

CREATE TABLE `aset` (
  `id` bigint UNSIGNED NOT NULL,
  `sekolah_id` int NOT NULL,
  `nama_aset` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kode_aset` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Kode inventaris aset',
  `kondisi` enum('baik','rusak_ringan','rusak_berat') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'baik',
  `jumlah` int NOT NULL DEFAULT '1',
  `satuan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unit' COMMENT 'unit, buah, set, dll',
  `tahun_pengadaan` year DEFAULT NULL,
  `harga_perolehan` decimal(15,2) DEFAULT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ruangan atau lokasi aset',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `foto_bukti` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aset`
--

INSERT INTO `aset` (`id`, `sekolah_id`, `nama_aset`, `kode_aset`, `kondisi`, `jumlah`, `satuan`, `tahun_pengadaan`, `harga_perolehan`, `lokasi`, `keterangan`, `foto_bukti`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Meja Belajar Siswa', 'AST-001', 'baik', 30, 'buah', '2022', 250000.00, 'Ruang Kelas 1', NULL, '', '2026-06-10 12:16:40', '2026-06-10 12:16:40', NULL),
(2, 2, 'Kursi Siswa', 'AST-002', 'baik', 30, 'buah', '2022', 150000.00, 'Ruang Kelas 1', NULL, '', '2026-06-10 12:16:40', '2026-06-10 12:16:40', NULL),
(3, 2, 'Papan Tulis', 'AST-003', 'rusak_ringan', 2, 'buah', '2019', 500000.00, 'Ruang Kelas 2', 'Cat mulai mengelupas', '', '2026-06-10 12:16:40', '2026-06-10 12:16:40', NULL),
(4, 3, 'Komputer Desktop', 'AST-004', 'baik', 10, 'unit', '2023', 7500000.00, 'Lab Komputer', NULL, '', '2026-06-10 12:16:40', '2026-06-10 12:16:40', NULL),
(5, 3, 'Proyektor', 'AST-005', 'rusak_berat', 1, 'unit', '2018', 4000000.00, 'Ruang Guru', 'Lampu proyektor mati', '', '2026-06-10 12:16:40', '2026-06-10 11:38:47', '2026-06-10 11:38:47'),
(6, 4, 'Lemari Kayu Lama', 'AST-006', 'rusak_berat', 1, 'buah', '2010', 800000.00, 'Gudang', 'Sudah tidak layak pakai', '', '2026-06-10 12:16:40', '2026-06-10 12:16:40', '2026-06-10 12:16:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:4:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"manage user \";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"manage data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"manage pengajuan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:17:\"manage validation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}}}', 1781184452);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_general_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kabupaten`
--

CREATE TABLE `kabupaten` (
  `id` bigint NOT NULL,
  `nama_kabupaten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kabupaten`
--

INSERT INTO `kabupaten` (`id`, `nama_kabupaten`, `created_at`, `updated_at`) VALUES
(1, 'KETAPANG', '2026-04-25 05:17:11', '2026-04-25 05:17:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kecamatan`
--

CREATE TABLE `kecamatan` (
  `id` bigint NOT NULL,
  `kabupaten_id` bigint NOT NULL,
  `nama_kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kecamatan`
--

INSERT INTO `kecamatan` (`id`, `kabupaten_id`, `nama_kecamatan`, `created_at`, `updated_at`) VALUES
(1, 1, 'DELTA PAWAN', '2026-04-17 23:22:47', '2026-04-17 23:22:47'),
(2, 1, 'MUARA PAWAN', '2026-04-17 23:23:23', '2026-04-17 23:23:23'),
(3, 1, 'BENUA KAYONG', '2026-04-17 23:23:40', '2026-04-17 23:23:40'),
(4, 1, 'MATAN HILIR UTARA', '2026-04-17 23:24:35', '2026-04-17 23:24:35'),
(5, 1, 'MATAN HILIR SELATAN', '2026-04-17 23:24:55', '2026-04-17 23:24:55'),
(6, 1, 'KENDAWANGAN', '2026-04-17 23:25:24', '2026-04-17 23:25:24'),
(7, 1, 'NANGA TAYAP', '2026-04-17 23:25:36', '2026-04-17 23:25:36'),
(8, 1, 'TUMBANG TITI', '2026-04-17 23:25:52', '2026-04-17 23:25:52'),
(9, 1, 'AIR UPAS', '2026-04-17 23:27:33', '2026-04-17 23:27:33'),
(10, 1, 'SANDAI', '2026-04-17 23:27:40', '2026-04-17 23:27:40'),
(11, 1, 'SUNGAI MELAYU RAYAK', '2026-04-17 23:28:05', '2026-04-17 23:28:05'),
(12, 1, 'HULU SUNGAI', '2026-04-17 23:29:16', '2026-04-17 23:29:16'),
(13, 1, 'JELAI HULU', '2026-04-17 23:29:28', '2026-04-17 23:29:28'),
(14, 1, 'MANIS MATA', '2026-04-17 23:29:51', '2026-04-17 23:29:51'),
(15, 1, 'MARAU', '2026-04-17 23:30:03', '2026-04-17 23:30:03'),
(16, 1, 'PEMAHAN', '2026-04-17 23:30:52', '2026-04-17 23:30:52'),
(17, 1, 'SIMPANG DUA', '2026-04-17 23:31:03', '2026-04-17 23:31:03'),
(18, 1, 'SIMPANG HULU', '2026-04-17 23:31:17', '2026-04-17 23:31:17'),
(19, 1, 'SINGKUP', '2026-04-17 23:31:26', '2026-04-17 23:31:26'),
(20, 1, 'SUNGAI LAUR', '2026-04-17 23:31:40', '2026-04-17 23:31:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_28_130852_create_permission_tables', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_penghapusan_asset`
--

CREATE TABLE `pengajuan_penghapusan_asset` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_pengajuan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Auto-generate, format: PHA/YYYY/MM/0001',
  `aset_id` bigint UNSIGNED NOT NULL,
  `sekolah_id` int NOT NULL,
  `diajukan_oleh` bigint UNSIGNED NOT NULL,
  `alasan_penghapusan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `metode_penghapusan` enum('pemusnahan','lelang','hibah','tukar_tambah') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pemusnahan',
  `jumlah_diajukan` int NOT NULL DEFAULT '1',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `dokumen_pendukung` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Path file di storage/public',
  `status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu',
  `divalidasi_oleh` bigint UNSIGNED DEFAULT NULL,
  `catatan_validasi` text COLLATE utf8mb4_general_ci,
  `tanggal_validasi` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage user ', 'web', '2026-04-28 07:00:24', '2026-04-28 07:00:24'),
(2, 'manage data', 'web', '2026-04-28 07:00:24', '2026-04-28 07:00:24'),
(3, 'manage pengajuan', 'web', '2026-04-28 07:00:24', '2026-04-28 07:00:24'),
(4, 'manage validation', 'web', '2026-04-28 07:00:24', '2026-04-28 07:00:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-04-28 07:00:24', '2026-04-28 07:00:24'),
(2, 'operator_sekolah', 'web', '2026-04-28 07:00:24', '2026-04-28 07:00:24'),
(3, 'kepala_dinas', 'web', '2026-04-28 07:00:24', '2026-04-28 07:00:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sekolah`
--

CREATE TABLE `sekolah` (
  `id` int NOT NULL,
  `nama_sekolah` varchar(255) NOT NULL,
  `npsn_sekolah` varchar(255) NOT NULL,
  `kecamatan_id` bigint DEFAULT NULL,
  `kabupaten_id` bigint DEFAULT NULL,
  `alamat_sekolah` varchar(255) NOT NULL,
  `jenjang_sekolah` enum('PAUD','SD','SMP','') NOT NULL,
  `scope_pengelola` enum('kabupaten','kecamatan') NOT NULL,
  `operator_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sekolah`
--

INSERT INTO `sekolah` (`id`, `nama_sekolah`, `npsn_sekolah`, `kecamatan_id`, `kabupaten_id`, `alamat_sekolah`, `jenjang_sekolah`, `scope_pengelola`, `operator_id`, `created_at`, `updated_at`) VALUES
(2, 'SDN 05 Delta Pawan', '23456782', 1, 1, 'Jalan  R. Suprapto', 'PAUD', 'kecamatan', 2, '2026-05-05 00:11:02', '2026-05-21 10:49:28'),
(3, 'SMPN 1 Ketapang', '34567893', 1, 1, 'Jl. RM. Sudiono', 'SMP', 'kabupaten', 2, '2026-05-21 11:03:26', '2026-05-21 11:03:39'),
(4, 'SDN 07 Delta Pawan', '12345678', 1, 1, 'Jalan  Ade Irma Suryani', 'SD', 'kecamatan', 2, '2026-05-21 11:04:53', '2026-05-21 11:05:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2BHoVBlTtRS0uuL7guLX9fI5ZbYszEEDDLOde2qP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUEdkaDRsSUcwdlhpWG55cGswSTd5VUdlTmxDMjlPeFFxQUZkWllCTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1781104971);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `login_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `login_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gemail.com', 'superadmin', NULL, '$2y$12$U6BLz4BwqzFXkTnsgwMeo.0c3GDE0e.5CVLRrOkuoDfZSZyJHjwBO', 'FoFPKGJQLY2Hc6ARrGeHeeoNSzk3GC8nWK54KHZEfAPRD1kq1oVwUBzl62Xf', '2026-04-28 07:27:55', '2026-04-28 07:27:55'),
(2, 'Operator Sekolah', 'operator@gmail.com', '123456789', NULL, '$2y$12$JU6lGT/8kMjaBmcKqq3W..BWh27UwdQRJfWLDmReWEpiF32iTZld6', NULL, '2026-04-28 07:27:55', '2026-04-28 07:27:55');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aset_kode_aset_unique` (`kode_aset`),
  ADD KEY `aset_deleted_at_index` (`deleted_at`),
  ADD KEY `aset_sekolah_id_foreign` (`sekolah_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kabupaten`
--
ALTER TABLE `kabupaten`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_key_kabupaten_id` (`kabupaten_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pengajuan_penghapusan_asset`
--
ALTER TABLE `pengajuan_penghapusan_asset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengajuan_penghapusan_asset_nomor_pengajuan_unique` (`nomor_pengajuan`),
  ADD KEY `pengajuan_penghapusan_asset_aset_id_foreign` (`aset_id`),
  ADD KEY `pengajuan_penghapusan_asset_sekolah_id_foreign` (`sekolah_id`),
  ADD KEY `pengajuan_penghapusan_asset_diajukan_oleh_foreign` (`diajukan_oleh`),
  ADD KEY `pengajuan_penghapusan_asset_divalidasi_oleh_foreign` (`divalidasi_oleh`),
  ADD KEY `pengajuan_penghapusan_asset_status_index` (`status`),
  ADD KEY `pengajuan_penghapusan_asset_deleted_at_index` (`deleted_at`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_key_user_id` (`operator_id`),
  ADD KEY `foreign_key_kecamatan_id` (`kecamatan_id`),
  ADD KEY `foreign_key_kabupaten_ids` (`kabupaten_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `login_id` (`login_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aset`
--
ALTER TABLE `aset`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kabupaten`
--
ALTER TABLE `kabupaten`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_penghapusan_asset`
--
ALTER TABLE `pengajuan_penghapusan_asset`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_sekolah_id_foreign` FOREIGN KEY (`sekolah_id`) REFERENCES `sekolah` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD CONSTRAINT `foreign_key_kabupaten_id` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuan_penghapusan_asset`
--
ALTER TABLE `pengajuan_penghapusan_asset`
  ADD CONSTRAINT `pengajuan_penghapusan_asset_aset_id_foreign` FOREIGN KEY (`aset_id`) REFERENCES `aset` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_penghapusan_asset_diajukan_oleh_foreign` FOREIGN KEY (`diajukan_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_penghapusan_asset_divalidasi_oleh_foreign` FOREIGN KEY (`divalidasi_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengajuan_penghapusan_asset_sekolah_id_foreign` FOREIGN KEY (`sekolah_id`) REFERENCES `sekolah` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  ADD CONSTRAINT `foreign_key_kabupaten_ids` FOREIGN KEY (`kabupaten_id`) REFERENCES `kabupaten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign_key_kecamatan_id` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign_key_user_id` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
