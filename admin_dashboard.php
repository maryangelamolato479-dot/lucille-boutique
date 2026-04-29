<?php
require 'app/middleware/admin_auth.php';
require 'db_conn.php';

// DELETE PRODUCT
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $img_query = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
    $img_data = mysqli_fetch_assoc($img_query);

    if ($img_data) {
        $file_path = "images/" . $img_data['image'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    mysqli_query($conn, "DELETE FROM products WHERE id=$id");

    header("Location: admin_dashboard.php");
    exit();
}

// ADD PRODUCT
if (isset($_POST['add_product'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $size  = mysqli_real_escape_string($conn, $_POST['size']);

    $image = $_FILES['image']['name'];
    $temp  = $_FILES['image']['tmp_name'];

    $unique_image = time() . "_" . $image;
    $target = "images/" . $unique_image;

    $sql = "INSERT INTO products (name, price, size, image)
            VALUES ('$name', '$price', '$size', '$unique_image')";

    if (mysqli_query($conn, $sql)) {
        move_uploaded_file($temp, $target);
        header("Location: admin_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { padding: 30px; background: #f5f5f5; }
        .box { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .img { width: 60px; height: 70px; object-fit: cover; }
    </style>
</head>
<body>

<div class="container">

    <h2>Admin Dashboard</h2>

    <!-- ADD PRODUCT -->
    <div class="box">
        <form method="POST" enctype="multipart/form-data">

            <input type="text" name="name" class="form-control mb-2" placeholder="Product Name" required>

            <input type="number" name="price" class="form-control mb-2" placeholder="Price" required>

            <input type="text" name="size" class="form-control mb-2" placeholder="Size" required>

            <input type="file" name="image" class="form-control mb-2" required>

            <button type="submit" name="add_product" class="btn btn-success">
                Add Product
            </button>

        </form>
    </div>

    <!-- PRODUCT LIST -->
    <div class="box">
        <table class="table table-bordered">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Size</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php
            $res = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($res)):
            ?>

            <tr>
                <td>
                    <img src="images/<?php echo $row['image']; ?>" class="img">
                </td>

                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['size']; ?></td>
                <td>₱<?php echo number_format($row['price'], 2); ?></td>

                <td>
                    <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this product?')">
                        Delete
                    </a>
                </td>
            </tr>

            <?php endwhile; ?>

        </table>
    </div>

    <a href="logout.php" class="btn btn-danger">Logout</a>

</div>

</body>
</html>