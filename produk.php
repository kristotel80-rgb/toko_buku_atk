<?php

require_once "config/database.php";

$keyword  = trim($_GET['keyword'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');

/*
|--------------------------------------------------------------------------
| FILTER (pencarian nama + kategori)
|--------------------------------------------------------------------------
*/

$where  = [];
$tipe   = '';
$params = [];

if ($keyword != '') {
    $where[]  = "nama LIKE ?";
    $tipe    .= 's';
    $params[] = '%' . $keyword . '%';
}

if ($kategori != '') {
    $where[]  = "kategori = ?";
    $tipe    .= 's';
    $params[] = $kategori;
}

$where_sql = count($where) > 0
    ? "WHERE " . implode(" AND ", $where)
    : "";

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$per_page = 9;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $per_page;

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM produk $where_sql");

if ($tipe != '') {
    mysqli_stmt_bind_param($stmt, $tipe, ...$params);
}

mysqli_stmt_execute($stmt);

$total_data = (int)mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];

$total_page = max(1, (int)ceil($total_data / $per_page));

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM produk
     $where_sql
     ORDER BY id DESC
     LIMIT $per_page OFFSET $offset"
);

if ($tipe != '') {
    mysqli_stmt_bind_param($stmt, $tipe, ...$params);
}

mysqli_stmt_execute($stmt);

$query = mysqli_stmt_get_result($stmt);

$judul = "Produk - " . NAMA_TOKO;
$area  = 'publik';
$base  = '';

include "includes/header.php";

?>

<main class="container page">

    <h1>Daftar Produk</h1>

    <div class="card">

        <form method="GET" class="form-cari">

            <div>
                <label for="keyword">Cari produk</label>
                <input
                    type="text"
                    id="keyword"
                    name="keyword"
                    placeholder="Contoh: buku tulis"
                    value="<?= e($keyword); ?>"
                >
            </div>

            <div>
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori">

                    <option value="">Semua kategori</option>

                    <?php foreach ($daftar_kategori as $kat): ?>
                        <option
                            value="<?= e($kat); ?>"
                            <?= $kategori === $kat ? 'selected' : ''; ?>
                        >
                            <?= e($kat); ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <button type="submit" class="btn">
                Cari
            </button>

        </form>

    </div>

    <?php if ($total_data === 0): ?>

        <div class="card">
            <h3>Produk tidak ditemukan</h3>
            <p class="teks-kecil">
                Coba kata kunci lain, atau
                <a href="produk.php">tampilkan semua produk</a>.
            </p>
        </div>

    <?php else: ?>

    <p class="teks-kecil">
        Menampilkan <?= $total_data; ?> produk
    </p>

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

                <p class="deskripsi"><?= e($row['deskripsi']); ?></p>

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

    <?php if ($total_page > 1): ?>

    <nav class="pagination" aria-label="Halaman">

        <?php for ($i = 1; $i <= $total_page; $i++): ?>

            <a
                href="?page=<?= $i; ?>&keyword=<?= urlencode($keyword); ?>&kategori=<?= urlencode($kategori); ?>"
                class="<?= $i === $page ? 'aktif' : ''; ?>"
            >
                <?= $i; ?>
            </a>

        <?php endfor; ?>

    </nav>

    <?php endif; ?>

    <?php endif; ?>

</main>

<?php include "includes/footer.php"; ?>
