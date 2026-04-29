<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = md5($_POST['password']); // match registration encryption

    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email' AND password='$pass'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);

        // Store user data in Session variables
        $_SESSION['user_id']    = $row['id'];
        $_SESSION['user_name']  = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['user_addr']  = $row['address'];
        $_SESSION['user_type']  = $row['user_type'];

        // Redirect based on user type
        if ($row['user_type'] === 'admin') {
            header('location:admin_dashboard.php');
        } else {
            header('location:customer_dashboard.php');
        }
        exit();
    } else {
        $message[] = 'Incorrect email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Login | Lucille Boutique</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 400px; border: none;">
        <h3 class="text-center text-info mb-4">LUCILLE</h3>
        
        <?php if(isset($message)){ foreach($message as $msg){ echo '<div class="alert alert-danger">'.$msg.'</div>'; } } ?>

        <form action="" method="post">
            <div class="mb-3">
                <input type="email" name="email" placeholder="Enter Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" placeholder="Enter Password" class="form-control" required>
            </div>
            <input type="submit" name="submit" value="LOGIN NOW" class="btn btn-info w-100 text-white fw-bold">
            <p class="text-center mt-3 small">Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</div>

</body>
</html>
