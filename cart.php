<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items with product details
$stmt = $conn->prepare("
    SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$grand_total = 0;
?>
<div class="card shadow p-4">
    <h3>My Shopping Cart</h3>
    <form action="process_order.php" method="POST">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Delivery Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $total = $row['price'] * $row['quantity'];
                        $grand_total += $total;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>₱<?= number_format($total, 2) ?></td>
                        <td>
                            <input type="text" name="address[<?= $row['product_id'] ?>]" 
                                   placeholder="Enter Delivery Address" 
                                   required class="form-control form-control-sm">
                            <input type="hidden" name="product_ids[]" value="<?= $row['product_id'] ?>">
                        </td>
                        <td>
                            <a href="remove_cart.php?id=<?= $row['cart_id'] ?>" 
                               class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                        <td class="fw-bold text-success">₱<?= number_format($grand_total, 2) ?></td>
                        <td colspan="2"></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">Your cart is empty.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
            <?php if ($grand_total > 0): ?>
                <input type="hidden" name="total_price" value="<?= $grand_total ?>">
                <button type="submit" name="place_order" class="btn btn-success px-4">Place Order</button>
            <?php endif; ?>
        </div>
    </form>
</div>
