<?php
session_start();
include 'db.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch cart items
$result = $conn->query("
    SELECT c.id, p.name, p.price, c.quantity, p.id AS product_id
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $user_id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Shopping Cart - Lucille Boutique</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        h3 { margin-bottom: 20px; }
        .grand-total { font-weight: bold; color: green; }
        .btn-remove { color: #fff; background-color: #dc3545; border: none; padding: 5px 10px; border-radius: 4px; text-decoration: none; }
        .btn-remove:hover { background-color: #c82333; }
        .btn-secondary { margin-right: 10px; }
        .address-input { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body class="container mt-4">
    <h3>My Shopping Cart</h3>
    <form method="POST" action="process_order.php">
        <table class="table table-bordered">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Delivery Address</th>
                <th>Action</th>
            </tr>
            <?php 
            $grand_total = 0;
            while ($row = $result->fetch_assoc()) {
                $total = $row['price'] * $row['quantity'];
                $grand_total += $total;
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>₱{$row['price']}</td>
                        <td>{$row['quantity']}</td>
                        <td>₱$total</td>
                        <td>
                            <input type='text' name='address[{$row['product_id']}]' 
                                   placeholder='Enter Delivery Address' 
                                   required class='form-control form-control-sm'>
                        </td>
                        <td><a href='remove_item.php?id={$row['id']}' class='btn-remove'>Remove</a></td>
                      </tr>";
            }
            ?>
            <tr>
                <td colspan='4'><strong>Grand Total</strong></td>
                <td colspan='2' class='grand-total'>₱<?= $grand_total ?></td>
            </tr>
        </table>
        <input type="hidden" name="total_price" value="<?= $grand_total ?>">
        <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
        <button type="submit" name="place_order" class="btn btn-success">Place Order</button>
    </form>
</body>
</html>


