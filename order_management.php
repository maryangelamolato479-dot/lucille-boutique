<?php
session_start();

// Security: Admin only (Recommended to add this here as well)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_conn.php'; 

// Fetch orders using MySQLi to match your update_status.php logic
$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management | Lucille Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container-fluid"> 
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✨ Order status updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow p-4">
        <h2 class="mb-4">Order Management</h2>
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Address</th> 
                    <th>Current Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong>#<?php echo $row['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td>₱<?php echo number_format($row['price'], 2); ?></td>
                        <td style="max-width: 200px; font-size: 0.9rem;"><?php echo htmlspecialchars($row['address']); ?></td> 
                        <td>
                            <?php 
                                $badge = 'bg-secondary';
                                if($row['status'] == 'Pending') $badge = 'bg-warning text-dark';
                                if($row['status'] == 'Processing') $badge = 'bg-info text-dark';
                                if($row['status'] == 'Shipped') $badge = 'bg-primary';
                                if($row['status'] == 'Delivered') $badge = 'bg-success';
                                if($row['status'] == 'Cancelled') $badge = 'bg-danger';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo $row['status']; ?></span>
                        </td>
                        <td>
                            <form action="update_status.php" method="POST" class="d-inline">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                
                                <select name="status" class="form-select form-select-sm d-inline w-auto">
                                    <option value="Pending" <?php if($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Processing" <?php if($row['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                    <option value="Shipped" <?php if($row['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                    <option value="Delivered" <?php if($row['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                    <option value="Cancelled" <?php if($row['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                                
                                <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="index.php" class="btn btn-outline-secondary">Back to Admin Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>