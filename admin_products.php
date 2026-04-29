<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);

    $stmt = $conn->prepare("INSERT INTO products (name, price, size, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $price, $size, $image);
    $stmt->execute();
}
$result = $conn->query("SELECT * FROM products");
?>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Dress Name" required>
    <input type="number" name="price" placeholder="Price" required>
    <select name="size">
        <option>Small</option><option>Medium</option><option>Large</option>
    </select>
    <input type="file" name="image" required>
    <button type="submit">Add</button>
</form>
<table class="table">
    <tr><th>Image</th><th>Product</th><th>Size</th><th>Price</th><th>Action</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><img src="uploads/<?php echo $row['image']; ?>" width="50"></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['size']; ?></td>
        <td><?php echo $row['price']; ?></td>
        <td>
            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
