<?php
session_start();
include 'db_conn.php'; 

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    // 1. Notice the table name is 'customers' based on your screenshot
    $sql = "SELECT * FROM customers WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // 2. Your screenshot shows MD5 hashes. Use md5() to check them.
        if (md5($pass) === $row['password']) {
            
            // 3. Set the sessions so the Cart works
            $_SESSION['user_id'] = $row['id']; 
            $_SESSION['username'] = $row['name']; // Using 'name' column
            
            header("Location: customer_dashboard.php");
            exit();
        }
    }
    echo "<script>alert('Invalid Login'); window.location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Lucille Boutique</title>
</head>
<body>
    <form method="POST" action="login_controller.php">
        <input type="text" name="uname" placeholder="Username" required> <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Lucille Boutique</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; padding-top: 100px; background: #f4f4f4; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input { display: block; width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #2c3e50; color: white; border: none; cursor: pointer; }
        button:hover { background: #34495e; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <input type="text" name="uname" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>