<?php
$conn = mysqli_connect("localhost", "root", "", "lucille_boutique");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Database connected successfully!";