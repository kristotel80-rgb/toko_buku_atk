<?php

require_once "../includes/auth.php";

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| HANYA BOLEH LEWAT FORM (POST) + TOKEN VALID
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {

    $_SESSION['error'] =
        "Permintaan tidak valid. Silakan coba lagi.";

    header("Location: produk.php");

    exit;
}

$id = isset($_POST['id'])
    ? (int)$_POST['id']
    : 0;

/*
|--------------------------------------------------------------------------
| AMBIL DATA GAMBAR
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT gambar FROM produk
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$data = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

/*
|--------------------------------------------------------------------------
| HAPUS DATABASE
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM produk
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

$delete = mysqli_stmt_execute($stmt);

/*
|--------------------------------------------------------------------------
| HAPUS FILE GAMBAR
|--------------------------------------------------------------------------
*/

if ($delete && $data && $data['gambar']) {

    $path = "../uploads/produk/" . basename($data['gambar']);

    if (file_exists($path)) {
        unlink($path);
    }
}

if ($delete) {

    $_SESSION['success'] =
        "Produk berhasil dihapus.";

} else {

    $_SESSION['error'] =
        "Produk gagal dihapus.";
}

header("Location: produk.php");

exit;
