<?php

require_once "../includes/auth.php";

require_once "../config/database.php";

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

    $_SESSION['error'] = "Produk tidak ditemukan.";

    header("Location: produk.php");

    exit;
}

$judul = "Edit Produk - " . NAMA_TOKO;
$area  = 'admin';
$base  = '../';

include "../includes/header.php";

?>

<main class="container page">

    <div class="card">

        <h1>Edit Produk</h1>

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
                value="edit"
            >

            <input
                type="hidden"
                name="id"
                value="<?= (int)$produk['id']; ?>"
            >

            <label for="nama">Nama Produk</label>

            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                value="<?= e($produk['nama']); ?>"
                required
            >

            <label for="kategori">Kategori</label>

            <select id="kategori" name="kategori" required>

                <?php foreach ($daftar_kategori as $kat): ?>
                    <option
                        value="<?= e($kat); ?>"
                        <?= $produk['kategori'] === $kat ? 'selected' : ''; ?>
                    >
                        <?= e($kat); ?>
                    </option>
                <?php endforeach; ?>

                <?php if (!in_array($produk['kategori'], $daftar_kategori, true)): ?>
                    <option value="<?= e($produk['kategori']); ?>" selected>
                        <?= e($produk['kategori']); ?> (kategori lama)
                    </option>
                <?php endif; ?>

            </select>

            <label for="deskripsi">Deskripsi</label>

            <textarea
                id="deskripsi"
                name="deskripsi"
                rows="5"
                required
            ><?= e($produk['deskripsi']); ?></textarea>

            <div class="form-baris">

                <div>
                    <label for="harga">Harga (Rp)</label>

                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        min="0"
                        value="<?= (int)$produk['harga']; ?>"
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
                        value="<?= (int)$produk['stok']; ?>"
                        required
                    >
                </div>

            </div>

            <label for="gambar">Ganti Gambar</label>

            <input
                type="file"
                id="gambar"
                name="gambar"
                accept=".jpg,.jpeg,.png,.webp"
            >

            <p class="bantuan">
                Kosongkan jika tidak ingin mengganti gambar.
                Format JPG, PNG, atau WebP. Maksimal 2 MB.
            </p>

            <?php if ($produk['gambar']): ?>

                <p>Gambar saat ini:</p>

                <img
                    src="../uploads/produk/<?= e($produk['gambar']); ?>"
                    alt="<?= e($produk['nama']); ?>"
                    width="200"
                >

            <?php endif; ?>

            <br><br>

            <div class="form-aksi">

                <button
                    type="submit"
                    class="btn"
                >
                    Simpan Perubahan
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
