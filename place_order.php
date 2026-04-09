<?php
include 'db.php';
session_start();

$cart = $_SESSION['cart'] ?? [];
$user = $_SESSION['user'] ?? 'guest';

$fullname = $_POST['fullname'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$payment = $_POST['payment'];

// create order
$conn->query("INSERT INTO orders(user, fullname, address, phone, payment) 
VALUES('$user','$fullname','$address','$phone','$payment')");

$order_id = $conn->insert_id;

// save order items
foreach($cart as $id => $qty){
  $conn->query("INSERT INTO order_items(order_id, product_id, quantity) 
  VALUES($order_id, $id, $qty)");
}

unset($_SESSION['cart']);

echo "<h2>Order placed successfully!</h2>";
?>