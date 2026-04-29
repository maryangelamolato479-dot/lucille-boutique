<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If the session variable isn't set, kick them back to login
if (!isset($_SESSION['admin_user'])) {
    header("Location: login.php");
    exit();
}
?>