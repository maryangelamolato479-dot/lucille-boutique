<?php
session_start();
include 'db_conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $total_price = $_POST['total_price'];

    // We only use user_id and total_amount to avoid 'Unknown Column' errors
    // If you have a 'status' or 'created_at' column, you can add them back later
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total_price);

    if ($stmt->execute()) {
        $order_id = $conn->insert_id;

        if (isset($_POST['product_ids']) && isset($_POST['address'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                // Get the address from the array using the product ID as the key
                $address = $_POST['address'][$product_id];
                $quantity = $_SESSION['cart'][$product_id];

                // Inserting into order_items
                $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, delivery_address) VALUES (?, ?, ?, ?)");
                $item_stmt->bind_param("iiis", $order_id, $product_id, $quantity, $address);
                $item_stmt->execute();
            }

            // Clear the cart
            unset($_SESSION['cart']);
            echo "<script>alert('Order placed successfully!'); window.location.href='customer_dashboard.php';</script>";
        }
    } else {
        echo "Database Error: " . $stmt->error;
    }
}
?>