<?php

require_once "config/database.php";

$judul = "Tentang Kami - " . NAMA_TOKO;
$area  = 'publik';
$base  = '';

include "includes/header.php";

?>

<main class="container page">

    <div class="card">

        <h1>Tentang <?= e(NAMA_TOKO); ?></h1>

        <div class="prosa">

            <p>
                <?= e(NAMA_TOKO); ?> adalah UMKM yang menjual
                perlengkapan buku: buku tulis, alat tulis, sampul,
                pembatas, dan berbagai pelengkap untuk menulis
                maupun membaca.
            </p>

            <p>
                Kami memilih produk yang nyaman dipakai setiap hari
                untuk kebutuhan sekolah, kuliah, kantor, jurnal
                pribadi, dan koleksi buku di rumah.
            </p>

        </div>

        <h2>Kontak</h2>

        <p>
            WhatsApp:
            <a
                href="https://wa.me/<?= e(WA_TOKO); ?>"
                target="_blank"
                rel="noopener"
            >
                +<?= e(WA_TOKO); ?>
            </a>
        </p>

        <p>
            Instagram: <?= e(IG_TOKO); ?>
        </p>

        <a href="produk.php" class="btn">
            Lihat Produk
        </a>

    </div>

</main>

<?php include "includes/footer.php"; ?>
