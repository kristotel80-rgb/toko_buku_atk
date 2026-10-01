<?php

/*
|--------------------------------------------------------------------------
| FOOTER (dipakai semua halaman)
|--------------------------------------------------------------------------
| Memakai variabel $area dan $base yang sama dengan header.php.
*/

$area = $area ?? 'publik';
$base = $base ?? '';

?>

<?php if ($area === 'publik'): ?>

<footer class="footer">

    <div class="container footer-inner">

        <div>
            <strong class="brand-footer"><?= e(NAMA_TOKO); ?></strong>
            <p>Perlengkapan buku untuk menulis, membaca, dan merawatnya.</p>
        </div>

        <div class="footer-links">
            <a href="<?= $base; ?>produk.php">Produk</a>
            <a href="<?= $base; ?>tentang.php">Tentang Kami</a>
            <a href="<?= $base; ?>auth/login.php">Login Admin</a>
        </div>

    </div>

    <div class="container footer-copy">
        &copy; <?= date('Y'); ?> <?= e(NAMA_TOKO); ?>
    </div>

</footer>

<?php endif; ?>

</body>

</html>
