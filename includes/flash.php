<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success" role="status">'
        . htmlspecialchars($_SESSION['success'])
        . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-error" role="alert">'
        . htmlspecialchars($_SESSION['error'])
        . '</div>';
    unset($_SESSION['error']);
}
