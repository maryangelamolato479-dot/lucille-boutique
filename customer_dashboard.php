<?php
session_start();
include 'db.php'; // database connection

// ✅ Ensure customer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info securely
$stmt = $conn->prepare("SELECT id, name, email, address FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle address update
if (isset($_POST['update_address'])) {
    $new_address = $_POST['address'];
    $stmt = $conn->prepare("UPDATE users SET address=? WHERE id=?");
    $stmt->bind_param("si", $new_address, $user_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['user_addr'] = $new_address; // keep session updated
    header("Location: customer_dashboard.php");
    exit();
}

// Fetch products
$products = $conn->query("SELECT * FROM products");

// Fetch orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id=? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lucille Boutique - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            background-color: #212529;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar h4 { color: #17a2b8; }
        .sidebar a { color: white; text-decoration: none; display: block; margin: 10px 0; }
        .sidebar a:hover { text-decoration: underline; }
        .logout-btn { background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; }
        .logout-btn:hover { background-color: #c82333; }
        .product-card img { height: 200px; object-fit: cover; }
        .product-card { margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-2 sidebar">
            <h4>LUCILLE</h4>
            <a href="customer_dashboard.php">Dashboard</a>
            <a href="my_cart.php">My Cart</a>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-btn mt-3">Logout</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="col-10 p-4">
            <h3>Welcome, <?= htmlspecialchars($user['name']) ?>!</h3>
            <p>Email: <?= htmlspecialchars($user['email']) ?></p>
            <p>Address: <?= htmlspecialchars($user['address']) ?></p>

            <!-- Update Address -->
            <form method="POST" class="mb-4">
                <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="form-control mb-2" placeholder="Update Address">
                <button type="submit" name="update_address" class="btn btn-primary">Update Address</button>
            </form>

            <!-- Product Listing -->
            <h4>Showing all products</h4>
            <div class="row">
                <?php while ($row = $products->fetch_assoc()): ?>
                    <div class="col-md-3 product-card">
                        <div class="card">
                            <img src="images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text">₱<?= number_format($row['price'], 2) ?></p>
                                <a href="add_to_cart.php?id=<?= $row['id'] ?>" class="btn btn-primary">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Orders Section -->
            <h4 class="mt-5">My Orders</h4>
            <?php while ($o = $orders->fetch_assoc()): ?>
                <div class="border rounded p-3 mb-3">
                    <p><strong>Order ID:</strong> <?= $o['id'] ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($o['status']) ?></p>
                    <p><strong>Date:</strong> <?= $o['created_at'] ?></p>
                    <h6>Items:</h6>
                    <ul>
                        <?php
                        $stmt = $conn->prepare("SELECT oi.quantity, p.name 
                                                 FROM order_items oi 
                                                 JOIN products p ON oi.product_id=p.id 
                                                 WHERE oi.order_id=?");
                        $stmt->bind_param("i", $o['id']);
                        $stmt->execute();
                        $items = $stmt->get_result();
                        while ($i = $items->fetch_assoc()):
                        ?>
                            <li><?= htmlspecialchars($i['name']) ?> (x<?= $i['quantity'] ?>)</li>
                        <?php endwhile; $stmt->close(); ?>
                    </ul>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
</body>
</html>

