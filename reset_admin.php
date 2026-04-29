<?php
include 'db_conn.php';

$user = 'amolato';
$pass = 'amolato123';
$hashed = password_hash($pass, PASSWORD_DEFAULT);
$role = 'admin';

// First, delete any existing record of this user to avoid "Duplicate" errors
mysqli_query($conn, "DELETE FROM users WHERE username='$user'");

// Now, insert the fresh record with the correctly generated hash
$sql = "INSERT INTO users (username, password, role) VALUES ('$user', '$hashed', '$role')";

if (mysqli_query($conn, $sql)) {
    echo "<h3>Success!</h3>";
    echo "User <b>$user</b> has been reset with password <b>$pass</b>.<br>";
    echo "The hash saved was: <code>$hashed</code><br>";
    echo "<a href='login.php'>Try Login Now</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>