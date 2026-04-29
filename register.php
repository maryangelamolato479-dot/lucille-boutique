<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $name      = mysqli_real_escape_string($conn, $_POST['name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = md5($_POST['password']); // match login encryption
    $address   = mysqli_real_escape_string($conn, $_POST['address']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']); // 'customer' or 'admin'

    // Check if email already exists
    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $message[] = 'User already exists!';
    } else {
        $insert = mysqli_query($conn, "INSERT INTO `users` (name,email,password,address,user_type) 
                                       VALUES ('$name','$email','$password','$address','$user_type')") 
                                       or die('query failed');

        if ($insert) {
            header('location:login.php');
            exit();
        } else {
            echo "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Register | Lucille Boutique</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 450px; border: none;">
        <h3 class="text-center text-info mb-4">Create Account</h3>

        <?php if(isset($message)){ foreach($message as $msg){ echo '<div class="alert alert-danger">'.$msg.'</div>'; } } ?>

        <form action="" method="post">
            <div class="mb-3">
                <input type="text" name="name" placeholder="Enter Name" class="form-control" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" placeholder="Enter Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" placeholder="Enter Password" class="form-control" required>
            </div>
            <div class="mb-3">
                <textarea name="address" placeholder="Enter Address" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <select name="user_type" class="form-select">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <input type="submit" name="submit" value="Register Now" class="btn btn-info w-100 text-white fw-bold">
            <p class="text-center mt-3 small">Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </div>
</div>

</body>
</html>
