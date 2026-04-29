<?php
session_start();
include 'config.php';

// Only allow logged-in customers
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle item removal
if (isset($_GET['delete'])) {
    $cart_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM cart WHERE id=$cart_id AND user_id=$user_id");
    header('location:my_cart.php');
    exit();
}

// Fetch cart items for this user
$result = mysqli_query($conn, "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity 
                               FROM cart c 
                               JOIN products p ON c.product_id = p.id 
                               WHERE c.user_id=$user_id");

$grand_total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Lucille Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-order { background: #00bcd4; color: white; padding: 10px 20px; border: none; cursor: pointer; transition: 0.3s; }
        .btn-order:hover { background: #00acc1; color: white; }
    </style>
</head>
<body class="bg-light p-5">

<div class="container bg-white p-4 rounded shadow-sm">
    <h2 class="mb-4">My Shopping Cart</h2>

    <form action="place_order.php" method="POST">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Shipping Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): 
                        $subtotal = $row['price'] * $row['quantity'];
                        $grand_total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td>₱<?= number_format($row['price'], 2); ?></td>
                        <td><?= $row['quantity']; ?></td>
                        <td>₱<?= number_format($subtotal, 2); ?></td>
                        <td>
                            <input type="text" name="address[<?= $row['product_id']; ?>]" placeholder="Enter Delivery Address" required 
                                   class="form-control form-control-sm" style="width: 250px;">
                            <input type="hidden" name="product_ids[]" value="<?= $row['product_id']; ?>">
                            <input type="hidden" name="quantities[]" value="<?= $row['quantity']; ?>">
                        </td>
                        <td>
                            <a href="my_cart.php?delete=<?= $row['cart_id']; ?>" class="btn btn-sm btn-outline-danger">Remove</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                        <td class="text-success fw-bold fs-5">₱<?= number_format($grand_total, 2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">Your cart is empty.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="customer_dashboard.php" class="btn btn-secondary">Continue Shopping</a>
            
            <?php if ($grand_total > 0): ?>
                <input type="hidden" name="total_price" value="<?= $grand_total ?>">
                <button type="submit" name="place_order" class="btn btn-order fw-bold">
                    PLACE ORDER NOW
                </button>
            <?php endif; ?>
        </div>
    </form>
</div>

</body>
</html>
