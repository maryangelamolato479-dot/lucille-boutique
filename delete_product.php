<?php
include 'db_conn.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

    // 1. Get image filename from DB
    $select = $conn->query("SELECT image FROM products WHERE id=$id");
    if ($select && $select->num_rows > 0) {
        $row = $select->fetch_assoc();
        $imagePath = "uploads/products/" . $row['image'];

        // 2. Delete image file if it exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // 3. Delete product record from DB
    $sql = "DELETE FROM products WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: inventory.php");
        exit();
    } else {
        echo "<script>alert('Delete failed'); window.location='inventory.php';</script>";
    }
}
?>
