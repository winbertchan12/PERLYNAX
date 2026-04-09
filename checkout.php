<?php
// 🔥 SHOW ERRORS (REMOVE THIS IN PRODUCTION LATER)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php';

// Redirect if cart is empty
if(empty($_SESSION['cart'])){
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
foreach($_SESSION['cart'] as $item){
    if(!isset($item['price']) || !isset($item['quantity'])){
        die("Cart data error.");
    }
    $total += $item['price'] * $item['quantity'];
}

// HANDLE CHECKOUT
if(isset($_POST['checkout'])){

    $payment_method = $_POST['payment_method'] ?? 'COD';
    $gcash_reference = ($payment_method === 'GCash') ? ($_POST['gcash_reference'] ?? '') : '';
    $user_id = $_SESSION['user_id'] ?? 1;

    // 🔍 Check DB connection
    if(!$conn){
        die("Database connection failed.");
    }

    // INSERT ORDER
    $stmt = $conn->prepare("INSERT INTO orders 
        (user_id, total, payment_method, gcash_reference, status, created_at)
        VALUES (?, ?, ?, ?, 'pending', NOW())");

    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("idss", $user_id, $total, $payment_method, $gcash_reference);

    if(!$stmt->execute()){
        die("Execute failed: " . $stmt->error);
    }

    $order_id = $stmt->insert_id;

    // INSERT ORDER ITEMS
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");

    if(!$stmt_items){
        die("Prepare items failed: " . $conn->error);
    }

    foreach($_SESSION['cart'] as $item){

        if(!isset($item['id'])){
            die("Cart item missing ID.");
        }

        $stmt_items->bind_param("iii", $order_id, $item['id'], $item['quantity']);

        if(!$stmt_items->execute()){
            die("Item insert failed: " . $stmt_items->error);
        }
    }

    // CLEAR CART
    unset($_SESSION['cart']);

    $_SESSION['order_success'] = "Order placed successfully!";
    header("Location: checkout_success.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout - PERLYNAX</title>
<link rel="stylesheet" href="style.css">

<style>
body { font-family: Arial; margin:0; background:#f5f6fa; }
.navbar { background:#111; color:#fff; padding:15px 30px; display:flex; justify-content:space-between; }
.navbar a { color:#fff; text-decoration:none; margin-left:15px; }
.section { max-width:900px; margin:40px auto; padding:20px; }
.card { background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
table { width:100%; border-collapse:collapse; margin-top:20px; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:center; }
th { background:#f4f4f4; }
.btn { background:#007bff; color:#fff; border:none; padding:12px 20px; border-radius:5px; cursor:pointer; }
.btn:hover { background:#0056b3; }
</style>
</head>
<body>

<div class="navbar">
    <h2>PERLYNAX</h2>
    <div>
        <a href="index.php">Home</a>
        <a href="cart.php">Cart</a>
    </div>
</div>

<div class="section">
    <div class="card">
        <h1>Checkout</h1>

        <!-- CART TABLE -->
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach($_SESSION['cart'] as $item): 
                $subtotal = $item['price'] * $item['quantity'];
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>₱<?php echo number_format($item['price'],2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>₱<?php echo number_format($subtotal,2); ?></td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>₱<?php echo number_format($total,2); ?></strong></td>
            </tr>
        </table>

        <!-- PAYMENT -->
        <h2 style="margin-top:20px;">Payment Method</h2>

        <form method="post">
            <label>
                <input type="radio" name="payment_method" value="COD" checked> Cash on Delivery
            </label><br>

            <label>
                <input type="radio" name="payment_method" value="GCash"> GCash
            </label><br><br>

            <div id="gcash-box" style="display:none; background:#f1f1f1; padding:10px; border-radius:5px;">
                <p><strong>Send payment to:</strong> 09392031398</p>
                <input type="text" name="gcash_reference" placeholder="Enter GCash Reference Number">
            </div>

            <br>
            <button class="btn" type="submit" name="checkout">Place Order</button>
        </form>

    </div>
</div>

<script>
const gcashRadio = document.querySelector('input[value="GCash"]');
const codRadio = document.querySelector('input[value="COD"]');
const gcashBox = document.getElementById('gcash-box');

function toggleGcash(){
    gcashBox.style.display = gcashRadio.checked ? 'block' : 'none';
}

gcashRadio.addEventListener('change', toggleGcash);
codRadio.addEventListener('change', toggleGcash);
</script>

</body>
</html>
