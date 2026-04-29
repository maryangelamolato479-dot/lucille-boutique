<?php
include 'db_conn.php';

if (isset($_POST['add'])) {
    $name  = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];

    // Ensure uploads/products folder exists
    $target_dir = "uploads/products/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Save uploaded image
    $image_name  = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO products (product_name, price, stock_quantity, image) 
                VALUES ('$name', '$price', '$stock', '$image_name')";
        $conn->query($sql);
        echo "Product added successfully!";
    } else {
        echo "Image upload failed.";
    }
}
?>

<!-- Product Form -->
<form method="POST" action="add_product.php" enctype="multipart/form-data">
    <input type="text" name="product_name" placeholder="Product Name" required><br>
    <input type="number" step="0.01" name="price" placeholder="Price (₱)" required><br>
    <input type="number" name="stock_quantity" placeholder="Stock Quantity" required><br>
    <input type="file" name="image" accept="image/*" required><br>
    <button type="submit" name="add">Add Product</button>
</form>


<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Product - Lucille Boutique</title>
</head>
<body class="bg-light p-5">
    <div class="container card shadow p-4" style="max-width: 500px;">
        <h2 class="text-center">Add New Item</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="pname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Price (₱)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Stock Quantity</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Product Image</label>
                <input type="file" name="pimage" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-success w-100">Save Product</button>
            <a href="inventory.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</body>
</html>
