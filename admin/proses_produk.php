<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {

    $_SESSION['error'] =
        "Permintaan tidak valid. Silakan coba lagi.";

    header("Location: produk.php");

    exit;
}

$aksi = $_POST['aksi'] ?? '';

/*
|--------------------------------------------------------------------------
| FUNGSI BANTU
|--------------------------------------------------------------------------
*/

/**
 * Validasi input produk.
 * Mengembalikan pesan error, atau null jika semua valid.
 */
function validasi_produk($nama, $kategori, $deskripsi, $harga, $stok, $daftar_kategori)
{
    if (
        $nama == '' ||
        $kategori == '' ||
        $deskripsi == '' ||
        $harga === '' ||
        $stok === ''
    ) {
        return "Semua data wajib diisi.";
    }

    if (!in_array($kategori, $daftar_kategori, true)) {
        return "Kategori tidak valid.";
    }

    if (!is_numeric($harga) || $harga < 0) {
        return "Harga harus berupa angka dan tidak boleh negatif.";
    }

    if (filter_var($stok, FILTER_VALIDATE_INT) === false || $stok < 0) {
        return "Stok harus berupa bilangan bulat dan tidak boleh negatif.";
    }

    return null;
}

/**
 * Validasi file gambar yang diupload.
 * Mengembalikan pesan error, atau null jika valid.
 */
function validasi_gambar($file)
{
    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
        return "Format gambar tidak diperbolehkan.";
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return "Ukuran gambar maksimal 2 MB.";
    }

    // pastikan isi file benar-benar gambar
    if (@getimagesize($file['tmp_name']) === false) {
        return "File yang diupload bukan gambar yang valid.";
    }

    return null;
}

/*
|--------------------------------------------------------------------------
| TAMBAH PRODUK
|--------------------------------------------------------------------------
*/

if ($aksi == 'tambah') {

    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok = trim($_POST['stok'] ?? '');

    $error = validasi_produk(
        $nama, $kategori, $deskripsi, $harga, $stok, $daftar_kategori
    );

    /*
    |----------------------------------------------------------------------
    | UPLOAD GAMBAR
    |----------------------------------------------------------------------
    */

    $nama_file = null;

    $ada_gambar = isset($_FILES['gambar'])
        && $_FILES['gambar']['error'] == 0;

    if (!$error && $ada_gambar) {
        $error = validasi_gambar($_FILES['gambar']);
    }

    if ($error) {

        $_SESSION['error'] = $error;

        header("Location: tambah.php");

        exit;
    }

    if ($ada_gambar) {

        $extension = strtolower(
            pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION)
        );

        $nama_file = uniqid() . "." . $extension;

        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            "../uploads/produk/" . $nama_file
        );
    }

    /*
    |----------------------------------------------------------------------
    | INSERT DATA
    |----------------------------------------------------------------------
    */

    $harga = (float)$harga;
    $stok = (int)$stok;

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO produk
        (nama, kategori, deskripsi, harga, stok, gambar)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssdis",
        $nama,
        $kategori,
        $deskripsi,
        $harga,
        $stok,
        $nama_file
    );

    if (mysqli_stmt_execute($stmt)) {

        $_SESSION['success'] =
            "Produk berhasil ditambahkan.";

    } else {

        $_SESSION['error'] =
            "Produk gagal ditambahkan.";
    }

    header("Location: produk.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| EDIT PRODUK
|--------------------------------------------------------------------------
*/

if ($aksi == 'edit') {

    $id = (int)($_POST['id'] ?? 0);

    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok = trim($_POST['stok'] ?? '');

    /*
    |----------------------------------------------------------------------
    | AMBIL DATA LAMA
    |----------------------------------------------------------------------
    */

    $stmt = mysqli_prepare(
        $conn,
        "SELECT gambar, kategori FROM produk
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $old_data = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );

    if (!$old_data) {

        $_SESSION['error'] = "Produk tidak ditemukan.";

        header("Location: produk.php");

        exit;
    }

    /*
    |----------------------------------------------------------------------
    | VALIDASI
    |----------------------------------------------------------------------
    */

    $error = validasi_produk(
        $nama, $kategori, $deskripsi, $harga, $stok,
        array_merge($daftar_kategori, [$old_data['kategori']])
    );

    $ada_gambar = isset($_FILES['gambar'])
        && $_FILES['gambar']['error'] == 0;

    if (!$error && $ada_gambar) {
        $error = validasi_gambar($_FILES['gambar']);
    }

    if ($error) {

        $_SESSION['error'] = $error;

        header("Location: edit.php?id=$id");

        exit;
    }

    /*
    |----------------------------------------------------------------------
    | JIKA ADA GAMBAR BARU
    |----------------------------------------------------------------------
    */

    $nama_file = $old_data['gambar'];

    if ($ada_gambar) {

        $extension = strtolower(
            pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION)
        );

        $nama_file = uniqid() . "." . $extension;

        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            "../uploads/produk/" . $nama_file
        );

        // hapus gambar lama
        if ($old_data['gambar']) {

            $path_lama = "../uploads/produk/" . basename($old_data['gambar']);

            if (file_exists($path_lama)) {
                unlink($path_lama);
            }
        }
    }

    /*
    |----------------------------------------------------------------------
    | UPDATE DATABASE
    |----------------------------------------------------------------------
    */

    $harga = (float)$harga;
    $stok = (int)$stok;

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE produk SET
            nama = ?,
            kategori = ?,
            deskripsi = ?,
            harga = ?,
            stok = ?,
            gambar = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssdisi",
        $nama,
        $kategori,
        $deskripsi,
        $harga,
        $stok,
        $nama_file,
        $id
    );

    /*
    |----------------------------------------------------------------------
    | FLASH MESSAGE
    |----------------------------------------------------------------------
    */

    if (mysqli_stmt_execute($stmt)) {

        $_SESSION['success'] =
            "Produk berhasil diperbarui.";

    } else {

        $_SESSION['error'] =
            "Produk gagal diperbarui.";
    }

    header("Location: produk.php");

    exit;
}

/*
|--------------------------------------------------------------------------
| AKSI TIDAK DIKENAL
|--------------------------------------------------------------------------
*/

header("Location: produk.php");

exit;
