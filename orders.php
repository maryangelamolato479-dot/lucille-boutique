<?php
session_start();
include 'config.php';

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('location:login.php');
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status   = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$order_id") or die('query failed');
    header('location:orders.php');
    exit();
}

// Fetch orders with customer info
$orders = mysqli_query($conn, "SELECT o.id, u.name, u.email, o.status, o.created_at 
                               FROM orders o 
                               JOIN users u ON o.customer_id=u.id 
                               ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status-badge { background: #ffeeba; color: #856404; padding: 5px 10px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Order Management</h2>
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Status</th>
                <th>Date</th>
                <th>Items</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($o = mysqli_fetch_assoc($orders)): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= htmlspecialchars($o['name']) ?></td>
                <td><?= htmlspecialchars($o['email']) ?></td>
                <td><span class="status-badge"><?= ucfirst($o['status']) ?></span></td>
                <td><?= $o['created_at'] ?></td>
                <td>
                    <ul class="mb-0">
                        <?php
                        $items = mysqli_query($conn, "SELECT oi.quantity, p.name 
                                                      FROM order_items oi 
                                                      JOIN products p ON oi.product_id=p.id 
                                                      WHERE oi.order_id={$o['id']}");
                        while($i = mysqli_fetch_assoc($items)):
                        ?>
                        <li><?= htmlspecialchars($i['name']) ?> (x<?= $i['quantity'] ?>)</li>
                        <?php endwhile; ?>
                    </ul>
                </td>
                <td>
                    <form method="POST" class="d-flex">
                        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                        <select name="status" class="form-select form-select-sm me-2">
                            <option value="pending"   <?= $o['status']=='pending'?'selected':'' ?>>Pending</option>
                            <option value="processing"<?= $o['status']=='processing'?'selected':'' ?>>Processing</option>
                            <option value="shipped"   <?= $o['status']=='shipped'?'selected':'' ?>>Shipped</option>
                            <option value="completed" <?= $o['status']=='completed'?'selected':'' ?>>Completed</option>
                            <option value="cancelled" <?= $o['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-success btn-sm">Save</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

