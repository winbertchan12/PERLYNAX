<?php
session_start();
include 'config.php';

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $res->fetch_assoc();

// Add to cart handling
if(isset($_POST['add_to_cart'])){
    $qty = intval($_POST['quantity']);
    $cart_item = [
        'id'=>$product['id'],
        'name'=>$product['name'],
        'price'=>$product['price'],
        'quantity'=>$qty
    ];

    if(isset($_SESSION['cart'])){
        // Check if already exists
        $found = false;
        foreach($_SESSION['cart'] as &$item){
            if($item['id'] == $product['id']){
                $item['quantity'] += $qty;
                $found = true;
                break;
            }
        }
        if(!$found) $_SESSION['cart'][] = $cart_item;
    } else {
        $_SESSION['cart'][] = $cart_item;
    }
    $added_msg = "Added to cart!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?> - PERLYNAX</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="navbar">
    <h2>PERLYNAX</h2>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="cart.php">Cart <?php if(isset($_SESSION['cart'])) echo "<span class='cart-badge'>".count($_SESSION['cart'])."</span>"; ?></a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="section">
    <div class="card" style="display:flex; gap:30px; flex-wrap:wrap;">
        <div style="flex:1; min-width:250px;">
            <img src="admin/uploads/<?php echo $product['image']; ?>" style="width:100%; border-radius:10px;">
        </div>
        <div style="flex:1; min-width:250px;">
            <h1><?php echo $product['name']; ?></h1>
            <p><?php echo $product['description']; ?></p>
            <h2>₱<?php echo $product['price']; ?></h2>

            <?php if(isset($added_msg)) echo "<p style='color:green;'>$added_msg</p>"; ?>

            <form method="post">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button class="btn" type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
