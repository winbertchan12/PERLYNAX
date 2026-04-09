<?php
session_start();
include 'config.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Count cart items
$cart_count = count($_SESSION['cart']);

// Fetch products
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PERLYNAX</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- HEADER / NAVBAR -->
<header>
    <div class="navbar">
        <h2>PERLYNAX</h2>

        <div class="nav-links">
            <input type="text" class="search-box" placeholder="Search...">

            <a href="index.php">Home</a>
            <a href="cart.php">
                Cart <span class="cart-badge"><?php echo $cart_count; ?></span>
            </a>

            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- HERO -->
<div class="hero">
    <div>
        <h1>Shop Smart with PERLYNAX</h1>
        <p>Quality products at the best price</p>
        <a href="#products"><button>Shop Now</button></a>
    </div>
</div>

<!-- PRODUCTS -->
<div class="section" id="products">
    <h2>Our Products</h2>

    <div class="products">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="admin/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <div class="price">₱<?php echo htmlspecialchars($row['price']); ?></div>
                    <a class="btn" href="product.php?id=<?php echo $row['id']; ?>">View</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- FOOTER -->
<footer>
    &copy; <?php echo date("Y"); ?> PERLYNAX
</footer>

</body>
</html>
