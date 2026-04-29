<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lucille_db"; // Make sure this matches your database name exactly

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// You can comment this out once you know it works so it doesn't show on your dashboard
// echo "Connected successfully"; 
?>