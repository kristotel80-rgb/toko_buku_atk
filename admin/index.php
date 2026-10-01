<?php

require_once "../includes/auth.php";

require_once "../config/database.php";

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT
            COUNT(*) AS total_produk,
            COUNT(DISTINCT kategori) AS total_kategori,
            COALESCE(SUM(stok <= 5), 0) AS stok_menipis
         FROM produk"
    )
);

$judul = "Dashboard - " . NAMA_TOKO;
$area  = 'admin';
$base  = '../';

include "../includes/header.php";

?>

<main class="container page">

    <h1>Dashboard Admin</h1>

    <p>
        Selamat datang,
        <strong><?= e($_SESSION['admin_nama']); ?></strong>.
    </p>

    <?php include "../includes/flash.php"; ?>

    <div class="grid">

        <div class="card stat">

            <h3>Total Produk</h3>

            <p class="angka"><?= (int)$data['total_produk']; ?></p>

        </div>

        <div class="card stat">

            <h3>Total Kategori Terpakai</h3>

            <p class="angka"><?= (int)$data['total_kategori']; ?></p>

        </div>

        <div class="card stat">

            <h3>Stok Menipis (5 atau kurang)</h3>

            <p class="angka"><?= (int)$data['stok_menipis']; ?></p>

        </div>

    </div>

    <div class="card" style="margin-top: 24px">

        <h2>Manajemen Produk</h2>

        <p>
            Kelola katalog produk <?= e(NAMA_TOKO); ?>:
            tambah, ubah, dan hapus produk.
        </p>

        <div class="form-aksi">

            <a href="produk.php" class="btn">
                Kelola Produk
            </a>

            <a href="tambah.php" class="btn btn-outline">
                Tambah Produk
            </a>

        </div>

    </div>

</main>

<?php include "../includes/footer.php"; ?>
