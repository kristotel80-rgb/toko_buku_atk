<?php

/*
|--------------------------------------------------------------------------
| KONEKSI DATABASE
|--------------------------------------------------------------------------
*/

$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku_atk";

// Laporkan error lewat nilai return (konsisten di PHP 7 maupun 8.1+)
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

/*
|--------------------------------------------------------------------------
| PENGATURAN TOKO
|--------------------------------------------------------------------------
| Ubah nilai di bawah ini sesuai toko Anda.
| Nomor WhatsApp memakai format internasional tanpa tanda + atau 0 di depan
| (contoh: 6281234567890).
*/

define('NAMA_TOKO', 'Lembar Pustaka');
define('WA_TOKO', '6281234567890');
define('IG_TOKO', '@lembarpustaka');

/*
|--------------------------------------------------------------------------
| DAFTAR KATEGORI
|--------------------------------------------------------------------------
| Dipakai di filter halaman publik serta form admin.
| Tambah atau ubah kategori cukup di sini.
*/

$daftar_kategori = [
    'Buku Tulis',
    'Alat Tulis',
    'Sampul & Pelindung',
    'Pembatas Buku',
    'Aksesoris Baca',
];

/*
|--------------------------------------------------------------------------
| HELPER
|--------------------------------------------------------------------------
*/

function e($teks)
{
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka)
{
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}
