<?php
session_start();

// ONLY ADMIN CAN ACCESS
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>