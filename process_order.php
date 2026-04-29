<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

$customer_id = $_SESSION['user_id'];
$status = "pending";

// Validate address_id
if (!isset($_POST['address_id']) || empty($_POST['address_id'])) {
    die("No address selected. Please go back and choose a delivery address.");
}
$address_id = (int)$_POST['address_id'];

// Validate cart
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    die("Cart is empty. Please add items before checkout.");
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['qty'] * $item['price'];
}

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (customer_id, total, status, address_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("idsi", $customer_id, $total, $status, $address_id);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert items
foreach ($_SESSION['cart'] as $item) {
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
    $stmt_item->bind_param("iisid", $order_id, $item['id'], $item['name'], $item['qty'], $item['price']);
    $stmt_item->execute();
}

// Clear cart
unset($_SESSION['cart']);

header("Location: customer_orders.php");
exit;
?>
