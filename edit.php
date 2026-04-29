<?php
include "db_conn.php";

// 1. GET: Fetch the product details to fill the form
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
    $product = mysqli_fetch_assoc($res);
}

// 2. POST: Handle the Update logic
if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = $_POST['price'];
    $old_image = $_POST['old_image'];

    // Check if a new file was uploaded
    if (!empty($_FILES['new_image']['name'])) {
        $image = $_FILES['new_image']['name'];
        $target = "images/" . basename($image);
        move_uploaded_file($_FILES['new_image']['tmp_name'], $target);

        // Delete old file to save space (but keep default.jpg)
        if ($old_image !== 'default.jpg' && file_exists("images/" . $old_image)) {
            unlink("images/" . $old_image);
        }
    } else {
        $image = $old_image; // Keep existing image if no new one is uploaded
    }

    $sql = "UPDATE products SET product_name='$name', price='$price', image='$image' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=Product Updated Successfully");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product | Lucille Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow-sm" style="max-width: 500px;">
            <div class="card-header bg-warning">Edit Product</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="old_image" value="<?php echo $product['image']; ?>">

                    <div class="mb-3">
                        <label>Product Name</label>
                        <input type="text" name="product_name" class="form-control" value="<?php echo $product['product_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Price (₱)</label>
                        <input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Current Image</label><br>
                        <img src="images/<?php echo $product['image']; ?>" width="100" class="mb-2 rounded">
                        <input type="file" name="new_image" class="form-control">
                        <small class="text-muted">Upload only if changing photo</small>
                    </div>
                    <button type="submit" name="update_product" class="btn btn-primary w-100">Save Changes</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>