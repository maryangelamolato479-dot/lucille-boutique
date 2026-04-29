<?php
session_start();
include 'db_conn.php';

// 1. SECURITY CHECK: Ensure only logged-in Admins can access this
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. VALIDATION: Check if the form was submitted
if (isset($_POST['update_status'])) {
    
    // Sanitize inputs to prevent SQL Injection
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    // 3. EXECUTION: Update the status in the orders table
    $sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back to the management page with a success message
        header("Location: order_management.php?msg=updated");
        exit();
    } else {
        // Show error if the query fails
        die("Update Error: " . mysqli_error($conn));
    }
} else {
    // If someone tries to access this file directly, send them back
    header("Location: order_management.php");
    exit();
}
?>