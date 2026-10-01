<?php

require_once "../includes/auth.php";

require_once "../config/database.php";

$judul = "Tambah Produk - " . NAMA_TOKO;
$area  = 'admin';
$base  = '../';

include "../includes/header.php";

?>

<main class="container page">

    <div class="card">

        <h1>Tambah Produk</h1>

        <?php include "../includes/flash.php"; ?>

        <form
            action="proses_produk.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <?= csrf_field(); ?>

            <input
                type="hidden"
                name="aksi"
                value="tambah"
            >

            <label for="nama">Nama Produk</label>

            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                required
            >

            <label for="kategori">Kategori</label>

            <select id="kategori" name="kategori" required>

                <option value="">Pilih kategori</option>

                <?php foreach ($daftar_kategori as $kat): ?>
                    <option value="<?= e($kat); ?>">
                        <?= e($kat); ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <label for="deskripsi">Deskripsi</label>

            <textarea
                id="deskripsi"
                name="deskripsi"
                rows="5"
                required
            ></textarea>

            <div class="form-baris">

                <div>
                    <label for="harga">Harga (Rp)</label>

                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        min="0"
                        required
                    >
                </div>

                <div>
                    <label for="stok">Stok</label>

                    <input
                        type="number"
                        id="stok"
                        name="stok"
                        min="0"
                        required
                    >
                </div>

            </div>

            <label for="gambar">Gambar Produk</label>

            <input
                type="file"
                id="gambar"
                name="gambar"
                accept=".jpg,.jpeg,.png,.webp"
            >

            <p class="bantuan">
                Format JPG, PNG, atau WebP. Maksimal 2 MB.
            </p>

            <div class="form-aksi">

                <button
                    type="submit"
                    class="btn"
                >
                    Simpan Produk
                </button>

                <a
                    href="produk.php"
                    class="btn btn-outline"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</main>

<?php include "../includes/footer.php"; ?>
