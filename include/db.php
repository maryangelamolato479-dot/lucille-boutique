<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lucille_boutique"; // Make sure this matches your phpMyAdmin DB name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>