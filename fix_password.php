<?php
include 'db_conn.php';

// We are setting the password to 'admin123'
$new_password = password_hash('admin123', PASSWORD_DEFAULT);

// This updates the 'admin' user in your screenshot
$sql = "UPDATE users SET password='$new_password' WHERE username='admin'";

if (mysqli_query($conn, $sql)) {
    echo "<h1>Success!</h1>";
    echo "The password for <b>admin</b> is now: <b>admin123</b>";
    echo "<br><a href='login.php'>Go to Login Page</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>