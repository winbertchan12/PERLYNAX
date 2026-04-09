<?php
session_start();
include 'config.php';

// Remove item
if(isset($_GET['remove'])){
    $id = intval($_GET['remove']);
    foreach($_SESSION['cart'] as $key => $item){
        if($item['id'] == $id) unset($_SESSION['cart'][$key]);
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
    header("Location: cart.php");
}

// Update quantities
if(isset($_POST['update_cart'])){
    foreach($_POST['qty'] as $id=>$q){
        foreach($_SESSION['cart'] as &$item){
            if($item['id'] == $id){
                $item['quantity'] = intval($q);
            }
        }
    }
    header("Location: cart.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart - PERLYNAX</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="navbar">
    <h2>PERLYNAX</h2>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="cart.php">Cart <?php if(isset($_SESSION['cart'])) echo "<span class='cart-badge'>".count($_SESSION['cart'])."</span>"; ?></a>
    </div>
</div>

<div class="section">
    <h1>Your Cart</h1>

    <?php if(empty($_SESSION['cart'])): ?>
        <p>Cart is empty. <a href="index.php">Shop now</a></p>
    <?php else: ?>
        <form method="post">
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php $total=0; foreach($_SESSION['cart'] as $item): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td>₱<?php echo $item['price']; ?></td>
                <td><input type="number" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1"></td>
                <td>₱<?php echo $subtotal = $item['price']*$item['quantity']; $total+=$subtotal; ?></td>
                <td><a href="?remove=<?php echo $item['id']; ?>" onclick="return confirm('Remove item?')">Remove</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Total</td>
                <td colspan="2">₱<?php echo $total; ?></td>
            </tr>
        </table>
        <br>
        <button class="btn" type="submit" name="update_cart">Update Cart</button>
        <a class="btn" href="checkout.php">Checkout</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
