<?php
include 'db_conn.php';

if (isset($_POST['submit'])) {
    // 1. Sanitize Text Inputs
    // Using mysqli_real_escape_string prevents simple SQL injection
    $name  = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $size  = mysqli_real_escape_string($conn, $_POST['size']);

    // 2. Handle the Image File
    $fileName    = $_FILES['product_image']['name'];
    $fileTmpName = $_FILES['product_image']['tmp_name'];
    
    // Create a unique name using time() to prevent overwriting existing files
    $uniqueName  = time() . "_" . basename($fileName);
    $uploadDir   = "uploads/products/";
    $uploadPath  = $uploadDir . $uniqueName;

    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // 3. Move file and Update Database
    if (move_uploaded_file($fileTmpName, $uploadPath)) {
        // Prepare SQL (Note: ensure your table columns match these names)
        $sql = "INSERT INTO products (product_name, price, stock_quantity, size, image) 
                VALUES ('$name', '$price', '$stock', '$size', '$uniqueName')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Success! Item added to Lucille Boutique.'); window.location='admin_dashboard.php';</script>";
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Could not upload file. Check folder permissions for 'uploads/products/'.";
    }
}
?>

<h2>Add New Product to Lucille Boutique</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="product_name" placeholder="Item Name" required><br><br>
    
    <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
    
    <input type="number" name="stock" placeholder="Stock Quantity" required><br><br>
    
    <select name="size" required>
        <option value="">Select Size</option>
        <option value="S">Small</option>
        <option value="M">Medium</option>
        <option value="L">Large</option>
        <option value="XL">Extra Large</option>
    </select><br><br>
    
    <label>Product Photo:</label><br>
    <input type="file" name="product_image" accept="image/*" required><br><br>
    
    <button type="submit" name="submit">Save Product</button>
</form>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>