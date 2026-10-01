<?php

require_once "config/database.php";

$id = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM produk
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$produk = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

if (!$produk) {

    http_response_code(404);

    die("Produk tidak ditemukan.");

}

$stok = (int)$produk['stok'];

$pesan_wa = "Halo " . NAMA_TOKO . ", saya ingin memesan: "
    . $produk['nama'] . " (" . rupiah($produk['harga']) . ").";

$link_wa = "https://wa.me/" . WA_TOKO . "?text=" . rawurlencode($pesan_wa);

$judul = $produk['nama'] . " - " . NAMA_TOKO;
$area  = 'publik';
$base  = '';

include "includes/header.php";

?>

<main class="container page">

    <p class="remah">
        <a href="produk.php">Produk</a>
        /
        <a href="produk.php?kategori=<?= urlencode($produk['kategori']); ?>">
            <?= e($produk['kategori']); ?>
        </a>
    </p>

    <div class="detail">

        <?php if ($produk['gambar']): ?>

            <img
                src="uploads/produk/<?= e($produk['gambar']); ?>"
                alt="<?= e($produk['nama']); ?>"
                class="product-image"
            >

        <?php else: ?>

            <div class="gambar-kosong">Tanpa gambar</div>

        <?php endif; ?>

        <div>

            <h1><?= e($produk['nama']); ?></h1>

            <p class="teks-kecil">
                Kategori: <?= e($produk['kategori']); ?>
            </p>

            <div class="harga-besar">
                <?= rupiah($produk['harga']); ?>
            </div>

            <p>
                <?php if ($stok === 0): ?>
                    <span class="badge badge-habis">Stok habis</span>
                <?php elseif ($stok <= 5): ?>
                    <span class="badge badge-menipis">
                        Sisa <?= $stok; ?>
                    </span>
                <?php else: ?>
                    <span class="badge">Stok tersedia: <?= $stok; ?></span>
                <?php endif; ?>
            </p>

            <p class="prosa">
                <?= nl2br(e($produk['deskripsi'])); ?>
            </p>

            <div class="form-aksi">

                <?php if ($stok > 0): ?>
                    <a
                        href="<?= e($link_wa); ?>"
                        class="btn"
                        target="_blank"
                        rel="noopener"
                    >
                        Pesan via WhatsApp
                    </a>
                <?php endif; ?>

                <a href="produk.php" class="btn btn-outline">
                    Kembali ke Produk
                </a>

            </div>

        </div>

    </div>

</main>

<?php include "includes/footer.php"; ?>
