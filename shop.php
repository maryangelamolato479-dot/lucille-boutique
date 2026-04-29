<?php
include "db_conn.php";

// 1. Get the search and size values from the URL (if they exist)
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$size_filter = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : '';

// 2. Build the SQL Query dynamically
$sql = "SELECT * FROM products WHERE (product_name LIKE '%$search%')";

// If a specific size is chosen, add it to the query
if ($size_filter != "") {
    $sql .= " AND size = '$size_filter'";
}

$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
    <form method="GET" action="shop.php" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search dress name..." value="<?php echo $search; ?>">
        </div>
        <div class="col-md-3">
            <select name="size" class="form-select">
                <option value="">All Sizes</option>
                <option value="Small" <?php if($size_filter == 'Small') echo 'selected'; ?>>Small</option>
                <option value="Medium" <?php if($size_filter == 'Medium') echo 'selected'; ?>>Medium</option>
                <option value="Large" <?php if($size_filter == 'Large') echo 'selected'; ?>>Large</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-info w-100 text-white">Search & Filter</button>
        </div>
    </form>

    <div class="row">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center p-3">
                        <img src="images/<?php echo $row['image']; ?>" class="card-img-top mx-auto" style="height: 250px; object-fit: cover;" onerror="this.src='images/default.jpg'">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo strtoupper($row['product_name']); ?></h5>
                            <p class="text-muted">Size: <?php echo $row['size']; ?></p>
                            <h4 class="text-dark">₱<?php echo number_format($row['price'], 2); ?></h4>
                            <button class="btn btn-primary w-100 mt-2">ADD TO CART</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="alert alert-warning">No dresses found matching your search.</p>
            </div>
        <?php endif; ?>
    </div>
</div>