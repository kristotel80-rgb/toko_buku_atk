-- Database: toko_buku
-- Import lewat phpMyAdmin (tab Import) atau: mysql -u root < toko_buku.sql

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `toko_buku`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `toko_buku`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Akun awal: username admin / password admin123 (segera ganti setelah login)
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`) VALUES
(1, 'Administrator', 'admin', '$2y$10$6tjxWea7J2UXhPZEHMKkOOfg3VnwTMFD5.EY3U0Oa6V0J52ylXSUC');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT '0',
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Data contoh produk
--

INSERT INTO `produk` (`id`, `nama`, `kategori`, `deskripsi`, `harga`, `stok`, `gambar`) VALUES
(1, 'Buku Tulis Dotted A5', 'Buku Tulis', 'Buku tulis A5 bersampul keras dengan 160 halaman kertas dotted 80 gsm. Tinta tidak tembus, cocok untuk catatan kuliah, bullet journal, dan sketsa ringan. Dilengkapi tali elastis dan pembatas halaman.', 38000.00, 30, 'buku-tulis-dotted.svg'),
(2, 'Pulpen Gel Hitam 0.5 mm (Isi 3)', 'Alat Tulis', 'Pulpen gel dengan tinta hitam pekat yang cepat kering dan tidak mudah belepotan. Ujung 0.5 mm menghasilkan tulisan rapi di kertas catatan maupun buku jurnal.', 24000.00, 50, 'pulpen-gel.svg'),
(3, 'Pembatas Buku Kuningan', 'Pembatas Buku', 'Pembatas buku berbahan kuningan dengan ujung berbentuk V yang menandai baris terakhir bacaanmu. Tipis, tidak merusak halaman, dan dikemas dalam kotak kecil.', 18000.00, 40, 'pembatas-buku.svg'),
(4, 'Sampul Buku Plastik Bening (Isi 10)', 'Sampul & Pelindung', 'Sampul plastik bening tahan sobek untuk buku tulis dan buku paket. Melindungi sampul dari air, debu, dan lecet tanpa menutupi desain buku.', 15000.00, 60, 'sampul-buku.svg'),
(5, 'Lampu Baca Klip USB', 'Aksesoris Baca', 'Lampu baca mungil dengan klip yang dijepit di sampul buku. Cahaya hangat dengan tiga tingkat terang, isi ulang lewat kabel USB.', 85000.00, 12, 'lampu-baca.svg'),
(6, 'Penyangga Buku Lipat', 'Aksesoris Baca', 'Penyangga buku lipat yang menahan buku tetap terbuka saat kamu membaca, menyalin, atau memasak sambil mengikuti resep. Mudah dilipat dan dibawa.', 55000.00, 3, 'penyangga-buku.svg');
