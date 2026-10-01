<?php

require_once "config/database.php";

/*
|--------------------------------------------------------------------------
| PRODUK TERBARU
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT * FROM produk
     ORDER BY id DESC
     LIMIT 6"
);

/*
|--------------------------------------------------------------------------
| JUMLAH PRODUK PER KATEGORI
|--------------------------------------------------------------------------
*/

$jumlah_kategori = [];

$hitung = mysqli_query(
    $conn,
    "SELECT kategori, COUNT(*) AS total
     FROM produk
     GROUP BY kategori"
);

while ($r = mysqli_fetch_assoc($hitung)) {
    $jumlah_kategori[$r['kategori']] = (int)$r['total'];
}

$judul = NAMA_TOKO . " - Perlengkapan Buku";
$area  = 'publik';
$base  = '';

include "includes/header.php";

?>

<section class="hero">

    <div class="container hero-inner">

        <div>

            <h1>
                Perlengkapan untuk buku yang kamu tulis dan kamu baca.
            </h1>

            <p>
                Buku tulis, alat tulis, sampul, pembatas, dan
                pelengkap membaca pilihan untuk pelajar, penulis,
                dan pencinta buku.
            </p>

            <div class="hero-actions">

                <a href="produk.php" class="btn">
                    Lihat Semua Produk
                </a>

                <a href="tentang.php" class="btn btn-outline">
                    Tentang Kami
                </a>

            </div>

        </div>

        <div class="rak" aria-hidden="true">
            <span style="--h: 62%;  --c: var(--sage)"></span>
            <span style="--h: 82%;  --c: var(--ink)"></span>
            <span style="--h: 70%;  --c: var(--mustard)"></span>
            <span style="--h: 94%;  --c: var(--line)"></span>
            <span style="--h: 76%;  --c: var(--sage)"></span>
            <span style="--h: 58%;  --c: var(--ink)"></span>
            <span style="--h: 88%;  --c: var(--mustard)"></span>
            <span style="--h: 66%;  --c: var(--line)"></span>
            <span class="miring" style="--h: 80%; --c: var(--sage)"></span>
        </div>

    </div>

</section>

<div class="container">

    <section class="section">

        <div class="section-head">
            <h2>Cari berdasarkan kategori</h2>
        </div>

        <div class="kategori-list">

            <?php foreach ($daftar_kategori as $kat): ?>

                <a
                    href="produk.php?kategori=<?= urlencode($kat); ?>"
                    class="kategori-item"
                >
                    <strong><?= e($kat); ?></strong>
                    <span>
                        <?= $jumlah_kategori[$kat] ?? 0; ?> produk
                    </span>
                </a>

            <?php endforeach; ?>

        </div>

    </section>

    <section class="section section-akhir">

        <div class="section-head">
            <h2>Produk Terbaru</h2>
            <a href="produk.php">Lihat semua produk</a>
        </div>

        <?php if (mysqli_num_rows($query) === 0): ?>

            <div class="card">
                <p class="teks-kecil" style="margin:0">
                    Belum ada produk. Produk akan tampil di sini
                    setelah ditambahkan lewat halaman admin.
                </p>
            </div>

        <?php else: ?>

        <div class="grid">

            <?php while ($row = mysqli_fetch_assoc($query)): ?>

            <article class="card produk-card">

                <?php if ($row['gambar']): ?>

                    <img
                        src="uploads/produk/<?= e($row['gambar']); ?>"
                        alt="<?= e($row['nama']); ?>"
                        class="product-image"
                        loading="lazy"
                    >

                <?php else: ?>

                    <div class="gambar-kosong">Tanpa gambar</div>

                <?php endif; ?>

                <div class="isi">

                    <h3><?= e($row['nama']); ?></h3>

                    <p class="kategori"><?= e($row['kategori']); ?></p>

                    <div class="harga"><?= rupiah($row['harga']); ?></div>

                    <a
                        href="detail.php?id=<?= (int)$row['id']; ?>"
                        class="btn"
                    >
                        Lihat Detail
                    </a>

                </div>

            </article>

            <?php endwhile; ?>

        </div>

        <?php endif; ?>

    </section>

</div>

<?php include "includes/footer.php"; ?>
