<?php
include "db_conn.php"; 

// --- CREATE: Add Product ---
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']); 
    $price = $_POST['price'];
    
    // Check if a file was actually uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "images/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = "default.jpg"; // Set default if no file uploaded
    }

    $sql = "INSERT INTO products (product_name, price, image) VALUES ('$name', '$price', '$image')";
    mysqli_query($conn, $sql);
}

// --- DELETE: Remove Product ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
}

// --- READ: Fetch Products ---
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lucille Boutique | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { width: 250px; background: #2c3e50; height: 100vh; position: fixed; color: white; padding: 20px; }
        .main-content { margin-left: 270px; padding: 30px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 class="text-info">Lucille</h2>
    <hr>
    <p>Dashboard</p>
    <p>Shop View</p>
    <a href="logout.php" class="btn btn-danger btn-sm w-100 mt-5">Logout</a>
</div>

<div class="main-content">
    <h3>Admin Dashboard</h3>

    <div class="card p-4 mb-4">
        <h5>Add New Product</h5>
        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="product_name" class="form-control" placeholder="Dress Name" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="price" class="form-control" placeholder="Price (₱)" required>
            </div>
            <div class="col-md-2">
                <input type="file" name="image" class="form-control">
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_product" class="btn btn-success w-100">Add</button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <?php 
                    // --- IMAGE SOLUTION LOGIC START ---
                    $uploadDir = 'images/';
                    // Use database image name, or 'default.jpg' if column is empty
                    $imageName = !empty($row['image']) ? $row['image'] : 'default.jpg';
                    $imagePath = $uploadDir . $imageName;

                    // Final check: if the file is missing from the folder, use default.jpg
                    if (!file_exists($imagePath)) {
                        $imagePath = 'images/default.jpg';
                    }
                    // --- IMAGE SOLUTION LOGIC END ---
                ?>
                <tr>
                    <td>
                        <img src="<?php echo $imagePath; ?>" class="product-img" alt="Product">
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['product_name']); ?></strong>
                    </td>
                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>