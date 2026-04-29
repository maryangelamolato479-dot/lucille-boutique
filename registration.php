<?php
include 'db_conn.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // Capture the address from the form
    $address = mysqli_real_escape_string($conn, $_POST['address']); 
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // SQL now includes the address column
    $sql = "INSERT INTO customers (name, email, address, password, role) 
            VALUES ('$name', '$email', '$address', '$pass', 'customer')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Account Created!'); window.location='login.php';</script>";
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <textarea name="address" placeholder="Complete Delivery Address" required></textarea>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="register">Register</button>
</form>