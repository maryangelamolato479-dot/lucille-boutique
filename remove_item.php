<?php
session_start();
include 'db_conn.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $cart_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Only delete the item if it belongs to the logged-in customer
    $sql = "DELETE FROM cart WHERE id = '$cart_id' AND user_id = '$user_id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: view_cart.php"); // Go back to cart after deleting
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: view_cart.php");
}
exit();
?>