<?php

require_once "../includes/auth.php";

require_once "../config/database.php";

$keyword  = trim($_GET['keyword'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');

/*
|--------------------------------------------------------------------------
| SEARCH & FILTER
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

$per_page = 5;

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

$judul = "Kelola Produk - " . NAMA_TOKO;
$area  = 'admin';
$base  = '../';

include "../includes/header.php";

?>

<main class="container page">

    <h1>Kelola Produk</h1>

    <?php include "../includes/flash.php"; ?>

    <p>
        <a href="tambah.php" class="btn">
            Tambah Produk
        </a>
    </p>

    <!-- SEARCH & FILTER -->

    <div class="card">

        <form method="GET" class="form-cari">

            <div>
                <label for="keyword">Cari produk</label>
                <input
                    type="text"
                    id="keyword"
                    name="keyword"
                    placeholder="Masukkan nama produk"
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

            <div class="form-aksi">

                <button type="submit" class="btn">
                    Cari
                </button>

                <a href="produk.php" class="btn btn-outline">
                    Reset
                </a>

            </div>

        </form>

    </div>

    <div class="card">

        <div class="table-wrap">

        <table>

            <thead>
            <tr>

                <th>No</th>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>

            </tr>
            </thead>

            <tbody>

            <?php

            $no = $offset + 1;

            while ($row = mysqli_fetch_assoc($query)):

            ?>

            <tr>

                <td><?= $no++; ?></td>

                <td>

                    <?php if ($row['gambar']): ?>

                        <img
                            src="../uploads/produk/<?= e($row['gambar']); ?>"
                            alt="<?= e($row['nama']); ?>"
                        >

                    <?php else: ?>

                        <span class="teks-kecil">Tidak ada</span>

                    <?php endif; ?>

                </td>

                <td><?= e($row['nama']); ?></td>

                <td><?= e($row['kategori']); ?></td>

                <td><?= rupiah($row['harga']); ?></td>

                <td>
                    <?php if ((int)$row['stok'] === 0): ?>
                        <span class="badge badge-habis">Habis</span>
                    <?php elseif ((int)$row['stok'] <= 5): ?>
                        <span class="badge badge-menipis"><?= (int)$row['stok']; ?></span>
                    <?php else: ?>
                        <?= (int)$row['stok']; ?>
                    <?php endif; ?>
                </td>

                <td class="aksi">

                    <a
                        href="edit.php?id=<?= (int)$row['id']; ?>"
                        class="btn btn-warning btn-sm"
                    >
                        Edit
                    </a>

                    <form
                        action="hapus.php"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                    >
                        <?= csrf_field(); ?>
                        <input type="hidden" name="id" value="<?= (int)$row['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>

                </td>

            </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

        </div>

        <?php if ($total_data === 0): ?>

            <p class="tabel-kosong">
                Tidak ada produk yang cocok.
            </p>

        <?php endif; ?>

    </div>

    <!-- PAGINATION -->

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

</main>

<?php include "../includes/footer.php"; ?>
