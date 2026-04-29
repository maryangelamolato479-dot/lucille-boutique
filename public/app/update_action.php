<?php
include('../include/db.php');

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE inventory 
            SET product_name = '$name', price = '$price', stock = '$stock' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../index.php?status=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>