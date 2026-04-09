<?php
include 'config.php';

// Fetch featured products (latest 4)
$featured = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PERLYNAX - Welcome</title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial, sans-serif; margin:0; }
header { background:#222; color:#fff; padding:20px; display:flex; justify-content:space-between; align-items:center; }
header a { color:#fff; text-decoration:none; margin-left:15px; }
.hero { background:url('images/hero-banner.jpg') no-repeat center center; background-size:cover; height:400px; display:flex; align-items:center; justify-content:center; color:#fff; text-align:center; }
.hero h1 { font-size:48px; margin:0; }
.hero p { font-size:20px; margin-top:10px; }
section { padding:50px 20px; text-align:center; }
.products { display:flex; justify-content:center; flex-wrap:wrap; gap:20px; margin-top:30px; }
.product-card { border:1px solid #ccc; padding:15px; width:200px; }
.product-card img { width:100%; }
button { padding:8px 12px; cursor:pointer; margin-top:10px; }
footer { background:#222; color:#fff; padding:20px; text-align:center; margin-top:50px; }
@media(max-width:768px){ .products{ flex-direction:column; align-items:center; } }
</style>
</head>
<body>

<header>
    <div class="logo"><h2>PERLYNAX</h2></div>
    <nav>
        <a href="landing.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="cart.php">Cart</a>
    </nav>
</header>

<div class="hero">
    <div>
        <h1>Welcome to PERLYNAX</h1>
        <p>Your one-stop shop for quality products</p>
        <a href="index.php"><button>Shop Now</button></a>
    </div>
</div>

<section>
    <h2>Featured Products</h2>
    <div class="products">
        <?php while($row = $featured->fetch_assoc()): ?>
        <div class="product-card">
            <img src="admin/uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p>$<?php echo $row['price']; ?></p>
            <a href="product.php?id=<?php echo $row['id']; ?>"><button>View</button></a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<footer>
    &copy; <?php echo date("Y"); ?> PERLYNAX. All rights reserved.
</footer>

</body>
</html>
