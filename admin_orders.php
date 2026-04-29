<?php
session_start();
require 'db.php';

$customer_id = $_SESSION['user_id'];

// Fetch orders with address info
$stmt = $conn->prepare("
    SELECT o.id, o.total, o.status, o.created_at, o.address_id,
           a.full_name, a.street, a.city, a.province, a.postal_code
    FROM orders o
    LEFT JOIN addresses a ON o.address_id = a.id
    WHERE o.customer_id = ?
    ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h2>My Orders</h2>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Order ID</th>
            <th>Total</th>
            <th>Status</th>
            <th>Items</th>
            <th>Address</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while($order = $orders->fetch_assoc()): ?>
        <tr>
            <td>#<?= $order['id'] ?></td>
            <td>₱<?= number_format($order['total'], 2) ?></td>
            <td><span class="badge bg-info"><?= ucfirst($order['status']) ?></span></td>
            <td>
                <?php
                $items = $conn->prepare("SELECT product_name, quantity, price FROM order_items WHERE order_id=?");
                $items->bind_param("i", $order['id']);
                $items->execute();
                $result_items = $items->get_result();
                while($item = $result_items->fetch_assoc()){
                    echo htmlspecialchars($item['product_name'])." (x".$item['quantity'].") - ₱".number_format($item['price'],2)."<br>";
                }
                ?>
            </td>
            <td>
                <?php if (!empty($order['full_name'])): ?>
                    <?= htmlspecialchars($order['full_name']) ?>, 
                    <?= htmlspecialchars($order['street']) ?>, 
                    <?= htmlspecialchars($order['city']) ?>, 
                    <?= htmlspecialchars($order['province']) ?> 
                    (<?= htmlspecialchars($order['postal_code']) ?>)
                <?php else: ?>
                    <span class="text-muted">No address found</span>
                <?php endif; ?>
            </td>
            <td><?= $order['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
