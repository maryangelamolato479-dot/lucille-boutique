<?php while($order = $orders->fetch_assoc()): ?>
<tr>
    <td>#<?= $order['id'] ?></td>
    <td>₱<?= number_format($order['total'], 2) ?></td>
    <td><span class="badge bg-info"><?= ucfirst($order['status']) ?></span></td>
    <td>
        <?php
        $items = $conn->prepare("SELECT * FROM order_items WHERE order_id=?");
        $items->bind_param("i", $order['id']);
        $items->execute();
        $result_items = $items->get_result();
        while($item = $result_items->fetch_assoc()){
            echo htmlspecialchars($item['product_name'])." (x".$item['quantity'].") - ₱".number_format($item['price'],2)."<br>";
        }
        ?>
    </td>
    <td>
        <?php
        $addr = $conn->prepare("SELECT * FROM addresses WHERE id=?");
        $addr->bind_param("i", $order['address_id']);
        $addr->execute();
        $result_addr = $addr->get_result();
        if ($address = $result_addr->fetch_assoc()) {
            echo htmlspecialchars($address['full_name']).", ".
                 htmlspecialchars($address['street']).", ".
                 htmlspecialchars($address['city']).", ".
                 htmlspecialchars($address['province'])." (".
                 htmlspecialchars($address['postal_code']).")";
        } else {
            echo "<span class='text-muted'>No address found</span>";
        }
        ?>
    </td>
    <td><?= $order['created_at'] ?></td>
</tr>
<?php endwhile; ?>
