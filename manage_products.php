<?php
include 'db_conn.php';
$result = $conn->query("SELECT * FROM products");

while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<img src='".$row['image']."' width='100'><br>";
    echo "Name: ".$row['product_name']."<br>";
    echo "Price: ₱".$row['price']."<br>";
    echo "Stock: ".$row['stock_quantity']."<br>";

    echo "<a href='update_product.php?id=".$row['id']."'>Update</a> | ";
    echo "<a href='delete_product.php?id=".$row['id']."'>Delete</a>";
    echo "</div><hr>";
}
?>
