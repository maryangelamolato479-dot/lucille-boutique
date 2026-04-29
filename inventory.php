<?php
include 'db_conn.php';
$result = $conn->query("SELECT * FROM products");

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td><img src='uploads/products/{$row['image']}' width='120' height='120' style='object-fit:cover;'></td>
        <td>{$row['product_name']}</td>
        <td>₱".number_format($row['price'], 2)."</td>
        <td>{$row['stock_quantity']}</td>
    </tr>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Boutique Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">
    <div class="container bg-white p-4 shadow rounded">
        <h2 class="mb-4">Lucille Boutique Inventory</h2>
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td><img src=\"uploads/products/{$row['image']}\" width=\"120\" height=\"120\" style=\"object-fit:cover;\"></td>
                        <td>{$row['product_name']}</td>
                        <td>₱".number_format($row['price'], 2)."</td>
                        <td>{$row['stock_quantity']}</td>
                        <td>
                            <a href='delete_product.php?id={$row['id']}' class='text-danger' onclick=\"return confirm('Delete this?')\">Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
