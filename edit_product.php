<?php
include 'db.php';
$id = $_GET['id'];

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $size = $_POST['size'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE products SET name=?, size=?, price=? WHERE id=?");
    $stmt->bind_param("ssdi", $name, $size, $price, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
}
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>
<form method="POST">
    <input type="text" name="name" value="<?= $product['name'] ?>" required>
    <input type="number" name="price" value="<?= $product['price'] ?>" required>
    <select name="size">
        <option <?= $product['size']=="Small"?"selected":"" ?>>Small</option>
        <option <?= $product['size']=="Medium"?"selected":"" ?>>Medium</option>
        <option <?= $product['size']=="Large"?"selected":"" ?>>Large</option>
    </select>
    <button type="submit" name="update">Update</button>
</form>
