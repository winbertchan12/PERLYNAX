<?php
session_start();
include 'config.php';

$message = $_SESSION['order_success'] ?? "Order completed!";
unset($_SESSION['order_success']);
?>
<!DOCTYPE html>
<html>
<head>
<title>Order Complete - PERLYNAX</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="section">
    <div class="card">
        <h1>Thank you!</h1>
        <p><?php echo $message; ?></p>
        <a class="btn" href="index.php">Continue Shopping</a>
    </div>
</div>
</body>
</html>
