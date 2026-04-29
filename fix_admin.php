<?php
include 'db_conn.php';

// Change 'admin123' to whatever password you want for your admin
$plain_password = 'admin123'; 
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Update the 'users' table (Admin table)
$sql = "UPDATE users SET password = '$hashed_password' WHERE username = 'admin'"; 

if (mysqli_query($conn, $sql)) {
    echo "Admin password hashed successfully! You can now log in.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>