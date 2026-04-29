<?php
session_start();
include 'db_conn.php';

// Security: Customer only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$msg = "";

// Fetch current customer data including address
$user_res = mysqli_query($conn, "SELECT * FROM customers WHERE id = $uid");
$user_data = mysqli_fetch_assoc($user_res);

if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $sql = "UPDATE customers SET name = '$name', address = '$address' WHERE id = $uid";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['name'] = $name; // Update session display name
        $msg = "✅ Profile updated successfully!";
        // Refresh data
        $user_data['name'] = $name;
        $user_data['address'] = $address;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile | Lucille Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h3>Edit Profile</h3>
            <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Delivery Address</label>
                    <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary w-100">Save Changes</button>
            </form>
            <br>
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>