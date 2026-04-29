<?php
include 'config.php';
session_start();

// Security: Check if user is logged in
if(!isset($_SESSION['user_id'])){
   header('location:login.php');
   exit();
}

// Fetch products for the shop
$products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lucille Boutique | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { background: #2c3e50; }
        .product-card { border: none; transition: 0.3s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .badge-location { background: #00bcd4; font-size: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark p-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-info" href="#">LUCILLE</a>
        <div>
            <span class="text-white me-3">Hi, <?php echo $_SESSION['user_name']; ?></span>
            <a href="cart.php" class="btn btn-outline-info btn-sm">My Cart</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h4 class="mb-4 text-secondary">Latest Collections</h4>
    <div class="row">
        <?php while($row = mysqli_fetch_assoc($products)): ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 product-card shadow-sm text-center p-3">
                <img src="images/<?php echo $row['p_image']; ?>" class="card-img-top" style="height:250px; object-fit:contain;">
                <div class="card-body">
                    <p class="text-muted small mb-1"><?php echo $row['size']; ?></p>
                    <h6 class="fw-bold"><?php echo strtoupper($row['p_name']); ?></h6>
                    <div class="badge badge-location mb-2 p-1 px-2 rounded-pill">📍 <?php echo $row['address']; ?></div>
                    <div class="text-dark fw-bold h5">₱<?php echo number_format($row['p_price'], 2); ?></div>
                    
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="p_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-info w-100 text-white mt-2">ADD TO CART</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>