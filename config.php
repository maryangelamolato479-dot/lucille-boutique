<?php

// Database configuration
$hostname = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$database = "lucille_boutique"; // Ensure this matches your phpMyAdmin DB name

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>