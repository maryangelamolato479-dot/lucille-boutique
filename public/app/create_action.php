<?php
include('../include/db.php'); // Note the ../ because we are inside the 'app' folder

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO inventory (product_name, price, stock) VALUES ('$name', '$price', '$stock')";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to dashboard with success message
        header("Location: ../index.php?status=success");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>