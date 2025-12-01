-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 30, 2025 at 02:45 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dinas_sekretariat`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_layanans`
--

CREATE TABLE `pengajuan_layanans` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jenis_layanan_id` bigint UNSIGNED NOT NULL,
  `nama_pihak` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tentang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi_terkait` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'menunggu_review_sp',
  `tahap_aktif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'surat_penawaran',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `surat_penawaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kerangka_acuan_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nota_kesepakatan_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_surat_penawaran` enum('pending','disetujui','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_surat_penawaran` text COLLATE utf8mb4_unicode_ci,
  `status_kak` enum('pending','disetujui','revisi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_kak` text COLLATE utf8mb4_unicode_ci,
  `status_nota` enum('pending','disetujui','revisi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_nota` text COLLATE utf8mb4_unicode_ci,
  `file_kak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_nota_kesepakatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_revisi_sp` text COLLATE utf8mb4_unicode_ci,
  `catatan_revisi_kak` text COLLATE utf8mb4_unicode_ci,
  `dokumen_hasil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pengajuan` timestamp NULL DEFAULT NULL,
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_layanans`
--

INSERT INTO `pengajuan_layanans` (`id`, `nomor_pengajuan`, `user_id`, `jenis_layanan_id`, `nama_pihak`, `tentang`, `instansi_terkait`, `status`, `tahap_aktif`, `catatan_admin`, `surat_penawaran`, `kerangka_acuan_kerja`, `nota_kesepakatan_link`, `status_surat_penawaran`, `catatan_surat_penawaran`, `status_kak`, `catatan_kak`, `status_nota`, `catatan_nota`, `file_kak`, `link_nota_kesepakatan`, `catatan_revisi_sp`, `catatan_revisi_kak`, `dokumen_hasil`, `tanggal_pengajuan`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(5, 'KSDPK/11/2025/0001', 2, 2, 'KJHKJH', 'OIOIU', 'PO', 'selesai', 'surat_penawaran', 'Pengajuan telah selesai diproses.', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'dokumen_layanan/5/1764501994_kak_SK 934 Tahun 2025 (alamat).pdf', 'https://docs.google.com/document/d/1iyEB60LdVELcu26q-1nSeeeustOZPmAlqhqmQLO6rAE/edit?tab=t.0#heading=h.5rf9wr4r3no2', NULL, NULL, 'dokumen_layanan/5/hasil/1764502061_surat_bukti_selesai_SK 934 Tahun 2025 (alamat).pdf', '2025-11-30 04:25:31', '2025-11-30 04:27:41', '2025-11-30 04:25:31', '2025-11-30 04:27:41'),
(6, 'KSDPK/11/2025/0002', 2, 2, 'LKADLKASJ', 'AKSDLAKJSL', 'LSKDJLKJDF', 'selesai', 'surat_penawaran', 'Pengajuan telah selesai diproses.', NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'dokumen_layanan/6/1764502250_kak_SK 934 Tahun 2025 (alamat).pdf', 'https://docs.google.com/document/d/1iyEB60LdVELcu26q-1nSeeeustOZPmAlqhqmQLO6rAE/edit?tab=t.0#heading=h.5rf9wr4r3no2', NULL, NULL, 'dokumen_layanan/6/hasil/1764502308_surat_bukti_selesai_SK 934 Tahun 2025 (alamat).pdf', '2025-11-30 04:29:57', '2025-11-30 04:31:48', '2025-11-30 04:29:57', '2025-11-30 04:31:48'),
(7, 'KSDD/11/2025/0001', 2, 1, 'SDASDA', 'ASDASD', 'ASDASD', 'dokumen_lengkap', 'surat_penawaran', NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, 'dokumen_layanan/7/1764510208_kak_v1_SK 934 Tahun 2025 (alamat).pdf', 'https://docs.google.com/document/d/1iyEB60LdVELcu26q-1nSeeeustOZPmAlqhqmQLO6rAE/edit?tab=t.0#heading=h.5rf9wr4r3no2', NULL, NULL, NULL, '2025-11-30 04:35:57', NULL, '2025-11-30 04:35:57', '2025-11-30 06:53:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengajuan_layanans`
--
ALTER TABLE `pengajuan_layanans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengajuan_layanans_nomor_pengajuan_unique` (`nomor_pengajuan`),
  ADD KEY `pengajuan_layanans_user_id_foreign` (`user_id`),
  ADD KEY `pengajuan_layanans_jenis_layanan_id_foreign` (`jenis_layanan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengajuan_layanans`
--
ALTER TABLE `pengajuan_layanans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengajuan_layanans`
--
ALTER TABLE `pengajuan_layanans`
  ADD CONSTRAINT `pengajuan_layanans_jenis_layanan_id_foreign` FOREIGN KEY (`jenis_layanan_id`) REFERENCES `jenis_layanans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_layanans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
