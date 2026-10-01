<?php

session_start();

if (isset($_SESSION['admin_id'])) {

    header("Location: ../admin/index.php");

    exit;
}

require_once "../config/database.php";

$judul = "Login Admin - " . NAMA_TOKO;
$area  = 'auth';
$base  = '../';

include "../includes/header.php";

?>

<main class="container">

    <div class="card login-box">

        <h1>Login Admin</h1>

        <p class="teks-kecil">
            Masuk untuk mengelola produk <?= e(NAMA_TOKO); ?>.
        </p>

        <?php include "../includes/flash.php"; ?>

        <form
            action="proses_login.php"
            method="POST"
        >

            <label for="username">Username</label>

            <input
                type="text"
                id="username"
                name="username"
                autocomplete="username"
                required
                autofocus
            >

            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button
                type="submit"
                class="btn"
            >
                Masuk
            </button>

        </form>

        <p style="margin: 20px 0 0">
            <a href="../index.php">Kembali ke website</a>
        </p>

    </div>

</main>

<?php include "../includes/footer.php"; ?>
