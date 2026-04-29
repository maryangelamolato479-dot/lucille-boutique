<?php
include 'db_conn.php';
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];

    $sql = "UPDATE products SET product_name='$name', price='$price', stock_quantity='$stock' WHERE id=$id";
    $conn->query($sql);
    header("Location: manage_products.php");
    exit();
}

$result = $conn->query("SELECT * FROM products WHERE id=$id");
$row = $result->fetch_assoc();
?>

<form method="POST">
    <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>"><br>
    <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>"><br>
    <input type="number" name="stock_quantity" value="<?php echo $row['stock_quantity']; ?>"><br>
    <button type="submit">Update</button>
</form>
