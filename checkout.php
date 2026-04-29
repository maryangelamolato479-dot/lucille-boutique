<?php
session_start();
require 'db.php';

// Example cart for testing if empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        ['id'=>1, 'name'=>'Pink Shoulder Dress', 'qty'=>1, 'price'=>50200.00]
    ];
}

// Fetch saved addresses
$stmt = $conn->prepare("SELECT * FROM addresses WHERE customer_id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$addresses = $stmt->get_result();

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['qty'] * $item['price'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h2>Checkout</h2>

<h4>Select Delivery Address</h4>
<form method="POST" action="process_order.php">
    <select name="address_id" class="form-select mb-3" required>
        <?php while($addr = $addresses->fetch_assoc()): ?>
            <option value="<?= $addr['id'] ?>">
                <?= $addr['full_name'] ?>, <?= $addr['street'] ?>, <?= $addr['city'] ?>, <?= $addr['province'] ?> (<?= $addr['postal_code'] ?>)
            </option>
        <?php endwhile; ?>
    </select>

    <h4>Order Summary</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $item): ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['qty'] ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td>₱<?= number_format($item['qty'] * $item['price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Total: ₱<?= number_format($total, 2) ?></h4>

    <button type="submit" class="btn btn-success">Place Order</button>
</form>

</body>
</html>
