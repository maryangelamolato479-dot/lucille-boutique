<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    die("Error: You do not have permission to access this page.");
}
?>