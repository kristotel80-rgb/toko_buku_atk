<?php

/*
|--------------------------------------------------------------------------
| HEADER (dipakai semua halaman)
|--------------------------------------------------------------------------
| Isi variabel ini sebelum include header.php:
|
|   $judul  => judul tab browser
|   $area   => 'publik' (default), 'admin', atau 'auth' (tanpa navbar)
|   $base   => '' untuk halaman di root, '../' untuk halaman di subfolder
|
| Wajib require config/database.php lebih dulu.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$judul  = $judul ?? NAMA_TOKO;
$area   = $area ?? 'publik';
$base   = $base ?? '';
$aktif  = basename($_SERVER['SCRIPT_NAME']);

if (!function_exists('nav_link')) {
    function nav_link($file, $label, $href, $aktif)
    {
        $kelas = ($file === $aktif) ? ' class="aktif" aria-current="page"' : '';

        return '<a href="' . $href . '"' . $kelas . '>' . $label . '</a>';
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($judul); ?></title>

    <link rel="stylesheet" href="<?= $base; ?>assets/style.css">

</head>

<body class="area-<?= e($area); ?>">

<?php if ($area === 'publik'): ?>

<header class="navbar">

    <div class="container navbar-inner">

        <a href="<?= $base; ?>index.php" class="brand">
            <?= e(NAMA_TOKO); ?>
        </a>

        <nav class="nav-links" aria-label="Menu utama">
            <?= nav_link('produk.php', 'Produk', $base . 'produk.php', $aktif); ?>
            <?= nav_link('tentang.php', 'Tentang Kami', $base . 'tentang.php', $aktif); ?>
        </nav>

    </div>

</header>

<?php elseif ($area === 'admin'): ?>

<header class="navbar">

    <div class="container navbar-inner">

        <a href="index.php" class="brand">
            <?= e(NAMA_TOKO); ?> Admin
        </a>

        <nav class="nav-links" aria-label="Menu admin">
            <?= nav_link('index.php', 'Dashboard', 'index.php', $aktif); ?>
            <?= nav_link('produk.php', 'Produk', 'produk.php', $aktif); ?>
            <a href="../index.php">Lihat Website</a>
            <a href="logout.php">Logout</a>
        </nav>

    </div>

</header>

<?php endif; ?>
