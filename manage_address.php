<?php
session_start();
require 'db.php';

$customer_id = $_SESSION['user_id'];

// Save new address
if (isset($_POST['save'])) {
    $stmt = $conn->prepare("INSERT INTO addresses (customer_id, full_name, street, city, province, postal_code, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $customer_id, $_POST['full_name'], $_POST['street'], $_POST['city'], $_POST['province'], $_POST['postal_code'], $_POST['phone']);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Address</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h2>Manage Address</h2>

<form method="POST">
    <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name" required>
    <input type="text" name="street" class="form-control mb-2" placeholder="Street" required>
    <input type="text" name="city" class="form-control mb-2" placeholder="City" required>
    <input type="text" name="province" class="form-control mb-2" placeholder="Province" required>
    <input type="text" name="postal_code" class="form-control mb-2" placeholder="Postal Code" required>
    <input type="text" name="phone" class="form-control mb-2" placeholder="Phone" required>
    <button type="submit" name="save" class="btn btn-success">Save Address</button>
</form>

</body>
</html>
