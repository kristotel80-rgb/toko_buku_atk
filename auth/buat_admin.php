<?php

/*
|--------------------------------------------------------------------------
| BUAT ADMIN PERTAMA
|--------------------------------------------------------------------------
| Hanya berjalan jika tabel admin masih kosong.
| SETELAH BERHASIL, HAPUS FILE INI dari server.
*/

require_once "../config/database.php";

$nama = "Administrator";
$username = "admin";
$password = "admin123";

$cek = mysqli_query($conn, "SELECT COUNT(*) AS total FROM admin");

$ada = (int)mysqli_fetch_assoc($cek)['total'];

if ($ada > 0) {

    die("Admin sudah ada. Hapus file buat_admin.php dari server.");
}

$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO admin
    (nama, username, password)
    VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $nama,
    $username,
    $password_hash
);

if (mysqli_stmt_execute($stmt)) {

    echo "Admin berhasil dibuat (username: admin, password: admin123). "
       . "Segera login, lalu hapus file buat_admin.php.";

} else {

    echo "Gagal membuat admin.";
}
